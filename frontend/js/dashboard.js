$("#pageTitle").text("Dashboard");

$.ajax({

    url: "../api/dashboard/stats.php",

    headers: {
        Authorization: token
    },

    success: function (res) {

        $("#productCount").text(res.products);

        $("#userCount").text(res.users);

        $("#discountCount").text(res.discount);

        $("#saleCount").text(res.active);

    }

});
$("#sidebar").load("components/sidebar.html", function () {

    $('a[href="dashboard.html"]').addClass("active");

});

$("#navbar").load("components/navbar.html", function () {

    $("#pageTitle").text("Dashboard");

});


$.ajax({

    url: "../api/dashboard/latestUsers.php",

    headers: {
        Authorization: token
    },

    success: function (res) {

        $("#lastUsers").empty();

        res.users.forEach(function (user) {

            $("#lastUsers").append(`

<li class="list-group-item">

<i class="bi bi-person-circle"></i>

${user.username}

</li>

`);

        });

    }

});

$.ajax({

    url: "../api/dashboard/latestProducts.php",

    headers: {
        Authorization: token
    },

    success: function (res) {

        console.log(res);

        $("#lastProducts").empty();

        res.products.forEach(function (p) {

            $("#lastProducts").append(`
                <tr>
                    <td>${p.id}</td>
                    <td>${p.product_name}</td>
                    <td>${p.price} ₺</td>
                </tr>
            `);

        });

    }

});

$.ajax({

    url: "../api/dashboard/chartData.php",

    headers: {
        Authorization: token
    },

    success: function (res) {

        const ctx = document.getElementById("productChart");

        new Chart(ctx, {

            type: "doughnut",

            data: {

                labels: [

                    "Satışta",

                    "Satışta Değil"

                ],

                datasets: [{

                    data: [

                        res.sale,

                        res.notSale

                    ],

                    backgroundColor: [

                        "#198754",

                        "#dc3545"

                    ]

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {

                        position: "bottom"

                    }

                }

            }

        });

    }

});