<?php

namespace App\Http\Controllers;

use App\Post;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if(Auth::check() && Auth::user()->isAdmin){
            $posts = Post::all();
        }else{
            $query = Post::where('isPublic',true);
            if(Auth::check()){
                $query = $query->orWhere('user_id',Auth::id());
            }
            $posts = $query->orderByDesc('created_at')->get();
        }
        return view('home', array('posts'=>$posts));
    }
    
    public function sortPosts(Request $request){
        if(Auth::check() && Auth::user()->isAdmin){
            $query = DB::table('posts');
        }else{
            $query = Post::where('isPublic',true);
            if(Auth::check()){
                $query = $query->orWhere('user_id',Auth::id());
            }
        }
        if(strlen($request['searchText'])>0){
            $query = $query->where(function ($sub_query)use($request) {
                $sub_query->where('title', 'LIKE', '%'.$request->get('searchText').'%')
                ->orWhere('short_description', 'LIKE', '%'.$request->get('searchText').'%');
            });
        }
        
        if($request['sortPopular']){
            $query = $query->orderBy('rating');
        }else{
            $query = $query->orderByDesc('created_at');
        }
        
        return $query->get();
    }
}
