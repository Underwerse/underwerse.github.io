<?php

	session_start();
	require_once 'connect.php';

	$lesson_group = $_POST['lesson_group'];

	mysqli_query($connect, "INSERT INTO `lessongroups` (`lesson_group`) VALUES ('$lesson_group')");


	header('Location: ../profile.php');

?>

