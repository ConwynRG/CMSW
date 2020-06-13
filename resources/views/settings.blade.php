@extends('layouts.app')

@section('content')
<script>
$(document).ready(function(){
    $("#avatar-image-section").on("change",'.custom-file-input', function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").html(fileName.substr(0,40)); 
        
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#avatar-image')
                    .attr('src', e.target.result)
            };
            reader.readAsDataURL(this.files[0]);
            $('#new-image').val('1');
        }
    });
    
    $("#email-section").on("change",'#email', function() {
        console.log('hello!!');
        if($(this).val() == '{{ $user->email }}'){
            $('#new-email').val('0');
        }else{ 
            $('#new-email').val('1');
        }
    });
});
</script>
<div class="container">
          
        <div class="col-md-8 mx-auto">
            <h4 class="mb-3">Post content</h4>
            {{Form::open(array('action'=>['SettingsController@updateSettings',Auth::id()], 'enctype'=>'multipart/form-data','method'=>'PUT'))}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        {{ Form::label('login', 'Login') }}
                        {{ Form::text('login', $user->name, ['class' => 'form-control'.($errors->has('login') ? ' is-invalid' : '')]) }}
                         @if ($errors->has('login'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('login') }}</strong>
                            </span>
                        @endif
                          
                    </div>
                    <div class="col-md-6 mb-3" id="email-section">
                        {{ Form::label('email', 'E-Mail') }}
                        {{ Form::text('email', $user->email, ['class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'id'=>'email']) }}
                        {{ Form::hidden('new-email','0',['id'=>'new-email'])}}
                         @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    {{Form::label('aboutMe', 'About me')}} <span class="text-muted">(Optional)</span>
                    {{Form::textarea('aboutMe', $user->about_me, ['class' => 'form-control'.($errors->has('aboutMe') ? ' is-invalid' : ''), 'placeholder'=>'Show passion as much as you want here', 'rows'=>'6']) }}                    
                    @if ($errors->has('aboutMe'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('aboutMe') }}</strong>
                        </span>
                    @endif                    
                </div>
                <hr class="mb-4">
                <h4 class="mb-3">Profile Image Settings</h4>
                <div class="row" id="avatar-image-section">
                    <div class="col-md-6 m-auto mb-3">
                        {{ Form::hidden('new-image', '0', ['id'=>'new-image'])}}
                        <img id="avatar-image" class="img-thumbnail rounded-circle centerInDiv my-3" style="width:250px; height:250px; object-fit: cover;" src="{{ url('uploads/'.$user->avatar_filename) }}" alt="Avatar image">
                    </div>
                    <div class="col-md-6 mx-auto my-md-5">
                        {{Form::label('avatarImgFile', 'Avatar File Image') }}
                        <div class="input-group">
                            {{Form::file('avatarImgFile', ['class'=>'custom-file-input'.($errors->has('avatarImgFile') ? ' is-invalid' : ''), 'id'=>'avatarImgFile']) }}
                            {{Form::label('avatarImgFile', substr($user->avatar_original_filename,0,30), ['class'=>'custom-file-label']) }}
                            @if ($errors->has('avatarImgFile'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('avatarImgFile') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="btn-group m-auto" style="width:100%">
                    <a type="button" class="mt-0 btn btn-secondary btn-lg btn-block" href="{{url('page',Auth::id())}}">Cancel</a>
                    {{Form::submit('Save changes', ['class'=>'mt-0 btn btn-primary btn-lg btn-block'])}}
                </div>
            {{Form::close()}}
        </div>
      </div>

    </div>
@endsection

<style>
    img.centerInDiv{
        display:block;
        margin:auto;
    }
</style>


