<?php

require 'userheader.php';

?>

<div class="container">


<!-- Entering teacher's data -->


	<div class="row justify-content-center added_data_block">
		<div class="col-sm-12 col-md-3">
			<form action="vendor/lessons.php" method="post">
				<div class="form-group">
					<label>Päivämäärä</label>
					<input type="date" name="lesson_date" value="<?php echo date('Y-m-d'); ?>" />
					
					<label>Koulutuksen tyyppi</label>
					<?php
			
						require_once "connect.php";
			
						$sql_study_type = "SELECT * FROM `studies`";
						$result_study_type = mysqli_query($connect, $sql_study_type);
						echo '<input name="study_name" list="study_name">
						<datalist id="study_name">';
						while($rows=mysqli_fetch_array($result_study_type)) {
						?>
						<option value="<?php echo $rows['study_name']; ?>">
						<?php
						}
						?>
						</datalist>
			
					<label>Ryhmä</label>
					<?php
			
						require_once "connect.php";
			
						$sql_les_group = "SELECT * FROM `lessongroups`";
						$result_les_group = mysqli_query($connect, $sql_les_group);
						echo '<input name="group_name" list="group_name">
						<datalist id="group_name">';
						while($rows=mysqli_fetch_array($result_les_group)){
						?>
						<option value="<?php echo $rows['lesson_group']; ?>">
						<?php
						}
						?>
						</datalist>
			
					<label>Ilmoita oppitunnin lukumäärä:</label>
					<input type="time" id="lesson_time" name="lesson_time" value="01:00">
				</div>
				<textarea name="comment" placeholder="Kommentti"></textarea>
				<br>
				<button class="btn btn-success" type="submit">Lähetä tiedon</button>
				<br>
			
				<!-- <?php
					#if (isset($_SESSION['message'])) {
					#	echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
					#}
					#unset($_SESSION['message']);
				?> -->
			
			</form>
		</div>


