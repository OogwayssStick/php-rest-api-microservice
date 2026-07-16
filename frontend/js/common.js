const token = localStorage.getItem("token");

if (!token) {
    location.href = "login.html";
}

$("#sidebar").load("components/sidebar.html");


$("#navbar").load("components/navbar.html");

$.ajax({

    url: "../api/auth/verifyToken.php",

    headers: {

        Authorization: token

    },

    success: function (res) {

        if (!res.success) {

            localStorage.clear();
            location.href = "login.html";
            return;
        }

        $("#username").text(res.username);

        // Rolü güncelle
        localStorage.setItem("role", res.role);

        // Sidebar yüklendiyse Users menüsünü kaldır
        if (res.role !== "admin") {
            $('#sidebar a[href="users.html"]').remove();
        }

    }

});

$(document).on("click", "#logoutBtn, #logoutBtn2", function (e) {

    e.preventDefault();

    $.ajax({

        url: "../api/auth/logout.php",

        type: "POST",

        headers: {
            Authorization: token
        },

        complete: function () {

            localStorage.clear();

            location.href = "login.html";

        }

    });

});

const theme = localStorage.getItem("theme");

if (theme == "dark") {

    $("body").addClass("dark");

}