<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Redirect, Response;

class FullCalendarController extends Controller
{
    public function index(Request $request)
    {
        $data = Event::get();
        return Response::json($data);
    }
}
