toastr.options = {

    closeButton: true,

    progressBar: true,

    positionClass: "toast-top-right",

    timeOut: 2000

};

$("#loginBtn").click(function () {

    $.ajax({

        url: "../api/auth/login.php",

        type: "POST",

        contentType: "application/json",

        data: JSON.stringify({

            username: $("#username").val(),

            password: $("#password").val()

        }),

        success: function (response) {

            localStorage.setItem(
                "token",
                response.token
            );

            toastr.success("Giriş başarılı");

            setTimeout(function () {

                window.location.href = "products.html";

            }, 1000);

        },

        error: function (xhr) {

            toastr.error(xhr.responseJSON.message);

        }

    });

});