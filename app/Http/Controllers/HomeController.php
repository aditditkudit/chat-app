<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Chat;
use DB;

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
    public function index(Request $request, $id = null)
    {
        $messages = [];
        $otherUser = null;
        $user_id = Auth::id();
        if($id)
        {
            $otherUser = User::findorfail($id);
            $group_id = (Auth::id()>$id)?Auth::id().$id:$id.Auth::id();
            $messages = Chat::where('group_id', $group_id)->get()->toArray();
            Chat::where(['user_id'=>$id,'other_user_id'=>$user_id,'is_read'=>0])->update(['is_read'=>1]);
        }
        $friends = User::where('id', '!=', Auth::id())->select('*',DB::raw("(SELECT count(id) FROM chats where chats.other_user_id = $user_id and chats.user_id = users.id and is_read = 0) as unread_messages"))->get()->toArray();
        // dd($friends);
        return view('home', compact('friends', 'messages', 'otherUser', 'id'));
    }
}
