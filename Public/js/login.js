/**
 * Created by janke on 17-3-15.
 */

    $('.link').click(function () {
        self.location='regist';
    });
    function login() {
        var username = $.trim($('#nameInput').val());
        var passwd = $.trim($('#passWdInput').val());
        $.ajax({
            url: USER_API,
            data: {
                'username': username,
                'password': passwd,
                'method': 'login'
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function () {
                $('#loadingToast').fadeIn(200);
                $('#loadingToast').fadeOut();
            },
            success: function (response) {
                if (response.msgCode == 400){
                    $('#successToast').fadeIn(300);
                    window.location.replace(NEWS_URL);
                }else {
                    $('.weui-cells__tips').html('用户名或密码错误');
                }
            },
            error: function () {
                $('.dialog').show();
            }
        });
    }
    $('.weui-btn-area').click(function () {
        login();
    });