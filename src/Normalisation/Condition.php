<?php


namespace App\Normalisation;


interface Condition
{
    public function check($item1, $item2) : bool;
}