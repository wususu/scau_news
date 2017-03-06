<?php
namespace Home\Controller;
use Common\Controller\BaseController;


class IndexController extends BaseController
{
    # 查询条件
    private $query = array();
    # origin值
    private $origin = 0;
    # 查询天数
    private $days;
    # 默认每页条数
    private $limit = 8;
    # 第几页；
    private $page = 1;
    # 每页第一条
    private $start_limit = 0;
    private $News;
    private $count;
    # 最新信息的时间
    private $last_news_time;
    private $field=array(
        'id',
        'title',
        'href',
        'news_time',
        'origin'
    );

    public function __construct()
    {
        parent::__construct();
        $this->News = M('News');
        $this->first_load();
    }

    public function index(){
        $this->display('Index/index');
    }

    private function write_origin($origin){
        $this->origin = $origin;
    }

    private function write_days($days){
        $this->days = $days;
    }

    private function write_limit($limit){
        $this->limit = $limit;
    }

    private function write_page($page){
        $this->page = $page;
    }

    private function write_start_limit(){
        $start_limit = ($this->page-1) * $this->limit;
        if ($this->origin == 1){
            $num = $this->count['count_n'];
        }elseif ($this->origin == 2){
            $num = $this->count['count_p'];
        }else{
            $num = $this->count['count_a'];
        }
        if ($num > $this->count) {
            $this->error_request('已无更多数据');
        }else{
            $this->start_limit = $start_limit;
        }
    }

    private function write_last_news_time($time){
        $this->last_news_time = $time;
    }

    private function first_load(){
        $count['count_a'] = $this->News->count();
        $count['count_n'] = $this->News->where("origin = 1")->count();
        $count['count_p'] = $this->News->where("origin = 2")->count();
        $this->count_array($count);
        $time = $this->News->Max('news_time');
        $this->write_last_news_time($time);
    }

    private function count_array($count){
        foreach ($count as $key=>$value){
            $this->count[$key] = $value;
        }
    }

    # API: 开始
    public function api_start(){
        D('Users');
        header('Content-type: application/json');
        if (isset($_GET['origin']) && $_GET['origin']!=''){
            $this->write_origin($_GET['origin']);
        }
        if (isset($_GET['limit']) && $_GET['limit']!=''){
            $this->write_limit($_GET['limit']);
        }
        if (isset($_GET['days']) && $_GET['days']!=''){
            $this->write_days($_GET['days']);
        }
        if (isset($_GET['page']) && $_GET['page']!=''){
            $this->write_page($_GET['page']);
            $this->write_start_limit();
        }
        $this->my_echo($this->return_data());
    }

    private function build_query(){
        if ($this->origin != 0){
            $this->query['origin'] = $this->origin;
        }
        if (isset($this->days)){
            $str = "-".$this->days." day";
            $start_date = date('Y-m-d h:i:s', strtotime($str));
            $end_date = date('Y-m-d h:i:s');
//            $this->query['_string'] = "news_time < '".$end_date."'AND news_time > '".$start_date."'";
            $this->query['news_time'] = array('between', array($start_date, $end_date));
        }
    }

    private function judge_limit($limit_start, $limit){
        return $this->News->where($this->query)->limit($limit_start, $limit)->field($this->field);
    }

    private function judge_order($sql){
        return $sql->order('-news_time');
    }

    private function judge_select($sql){
        $result = $sql->select();
        return $result;
    }

    private function search_from_db(){
        $this->build_query();
        $sql_1 = $this->judge_limit($this->start_limit, $this->limit);
        $sql_2 = $this->judge_order($sql_1);
        $ret = $this->judge_select($sql_2);
        return $ret;
    }

    private function sove_data($ret){
        $data['count'] = $this->count;
        $data['origin'] = $this->origin;
        $data['limit'] = $this->limit;
        $data['page'] = $this->page;
        $data['num'] = \count($ret);
        $data['news'] = $ret;

        return $data;
    }

    private function return_data(){
        $ret = $this->search_from_db();
        $data = $this->sove_data($ret);
        $this->check_count();
        return $data;
    }

    private function check_count(){
        $new_count = $this->News->count();
        if ($new_count > $this->count){
            $this->first_load();
        }
    }

    private function my_echo($data){
        echo my_encode($data);
    }

    private function error_request($info){
        echo my_encode($info);
    }
}