<?php

class VodsModel extends Model
{

    protected $connection = "DB_CONFIG2";

    public function test()
    {
        print_r($this->table("nvr_live_test1")->count());exit;
    }

}