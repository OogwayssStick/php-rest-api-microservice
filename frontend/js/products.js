$("#sidebar").load("components/sidebar.html", function () {

    $('a[href="products.html"]').addClass("active");

});

$("#navbar").load("components/navbar.html", function () {

    $("#pageTitle").text("Products");

});
$("#sidebar").load("components/sidebar.html", function () {

    $('a[href="products.html"]').addClass("active");

});

$("#navbar").load("components/navbar.html", function () {

    $("#pageTitle").text("Products");

});
$("#pageTitle").text("Products");
toastr.options = {

    closeButton: true,

    progressBar: true,

    positionClass: "toast-top-right",

    timeOut: "2000"

};
let table = null;

$(document).ready(function () {

    loadProducts();

    $("#addProduct").click(function () {
        saveProduct();
    });

    $(document).on("click", ".editBtn", function () {

        $("#product_name").val($(this).data("name"));
        $("#description").val($(this).data("description"));
        $("#price").val($(this).data("price"));
        $("#discount").val($(this).data("discount"));
        $("#sale_status").val($(this).data("status"));

        $("#addProduct")
            .text("Güncelle")
            .attr("data-id", $(this).data("id"));

    });

    $(document).on("click", ".deleteBtn", function () {
        deleteProduct($(this).data("id"));
    });

});


function loadProducts() {

    $.ajax({

        url: "../api/products/getProducts.php?page=1&limit=100",

        type: "GET",

        headers: {

            Authorization: localStorage.getItem("token")

        },

        success: function (response) {

            if ($.fn.DataTable.isDataTable("#productsTable")) {

                $("#productsTable").DataTable().destroy();

            }

            $("#productTable").empty();

            response.products.forEach(function (product) {

                $("#productTable").append(`

<tr>
<td><img src="../${product.image}" width="45" height="45" class="rounded-circle border"></td>
<td>${product.id}</td>

<td>${product.product_name}</td>

<td>${product.description}</td>

<td>${product.price}</td>

<td>${product.discount}</td>

<td>${product.sale_status}</td>

<td>

<button
class="btn btn-warning btn-sm editBtn"

data-id="${product.id}"

data-name="${product.product_name}"

data-description="${product.description}"

data-price="${product.price}"

data-discount="${product.discount}"

data-status="${product.sale_status}">

Düzenle

</button>

<button
class="btn btn-danger btn-sm deleteBtn"

data-id="${product.id}">

Sil

</button>

</td>

</tr>

`);

            });

            table = $("#productsTable").DataTable({

                pageLength: Number(localStorage.getItem("pageSize") || 5),

                destroy: true,

                language: {

                    url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/tr.json"

                }

            });

        },

        error: function (xhr) {

            console.log(xhr.responseText);

            toastr.error("Ürünler yüklenemedi.");

        }

    });

}



function saveProduct() {

    let id = $("#addProduct").attr("data-id");

    let url = "../api/products/addProduct.php";

    if (id) {
        url = "../api/products/updateProduct.php";
    }

    let formData = new FormData();

    formData.append("id", id ?? "");
    formData.append("product_name", $("#product_name").val());
    formData.append("description", $("#description").val());
    formData.append("price", $("#price").val());
    formData.append("discount", $("#discount").val());
    formData.append("sale_status", $("#sale_status").val());

    if ($("#image")[0].files.length > 0) {
        formData.append("image", $("#image")[0].files[0]);
    }

    $.ajax({

        url: url,

        type: "POST",

        headers: {
            Authorization: localStorage.getItem("token")
        },

        data: formData,

        processData: false,

        contentType: false,

        success: function (res) {

            toastr.success(res.message);

            clearForm();

            loadProducts();

        },

        error: function (xhr) {

            console.log(xhr.responseText);

            toastr.error(xhr.responseJSON?.message || "İşlem başarısız.");

        }

    });

}


function deleteProduct(id) {

    Swal.fire({

        title: "Emin misiniz?",

        text: "Bu ürün kalıcı olarak silinecek.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#d33",

        cancelButtonColor: "#3085d6",

        confirmButtonText: "Evet, Sil",

        cancelButtonText: "Vazgeç"

    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        $.ajax({

            url: "../api/products/deleteProduct.php",

            type: "POST",

            contentType: "application/json",

            headers: {

                Authorization: localStorage.getItem("token")

            },

            data: JSON.stringify({

                id: id

            }),

            success: function (response) {

                toastr.success(response.message);

                loadProducts();

            },

            error: function (xhr) {

                toastr.error(xhr.responseJSON.message);

            }

        });

    });


}



function clearForm() {

    $("#product_name").val("");

    $("#description").val("");

    $("#price").val("");

    $("#discount").val("Yok");

    $("#sale_status").val("Satışta");

    $("#addProduct")
        .text("Ürün Ekle")
        .removeAttr("data-id");

}
$(document).on("click", "#logoutBtn", function () {

    localStorage.clear();

    window.location.href = "login.html";

});
