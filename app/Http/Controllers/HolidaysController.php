<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\Holiday;
use App\User;
use App\Role;
use App\Department;
use App\Event;
use \Carbon\Carbon;
use App\Notifications\HolidaysPetition;
use App\Notifications\AcceptHolidays;
use App\Notifications\ModifiedHolidays;
use App\Notifications\ModifyHolidays;
use App\Notifications\RejectHolidays;

class HolidaysController extends Controller
{
    public function me(Request $request)
    {
        $data['holidays'] = Holiday::where('user_id', $request->user()->id)->get();
        return view('holidays.me', $data);
    }

    public function new(Request $request)
    {
        $min = Carbon::now()->addDay();
        $data['min'] = $min->isoFormat('YYYY-MM-DD');
        $minFin = $min->addDay();
        $data['minFin'] = $minFin->isoFormat('YYYY-MM-DD');
        $data['max'] = date('Y') . "-12-31";
        return view('holidays.new', $data);
    }

    public function edit($id)
    {
        $holidays = Holiday::find($id);
        $min = Carbon::now()->addDay();
        $data['min'] = $min->isoFormat('YYYY-MM-DD');
        $minFin = $min->addDay();
        $data['minFin'] = $minFin->isoFormat('YYYY-MM-DD');
        $data['holidays'] = $holidays;
        $data['id'] = $id;
        $data['max'] = date('Y') . "-12-31";
        return view('holidays.edit', $data);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fecha_inicio' => ['required', 'date_format:Y-m-d'],
            'fecha_fin' => ['required', 'date_format:Y-m-d'],
            'total_days' => ['required', 'integer', 'max:22'],
            'comments' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($holidays = $this->create($request, $request->all())));

        return $this->registered($request, $holidays)
            ?: redirect($this->redirectPath());
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upgrade(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($holidays = $this->update($request, $request->all())));

        return $this->registered($request, $holidays)
            ?: redirect($this->redirectPath());
    }

    /**
     * Create a new Holidays instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Holiday
     */
    protected function create(Request $request, array $data)
    {
        $holidays = Holiday::create([
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin'],
            'total_days' => $data['total_days'],
            'comments' => $data['comments'],
            'user_id' => $request->user()->id,
            'status' => 'asked'
        ]);

        $administrators = Role::where('name', 'Administrador')->first()->users()->get();
        foreach ($administrators as $administrator) {
            $administrator->notify(new HolidaysPetition($holidays));
        }
    }

    protected function update(Request $request, array $data)
    {

        $holidays = Holiday::find($data['id']);
        $holidays->fecha_inicio = $data['fecha_inicio'];
        $holidays->fecha_fin = $data['fecha_fin'];
        $holidays->total_days = $data['total_days'];
        $holidays->comments = $data['comments'];
        unset($holidays->status);
        $holidays->status = 'asked';
        $holidays->save();

        $administrators = Role::where('name', 'Administrador')->first()->users()->get();
        foreach ($administrators as $administrator) {
            $administrator->notify(new ModifiedHolidays($holidays));
        }
    }


    /**
     * The holidays had been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $holidays)
    {
        return redirect('holidays');
    }

    public function review()
    {
        $user = \Auth::user();
        if ($user->hasRole('Director')) {
            foreach ($user->departments as $department) {
                $holidays[$department->name] = Department::with('users')->where('name', $department->name)->get();
            }
            foreach ($holidays['Desarrollo']->flatMap->users as $user) {
                $holidays[$department->name][$user->name] = Holiday::where('user_id', $user->id)->get();
            }
            $data['holidays'] = $holidays;
            return view('holidays.reviewDirector', $data);
        } else if ($user->hasRole('Administrador')) {
            $data['holidays'] = Holiday::with('user')->where('status', '!=', 'accepted')->where('status', '!=', 'rejected')->get();
        }
        return view('holidays.review', $data);
    }

    public function single($id)
    {
        $data['holidays'] = Holiday::with('user')->where('id', $id)->first();
        return view('holidays.single', $data);
    }

    public function approve($id)
    {
        $holidays = Holiday::find($id);
        $user = User::find($holidays->user_id);
        unset($holidays->status);
        $holidays->status = 'accepted';
        $holidays->save();
        $totaldays = $user->days_off - $holidays->total_days;
        $user->days_off = $totaldays;
        $user->save();
        $event = Event::create([
            'title' => 'Vacaciones: ' . $user->name,
            'start' => $holidays->fecha_inicio,
            'end' => $holidays->fecha_fin,
            'color' => '#7CCC0C'
        ]);
        $user->notify(new AcceptHolidays($holidays));
        return redirect('/admin/holidays');
    }

    public function modificate(Request $request, $id)
    {
        $holidays = Holiday::find($id);
        $user = User::find($holidays->user_id);
        unset($holidays->status);
        $holidays->status = 'modification';
        $holidays->comments_to_modify = $request->comments_to_modify;
        $holidays->save();
        $user->notify(new ModifyHolidays($holidays));
        return redirect('/holidays/review');
    }

    public function reject(Request $request, $id)
    {
        $holidays = Holiday::find($id);
        $user = User::find($holidays->user_id);
        unset($holidays->status);
        $holidays->status = 'rejected';
        $holidays->comments_to_modify = $request->comments_to_modify;
        $holidays->save();
        $user->notify(new RejectHolidays($holidays));
        return redirect('/holidays/review');
    }


    public function adminHolidays()
    {
        $data['holidays'] = Holiday::with('user')->where('status', 'accepted')->get();
        return view('holidays.admin', $data);
    }
}
