<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { //認証済みの場合
            //ユーザーを取得
            $user = \Auth::user();
            //ユーザーの投稿した日時の降順で、１０個ずつ取得
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
              'user' => $user,
              'microposts' => $microposts,
            ];
        } 
        
        //welcomeビューで表示
        return view('welcome', $data);
    }
    
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);
        
        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);
        
        return back();
    }
    
    public function destroy($id)
    {
        //id値で投稿を検索取得
        $micropost = \App\Micropost::findOrFail($id);
        
        //認証済みユーザーが投稿の所有者である場合のみ処理できる
        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
        }
        //前のページへリダイレクト
        return back();
    }

}
