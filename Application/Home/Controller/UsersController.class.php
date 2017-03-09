<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-5
 * Time: 下午10:34
 */

namespace Home\Controller;
use Think\Controller;


class UsersController extends Controller
{
    protected $postMethod;
    protected $userName;
    protected $passWord;
    protected $status;
    protected $lastView;
    // 100: 退出成功   101: 初始值
    // 202: 参数错误   200: 关键字搜索成功   201： 关键字搜索不到相关数据
    // 300: 注册成功   301: 用户名不存在     302: 用户名已存在     303: 插入失败
    // 400: 登录成功   402: 已登录          403: 用户名或密码出错   404: 未登录
    protected $msgCode=101;
    protected $User;
    protected $isSuccess=false;


    function __construct()
    {
        parent::__construct();
        $this->User = M('Users');
//        if (session('?uuid'))
//        {
//            echo "aaaa";
//            $this->msgCode = 402;
//            $this->uuid = session('uuid');
//            $this->userName = session('username');
//            $this->lastView = session('lastView');
//        }
//        else {
            if (isset($_POST['method']) && $_POST['method'] != '') {
                $this->postMethod = $_POST['method'];
                if (isset($_POST['username']) && $_POST['username'] != '')
                {
                    $this->set_username($_POST['username']);
                }
                if (isset($_POST['password']) && $_POST['password'] != '')
                {
                    $this->set_passwd($_POST['password']);
                }
            }
//        }
    }

    protected function set_passwd($passwd)
    {
        $this->passWord = $_POST['method'] == 'register'? my_md5($passwd, get_salt()):$passwd;
    }

    protected function set_username($username)
    {
        $ret = $this->clean_data($username);
        if ($ret) {
            $this->userName = $ret;
        }
        else
        {
            $this->msgCode = 202;
        }
    }

    protected function set_uuid()
    {
        $this->uuid = get_uuid();
    }

    protected function set_session()
    {
        session('username', $this->userName);
        session('uuid', $this->uuid);
        session('last_view', $this->lastView);
    }

    protected function clean_data($data)
    {
        if (preg_match('/^[0-9a-zA-Z@#_\.]+$/', $data))
        {
            return $data;
        }
        return false;
    }

    protected function my_return()
    {
        my_echo($this->build_data());
    }

    protected function build_data()
    {
        $data = array(
            'msgCode' => $this->msgCode,
        );
        if (session('?uuid'))
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
        // 初始值或已登录
        if($this->msgCode == 101 || $this->msgCode == 402)
        {
            if (isset($_POST['method']) && $_POST['method'] != '')
            {
                switch ($_POST['method'])
                {
                    case 'login':
                        $this->login();
                        break;
                    case 'register':
                        $this->register();
                        break;
                    case 'out':
                        $this->logout();
                        break;
                    case 'check':
                        $this->check_name();
                        break;
                }
            }
        }
        $this->my_return();
    }

    protected function register()
    {
        if (D('Users')->check_user($this->User, $this->userName))
        {
            $this->msgCode = 302;
        }
        else {
            $this->msgCode = D('Users')->regist_insert($this->User, $this->userName, $this->passWord)?300: 303;
        }
    }

    protected function login()
    {

        if (! $user = D('Users')->check_user($this->User, $this->userName))
        {
            $this->msgCode = 301;
            return false;
        }
        if (!D('Users')->check_passwd($this->passWord, $user))
        {
            $this->msgCode = 403;
            return false;
        }
        $this->isLogin = true;
        $this->set_uuid();
        $this->set_session();
        $this->msgCode = 400;
        if(D('Users')->login_update($this->User, $this->userName))
        {
            //
        }
        return true;

    }

    protected function check_name()
    {
        if (isset($this->userName))
        {
            $this->msgCode = D('Users')->check_user($this->User,$this->userName)?302:301;
        }
    }

    protected function logout()
    {
        if (session('?uuid'))
        {
            unset_session();
            $this->msgCode = 100;
        }
    }

}