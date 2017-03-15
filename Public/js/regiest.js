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

    function errorMsg(msg, msgCode) {
        $('.weui-cells__tips').html(msg+",错误码： "+msgCode);
    }

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
                    }else {
                        $('.dialog').show();
                    }
                },
                error:function () {
                    $('.dialog').show();
                }
            });
        }
    }

    function checkUserName() {
        $('#nameInput').focus(function () {
            $('.weui-icon-warn ').removeClass('weui-icon-warn');
            $('.weui-cell_warn').removeClass('weui-cell_warn');
            $('.weui-cells__tips').html('');
        });
        $('#nameInput').blur(function (){
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
                        if (response.msgCode == 302) {
                            window.name = false;
                            $('#nameCell').addClass('weui-cell_warn');
                            $('.nameCron').addClass('weui-icon-warn');
                            errorMsg("用户名已存在", response.msgCode);
                        } else if (response.msgCode == 301){
                            window.name = true;
                            $('.nameCron ').removeClass('weui-icon-warn');
                            $('#nameCell').removeClass('weui-cell_warn');
                            $('.weui-cells__tips').html('');
                        }else if(response.msgCode == 202){
                            window.name = false;
                            window.name = false;
                            $('#nameCell').addClass('weui-cell_warn');
                            $('.nameCron').addClass('weui-icon-warn');
                            errorMsg("用户名含有不允许的特殊符号", response.msgCode);
                        }
                    },
                    error:function () {
                        $('.dialog').show();
                    }
                });
            }
        });
    }

    function checkRPassWd(){
        var func = function () {
            var passWd = $.trim($('#passWdInput').val());
            var rpassWd = $.trim($('#rpassWdInput').val());
            if (passWd == rpassWd){
                $('.rpassWdCron ').removeClass('weui-icon-warn');
                $('#rpassWdCell').removeClass('weui-cell_warn');
                $('.weui-cells__tips').html('');
                if (passWd != '') {
                    window.passwd = true;
                }
            }
            else {
                window.passwd = false;
                $('#rpassWdCell').addClass('weui-cell_warn');
                $('.rpassWdCron').addClass('weui-icon-warn');
                errorMsg("密码不一致", '');
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
            errorMsg('用户名被注册', '');
        }
        if (!window.passwd){
            errorMsg('密码不一致', '');
        }
        $('.weui-cells__warn').html('用户名已存在');
    });