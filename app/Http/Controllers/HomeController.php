<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('home');
    }

    public function show($post)
    {
        $posts = ['post1' => 'Post new one', 'post2' => 'Post new second', 'post3' => 'Post third'];

        if(! array_key_exists($post, $posts)){
            abort(404, "Not exists");
        }
        return view('post', ['post' => $posts[$post]]);
    }
}
