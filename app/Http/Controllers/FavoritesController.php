<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //データの登録／削除
    
    //これでだめなら Request $request,　追加
    public function store($id)
    {
        \Auth::user()->favorite($id);
        
        return back();
    }
    
    public function destroy($id)
    {
        \Auth::user()->unfavorite($id);
        
        return back();
    }
}
