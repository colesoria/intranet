<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\Remote;
use App\User;
use App\Role;
use App\Event;
use App\Department;
use \Carbon\Carbon;
use App\Notifications\RemotePetition;
use App\Notifications\AcceptRemote;
use App\Notifications\RejectRemote;

class RemoteController extends Controller
{
    public function me(Request $request)
    {
        $data['remote'] = Remote::where('user_id', $request->user()->id)->get();
        return view('remote.me', $data);
    }

    public function new(Request $request)
    {
        $min = Carbon::now()->addDay();
        $data['min'] = $min->isoFormat('YYYY-MM-DD');
        $minFin = $min->addDay();
        $data['minFin'] = $minFin->isoFormat('YYYY-MM-DD');
        $data['max'] = date('Y') . "-12-31";
        return view('remote.new', $data);
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
            'date' => ['required', 'date_format:Y-m-d'],
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

        event(new Registered($remote = $this->create($request, $request->all())));

        return $this->registered($request, $remote)
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

        event(new Registered($remote = $this->update($request, $request->all())));

        return $this->registered($request, $remote)
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
        $remote = Remote::create([
            'date' => $data['date'],
            'user_id' => $request->user()->id,
            'status' => 'asked'
        ]);
        $user = \Auth::user();
        $administrators = Role::where('name', 'Director')->first()->users()->get();
        foreach ($administrators as $administrator) {
            $dir = User::with('departments')->where('id', $administrator->id)->first();
            if($user->departments() == $dir->departments()){
                $administrator->notify(new RemotePetition($remote));
            }
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
        return redirect('remote');
    }

    public function review()
    {
        $user = \Auth::user();
        if ($user->hasRole('Director')) {
            foreach ($user->departments as $department) {
                $remote[$department->name] = Department::with('users')->where('name', $department->name)->get();
            }
            foreach ($remote[$department->name]->flatMap->users as $user) {
                $remote[$department->name][$user->name] = Remote::where('user_id', $user->id)->get();
            }
            $data['remote'] = $remote;
            return view('remote.reviewDirector', $data);
        } else if ($user->hasRole('Administrador')) {
            $data['remote'] = Remote::with('user')->where('status', 'accepted')->get();
        }
        return view('remote.review', $data);
    }

    public function single($id)
    {
        $data['remote'] = Remote::with('user')->where('id', $id)->first();
        return view('remote.single', $data);
    }

    public function approve($id)
    {
        $remote = Remote::find($id);
        $user = User::find($remote->user_id);
        unset($remote->status);
        $remote->status = 'accepted';
        $remote->save();
        $event = Event::create([
            'title' => 'teletrabajo: ' . $user->name,
            'start' => $remote->date,
            'end' => $remote->date,
            'color' => '#055FD4'
        ]);
        $user->notify(new AcceptRemote($remote));
        return redirect('/remote/review');
    }

    public function reject(Request $request, $id)
    {
        $remote = Remote::find($id);
        $user = User::find($remote->user_id);
        unset($remote->status);
        $remote->status = 'rejected';
        $remote->comments = $request->comments;
        $remote->save();
        $user->notify(new RejectRemote($remote));
        return redirect('/remote/review');
    }
}
