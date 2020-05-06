<?php

	session_start();
	require_once 'connect.php';

	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$login = $_POST['login'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password_confirm = $_POST['password_confirm'];

	if ($password === $password_confirm) {

		# Проверка занятости логина
		$check_login = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '". $login ."'");

		if (mysqli_num_rows($check_login)) {
			$_SESSION['message'] = 'Login on jo varattu, valitse toinen';
				header('Location: ../register.php');
		};  

			$path = 'uploads/' . time() . $_FILES['avatar']['name'];
			if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $path)) {
				$_SESSION['message'] = 'Tapahtui jonkinlainen virhe';
				header('Location: ../register.php');
			}

			$password = md5($password);

			mysqli_query($connect, "INSERT INTO `users` (`name`, `surname`, `login`, `email`, `password`, `avatar`) VALUES ('$name', '$surname', '$login', '$email', '$password', '$path')");

			$_SESSION['message'] = 'Rekisteröinti onnistui!';
			header('Location: ../index.php');


	} else {
			$_SESSION['message'] = 'Salasanat eivät täsmää';
			header('Location: ../register.php');
	}

?>