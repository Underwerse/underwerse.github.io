<?php

	session_start();
	require_once 'connect.php';
	
	$id = $_POST['id'];
	$query = "DELETE FROM test_table WHERE id='$id'";
	$sql = "DELETE FROM `timetracking` WHERE `id` = {$id}";
	mysqli_query($sql, $connect);

?>