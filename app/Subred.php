<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subred extends Model
{
    protected $table = "subredes";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function mask2cdr() {
        $dq = explode(".",$this->mask);
        for ($i=0; $i<4 ; $i++) {
            $bin[$i]=str_pad(decbin($dq[$i]), 8, "0", STR_PAD_LEFT);
        }
        $bin = implode("",$bin); 
        return strlen(rtrim($bin,"0"));
    }
        
    public function isMask(){
        $s_ValidMasks = Array(
            '255.255.255.255' => false,
            '255.255.255.254' => true,
            '255.255.255.252' => true,
            '255.255.255.248' => true,
            '255.255.255.240' => true,
            '255.255.255.224' => true,
            '255.255.255.192' => true,
            '255.255.255.128' => true,
            '255.255.255.0' => true,
            '255.255.254.0' => true,
            '255.255.252.0' => true,
            '255.255.248.0' => true,
            '255.255.240.0' => true,
            '255.255.224.0' => true,
            '255.255.192.0' => true,
            '255.255.128.0' => true,
            '255.255.0.0' => true,
            '255.254.0.0' => true,
            '255.252.0.0' => true,
            '255.248.0.0' => true,
            '255.240.0.0' => true,
            '255.224.0.0' => true,
            '255.192.0.0' => true,
            '255.128.0.0' => true,
            '255.0.0.0' => true,
            '254.0.0.0' => true,
            '252.0.0.0' => true,
            '248.0.0.0' => true,
            '240.0.0.0' => true,
            '224.0.0.0' => true,
            '192.0.0.0' => true,
            '128.0.0.0' => true,
            '0.0.0.0' => false,
        );
        return isset($s_ValidMasks[$this->mask]);
    }
}