<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-12
 * Time: 下午3:06
 */

namespace Home\Controller;
use Common\Controller\BaseController;

class UserCenter extends BaseController
{
    protected $userModel;
    protected $userDModel;

    function __construct()
    {
        $this->userModel = M('Users');
        $this->userDModel = D('Users');
    }

    public function index()
    {
        my_echo($this->user_data());
    }

    protected function user_data()
    {
        
    }


}