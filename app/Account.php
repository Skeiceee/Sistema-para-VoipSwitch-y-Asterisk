<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = "accounts";
    protected $primaryKey = "id";
    protected $connection = 'mysql';

    public $timestamps = false;

    protected $fillable = [
        'id_user', 
        'title', 
        'username', 
        'password', 
        'description'
    ];

    public function getUsernameAttribute($value){
        return decrypt($this->attributes['username']);
    }

    public function getPasswordAttribute($value){
        return decrypt($this->attributes['password']);
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }
}
