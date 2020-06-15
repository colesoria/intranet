<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use \Carbon\Carbon;
use App\Sign;
use App\Role;
use App\User;
use App\Rest;

use App\Notifications\SignIn;
use App\Notifications\SignOut;

class SignController extends Controller
{
    private $roles = ['Administrador', 'Super administrador', 'Director'];

    public function index()
    {
        $user = \Auth::user();
        $date = Carbon::now();
        $data['date'] = $date->isoFormat('YYYY-MM-DD');
        $data['time'] = $date->isoFormat('HH:mm');
        $data['name'] = $user->name;
        $sign = Sign::where('date', $data['date'])->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        if (!$sign) {
            return view('sign.in', $data);
        } else if (!$sign['out']) {
            $data['sign'] = $sign;
            return view('sign.out', $data);
        } else {
            return view('sign.in', $data);
        }
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
            'comments' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function in(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($sign = $this->create($request, $request->all())));
        return $this->registered($request, $sign)
            ?: redirect($this->redirectPath());
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function out(Request $request)
    {
        event(new Registered($sign = $this->update($request, $request->all())));
        return $this->signedIn($request, $sign)
            ?: redirect($this->redirectPath());
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rest(Request $request)
    {
        event(new Registered($rest = $this->createRest($request, $request->all())));
        return $this->registered($request, $rest)
            ?: redirect($this->redirectPath());
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unrest(Request $request)
    {
        event(new Registered($unrest = $this->updateRest($request, $request->all())));
        return $this->registered($request, $unrest)
            ?: redirect($this->redirectPath());
    }

    /**
     * Create a new Rest instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Rest
     */
    protected function createRest()
    {
        $user = \Auth::user();
        $date = Carbon::now();
        $data['date'] = $date->isoFormat('YYYY-MM-DD');
        $data['time'] = $date->isoFormat('HH:mm');
        $data['sign'] = Sign::where('date', $data['date'])->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        if (isset($data['sign'])) {
            $data['rest'] = Rest::where('sign_id', $data['sign']->id)->orderBy('created_at', 'DESC')->first();
            if (isset($data['rest']->in) || !isset($data['rest'])) {
                $rest = Rest::create([
                    'sign_id' => $data['sign']->id,
                    'out' => $data['time']
                ]);
            }
        }
    }

    protected function updateRest()
    {
        $user = \Auth::user();
        $date = Carbon::now();
        $data['date'] = $date->isoFormat('YYYY-MM-DD');
        $data['time'] = $date->isoFormat('HH:mm');
        $data['sign'] = Sign::where('date', $data['date'])->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        if (isset($data['sign'])) {
            $rest = Rest::where('sign_id', $data['sign']->id)->orderBy('created_at', 'DESC')->first();
            if (!isset($rest->in)) {
                $rest->in = $data['time'];
                $rest->save();
            }
        }
    }

    /**
     * Create a new Sign instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Sign
     */
    protected function create(Request $request, array $data)
    {
        $user = \Auth::user();
        $date = Carbon::now();
        $data['date'] = $date->isoFormat('YYYY-MM-DD');
        $data['time'] = $date->isoFormat('HH:mm');
        $data['comments'] = $request->comments;
        $data['sign'] = Sign::where('date', $data['date'])->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        if (!isset($data['sign']) || isset($data['sign']->out)) {
            $sign = Sign::create([
                'date' => $data['date'],
                'in' => $data['time'],
                'comments' => $data['comments'],
                'user_id' => $user->id,
            ]);
            $roles = Role::with('users')->where('name', 'Administrador')->orWhere('name', 'Director')->get();
            foreach ($roles as $role) {
                foreach ($role->users as $administrator) {
                    $dir = User::with('departments')->where('id', $administrator->id)->first();
                    if($user->departments[0]->id == $dir->departments[0]->id){
                        $dir->notify(new SignIn($sign));
                    }
                }
            }
        }
    }

    protected function update(Request $request, array $data)
    {
        $date = Carbon::now();
        $data['time'] = $date->isoFormat('HH:mm');
        $data['date'] = $date->isoFormat('YYYY-MM-DD');
        $user = \Auth::user();
        $sign = Sign::where('date', $data['date'])->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        $sign->out = $data['time'];
        $sign->save();
        $roles = Role::with('users')->where('name', 'Administrador')->orWhere('name', 'Director')->get();
        foreach ($roles as $role) {
            foreach ($role->users as $administrator) {
                $dir = User::with('departments')->where('id', $administrator->id)->first();
                if($user->departments[0]->id == $dir->departments[0]->id){
                    $administrator->notify(new SignOut($sign));
                }
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
    protected function registered(Request $request, $sign)
    {
        return view('sign/signed');
    }

    public function signedIn(Request $request, $ign)
    {
        return view('sign/signed');
    }

    public function today()
    {
        $date = Carbon::now();
        $data['date'] = $date->isoFormat('YYYY-MM-DD');
        $data['today'] = $data['date'];
        $data['limit_edit'] = $date->addDays(-5);
        $data['min'] = date('Y') . "-01-01";
        $data['max'] = date('Y') . "-12-31";
        $data['date_init'] = $data['min'];
        $data['date_end'] = $data['max'];
        $signs = Sign::with('rests')->where('user_id',\Auth::user()->id)->whereDate('date', $data['date'])->get();
        $data['signs'] = $signs;
        return view('sign.day', $data);
    }

    public function day($init, $end)
    {
        $date = Carbon::now();
        $data['max'] = $date->isoFormat('YYYY-MM-DD');
        $data['today'] = $data['max'];
        $data['limit_edit'] = $date->addDays(-5);
        $data['date'] = $init;
        $data['date_init'] = $init;
        $data['date_end'] = $end;
        $data['min'] = date('Y') . "-01-01";
        $signs = Sign::with('rests')->where('user_id',\Auth::user()->id)->whereDate('date', '>=', $init)->whereDate('date', '<=', $end)->get();
        $data['signs'] = $signs;
        foreach($signs as $v=>$sign){
            $data['total'][$v] = $this->calculateHours($sign);
        }
        return view('sign.day', $data);
    }

    private function calculateHours($sign){
        $in = new \DateTime($sign->in);
        if($sign->out){
            $out = new \DateTime($sign->out);
        }else{
            $out = new \DateTime('19:00:00');
        }
        $total = $in->diff($out);
        foreach($sign->rests as $v=>$rest){
            $out = new \DateTime($rest->out);
            $in = new \DateTime($rest->in);
            $totalRest[$v] = $out->diff($in);
            $totalRest[$v] = new \DateTime($totalRest[$v]->format('%H:%I'));
        }
        if(isset($totalRest)){
            $total = new \DateTime($total->format('%H:%I'));
            foreach($totalRest as $r){
                $total = $total->diff($r);
                $total = new \DateTime($total->format('%H:%I'));
            }
        }
        return $total->format('%H:%I');
    }

    public function edit($day)
    {
        $data['date'] = $day;
        $sign = Sign::with('rests')->where('user_id',\Auth::user()->id)->whereDate('date', $day)->first();
        $data['sign'] = $sign;
        return view('sign.edit', $data);
    }

    public function store(Request $request){
        $sign = Sign::with('rests')->where('user_id',\Auth::user()->id)->whereDate('date', $request->day)->first();
        $sign->in = $request->in;
        $sign->out = $request->out;
        foreach($sign->rests as $v=>$rest){
            $rest->out = $request->{'out-'.$rest->id};
            $rest->in = $request->{'in-'.$rest->id};
        }
        $sign->save();
        return redirect('/sign/day/'. $request->day . '/' .$request->day);
    }

    public function seeToday()
    {
        $admin = \Auth::user();
        if (!$admin->hasAnyRole($this->roles)) {
            return redirect('/');
        }
        $date = Carbon::now();
        $data['date'] = $date->isoFormat('YYYY-MM-DD');
        $data['max'] = $data['date'];
        $data['min'] = date('Y') . "-01-01";
        if ($admin->hasRole(['Administrador'])) {
            $signs = Sign::with('rests')->with('user')->whereDate('date', $data['date'])->get();
            $data['signs'] = $signs;
            return view('sign.seeAdminDay', $data);
        } elseif ($admin->hasRole(['Director'])) {
            foreach ($admin->departments as $department) {
                $users = User::with('departments')->get();
                foreach($users as $user){
                    if($user->departments[0]->id == $department->id){
                        $signs[$department->name][$user->name] = Sign::with('user')->where('user_id', $user->id)->whereDate('date', $data['date'])->get();
                    }
                }
            }
            $data['signs'] = $signs;
            return view('sign.seeAdminDayDirector', $data);
        }
    }
    public function seeDay($init, $end)
    {
        $admin = \Auth::user();
        if (!$admin->hasAnyRole($this->roles)) {
            return redirect('/');
        }
        $date = Carbon::now();
        $data['date_init'] = $init;
        $data['date_end'] = $end;
        $data['max'] = $date->isoFormat('YYYY-MM-DD');
        $data['min'] = date('Y') . "-01-01";
        if ($admin->hasRole(['Administrador'])) {
            $signs = Sign::with('rests')->with('user')->whereDate('date', '>=', $init)->whereDate('date', '<=', $end)->get();
            $data['signs'] = $signs;
            return view('sign.seeAdminDay', $data);
        } elseif ($admin->hasRole(['Director'])) {
            foreach ($admin->departments as $department) {
                $users = User::with('departments')->get();
                foreach($users as $user){
                    if($user->departments[0]->id == $department->id){
                        $signs[$department->name][$user->name] = Sign::with('rests')->where('user_id', $user->id)->whereDate('date', '>=', $init)->where('date', '<=', $end)->get();
                    }
                }
            }
            $data['signs'] = $signs;
            return view('sign.seeAdminDayDirector', $data);
        }
    }
}
