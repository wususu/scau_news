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
    </style>
    <script type="text/javascript" src="/hmt_jwc_push/Public/jq.js"></script>
</head>
<body>
<div class="container">

</div>
</body>
<script type="text/javascript">
$(document).ready(function () {
    var process = function (data) {
        $.each(data, function (i, item) {
            $('.container').append(
                '<div id="'+item.id+'" value="'+item.origin+'" ><h1><a href="'+item.href+'">'+item.title+'</a></h1>'+
                '<span class="news_time">'+item.news_time+'</span>'+
                    '</div>'
            );
        });
    };

    $.ajax({
        url: 'get_publishes',
        type: 'GET',
        dataType: 'json',
        data: {},
        success: process
    });
});


</script>

</html>