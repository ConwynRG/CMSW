<?php

namespace App\Http\Controllers;

use App\Post;
use App\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $posts = Post::where('user_id', $id)->orderByDesc('created_at')->get();
        $timeDiff = array();
        foreach($posts as $post){
            $start = Carbon::parse($post->created_at);
            $end = Carbon::parse(Carbon::now());
            $hours = $end->diffInHours($start);
            $minutes = $end->diffInMinutes($start) % 60;
            $seconds = $end->diffInSeconds($start) % 60;

            $timeDiff[$post->id] ='';
            if($hours < 24){
                $timeDiff[$post->id] .=  __('messages.posted');
                if($hours >= 1){
                    $timeDiff[$post->id] .= ' '.$hours.' '.__('messages.hour');
                }
                $timeDiff[$post->id] .=  ' '.$minutes.' '.__('messages.minute');
                if($hours < 1){
                    $timeDiff[$post->id] .= ' '.$seconds.' '.__('messages.second');
                }
                $timeDiff[$post->id] .=' '.__('messages.ago');
            }
        }
        $page = Page::find($id);
        return view('personalPage', array('posts'=>$posts, 'page'=>$page, 'timeDif' => $timeDiff));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
