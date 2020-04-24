<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecurringCharge extends Model
{
    protected $table = "recurring_charges";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
}
