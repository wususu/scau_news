<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-5
 * Time: 下午9:16
 */

namespace Home\Model;
use Think\Model;

class UsersModel extends Model
{
    protected $trueTableName = 'users';
    protected $tablePrefix = '';
    protected $dbName = 'hmt_news';
    protected $fields = array(
        'id',
        'username',
        'passwd',
        'status',
        'last_views',
        'register_time',
        'last_login_time',
        'salt'
    );

    protected $pk = 'id';

    public function login_update($model, $username)
    {
        $data = array(
            'last_login_time' => date('Y-m-d H:i:s')
        );
        $result =$model->where("username='$username' ")->save($data);
        return $result;
    }

    public function regist_insert($model, $username, $password)
    {
        $salt = get_salt();
        $data = array(
            'username' => $username,
            'passwd' => $password,
            'salt' => $salt
        );
        try
        {
            $resp = $model->data($data)->add();
            if ($resp)
            {
                return true;
            }
        }
        catch (Exception $e){

        }
        return false;
    }

    public function check_user($model, $username)
    {
        $data['username'] = $username;
        $result = $model->where($data)->select();
        return $result? $result: false;
    }

    public function check_passwd($password, $user)
    {

        $md5_passwd = my_md5($password, $user[0]['salt']);
        return $md5_passwd == $user[0]['passwd'];
    }

    public function get_user_data($model, $username)
    {
        $data['username'] = $username;
        $result = $model->where($data)->field('username, last_views, register_time, last_login_time')->select();
        return $result? $result[0]: false;
    }

}