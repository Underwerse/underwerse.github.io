<?php

	$connect = mysqli_connect('localhost', 'root', '', 'kekolessons');

	if (!$connect) {
			die('Error connect to DataBase');
	}

?>