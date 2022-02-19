<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public static function showProfile(){
        $user = auth()->user();
        return view('pages.profile', ['user'=>$user]);
    }
    public static function updateProfile(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname'=>['required', 'string', 'max:255'],
        ]);
        $name = $request->name;
        $lastname = $request->lastname;
        $email = $request->email;
        $password = $request->password;

        $user = auth()->user();
        $user->name = $name;
        $user->lastname = $lastname;
        if($email != $user->email){
            $request->validate(['email'=>['required', 'string', 'email', 'max:255', 'unique:users']]);
            $user->email = $email;
        }
        if(!empty($password)){
            $request->validate(['password' => ['required', Rules\Password::defaults()]]);
            $user->password = Hash::make($password);
        }
        if(isset($request->avatar)){
            $request->validate(['avatar'=>['image']]);
            $avatarInput = $request->file('avatar');
            $avatarDir = 'public/img/userAvatar';
            $filename = $user->getAuthIdentifier() . '_avatar.' . $avatarInput->getClientOriginalExtension();
            Storage::putFileAs($avatarDir, $avatarInput, $filename);
            $avatar = '/storage/img/userAvatar/' . $filename;
            $user->avatar = $avatar;
        }
        $user->save();
        return redirect()->route('profile');
    }
}
