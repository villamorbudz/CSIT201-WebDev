$(document).ready(function() {

    let session = [
        isLoggedin = false,
        userInfo = []
    ];

    // ADMIN
    autoLogin();

    $(function () {
        $('#datetimepicker1').datetimepicker();
    });

    if(!isLoggedin) {
        $('#loginModal').modal('show');
        $('#login-buttons').css('display','block');
    }
    
    $('#create-new-user').click(function(){
        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: {
                action: 'create-user',
                firstName: $('#newUser-firstName').val(),
                lastName: $('#newUser-lastName').val(),
                username: $('#newUser-username').val(),
                email: $('#newUser-email').val(),
                password: $('#newUser-password').val()
            },
            success: (data) => {
                let res = JSON.parse(data);
                if(res.success) {
                    $('#createUser-responseMSG').empty();
                    $('#createUser-responseMSG').append(`<div class="alert alert-success" role="alert">${res.message}</div>`);
                } else {
                    $('#createUser-responseMSG').empty();
                    $('#createUser-responseMSG').append(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
                }

                console.log("Create New User: " + data);
                setTimeout(function() {
                    $('#createUser-responseMSG').empty();
                    if(res.success) {
                        $('#createUserModal').modal('hide');
                        setTimeout(function() {
                            $('#loginModal').modal('show');
                        }, 200);
                    }
                }, 3000);
            },
        });
    });

    $('#createUserModal').on('hidden.bs.modal', function () {
        $(this).find('input').val('');
        $('#createUser-responseMSG').empty();
    });

    $('#login-button').click(function() {
        $.ajax({
            url: 'api.php',
            method: 'POST', 
            data: {
                action: 'user-login',
                username: $('#login-username').val(),
            },
            success:(data)=>{
                let res = JSON.parse(data);
                session = res.userInfo;
                console.log(res);

                if(res.success) {
                    $('#login-responseMsg').empty();
                    $('#login-responseMsg').append(`<div class="alert alert-success" role="alert">${res.message}</div>`);
                } else {
                    $('#login-responseMsg').empty();
                    $('#login-responseMsg').append(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
                }
                setTimeout(function() {
                    $('#login-responseMsg').empty();
                    if(res.success) {
                        $('#login-buttons').css('display','none');
                        $('#home-buttons').css('display','block');
                        $('#loginModal').modal('hide');
                    }
                }, 1500);
            },
        });
    });

    $('#loginModal').on('hidden.bs.modal', function () {
        $(this).find('input').val('');
        $('#login-responseMsg').empty();
    });

    function autoLogin() {
        $.ajax({
            url: 'api.php',
            method: 'POST', 
            data: {
                action: 'user-login',
                username: "admin",
            },
            success:(data)=>{
                let res = JSON.parse(data);
                session = res.userInfo;
                console.log(res);

                if(res.success) {
                    $('#login-responseMsg').empty();
                    $('#login-responseMsg').append(`<div class="alert alert-success" role="alert">${res.message}</div>`);
                } else {
                    $('#login-responseMsg').empty();
                    $('#login-responseMsg').append(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
                }
                setTimeout(function() {
                    $('#login-responseMsg').empty();
                    if(res.success) {
                        $('#login-buttons').css('display','none');
                        $('#home-buttons').css('display','block');
                        $('#loginModal').modal('hide');
                    }
                }, 1500);
            },
        });
    }
});