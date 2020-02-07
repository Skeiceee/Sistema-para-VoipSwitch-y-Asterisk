<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyRevenue extends Model
{
    protected $table = "daily_revenues";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
}
