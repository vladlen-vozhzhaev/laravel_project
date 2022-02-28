<?php

namespace App\Http\Controllers;

use App\Models\Friends;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public static function showProfile(Request $request){
        if(!empty($request->id)){
            $user = User::where('id', $request->id)->first();
        }else{
            $user = auth()->user();
        }
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

    public static function showUsers(){
        $users_fromDB = User::all();
        $friends_fromDB = Friends::all();
        $users = [];
        $friends = [];
        $newFriends = [];
        foreach ($users_fromDB as $user){
            foreach ($friends_fromDB as $friend){
                if($friend->friend_id == $user->id and $user->id != auth()->user()->getAuthIdentifier()) {
                    $friends[] = $user;
                    continue 2;
                }
            }
            $users[] = $user;
        }
        foreach ($users_fromDB as $user){
            foreach ($friends_fromDB as $friend){
                if($friend->friend_id == auth()->user()->getAuthIdentifier() and $user->id != auth()->user()->getAuthIdentifier() and !$friend->agree) {
                    $newFriends[] = $user;
                    continue 2;
                }
            }
        }
        return view('pages.users', ['users'=>$users, 'friends'=>$friends, 'newFriends'=>$newFriends]);
    }
    public static function addFriend(Request $request){
        $userId = auth()->user()->getAuthIdentifier();
        $friend = new Friends();
        $friend->user_id = $userId;
        $friend->friend_id = $request->friend_id;
        $friend->save();
        return redirect()->route('users');
    }
}
