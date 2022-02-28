<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChatController extends Controller
{
    public static function showChat(Request $request){
        $userId = $request->id;
        $currentUserId = auth()->user()->getAuthIdentifier();
        $chats = Chats::where('user_id', $currentUserId)->get();
        $findChat = null;
        foreach ($chats as $chat){
            $findChat = Chats::where([['user_id',$userId], ['name_chat',$chat->name_chat]])->first();
            if(!empty($findChat)){
                break;
            }
        }
        if(empty($findChat)){
            DB:Schema::create($currentUserId.'_'.$userId, function (Blueprint $table){
               $table->id();
               $table->timestamp('crated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
               $table->text('message');
               $table->bigInteger('from');
               $table->bigInteger('to');
            });
            $usersId = [$currentUserId, $userId];
            for($i=0; $i<2; $i++){
                $chat = new Chats();
                $chat->name_chat = $currentUserId.'_'.$userId;
                $chat->user_id = $usersId[$i];
                $chat->save();
            }
            return redirect()->route('showChat', ['id' => $userId]);
        }
        $user = User::where('id', $userId)->first();
        $chat = DB::table($findChat->name_chat)->get();
        return view('pages.chat', ['currentUser'=>auth()->user(), 'user'=>$user, 'chat'=>$chat]);
    }
    // Метод приёма сообшения от клиента
    public static function receivingMessage(Request $request){
        $formId = auth()->user()->getAuthIdentifier();
        $toId = $request->to;
        $message = $request->message;
        $chatId = $request->to;
        $chat = Chats::where('id',$chatId)->first();
        $chatName = $chat->name_chat;
        DB::table($chatName)->insert([
            'message'=>$message,
            'to'=>$toId,
            'from'=>$formId
        ]);
        return json_encode(['result'=>'success']);
    }
    public static function getMessage(Request $request){
        $chatId = $request->chat_id;
        $chat = Chats::where('id',$chatId)->first();
        $chatName = $chat->name_chat;
        $chat = DB::table($chatName)->get();
        return json_encode($chat);
    }
}
