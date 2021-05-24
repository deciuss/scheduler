<?php


namespace App\Scheduler\Normalization;


interface Writer
{
    public function appendInt(int $data) : void;
    public function appendBoolMatrix(array $data) : void;
    public function appendIntOneToMany(array $data) : void;
    public function appendIntArray(array $data) : void;
}