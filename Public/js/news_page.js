/**
 * Created by janke on 17-3-15.
 */

    window.ajax = false;
    window.pageFlag = true;
    window.limit=5;
    window.load_flag = false;
    window.origin='';
    window.day='';
    window.KIND_FLAG = 'jwc';

    main();
    // 开始
    function main() {
        start();
        if ($(window).height() == $(document).height()){
            loading();
        }
    }

    // 顶部菜单逻辑
    var click = false;
    $('#link').click(function () {
        if(click==false)
        {click=true;$('#ull').show()}else
        {$('#ull').hide();click = false;}
    });
    $('.item').click(function () {
        click = false;
        $('#ull').hide();
        $('.select').removeClass('select');
        $(this).addClass('select');
        $('#link').html($(this).html());
        // 重新加载
        var kind = $(".select").attr("id");
        if (kind != KIND_FLAG)
        {
            // reload()
            if (kind == 'jwc')
            {
                main();
                KIND_FLAG = kind;
            }
            else
            {
                KIND_FLAG = kind;
                $('#container').empty();
                show_alert();
                close_alert();
            }

        }
    });

    // 封装alert
    function show_alert() {
        console.log('in');
        $('div.nodata').show();
    }

    function close_alert() {
        $('.closenodata').click(function () {
            $('.nodata').hide();
        })
    }


    // 左右上角图标逻辑
    $('.weui-search-bar').hide();
    $('.search_icon').click(function () {
        $('.bar-nav').hide();
        $('#container').hide();
        $('.weui-search-bar').show();
        $('#search_input').focus();
    });
    $('#actionsheet_cancel_2').click(function () {
        $('#weui-android-actionsheet').hide();

    });
    $('.menu_icon').click(function () {
        $('#weui-android-actionsheet').show();
    });
    $('.weui-mask').click(function () {
        $('#weui-android-actionsheet').hide();

    });


$('.logout').click(function () {
        // 注销
    $.ajax({
        url:NEWS_API,
        data: {'method': 'out'},
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            if (response.msgCode == 100) {
                self.location=LOGIN_URL;
            }
        }
    });
});


if (window.pageFlag == true) {
    judge_load();
    $(window).scroll(judge_load);
}
// 搜索框事件
    // 点击搜索框事件
    $('#search_input').focus(function () {
        $('#container').hide();
        $('.weui-search-bar').addClass('weui-search-bar_focusing');
        $('#search_input').keyup(function () {
            if (window.ajax == true){
                myAjax.abort();
            }
            $('.search_show').empty();
            var keyWard = $.trim($('#search_input').val());
            if (keyWard != ''){
                myAjax = $.ajax({
                    url: NEWS_SEARCH_API,
                    data: {'keyWard': keyWard},
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function () {
                        window.ajax = true;
                    },
                    success: function (response) {
                        if (response.msgCode == 200){
                            bulidSearch(response.news);
                        }
                    },
                    complete: function () {
                        window.ajax = false;
                    }
                });
            }
        });
    });
    // 取消搜索事件
    $('#search_cancel').click(function () {
        $('.weui-search-bar').hide();
        $('.search_show').empty();
        $('.weui-search-bar').removeClass('weui-search-bar_focusing');
        $('#container').show();
        $('.bar-nav').show();
    });
    // 清除搜索文本事件
    $('#search_clear').click(function () {
        $('.search_show').empty();
        $('#search_input').val('').focus();
    });

// 组织搜索到的文本
function bulidSearch(data) {
    for (i in data) {
        var str = '';
        str += '<div class="weui-cell">'
            + '<div class="weui-cell__bd weui-cell_primary">'
            + '<p><a href="' + data[i]['href'] + '" >' + data[i]['title'] + '</a></p>'
            + '</div>'
            + '</div>';
        $('.search_show').append(str);
    }
}
// 组织数据为DOM，并插入
function process(data) {
    if (data.num == 0){
        window.pageFlag = false;
    }
    var news = data.news;
    $.each(news, function (i, news) {
        $('div#container').append(
            '<div class="news weui-media-box weui-media-box_text" id="'+news.id+'" value="'+news.origin+'" >' +
            '<h4 class="weui-media-box__title"><a href="'+news.href+'">'+news.title+'</a></h4>'+
            '<p class="weui-media-box__desc time" >'+news.news_time+'</p>'+
            '</div>'
        );
    });
}
// 初始请求
function start() {
    $.ajax({
        url: NEWS_API,
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

function loading() {
    window.load_flag = true;
    window.page++;
    $.ajax({
        url: NEWS_API,
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

function judge_load() {
    var wheight = $(window).height();
    var dheight = $(document.body).height();
    var scrollT = $(window).scrollTop();
    var flag = (dheight - scrollT - wheight) / wheight;
    if (flag < 0.02 && load_flag == false && window.pageFlag) {
        loading();
    } else {
        $('.note').html("已无更多数据");
    }
}