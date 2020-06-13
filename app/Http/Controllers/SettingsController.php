<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function viewSettings(){
        $user = Auth::user();
        return view('settings', ['user'=>$user]);
    }
    
    public function updateSettings(Request $request,$id){
        $rules=array(
            'login' => 'required|string|min:3',
            'aboutMe' => 'nullable|string|min:5',
            'avatarImgFile'=>'required_if: new-image,==,1|mimes:jpeg,bmp,png,jpg,svg,jfif'
        );
        if($request['new-email']=='1'){
            $rules['email'] = 'required|email|unique:users';
        }
        
        $this->validate($request, $rules);
        
        $user = User::find($id);
        $user->name = $request['login'];
        if($request['new-email']=='1'){
            $user->email = $request['email'];
        }
        $user->about_me = $request['aboutMe'];
        
        if($request['new-image'] == '1'){
            if($user->avatar_filename != 'defaultAvatar.png'){
                Storage::disk('public')->delete($user->avatar_filename);
            }
            $avatarFile = $request->file('avatarImgFile');
            $avatarFileExtension = $avatarFile->getClientOriginalExtension();
            Storage::disk('public')->put($avatarFile->getFilename().'.'.$avatarFileExtension, File::get($avatarFile));

            $user->avatar_mime = $avatarFile->getClientMimeType();
            $user->avatar_original_filename = $avatarFile->getClientOriginalName();
            $user->avatar_filename = $avatarFile->getFilename().'.'.$avatarFileExtension;
        }
        $user->save();
        return redirect('/page/'.Auth::id());    
    }
}
