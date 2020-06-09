<?php

namespace App\Http\Controllers;

use App\Post;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $query = Post::where('isPublic',true);
        if(Auth::check()){
            $query = $query->orWhere('user_id',Auth::id());
        }
        $posts = $query->orderByDesc('created_at')->get();
        return view('home', array('posts'=>$posts));
    }
}
