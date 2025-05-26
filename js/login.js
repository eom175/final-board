$(document).ready(function () {
    $("#main-content").load("ajax/load_login_form.php");

    $(document).on("click", "#loginBtn", function () {
        const userid = $("#userid").val();
        const password = $("#password").val();

        $.post("ajax/login.php", { userid, password }, function (response) {
            if (response === "success") {
                $("#main-content").load("list.php");
            } else {
                alert("Login failed: " + response);
            }
        });
    });

    $(document).on("click", "#logoutBtn", function () {
        $.post("ajax/logout.php", function () {
            $("#main-content").load("ajax/load_login_form.php");
        });
    });

    $(document).on("click", ".view-post", function () {
        const postId = $(this).data("id");
        $("#main-content").load("view.php?id=" + postId);
    });

    $(document).on("click", "#writeBtn", function () {
        $("#main-content").load("write.php");
    });

    $(document).on("click", "#listBtn", function () {
        $("#main-content").load("list.php");
    });

    $(document).on("click", ".edit-post", function () {
        const postId = $(this).data("id");
        $("#main-content").load("edit.php?id=" + postId);
    });
});
