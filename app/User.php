<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    //このユーザーが所有する投稿
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']); //favoritesの追加
    }
    
    //ユーザーがフォローしているユーザー
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    } 
    
    //ユーザーをフォローしているユーザー
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    //$userIdで指定されたユーザーをフォロー
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    //$userIdで指定されたユーザーのフォローを外す
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    //指定された$userIdのユーザーを、このユーザーがフォロー中であるか調べる。フォロー中ならtrue
    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        //このユーザーのフォローしているユーザーのidのみ取得、配列にいれる
        $userIds = $this->followings()->pluck('users.id')->toArray();
        
        //このユーザーのidもこの配列に加える
        $userIds[] = $this->id;
        
        //それらが所有する投稿に絞りこむ
        return Micropost::whereIn('user_id', $userIds);
    }
    
    //あるユーザーがお気に入りしている投稿を取得
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    //$micropostIdで指定された投稿をお気に入りにする
    public function favorite($micropostId)
    {
        //すでにお気に入りしているか確認
        $exist = $this->is_favorites($micropostId);
        
        /*これは後で確認
        $its_me = $this->id === $micropostId;
        */
        
        if ($exist) {
            return false;
        } else {
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    //$micropostIdで指定された投稿をお気に入りから外す
    public function unfavorite($micropostId)
    {
        //すでにお気に入りにしているか確認
        $exist = $this->is_favorites($micropostId);
        
        /*これも後で確認
        $its_me = $this->id === $micropostId;
        */
        
        if ($exist) {
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }
    
    public function is_favorites($micropostId)
    {
        return $this->favorites()->Where('micropost_id', $micropostId)->exists();
    }
}
