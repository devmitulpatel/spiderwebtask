<?php

if(!function_exists('format_for_zoom')){
    function format_for_zoom(Illuminate\Support\Carbon $date){
            return $date->format('Y-m-d\TH:i:s.000\Z');
    }

}

if(!function_exists('enum')){
    function enum(Object $class){
        return $class->value;
    }

}
