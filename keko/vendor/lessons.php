<?php

	session_start();
	require_once 'connect.php';

	$user_id = $_SESSION['user']['id'];
	$lesson_date = $_POST['lesson_date'];
	$lesson_time = $_POST['lesson_time'];
	$group_name = $_POST['group_name'];
	$study_name = $_POST['study_name'];
	$comment = $_POST['comment'];

	mysqli_query($connect, "INSERT INTO `basiclessons` (`id_teacher`, `lesson_date`, `lesson_time`, `group_name`, `study_type`, `comment`) VALUES ('$user_id', '$lesson_date', '$lesson_time', '$group_name', '$study_name', '$comment')");

	// $_SESSION['message'] = 'Tiedöt lisätty!';
	// header('Location: ../profile.php');

?>

