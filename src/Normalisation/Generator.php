<?php


namespace App\Normalisation;


interface Generator
{
    public function generate() : array;
    public function getMode() : string;
}