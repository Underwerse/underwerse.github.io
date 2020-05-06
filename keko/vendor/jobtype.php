<?php

	session_start();
	require_once 'connect.php';

	$job_name = $_POST['job_name'];

	mysqli_query($connect, "INSERT INTO `otherjobs` (`job_name`) VALUES ($job_name)");


	header('Location: ../profile.php');

?>

