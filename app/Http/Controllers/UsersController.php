<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    public function index()
    {
        //ユーザー一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        return view('users.index', [
            'users' => $users,
        ]);
    }
    
    public function show($id)
    {
        //idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        //モデル件数をロード
        $user->loadRelationshipCounts();
        
        //ユーザーの投稿を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        //ユーザー詳細ビューで表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }
    
    //ユーザーのフォロー一覧を表示するアクション
    public function followings($id)
    {
        $user = User::findOrFail($id);
        
        $user->loadRelationshipCounts();
        
        $followings = $user->followings()->paginate(5);
        
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }
    
    //ユーザーのフォロワー一覧を表示するアクション
    public function followers($id)
    {
        $user = User::findOrFail($id);
        
        $user->loadRelationshipCounts();
        
        $followers = $user->followers()->paginate(5);
        
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
    
    //ユーザーが追加したお気に入りを一覧表示するアクション
    public function favorites($id)
    {
        //id値でユーザーの検索取得
        $user = User::findOrFail($id);
        //モデルの件数を取得
        $user->loadRelationshipCounts();
        //ユーザーのお気に入り投稿一覧を取得
        $favorites = $user->favorites()->paginate(5);
        
        return view('users.favorites', [
           'user' => $user,
           'favorites' => $favorites,
        ]);
    }
}
