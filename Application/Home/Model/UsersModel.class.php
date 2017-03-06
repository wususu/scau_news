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

    public function login_update($model)
    {
        $data = array(
            'last_login_time' => ''
        );
        $result =$model->data($data)->save();
        echo 'login_update: '.$result.'<br/>';
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
        if ($result)
        {
            return true;
        }
        return false;
    }

    public function check_passwd($model, $username, $password)
    {
        $data = array(
            'username' => $username,
            'passwd' => $password
        );
        $result = $model->where($data)->select();
        if ($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}