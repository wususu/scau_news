<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-6
 * Time: 上午10:05
 */

namespace Common\Controller;
use Think\Controller;


class BaseController extends Controller
{
    protected $isLogin = false;
    protected $uuid;

    function _initialize()
    {
        // 验证登录
//        echo 'This is a init';
        if (!check_login())
        {
            if(IS_AJAX)
            {
                my_echo('您需要登录');
            }
            else
            {
                $this->error('您需要登录');
            }
        }
    }

}