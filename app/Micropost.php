<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content'];
    
    //この投稿を所有するユーザー 

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    //ある投稿をお気に入りしているユーザー
    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }
}
