<?php

	session_start();
	require_once 'connect.php';

	$study_name = $_POST['study_name'];

	mysqli_query($connect, "INSERT INTO `studies` (`study_name`) VALUES ('$study_name')");


	header('Location: ../profile.php');

?>

