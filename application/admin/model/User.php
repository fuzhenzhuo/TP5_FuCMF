<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    public function abb()
    {
        $this->hasOne('Abbr','user_id','id');
    }
}
