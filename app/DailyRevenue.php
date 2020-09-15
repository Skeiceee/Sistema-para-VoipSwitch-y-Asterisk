<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyRevenue extends Model
{
    protected $table = "daily_revenues";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    
    public $timestamps = false;

    protected $fillable = [
        'id_client', 
        'id_voipswitch', 
        'date', 
        'login', 
        'minutes_real', 
        'seconds_real_total', 
        'minutes_effective', 
        'seconds_effective_total', 
        'sale', 
        'cost'
    ];
}
