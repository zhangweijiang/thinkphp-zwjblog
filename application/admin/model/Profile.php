<?php

namespace app\admin\model;

use think\Model;

/**
 * 会员模型
 */
class Profile Extends Model
{

    // 表名
    protected $name = 'profile';
    public function user()
    {
        return $this->belongsTo('User1','user_id');
    }

    



}
