<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-12
 * Time: 下午3:06
 */

namespace Home\Controller;
use Common\Controller\BaseController;

class UserCenterController extends BaseController
{
    protected $M_Users;
    protected $D_Users;
    protected $userName;

    function __construct()
    {
        parent::__construct();
        $this->M_Users = M('Users');
        $this->D_Users = D('Users');
        $this->userName = $this->get_username_from_session();
    }

    public function index()
    {
        my_echo($this->user_data());
    }

    public function get_username_from_session()
    {
        return session('username')? session('username'): false;
    }

    protected function user_data()
    {

        if ($this->userName)
        {
            my_echo($this->userName);
            $userData = $this->D_Users->get_user_data($this->M_Users, $this->userName);
            my_echo($userData);
        }
    }

}