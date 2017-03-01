<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    # 所有信息 json
    private $query;
    private $publishes;
    private $publishes_n;
    private $publishes_p;


    public function index(){
        $this->display('Index/index');
    }

    # API: 开始
    public function api_start(){
        if (isset($_GET['origin'])){
            echo $this->api_origin();
        }
    }

    # API: judge origin
    private function api_origin(){
        if ($_GET['origin'] == 1){
            return isset($this->publishes_n)? $this->publishes_n : $this->origin_publishes_from_db(1);
        }elseif ($_GET['origin'] == 2){
            return isset($this->publishes_p)? $this->publishes_p : $this->origin_publishes_from_db(2);
        }elseif ($_GET['origin'] == 0){
            return isset($this->publishes)? $this->publishes : $this->get_all_publishes();
        }else{
            return self::encode('origin 参数错误');
        }
    }

    # 根据origin获取数据
    private function origin_publishes_from_db($origin=1){
        $this->set_query("select id, title, href, origin, news_time from news where origin = $origin");
        $temp = $this->get_all_publishes_from_db($this->query);
        if ($temp == false){
            return false;
        }
        $temp = self::encode($temp);
        $origin==1? $this->publishes_n = $temp: $this->publishes_p = $temp;
        return $temp;
    }

    # 设置搜索语句
    private function set_query($query){
        $this->query = $query;
    }

    # 获取所有
    public function get_all_publishes(){
        $query = "select id, title, href, origin, news_time from news";
        $temp = $this->get_all_publishes_from_db($query);
        if ($temp == false){
            return false;
        }
        $temp = self::encode($temp);
        $this->publishes = $temp;
        return $this->publishes;
    }

    # 编码为 json
    static function encode($data){
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    # 数据库链接
    private function get_all_publishes_from_db($query){
        try{
            $mysql = new \PDO('mysql:host=localhost;dbname=hmt_news;charset=utf8', 'root', 'root' );
        }catch (\PDOException $e){
            die('数据库连接失败');
        }
        $result = $mysql->query($query);
        if ($result->rowCount() > 0){
            $ret = $result->fetchAll();
        }
        $result = null;
        $mysql = null;
        return isset($ret) ? $ret : false;
    }
}