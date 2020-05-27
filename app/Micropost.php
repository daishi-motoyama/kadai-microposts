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
}