<!-- Today entered data statistics -->


	<div class="col-sm-12 col-md-9">
		<form>

			<?php
				
				echo '<h3>Tiedot syötetty tänä päivänä</h3>';
		
				require_once 'vendor/connect.php';
				$user_id = $_SESSION['user']['id'];
				
			
				if (isset($_GET['del_id'])) { //check if it's row to delete
					//delete row from DB
					$sql = mysqli_query($connect, "DELETE FROM `basiclessons` WHERE `id` = {$_GET['del_id']}");
					// echo '<pre>';
					// print_r($_GET);
					// echo '</pre>';
					if ($sql) { //show message of successfull deletion
						echo '<script>$(window).load(function() {
							$("#dialog").dialog();
						});
					</script>';
						echo '<div id="dialog" title="Viesti">Rivi poistettu</div>';
					} else {
						echo '<div id="dialog" title="Viesti">Virhe: ' . mysqli_error($connect) . '</div>';
					}
					header('Location: /profile.php');
				}


				$res_study_types = mysqli_query($connect, "SELECT DISTINCT `study_type` 
				FROM `basiclessons` 
				WHERE `id_teacher` = '$user_id' AND date_format(`created_at`, '%Y-%m-%d') = CURDATE()") or die;
									
				while($res_study_types_rows = mysqli_fetch_assoc($res_study_types)) {
					$res_study_types_row = $res_study_types_rows['study_type'];
					$res = mysqli_query($connect, "SELECT id, `group_name`, `study_type`, TIME_FORMAT(lesson_time, '%k:%i') as `lesson_time`, ROUND((TIME_TO_SEC(lesson_time) / 2700), 2) as lesson_qty, lesson_date, comment FROM `basiclessons` WHERE `id_teacher` = '$user_id' AND `study_type` = '$res_study_types_row' AND date_format(`created_at`, '%Y-%m-%d') = CURDATE()") or die;
					// echo '<pre>';
					// print_r($res_study_types_rows);
					// echo '</pre>';

					echo '<div class="table_check_data_wrap"><table class="table_added_data">
					<h4>'.$res_study_types_row.':</h4>
					<tr>
					<th>Päivä</th><th>Ryhmä</th><th>Tuntimäärä</th><th>Oppituntien lkm</th><th>Kommentti</th>
					</tr>';
					while($row = mysqli_fetch_assoc($res)) {
						echo '<tr><td>'.$row['lesson_date'].'</td><td>'.$row['group_name'].'</td><td>'.$row['lesson_time'].'</td><td>'.$row['lesson_qty'].'</td><td>'.$row['comment'].'</td><td><a href="?del_id='.$row['id'].'">Poistaa</a></td></tr>';
					}
					echo '</table>';
			
					echo '<br>';

					$sum = mysqli_query($connect, "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(`lesson_time`))), '%k:%i') AS `total_time`, round(SUM(TIME_TO_SEC(`lesson_time`) / 2700),2) as `total_les_qty` FROM basiclessons WHERE `id_teacher` = '$user_id' AND `study_type` = '$res_study_types_row' AND date_format(created_at, '%Y-%m-%d') = CURDATE()") or die;
			
					echo '<table class="table_added_data table_summary_row">';
					while($summary = mysqli_fetch_assoc($sum)) {
						echo '<tr><td>'.'Kokonaisaika'.'</td><td></td><td>'.$summary['total_time'].'</td><td>'.$summary['total_les_qty'].'</td><td>'.$row['comment'].'</td><td></td></tr>';
					}
					echo '</table></div>';
					echo '<br>';
				}

			?>
		</form>
	</div>

	</div>


<!-- Check teacher's data by teacher -->
	

<h3>Tietojen tarkastus</h3>
	<div class="row justify-content-center admin_check">
		<div class="col-md-3 col-sm-12">
		<form class="" action="" method="post">
			<label>Aloituspäivä</label>
			<input type="date" name="lesson_date_from" value="<?php echo date('Y-m-d', strtotime("-6 day")); ?>" />
			<label>Päättymispäivä</label>
			<input type="date" name="lesson_date_to" value="<?php echo date('Y-m-d'); ?>" />
			
			
				<button name="admin_submit" class="btn btn-success" type="submit">Näytä tiedot</button>
			</div>
				
			<div class="col-md-9 col-sm-12 table_statistics">
				<h3>Tiedot valitusta ajanjaksosta</h3>

				<?php
	
					require_once 'connect.php';

					if (isset($_POST['admin_submit'])) {
						$lesson_date_from = $_POST['lesson_date_from'];
						$lesson_date_to = $_POST['lesson_date_to'];
					} else {
						$lesson_date_from = date('Y-m-d', strtotime("-6 day"));
						$lesson_date_to = date('Y-m-d');
					}
						$teacher_name = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname'];
						$user_id = $_SESSION['user']['id'];

					// echo '<pre>';
					// print_r($_SESSION);
					// print_r($_GET);
					// print_r($_POST);
					// echo '</pre>';

						if (isset($_GET['del_id'])) { //check if it's row to delete
							//delete row from DB
							$sql = mysqli_query($connect, "DELETE FROM `basiclessons` WHERE `id` = {$_GET['del_id']}");
							if ($sql) { //show message of successfull deletion
								echo '<script>
								$(window).load(function() {
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


						$res_study_types = mysqli_query($connect, "SELECT DISTINCT `study_type` 
						FROM `basiclessons` 
						WHERE `id_teacher` = '$user_id' AND `lesson_date` >= '$lesson_date_from' AND `lesson_date` <= '$lesson_date_to'") or die;

						while($res_study_types_rows = mysqli_fetch_assoc($res_study_types)) {
							$res_study_types_row = $res_study_types_rows['study_type'];

							$res = mysqli_query($connect, "SELECT id, `group_name`, `study_type`, TIME_FORMAT	(lesson_time, '%k:%i') as `lesson_time`, ROUND((TIME_TO_SEC(lesson_time) / 2700), 2) as lesson_qty, lesson_date, comment FROM `basiclessons` WHERE `id_teacher` = '$user_id' AND `study_type` = '$res_study_types_row' AND `lesson_date` >= '$lesson_date_from' AND `lesson_date` <= '$lesson_date_to'
							ORDER BY `lesson_date`") or die;
		
							echo '<div class="table_check_data_wrap"><table class="table_added_data">
							<h4>'.$res_study_types_row.':</h4>
							<tr>
							<th>Päivä</th><th>Ryhmä</th><th>Tuntimäärä</th><th>Oppituntien lkm</th><th>Kommentti</th>
							</tr>';
							while($row = mysqli_fetch_assoc($res)) {
								echo '<tr><td>'.$row['lesson_date'].'</td><td>'.$row['group_name'].'</td><td>'.$row['lesson_time'].'</td><td>'.$row['lesson_qty'].'</td><td>'.$row['comment'].'</td><td><a href="?del_id='.$row['id'].'">Poistaa</a></td></tr>';
							}
							echo '</table>';
					
							echo '<br>';
		
							$sum = mysqli_query($connect, "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(`lesson_time`))), '%k:%i') AS `total_time`, round(SUM(TIME_TO_SEC(`lesson_time`) / 2700),2) as `total_les_qty` FROM basiclessons WHERE `id_teacher` = '$user_id' AND `study_type` = '$res_study_types_row' AND `lesson_date` >= '$lesson_date_from' AND `lesson_date` <= '$lesson_date_to'") or die;
					
							echo '<table class="table_added_data table_summary_row">';
							while($summary = mysqli_fetch_assoc($sum)) {
								echo '<tr><td>'.'Kokonaisaika'.'</td><td></td><td>'.$summary['total_time'].'</td><td>'.$summary['total_les_qty'].'</td><td>'.$row['comment'].'</td><td></td></tr>';
							}
							echo '</table></div>';
							echo '<br>';
						}
					
				?>
			</div>
			
		</form>
	</div>

<!-- </> -->