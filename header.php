<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Kreni</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">

	<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  	<script>tinymce.init({selector:'textarea'});</script>

</head>
<body>

<?php include 'database.php'; ?>
<?php $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>

<div class="navbar navbar-expand-lg bg-light justify-content-center">
	<ul class="navbar-nav">
		<!--Ukoliko korisnik nije prijavljen, prikazi mu link do prijave -->
		<?php if (!isset($_SESSION['prijavljen'])) { ?>
			<li class="nav-item">
				<a class="<?php echo ($activePage == 'login') ? 'active' : ''; ?> nav-link" href="login.php">Prijava</a>
			</li> 
			<li class="nav-item">
				<a class="<?php echo ($activePage == 'prelistavanje') ? 'active' : ''; ?> nav-link" href="prelistavanje.php">Prelistavanje vesti</a>
			</li>
		<?php } ?>

		<!--Ukoliko je korisnik prijavljen i rola mu je admin, prikazi mu odgovarajuce stranice -->
		 <?php if(isset($_SESSION['prijavljen']) && $_SESSION['prijavljen'] == 1 && $_SESSION['rola'] == 'admin'){ ?>
		 	<li>
		 		<a class="<?php echo ($activePage == 'pregled') ? 'active' : ''; ?> nav-link" href="pregled.php">Pregled vesti</a>
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'video') ? 'active' : ''; ?> nav-link" href="video.php">Linkovi</a>	
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'kategorije') ? 'active' : ''; ?> nav-link" href="kategorije.php">Kategorije</a>
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'login') ? 'active' : ''; ?> nav-link" href="statistika.php">Statistika</a>
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'login') ? 'active' : ''; ?> nav-link" href="logout.php">Logout</a>
		 	</li>
		 

		  <?php } ?>

		 <!--Ukoliko je korisnik prijavljen i rola mu je superAdmin, prikazi mu odgovarajuce stranice -->
		 <?php if (isset($_SESSION['prijavljen']) && $_SESSION['prijavljen'] == 1 && $_SESSION['rola'] == 'superAdmin') { ?>
		 	<li>
		 		<a class="<?php echo ($activePage == 'pregled') ? 'active' : ''; ?> nav-link" href="pregled.php">Pregled vesti</a>
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'korisnici')? 'active' : ''; ?> nav-link" href="korisnici.php">Korisnici</a>
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'video') ? 'active' : ''; ?> nav-link" href="video.php">Linkovi</a>	
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'kategorije') ? 'active' : ''; ?> nav-link" href="kategorije.php">Kategorije</a>
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'login') ? 'active' : ''; ?> nav-link" href="logout.php">Logout</a>
		 	</li>

		 <?php } ?>
		 <!-- Ukoliko je korisnik prijavljen i rola mu je Korisnik-prazno, prikazi mu odgovarajuce stranice -->
		 <?php if (isset($_SESSION['prijavljen']) && $_SESSION['prijavljen'] == 1 && $_SESSION['rola'] == '') { ?>
		 	<li>
		 		<a class="<?php echo ($activePage == 'pregled') ? 'active' : ''; ?> nav-link" href="pregled.php">Pregled vesti</a>
		 	</li>
		 	<li>
		 		<a class="<?php echo ($activePage == 'login') ? 'active' : ''; ?> nav-link" href="logout.php">Logout</a>
		 	</li>
		 <?php } ?>

	</ul>
</div>