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
    public function index()
    {
        $this->display('Index/index');
    }




}