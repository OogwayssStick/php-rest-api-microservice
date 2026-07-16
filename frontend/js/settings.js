$("#sidebar").load("components/sidebar.html", function () {

    $('a[href="settings.html"]').addClass("active");

});

$("#navbar").load("components/navbar.html", function () {

    $("#pageTitle").text("Settings");

});

$("#theme").val(localStorage.getItem("theme") || "light");

$("#pageSize").val(localStorage.getItem("pageSize") || "5");

$("#saveSettings").click(function () {

    localStorage.setItem("theme", $("#theme").val());

    localStorage.setItem("pageSize", $("#pageSize").val());

    alert("Settings Saved");

    location.reload();

});