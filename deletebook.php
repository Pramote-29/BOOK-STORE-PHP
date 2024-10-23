<?php
include "includes/config.php";

$id = $_GET['id'];

$sql = "DELETE FROM books WHERE id='$id'";
$conn->query($sql);

header('Location: admin.php');
?>
