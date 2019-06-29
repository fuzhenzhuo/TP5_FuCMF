<?php

namespace app\admin\model;

use think\Model;

class Article extends Model
{
    
    public function cover()
    {
        return $this->hasMany('Cover', 'article_id');
    }
}
