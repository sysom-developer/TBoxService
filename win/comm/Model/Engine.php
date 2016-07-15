<?php

namespace comm\Model;

use \comm\Model\BaseModel as Model;

class Engine extends Model{

    protected $table = 'engine';

    function __construct(){
        parent::__construct();
    }


    function save($data){
        self::$_db->insert($this->table, $data);

    }
}
