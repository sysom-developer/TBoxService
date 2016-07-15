<?php

namespace comm\Model;

use \comm\Model\BaseModel as Model;

class Transmission extends Model{

    protected $table = 'transmission';

    function __construct(){
        parent::__construct();
    }


    function save($data){
        self::$_db->insert($this->table, $data);

    }
}
