<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Holiday;
use App\Remote;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $year = date('Y');
        $data['holidays'] = Holiday::with('user')->where('status', 'accepted')->where('fecha_inicio', 'like', '%' . $year . '%')->get();
        $data['remotes'] = Remote::with('user')->where('status', 'accepted')->where('date', 'like', '%' . $year . '%')->get();
        return view('home', $data);
    }
}
