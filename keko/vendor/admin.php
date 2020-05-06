<?php

require 'userheader.php';

?>

<h2 style="color: #b7625c">Pääkäyttäjän paneeli</h2>
<br>

<div class="container">


<!-- Check teacher's data -->


	<h3>Tietojen tarkastus</h3>
	<div class="row justify-content-center admin_check">
		<div class="col-md-3 col-sm-12">
		<form class="" action="" method="post">
			<label>Aloituspäivä</label>
			<input type="date" name="lesson_date_from" value="<?php echo date('Y-m-d', strtotime("-6 day")); ?>" />
			<label>Päättymispäivä</label>
			<input type="date" name="lesson_date_to" value="<?php echo date('Y-m-d'); ?>" />
			<label>Valitse opettaja</label>
			<?php
	
				require_once "connect.php";
				$sql_teacher_name = "SELECT CONCAT(`name`, ' ', `surname`) AS `name` FROM `users` WHERE NOT login = 'admin'";
				$result_teacher_name = mysqli_query($connect, $sql_teacher_name);
				echo '<input name="teacher_name" list="teacher_name">
				<datalist id="teacher_name">';
				while($rows_name = mysqli_fetch_array($result_teacher_name)) {
					?>
					<option value="<?php echo $rows_name['name']; ?>">
					<?php
				}

			?>
			</datalist>

			<!-- <label>Valitse koulutuksen tyyppi</label>
			#<?php
		
			#	require_once "connect.php";
			#	$sql_study_type = "SELECT * FROM `studies`";
			#	$result_study_type = mysqli_query($connect, $sql_study_type);
			#	echo '<input name="study_name" list="study_name">
			#	<datalist id="study_name">';
			#	while($rows=mysqli_fetch_array($result_study_type)) {
			#	?>
			#	<option value="<?php echo $rows['study_name']; ?>">
			#	<?php
			#	}
			#	?>
				</datalist> -->
				<button name="admin_submit" class="btn btn-success" type="submit">Näytä tiedot</button>
			</div>
				
			<div class="col-md-9 col-sm-12 table_statistics">
				<h3>Tiedot valitusta ajanjaksosta</h3>
				
				<?php
	
					require_once 'connect.php';

					if (isset($_POST['admin_submit'])) {
						$lesson_date_from = $_POST['lesson_date_from'];
						$lesson_date_to = $_POST['lesson_date_to'];
						$teacher_name = $_POST['teacher_name'];

						$_SESSION['check_form'] = [
							"lesson_date_from" => $lesson_date_from,
							"lesson_date_to" => $lesson_date_to,
							"teacher_name" => $teacher_name
						];

					} else {
						$lesson_date_from = date('Y-m-d', strtotime("-6 day"));
						$lesson_date_to = date('Y-m-d');
						$teacher_name = '';
					}
					
					if (isset($_GET['del_id'])) { //check if it's row to delete
						//delete row from DB
						$sql = mysqli_query($connect, "DELETE FROM `basiclessons` WHERE `id` = {$_GET['del_id']}");
						if ($sql) { //show message of successfull deletion
							echo '<script>$(window).load(function() {
								$("#dialog").dialog();
							});
						</script>';
							echo '<div id="dialog" title="Viesti">Rivi poistettu</div>';
						} else {
							echo '<div id="dialog" title="Viesti">Virhe: ' . mysqli_error($connect) . '</div>';
						}
						echo '<script type="text/javascript">
						location.href="/profile.php"</script>';
					}
					
					if ($_SESSION['check_form'] != []) {
						$lesson_date_from = $_SESSION['check_form']['lesson_date_from'];
						$lesson_date_to = $_SESSION['check_form']['lesson_date_to'];
						$teacher_name = $_SESSION['check_form']['teacher_name'];
					}

					// echo '<pre>';
					// print_r($_SESSION);
					// print_r($_POST);
					// print_r($_GET);
					// echo '</pre>';

						$res_study_types = mysqli_query($connect, "SELECT DISTINCT `study_type` 
						FROM `basiclessons` 
						WHERE `id_teacher` = 
						(SELECT id_teacher FROM basiclessons 
						WHERE id_teacher =
						(SELECT id FROM users WHERE CONCAT(users.name, ' ', users.surname) = '$teacher_name') LIMIT 1) AND `lesson_date` >= '$lesson_date_from' AND `lesson_date` <= '$lesson_date_to'") or die;
						
						echo '<h4 style="text-align: center">'.$teacher_name.'</h4>';

						// echo '<pre>';
						// print_r($teacher_name);
						// echo '</pre>';

						while($res_study_types_rows = mysqli_fetch_assoc($res_study_types)) {

							$res_study_types_row = $res_study_types_rows['study_type'];

							$res = mysqli_query($connect, "SELECT id, `group_name`, `study_type`, TIME_FORMAT	(lesson_time, '%k:%i') as `lesson_time`, ROUND((TIME_TO_SEC(lesson_time) / 2700), 2) as lesson_qty, lesson_date, comment FROM `basiclessons` WHERE (SELECT CONCAT(users.name, ' ', users.surname) FROM users WHERE users.id = basiclessons.id_teacher LIMIT 1) = '$teacher_name' AND `study_type` = '$res_study_types_row' AND `lesson_date` >= '$lesson_date_from' AND `lesson_date` <= '$lesson_date_to'
							ORDER BY `lesson_date`") or die;

							$res_sum_lesson_time = mysqli_query($connect, "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(time_to_sec(lesson_time))), '%k:%i') as `sum_lesson_time` FROM `basiclessons` WHERE (SELECT CONCAT(users.name, ' ', users.surname) FROM users WHERE users.id = basiclessons.id_teacher LIMIT 1) = '$teacher_name' AND `study_type` = '$res_study_types_row' AND `lesson_date` >= '$lesson_date_from' AND `lesson_date` <= '$lesson_date_to'") or die;

							$row_sum_lesson_time = mysqli_fetch_assoc($res_sum_lesson_time);

							echo '<pre>';
							print_r($row_sum_lesson_time);
							echo '</pre>';
						
							echo '<div class="table_check_data_wrap"><table class="table_added_data">
							<h4>'.$res_study_types_row.':</h4>
							<tr>
							<th>Päivä</th><th>Ryhmä</th><th>Tuntien määrä</th><th>Oppituntien lkm</th><th>Kommentti</th>
							</tr>';

							while($row = mysqli_fetch_assoc($res)) {
								echo '<tr><td>'.$row['lesson_date'].'</td><td>'.$row['group_name'].'</td><td>'.$row['lesson_time'].'</td><td>'.$row['lesson_qty'].'</td><td>'.$row['comment'].'</td><td><a href="?del_id='.$row['id'].'">Poistaa</a></td></tr>';
							}
							echo '</table>';
						
							echo '<br>';

							$sum = mysqli_query($connect, "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(`lesson_time`))), '%k:%i') AS `total_time`, round(SUM(TIME_TO_SEC(`lesson_time`) / 2700),2) as `total_les_qty` FROM basiclessons WHERE (SELECT CONCAT(users.name, ' ', users.surname) FROM users WHERE users.id = basiclessons.id_teacher LIMIT 1) = '$teacher_name' AND `study_type` = '$res_study_types_row' AND `lesson_date` >= '$lesson_date_from' AND `lesson_date` <= '$lesson_date_to'") or die;
					
							echo '<table class="table_added_data table_summary_row">';
							while($summary = mysqli_fetch_assoc($sum)) {
								echo '<tr><td>'.'Kokonaisaika'.'</td><td></td><td>'.$summary['total_time'].'</td><td>'.$summary['total_les_qty'].'</td><td>'.$row['comment'].'</td><td></td></tr>';
							}
							echo '<tr><td>'.'Yhteenveto'.'</td><td></td><td>'.$row_sum_lesson_time['sum_lesson_time'].'</td><td>'.$row_sum_lesson_time['sum_lesson_time'].'</td><td></td><td></td></tr>';
							echo '</table></div>';
							echo '<br>';
						}
				
				?>
			</div>
		</form>
	</div>


	<!-- Add data to database -->


	<h3>Lisää tiedot tietokantaan</h3>				
	<div class="row justify-content-center admin_data_add">
		<div class="col-md-6 col-lg-4">
			<form action="vendor/lessontype.php" method="post">
			
				<h4>Lisää uuden oppitunnin</h4>
				<input type="text" name="lesson_type" placeholder="Syötä uuden">
					<button name="admin_add" class="btn btn-success" type="submit">Lisää tyyppi</button>
				<br>
	
			</form>
		</div>
		
		<div class="col-md-6 col-lg-4">
			<form action="vendor/study.php" method="post">
			
				<h4>Lisää koulutuksen tyyppi</h4>
				<input type="text" name="study_name" placeholder="Syötä uuden">
					<button name="admin_add" class="btn btn-success" type="submit">Lisää koulutus</button>
				<br>
			
			</form>
		</div>
		
		<div class="col-md-6 col-lg-4">
			<form action="vendor/lessongroup.php" method="post">
			
				<h4>Lisää uuden opetusryhmän</h4>
				<input type="text" name="lesson_group" placeholder="Syötä uuden">
					<button name="admin_add" class="btn btn-success" type="submit">Lisää ryhmä</button>
				<br>
			
			</form>
		</div>
		
	</div>

	<h3>Poistaa tiedot tietokannasta</h3>				
	<div class="row justify-content-center admin_data_add">
		<div class="col-md-6 col-lg-4">
			<form action="" method="post">
			
				<h4>Poistaa oppitunnin</h4>

				<?php
	
					require_once "connect.php";
					$sql_lessons = "SELECT * FROM `lessons`";
					$result_lessons = mysqli_query($connect, $sql_lessons);
					echo '<input name="lessons" list="lessons" autocomplete="off">
					<datalist id="lessons">';
					while($rows=mysqli_fetch_array($result_lessons)){
					?>
					<option value="<?php echo $rows['les_type']; ?>">
					<?php
					}
					?>
					</datalist>

					<button name="admin_lesson_remove" class="btn btn-warning" type="submit">Poista tyyppi</button>
				<br>
	
				<?php
					if (isset($_POST['admin_lesson_remove'])) {
						$lesson_type = $_POST['lessons'];
						if ($lesson_type != '') {
							require_once 'connect.php';
	
							mysqli_query($connect, "DELETE FROM `lessons` WHERE les_type = '$lesson_type'");
							echo '<script type="text/javascript">
							location.href="/profile.php"</script>';
						}
					}
				?>
			
			</form>
		</div>
		
		<div class="col-md-6 col-lg-4">
			<form action="" method="post">
			
				<h4>Poistaa koulutuksen tyyppi</h4>

				<?php
	
					require_once "connect.php";
					$sql_studies = "SELECT * FROM `studies`";
					$result_studies = mysqli_query($connect, $sql_studies);
					echo '<input name="studies" list="studies" autocomplete="off">
					<datalist id="studies">';
					while($rows=mysqli_fetch_array($result_studies)){
					?>
					<option value="<?php echo $rows['study_name']; ?>">
					<?php
					}
					?>
					</datalist>

					<button name="admin_study_remove" class="btn btn-warning" type="submit">Poista tyyppi</button>
				<br>
	
				<?php
					if (isset($_POST['admin_study_remove'])) {
						$studies = $_POST['studies'];
						if ($studies != '') {
							require_once 'connect.php';
	
							mysqli_query($connect, "DELETE FROM `studies` WHERE study_name = '$studies'");
							echo '<script type="text/javascript">
							location.href="/profile.php"</script>';
						}
					}
				?>
			
			</form>
		</div>
		
		<div class="col-md-6 col-lg-4">
			<form action="" method="post">
			
				<h4>Poistaa opetusryhmän</h4>
				
				<?php
	
					require_once "connect.php";
					$sql_lesson_groups = "SELECT * FROM `lessongroups`";
					$result_lesson_groups = mysqli_query($connect, $sql_lesson_groups);
					echo '<input name="lesson_groups" list="lesson_groups" autocomplete="off">
					<datalist id="lesson_groups">';
					while($rows=mysqli_fetch_array($result_lesson_groups)){
					?>
					<option value="<?php echo $rows['lesson_group']; ?>">
					<?php
					}
					?>
					</datalist>

					<button name="admin_lesson_group_remove" class="btn btn-warning" type="submit">Poista tyyppi</button>
				<br>
	
				<?php
					if (isset($_POST['admin_lesson_group_remove'])) {
						$lesson_group = $_POST['lesson_groups'];
						if ($lesson_group != '') {
							require_once 'connect.php';
	
							mysqli_query($connect, "DELETE FROM `lessongroups` WHERE lesson_group = '$lesson_group'");
							echo '<script type="text/javascript">
							location.href="/profile.php"</script>';
						}
					}
				?>
			
			</form>
		</div>
		
	</div>
</div>
