<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Numeration;

class Client extends Model
{
    protected $table = "clients";
    protected $primaryKey = "id";
    protected $connection = 'mysql';

    public function numerations()
    {
        return $this->belongsToMany(Numeration::class);
    }

    public function intervals()
    {
        $numerations = $this->numerations()->get();
        $numerations = $numerations->sortBy('number');

        if($numerations->count() != 0){
            $intervals = [];
            $intervalIndex = 0;

            foreach ($numerations as $key => $numeration) {
                if(!isset($intervals[$intervalIndex][0])){
                    $intervals[$intervalIndex][] = $numeration->number;
                }

                if($key != 0){
                    if($numeration->number == ($numerations[$key - 1]->number + 1)){
                        $intervals[$intervalIndex][1] = $numeration->number;
                    }else{
                        $intervalIndex++;
                        $intervals[$intervalIndex][0] = $numeration->number;
                    }
                }
            }
        }else{
            $intervals = [];
        }

        return $intervals;
    }
}
