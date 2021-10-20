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
        if($id)
        {
            $otherUser = User::findorfail($id);
            $group_id = (Auth::id()>$id)?Auth::id().$id:$id.Auth::id();
            $messages = Chat::where('group_id', $group_id)->get()->toArray();
        }
        $friends = User::where('id', '!=', Auth::id())->get()->toArray();
        return view('home', compact('friends', 'messages', 'otherUser', 'id'));
    }
}
