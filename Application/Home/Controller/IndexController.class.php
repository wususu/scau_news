<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    private $publishes = '';

    function __construct()
    {
        parent::__construct();
        $this->publishes = $this->get_publishes_from_db();

    }

    public function index(){
        $this->display('Index/index');
    }

    public function get_publishes(){
//        header("Content-Type:text/html;charset=utf-8");
        header('Content-type: application/json');
        echo json_encode($this->publishes, JSON_UNESCAPED_UNICODE);
    }

    private function get_publishes_from_db(){
        try{
            $mysql = new \PDO('mysql:host=localhost;dbname=hmt_news;charset=utf8', 'root', 'root' );
        }catch (\PDOException $e){
            die('数据库连接失败');
        }
        $query = "select id, title, href, origin, news_time from news";
        $result = $mysql->query($query);
        if ($result->rowCount() > 0){
            $ret = $result->fetchAll();
        }
        $result = null;
        $mysql = null;
        return isset($ret) ? $ret : false;
    }
}