<?php

	session_start();
	require_once 'connect.php';

	$user_id = $_SESSION['user']['id'];
	$lesson_date = $_POST['lesson_date'];
	$lesson_time = $_POST['lesson_time'];
	$group_name = $_POST['group_name'];
	$comment = $_POST['comment'];

	mysqli_query($connect, "INSERT INTO `timetracking` (`id`, `id_teacher`, `lesson_date`, `lesson_time`, `group_name`, `comment`) VALUES (NULL, '$user_id', '$lesson_date', '$lesson_time', '$group_name', '$comment')");


	header('Location: ../profile.php');

?>

