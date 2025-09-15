<?php
$id = $_GET['id'];
$sql = "DELETE FROM banners WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header("Location: index.php?page_layout=banner");
exit();
?>