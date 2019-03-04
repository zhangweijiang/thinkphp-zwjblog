<?php

namespace app\admin\model;

use think\Model;

/**
 * 会员模型
 */
class User1 Extends Model
{

    // 表名
    protected $name = 'user1';

    public function profile()
    {
        return $this->hasOne('Profile','user_id','id')->bind('email');
    }
    



}
