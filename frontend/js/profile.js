$("#sidebar").load("components/sidebar.html", function () {

    $('a[href="profile.html"]').addClass("active");

});

$("#navbar").load("components/navbar.html", function () {

    $("#pageTitle").text("Profile");

});

$.ajax({

    url: "../api/profile/getProfile.php",

    headers: {
        Authorization: token
    },

    success: function (res) {

        $("#userId").text(res.id);

        $("#usernameProfile").text(res.username);

    }

});

$("#changePassword").click(function () {

    $.ajax({

        url: "../api/profile/changePassword.php",

        type: "POST",

        headers: {
            Authorization: token
        },

        contentType: "application/json",

        data: JSON.stringify({

            oldPassword: $("#oldPassword").val(),

            newPassword: $("#newPassword").val(),

            newPassword2: $("#newPassword2").val()

        }),

        success: function (res) {

            if (res.success) {

                toastr.success(res.message);

                $("#oldPassword").val("");

                $("#newPassword").val("");

                $("#newPassword2").val("");

            } else {

                toastr.error(res.message);

            }

        }

    });

});

$("#uploadImage").click(function () {

    let file = $("#imageFile")[0].files[0];

    if (!file) {

        toastr.error("Fotoğraf seçiniz.");

        return;

    }

    let formData = new FormData();

    formData.append("image", file);

    $.ajax({

        url: "../api/users/uploadImage.php",

        type: "POST",

        headers: {
            Authorization: localStorage.getItem("token")
        },

        data: formData,

        processData: false,

        contentType: false,

        success: function (res) {

            if (res.success) {

                $("#profileImage").attr("src", "../" + res.image + "?" + Date.now());

                toastr.success("Fotoğraf güncellendi.");

            }
            
        }
        

    });

});
$.ajax({

    url: "../api/auth/verifyToken.php",

    headers: {
        Authorization: localStorage.getItem("token")
    },

    success: function (res) {

        $("#profileImage").attr("src", "../" + res.image);

    }

});