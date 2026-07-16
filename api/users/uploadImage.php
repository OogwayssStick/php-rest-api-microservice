<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

$user = checkToken();

if (!isset($_FILES["image"])) {

    echo json_encode([
        "success"=>false,
        "message"=>"Dosya seçilmedi."
    ]);

    exit;

}

$ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

$allowed = ["jpg","jpeg","png"];

if(!in_array($ext,$allowed)){

    echo json_encode([
        "success"=>false,
        "message"=>"Sadece JPG ve PNG yükleyebilirsiniz."
    ]);

    exit;

}

$fileName = "user_" . $user["id"] . "." . $ext;

$target = "../../uploads/users/" . $fileName;

move_uploaded_file($_FILES["image"]["tmp_name"],$target);

$imagePath = "uploads/users/".$fileName;

$stmt = $conn->prepare("UPDATE users SET image=? WHERE id=?");

$stmt->bind_param("si",$imagePath,$user["id"]);

$stmt->execute();

echo json_encode([
    "success"=>true,
    "image"=>$imagePath
]);

$stmt->close();
$conn->close();