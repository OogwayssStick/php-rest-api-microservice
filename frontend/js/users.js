$("#sidebar").load("components/sidebar.html", function () {
    $('a[href="users.html"]').addClass("active");
});

$("#navbar").load("components/navbar.html", function () {
    $("#pageTitle").text("Users");
});

$(document).ready(function () {

    $.ajax({

        url: "../api/users/getUsers.php",

        headers: {
            Authorization: localStorage.getItem("token")
        },

        success: function (res) {

            console.log(res);

            if (!res.success) {
                toastr.error(res.message);
                return;
            }

            $("#userTable").empty();

            res.users.forEach(function (u) {

                $("#userTable").append(`
<tr>

    <td>${u.id}</td>

    <td>${u.username}</td>

    <td>

        ${u.role === "admin"
                        ? '<span class="badge bg-danger">Admin</span>'
                        : '<span class="badge bg-primary">User</span>'
                    }

    </td>

    <td>${u.product_count}</td>

    <td>

        <button
            class="btn btn-warning btn-sm editBtn"
            data-id="${u.id}">

            <i class="bi bi-pencil"></i>

        </button>

        <button
            class="btn btn-danger btn-sm deleteBtn"
            data-id="${u.id}">

            <i class="bi bi-trash"></i>

        </button>

    </td>
    <td>

<img
src="../${u.image}"
width="45"
height="45"
class="rounded-circle border">

</td>

</tr>
`);;

            });

            if ($.fn.DataTable.isDataTable('#usersTable')) {
                $('#usersTable').DataTable().destroy();
            }

            $('#usersTable').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/tr.json"
                }
            });

        }

    });

});

$("#addUserBtn").click(function () {

    $("#userModal").modal("show");

});
$("#saveUser").click(function () {

    $.ajax({

        url: "../api/users/addUser.php",

        type: "POST",

        headers: {

            Authorization: token

        },

        contentType: "application/json",

        data: JSON.stringify({

            username: $("#newUsername").val(),

            password: $("#newPassword").val(),

            role: $("#newRole").val()

        }),

        success: function (res) {

            if (res.success) {

                toastr.success(res.message);

                $("#userModal").modal("hide");

                location.reload();

            }
            else {

                toastr.error(res.message);

            }

        }

    });

});
