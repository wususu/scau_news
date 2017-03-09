<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-9
 * Time: 下午3:43
 */

namespace Home\Controller;
use Common\Controller\BaseController;

class SearchNewsController extends BaseController
{
    // 数据表
    protected $MNews;
    // 搜索关键字
    protected $keyWard;

    protected $msgCode;

    function __construct()
    {
        parent::__construct();
        $this->MNews = M('News');
        $this->keyWard = clean_data($_GET['keyWard']);
    }

    public function index()
    {
        if (isset($_GET['keyWard']))
        {
            if ($result = D('News')->search($this->MNews, $this->keyWard))
            {
                $this->msgCode = 200;
            }
            else
            {
                $this->msgCode = 201;
            }
            my_echo($this->bulid_data($result));
        }
    }

    protected function bulid_data($data)
    {
        $n_data = array();
        foreach ($data as $item){

            array_push($n_data, array(
                'title' => $item['title'],
                'href' => $item['href']
            ));
        }
        $result = array(
            'msgCode' => $this->msgCode,
            'news' => $n_data
        );
       return $result;
    }
}