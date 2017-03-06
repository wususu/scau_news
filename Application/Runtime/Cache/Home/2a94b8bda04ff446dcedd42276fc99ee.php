<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>haha</title>
    <style type="text/css">
        bpdy{
            font-family: "Trebuchet MS", Arial, Verdana;
            width: 600px;
        }
        .news{
            height: 150px;
            width:500px;
        }
        .footer{
            height: 100px;
        }
    </style>
    <script type="text/javascript" src="/hmt_jwc_push/Public/jq.js"></script>
</head>
<body>
<div class="container">

</div>
<div class="note"></div>
<div class="footer"></div>
</body>

<script type="text/javascript">
$(document).ready(function () {
    window.limit=5;
    window.load_flag = false;
    window.origin='';
    window.day='';
    start();
    if ($(window).height() == $(document).height()){
        loading();
    }

    function loading() {
        window.load_flag = true;
        window.page++;
        $.ajax({
            url: 'api_start',
            type: 'GET',
            dataType: 'json',
            data: {
                'limit': window.limit,
                'origin': window.origin,
                'days': window.day,
                'page': window.page
            },
            success: function (data) {
                process(data);
                load_flag = false;
            }

        });
    }

    judge_load=function() {
        var wheight = $(window).height();
        var dheight = $(document.body).height();
        var scrollT = $(window).scrollTop();
        var flag = (dheight - scrollT - wheight) / wheight;
        console.log(flag);
        console.log(wheight);
        console.log(dheight);
        console.log(scrollT);
        if (flag < 0.02 && load_flag == false) {
            loading();
        } else {
            $('.note').html("已无更多数据");
        }
    };
    judge_load();
    $(window).scroll(judge_load);
});

var process = function (data) {
    var news = data.news;
    $.each(news, function (i, news) {
        $('div.container').append(
            '<div class="news" id="'+news.id+'" value="'+news.origin+'" ><h1><a href="'+news.href+'">'+news.title+'</a></h1>'+
            '<span class="news_time">'+news.news_time+'</span>'+
            '</div>'
        );
    });
};

var start = function constructer() {
    $.ajax({
        url: 'api_start',
        type: 'GET',
        dataType: 'json',
        data: {
            'limit': window.limit,
            'origin': window.origin,
            'days': window.day,
            'page': window.page
        },
        success: process
    });
    window.page=1;
}


</script>

</html>