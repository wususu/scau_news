<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-5
 * Time: 下午10:34
 */

namespace Home\Controller;
use Think\Controller;
use Home\Model;


class UsersController extends Controller
{
    protected $postMethod;
    protected $userName;
    protected $passWord;
    protected $status;
    protected $lastView;
    protected $isArgsError=false;
    // 300: 注册成功 400： 登录成功 301： 参数错误 302: 用户名已存在  303: 插入失败
    protected $msgCode;
    protected $User;
    protected $isSuccess=false;


    function __construct()
    {
        parent::__construct();
        if (isset($_POST['method']) && $_POST['method'] != '')
        {
            $this->postMethod = $_POST['method'];
            if (isset($_POST['username']) && $_POST['username'] != ''
                && isset($_POST['password']) && $_POST['password'] != '')
            {
                $this->User = M('Users');
                $this->set_username($_POST['username']);
                $this->set_passwd($_POST['password']);
            }
            else
            {
                $this->isArgsError = true;
            }
        }
        else
        {
            $this->isArgsError = true;
        }
    }

    protected function set_passwd($passwd)
    {
        $this->passWord = my_md5($passwd, get_salt());
    }

    protected function set_username($username)
    {
        $ret = $this->clean_data($username);
        $this->userName = $ret? $ret: $this->my_error(301);
    }

    protected function clean_data($data)
    {
        if (preg_match('/^[a-zA-Z@#_\.]+$/', $data))
        {
            return $data;
        }
        return false;
    }

    protected function my_error($errorCode)
    {
        $this->msgCode = $errorCode;
        my_echo($this->build_data());
    }

    protected function my_success($successCode)
    {
        $this->msgCode = $successCode;
        my_echo($this->build_data());
    }

    protected function my_return($msg)
    {
        echo my_encode($msg);
    }

    protected function build_data()
    {
        $data = array(
            'msgCode' => $this->msgCode,
        );
        if ($this->msgCode == C('LOGIN_SUCCESS'))
        {
            $data = array_merge($data, array(
                'username' => $this->userName,
                'uuid' => get_uuid(),
                'last_view' => $this->lastView
            ));
        }
        return $data;
    }


    public function user_api()
    {
        if (isset($_POST['method']) && $_POST['method'] != '')
        {
            if ($_POST['method'] == 'L')
            {
                $this->login();
            }
            elseif($_POST['method'] == 'R')
            {
                $this->register();
            }
        }
    }

    protected function register()
    {
        if (D('Users')->check_user($this->User, $this->userName))
        {
            $this->my_error(302);
        }
        else {
            D('Users')->regist_insert($this->User, $this->userName, $this->passWord) ?
                $this->my_success(300): $this->my_error(303);
        }
    }

    protected function login()
    {}

}