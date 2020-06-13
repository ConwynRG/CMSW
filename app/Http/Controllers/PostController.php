<?php

namespace App\Http\Controllers;

use App\Post;
use App\Image;
use App\Comment;
use App\PostReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }
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
        $userPosts = Post::where('user_id',Auth::id())->orderByDesc('created_at')->paginate(10);
        return view('create_post', array('posts'=>$userPosts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $rules=array(
            'title' => 'required|string|min:3',
            'short-description' => 'nullable|string|min:5',
            'description' => 'nullable|string|min:3',
            'type' => 'required|min:1|',
            'access' => 'required',
            'imgMain' => 'required|integer|min:1|max:5',
        );
        
        for($i = 1; $i<=$request['image-count'];$i++){
            $rules['imgFile'.$i]= 'required|mimes:jpeg,bmp,png,jpg,svg,jfif';
            $rules['imgTitle'.$i] = 'required|string|min:3';
            $rules['image-description'.$i] = 'nullable|string|min:5';
        }
        
        $this->validate($request, $rules);
        
        $newPost = new Post();
        $newPost->title = $request['title'];
        if(isset($request['short-description']) && !empty($request['short-description'])){
            $newPost->short_description = $request['short-description'];
        }else{
            $newPost->short_description = null;
        }
        
        if(isset($request['description']) && !empty($request['description'])){
            $newPost->description = $request['description'];
        }else{
            $newPost->description = null;
        }
        $newPost->isRecipe = $request['type'] == 'recipe' ? true : false;
        $newPost->isPublic =  $request['access'] == 'public' ? true : false;
        $newPost->rating = 0;
        $newPost->user_id = Auth::id();
        $newPost->page_id = Auth::id();
        $newPost->mainImage_id = 0;
        $newPost->save();
        
        for($i = 1; $i <= $request['image-count']; $i++){
            $newImage = new Image();
            $newImage->title = $request['imgTitle'.$i];
            $newImage->description = $request['image-description'.$i];
            $imageFile = $request->file('imgFile'.$i);
            $imgFileExtension = $imageFile->getClientOriginalExtension();
            Storage::disk('public')->put($imageFile->getFilename().'.'.$imgFileExtension, File::get($imageFile));
            
            $newImage->mime = $imageFile->getClientMimeType();
            $newImage->original_filename = $imageFile->getClientOriginalName();
            $newImage->filename = $imageFile->getFilename().'.'.$imgFileExtension;
            $newImage->post_id = $newPost->id;
            $newImage->save();
            if($i == $request['imgMain']){
                $newPost->mainImage_id = $newImage->id;
                $newPost->save();
            }
        }

        return redirect('/page/'.Auth::id());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        $images = Image::where('post_id',$post->id)->get();
        $comments = Comment::where('post_id', $post->id)->get();
        $review_value = 0;
        if(PostReview::where('post_id',$post->id)->where('user_id',AUth::id())->count()>0){
            $review_value = PostReview::where('post_id',$post->id)->where('user_id',AUth::id())->first()->review;
        }
        return view('post', array('post'=>$post, 'images'=>$images, 'comments'=>$comments, 
            'review_value'=>$review_value));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        if(Auth::id() == $post->user_id){
            $images = Image::where('post_id',$id)->get();
            $comments = Comment::where('post_id',$id)->orderByDesc('created_at')->paginate(10);
            return view('edit_post', array('images'=>$images, 'post'=>$post, 'comments'=>$comments));
        }
        else{
            return redirect('');
        }
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
        $post = Post::find($id);
        if(Auth::id() == $post->user_id){
            $rules=array(
                'title' => 'required|string|min:3',
                'short-description' => 'nullable|string|min:5',
                'description' => 'nullable|string|min:3',
                'type' => 'required|min:1|',
                'access' => 'required',
                'imgMain' => 'required|integer|min:1|max:5',
            );

            for($i = 1; $i<=$request['image-count'];$i++){
                $rules['imgFile'.$i]= 'required_if: newImage'.$i.',==,1|mimes:jpeg,bmp,png,jpg,svg,jfif';
                $rules['imgTitle'.$i] = 'required|string|min:3';
                $rules['image-description'.$i] = 'nullable|string|min:5';
            }

            $this->validate($request, $rules);

            for($i = 1; $i <= $request['image-toDelete-count']; $i++){
                $image = Image::find($request['imgToDelete'.$i]);
                Storage::disk('public')->delete($image->filename);
                $image->delete();
            }

            $post->title = $request['title'];
            if(isset($request['short-description']) && !empty($request['short-description'])){
                $post->short_description = $request['short-description'];
            }else{
                $post->short_description = null;
            }

            if(isset($request['description']) && !empty($request['description'])){
                $post->description = $request['description'];
            }else{
                $post->description = null;
            }
            $post->isRecipe = $request['type'] == 'recipe' ? true : false;
            $post->isPublic =  $request['access'] == 'public' ? true : false;
            $post->save();

            for($i = 1; $i <= $request['image-count']; $i++){
                if($request['newImage'.$i]=='1'){
                    $newImage = new Image();
                    $newImage->title = $request['imgTitle'.$i];
                    $newImage->description = $request['image-description'.$i];
                    $imageFile = $request->file('imgFile'.$i);
                    $imgFileExtension = $imageFile->getClientOriginalExtension();
                    Storage::disk('public')->put($imageFile->getFilename().'.'.$imgFileExtension, File::get($imageFile));

                    $newImage->mime = $imageFile->getClientMimeType();
                    $newImage->original_filename = $imageFile->getClientOriginalName();
                    $newImage->filename = $imageFile->getFilename().'.'.$imgFileExtension;
                    $newImage->post_id = $post->id;
                    $newImage->save();
                }

                if($i == $request['imgMain']){
                    $post->mainImage_id = $request['newImage'.$i]=='1' ? $newImage->id : $request['oldImageId'.$i];
                }
                $post->save();
            }

            return redirect('/page/'.Auth::id());
        }else{
            return redirect('');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        
        $post = Post::find($id);
        
        if($post->user_id == Auth::id() || Auth::user()->isAdmin){
            $images = Image::where('post_id',$id)->get();
            foreach($images as $image){
                Storage::disk('public')->delete($image->filename);
                $image->delete();
            }
            PostReview::where('post_id',$id)->delete();
            Comment::where('post_id',$id)->delete();
            $post->delete();
        }
        
        if(Auth::user()->isAdmin){
            return redirect()->back();
        }else{
            return redirect('/page/'.Auth::id());
        }
    }
}
