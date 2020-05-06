<?php

	session_start();
	require_once 'connect.php';

	$lesson_type = $_POST['lesson_type'];

	mysqli_query($connect, "INSERT INTO `lessons` (`les_type`) VALUES ('$lesson_type')");

	$_SESSION['message'] = 'Lisääminen onnistui!';
	header('Location: ../profile.php');

?>

