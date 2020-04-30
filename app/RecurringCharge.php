<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RecurringCharge extends Model
{
    protected $table = "recurring_charges";
    protected $primaryKey = "id";
    protected $connection = 'mysql';

    function getCarbonDate(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->date, 'America/Santiago');
    }

    function getCarbonDateServiceStart(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->date_service_start, 'America/Santiago');
    }
}
