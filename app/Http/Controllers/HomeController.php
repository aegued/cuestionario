<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

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
        $user = auth()->user();

        if ($user->role == 'admin')
            return redirect()->route('dashboard');

        $questionnaire = $user->questionnaire;
        $questions = $questionnaire->questions;

        $start  = Carbon::createFromTimeString($questionnaire->start)->format('m/d/Y H:m:s');

        return view('front.home', [
            'user'          =>  $user,
            'questionnaire' =>  $questionnaire,
            'questions'     =>  $questions,
            'start'         =>  $start
        ]);
    }
}
