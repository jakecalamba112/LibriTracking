<?php
require_once "config.php";
$id = $_GET['id'];
$res = $mysqli->query("SELECT * FROM students WHERE student_id = '$id'");
if($row = $res->fetch_assoc()) {
    $row['success'] = true;
    echo json_encode($row);
} else {
    echo json_encode(['success' => false]);
}