<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-9
 * Time: 下午7:41
 */

namespace Home\Controller;
use Common\Controller\BaseController;

class ViewController extends BaseController
{
    //　首页
    public function index()
    {
        $this->display('Index/index');
    }

    // 用户中心
    public function user()
    {
        $this->display('User/center');
    }

}