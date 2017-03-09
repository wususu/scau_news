<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-9
 * Time: 下午8:25
 */

namespace Home\Controller;
use Think\Controller;

class UserViewController extends Controller
{
    public function login()
    {
        $this->display('Index/login');
    }
    public function regist()
    {
        $this->display('Index/regist');
    }
}