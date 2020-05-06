<div class="container">
	<div class="row justify-content-center">
		<div class="col">
			<form class="user_header">
				<img src="<?= $_SESSION['user']['avatar'] ?>" alt="user_avatar">
				<div class="user_header__wrap">
					<h2><?= $_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname'] ?></h2>
					<p><?= $_SESSION['user']['email'] ?></p>
					<a href="vendor/logout.php" class="logout">Kirjaudu ulos</a>
				</div>
			</form>
		</div>
	</div>
</div>