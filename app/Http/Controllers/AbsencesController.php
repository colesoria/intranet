<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\Absence;
use App\User;
use App\Role;
use App\Department;
use App\Event;
use \Carbon\Carbon;
use App\Notifications\AbsencesNotification;
use App\Notifications\ModifiedAbscene;

class AbsencesController extends Controller
{
    public function me(Request $request)
    {
        $data['absences'] = Absence::where('user_id', $request->user()->id)->get();
        return view('absences.me', $data);
    }

    public function new(Request $request)
    {
        $data['min'] = date('Y') . "-01-01";
        $data['minFin'] = date('Y') . "-01-01";
        $data['max'] = date('Y') . "-12-31";
        $data['day'] = false;
        return view('absences.new', $data);
    }

    public function edit($id)
    {
        $data['min'] = date('Y') . "-01-01";
        $data['minFin'] = date('Y') . "-01-01";
        $data['max'] = date('Y') . "-12-31";
        $data['day'] = false;
        $absence = Absence::find($id);
        $data['absence'] = $absence;
        $data['id'] = $id;
        return view('absences.edit', $data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if($data['day'] == 1){
            return Validator::make($data, [
                'fecha_inicio' => ['required', 'date_format:Y-m-d'],
                'hora_inicio' => ['nullable','date_format:H:i'],
                'hora_fin' => ['nullable','date_format:H:i|after:hora_inicio'],
                'day' => ['required'],
                'comments' => ['required', 'string', 'max:255'],
                'document' => ['nullable','mimes:jpeg,png,jpg,pdf','max:2048' ]
            ]);
        }else{
            return Validator::make($data, [
                'fecha_inicio' => ['required', 'date_format:Y-m-d'],
                'fecha_fin' => ['required', 'date_format:Y-m-d'],
                'day' => ['nullable'],
                'comments' => ['required', 'string', 'max:255'],
                'document' => ['nullable','mimes:jpeg,png,jpg,pdf','max:2048' ]
            ]);           
        }
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

        event(new Registered($absences = $this->create($request, $request->all())));

        return $this->registered($request, $absences)
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

        event(new Registered($abscene = $this->update($request, $request->all())));

        return $this->registered($request, $abscene)
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
        if($data['day'] == 0){
            $data['fecha_fin'] = null;
        }
        if($request->file('document')){
            $path = $request->file('document')->getRealPath();
            $document = file_get_contents($path);
            $base64 = base64_encode($document);
            $data['document'] = $base64;
            $data['mimetype'] = $request->file('document')->mime_content_type;
            $data['extension'] = $request->file('document')->extension();
        }
        $absence = Absence::create([
            'day' => $data['day'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin' => $data['hora_fin'],
            'document' => $data['document'],
            'mimetype' => $data['mimetype'],
            'extension' => $data['extension'],
            'comments' => $data['comments'],
            'user_id' => $request->user()->id
        ]);

        $administrators = Role::where('name', 'Administrador')->first()->users()->get();
        foreach ($administrators as $administrator) {
            $administrator->notify(new AbsencesNotification($absence));
        }
    }

    protected function update(Request $request, array $data)
    {
        if($request->file('document')){
            $path = $request->file('document')->getRealPath();
            $document = file_get_contents($path);
            $base64 = base64_encode($document);
            $data['document'] = $base64;
            $data['mimetype'] = $request->file('document')->getMimeType();
            $data['extension'] = $request->file('document')->getClientOriginalExtension();
        }
        $absence = Absence::find($data['id']);
        $absence->day = $data['day'];
        $absence->fecha_inicio = $data['fecha_inicio'];
        $absence->fecha_fin = $data['fecha_fin'];
        $absence->hora_inicio = $data['hora_inicio'];
        $absence->hora_fin = $data['hora_fin'];
        if($request->file('document')){
            $absence->document = $data['document'];
            $absence->mimetype = $data['mimetype'];
            $absence->extension = $data['extension'];
        }
        $absence->comments = $data['comments'];
        $absence->save();

        $administrators = Role::where('name', 'Administrador')->first()->users()->get();
        foreach ($administrators as $administrator) {
            $administrator->notify(new ModifiedAbscene($absence));
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
        return redirect('absences');
    }

    public function review()
    {
        $user = \Auth::user();
        if ($user->hasRole('Director')) {
            foreach ($user->departments as $department) {
                $absences[$department->name] = Department::with('users')->where('name', $department->name)->get();
            }
            foreach ($absences['Desarrollo']->flatMap->users as $user) {
                $absences[$department->name][$user->name] = Absence::where('user_id', $user->id)->get();
            }
            $data['absences'] = $absences;
            return view('absences.reviewDirector', $data);
        } else if ($user->hasRole('Administrador')) {
            $data['absences'] = Absence::with('user')->get();
        }
        return view('absences.review', $data);
    }

    public function single($id)
    {
        $data['absences'] = Absence::with('user')->where('id', $id)->first();
        return view('absences.single', $data);
    }

    public function adminAbsences()
    {
        $data['abnsences'] = Absence::with('user')->get();
        return view('absences.admin', $data);
    }
}
