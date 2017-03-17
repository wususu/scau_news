/**
 * Created by janke on 17-3-15.
 */
    window.name = true;
    window.passwd = true;
    $('.weui-dialog__btn').click(function () {
        $('.dialog').hide();
    });
    $('.link').click(function () {
        self.location='login';
    });

    function show_alert(message) {
        $('.new_dialog_message').html(message)
        $('div.new_dialog').show();
    }

    function close_alert() {
        $('.closenodata').click(function () {
            $('.new_dialog').hide();
        })
    }

    // 注册
    function checkValue() {
        var username = $.trim($('#nameInput').val());
        var passwd = $.trim($('#passWdInput').val());
        var rpasswd = $.trim($('#rpassWdInput').val());
        // test
        var method = 'register';
        if (username && passwd && rpasswd && passwd == rpasswd) {
            $.ajax({
                url: USER_API,
                dataType: 'json',
                type: 'POST',
                data: {
                    'username': username,
                    'password': passwd,
                    'method': method
                },
                beforeSend: function () {
                    $('#loadingToast').fadeIn(100);
                    setTimeout(function () {
                        $('#loadingToast').fadeOut(),2000
                    });
                },
                success: function (response) {
                    if (response.msgCode == '300') {
                        function out() {
                            $('#successToast').fadeOut();
                        }
                        $('#successToast').fadeIn(2000);
                        out();
                        location.href = LOGIN_URL;
                    }else if (response.msgCode == '202'){
                        show_alert("用户名不得包含特殊符号哦");
                    }
                },
                error:function () {
                    show_alert("异步error");
                }
            });
        }
    }
    // 验证用户名是否被注册
    function checkUserName() {
        $('#nameInput').focus(function () {
            $('.weui-icon-warn ').removeClass('weui-icon-warn');
            $('.weui-cell_warn').removeClass('weui-cell_warn');
        });
        $('#nameInput').keyup(function (){
            var username = $('#nameInput').val();
            if (username && username != '') {
                $.ajax({
                    url: USER_API,
                    data: {
                        'username': username,
                        'method': 'check'
                    },
                    dataType: 'json',
                    type: 'POST',
                    success: function (response) {
                        //　用户名已存在
                        if (response.msgCode == '302') {
                            window.name = false;
                            $('#nameCell').addClass('weui-cell_warn');
                            $('.nameCron').addClass('weui-icon-warn');
                            $('.username_exist').show();
                        }
                        // 用户名不存在
                        else if (response.msgCode == '301'){
                            window.name = true;
                            $('.nameCron ').removeClass('weui-icon-warn');
                            $('#nameCell').removeClass('weui-cell_warn');
                            $('.username_exist').hide();
                            $('.username_error').hide();
                        }
                        // 用户名不允许
                        else if(response.msgCode == '202'){
                            window.name = false;
                            window.name = false;
                            $('#nameCell').addClass('weui-cell_warn');
                            $('.nameCron').addClass('weui-icon-warn');
                            $('.username_error').show();
                        }
                    },
                    error:function () {
                        $('.dialog').show("异步error");
                    }
                });
            }
        });
    }

    function checkRPassWd(){
        var func = function () {
            var passWd = $.trim($('#passWdInput').val());
            var rpassWd = $.trim($('#rpassWdInput').val());
            // 密码校验通过
            if (passWd == rpassWd){
                $('.rpassWdCron ').removeClass('weui-icon-warn');
                $('#rpassWdCell').removeClass('weui-cell_warn');
                $('.rpasswd_error').hide();
                if (passWd != '') {
                    window.passwd = true;
                }
            }
            // 密码不一致
            else {
                window.passwd = false;
                $('#rpassWdCell').addClass('weui-cell_warn');
                $('.rpassWdCron').addClass('weui-icon-warn');
                $('.rpasswd_error').show();
            }
        };
        $('#rpassWdInput').keyup(func);
        $('#passWdInput').keyup(func);
    }

    checkUserName();
    checkRPassWd();

    $('.weui-btn-area').click(function () {
        if (window.name && window.passwd) {
            checkValue();
        }
        if (!window.name){
            $('.username_error').show();
        }
        if (!window.passwd){
            $('.rpasswd_error').show();
        }
        $('.weui-cells__warn').html('用户名已存在');
    });