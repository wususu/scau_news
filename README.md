## 教务处新闻公告推送平台
### 为hmt运营提供聚合的实时教务通知，增加便利性
已完成功能模块：
1. 教务信息抓取入库
2. web平台异步获取后台数据
3. api设计

#### API规则
| API | 来源过滤 | 
| --- | ------ | 
| url | http://localhost/hmt_jwc_push/index.php/Home/Index/api_start |
| 请求方法 | GET |
| 参数 | *origin*：数据来源编号，默认0（0：所有来源 1：教务公告 2：教务新闻 ）<br/>*limit*：每次最多推送条数，默认8，即每页条数<br/>*page*：显示第几页数据，默认1<br/>*days*：显示多少天内的数据,默认最大值<br/>
| 用法示例 | http://localhost/hmt_jwc_push/index.php/Home/Index/api_start?origin=0&days=50&limit=5&page=3 |

返回数据格式：
<code>        
{

         "count": 
         {
            "count_a":"44",
            "count_n":"20",
            "count_p":"24"  
            },
        "origin":"0",
        "limit":"5",
        "page":"3",
        "num":5,
        "news":[
                {
                    "id":"25",
                    "title":"关于提供2016届毕业生重要专业课程信息的通知",
                    "href":"http:\/\/jwc.scau.edu.cn\/jwc\/index.php?m=content&c=index&a=show&catid=24&id=357",
                    "news_time":"2017-02-20 11:41:31","origin":"2"
                    },
                {
                    "id":"26",
                    "title":"关于在超星尔雅网修读MOOC课程的通知",
                    "href":"http:\/\/jwc.scau.edu.cn\/jwc\/index.php?m=content&c=index&a=show&catid=19&id=356",
                    "news_time":"2017-02-17 14:41:20",
                    "origin":"2"
                    },
                {
                    "id":"27",
                    "title":"2015级第一周学科通识训练安排通知",
                    "href":"http:\/\/jwc.scau.edu.cn\/jwc\/index.php?m=content&c=index&a=show&catid=19&id=354",
                    "news_time":"2017-02-16 09:43:11",
                    "origin":"2"
                    },
                {
                    "id":"3",
                    "title":"学校检查新学期教学准备工作",
                    "href":"http:\/\/www.scau.edu.cn\/xw\/201702\/t20170216_144423.htm",
                    "news_time":"2017-02-16 00:00:00",
                    "origin":"1"
                    },
                {
                    "id":"28",
                    "title":"关于做好2017年本科学生转专业报名与录取工作的通知",
                    "href":"http:\/\/jwc.scau.edu.cn\/jwc\/index.php?m=content&c=index&a=show&catid=20&id=335",
                    "news_time":"2017-02-15 15:09:00",
                    "origin":"2"
                    }
                ]
                
        }
</code>