<?php include 'header.php'; ?>
<?php 

$errorPoruka = array('naziv' =>'', 'lozinka'=> '', 'potvrda' =>'');
$noviKorisnici = NULL;
$admin = "admin";


	if(!isset($_SESSION['prijavljen']) || $_SESSION['rola'] == 'admin'){
		header("Location:pregled.php");
		exit();
	}

//izlistavanje svih korisnika
	$sql = "SELECT * FROM users";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();

//brisanje korisnika
	if (isset($_GET['ukloni']) && !empty($_GET['ukloni'])) {
		$sql = "DELETE FROM users WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $_GET['ukloni']);
		$stmt->execute();
		header("Location:korisnici.php");
	}

//dodavanje novog korisnika
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$naziv_korisnika = $_POST['naziv'];
		$potvrda_lozinke = $_POST['potvrda'];
		$lozinka_korisnika = $_POST['lozinka'];

		if(empty($naziv_korisnika)){
			$errorPoruka['naziv'] = "Morate uneti naziv";
		}elseif(empty($lozinka_korisnika)){
			$errorPoruka['lozinka'] = "Morate uneti lozinku korisnika";
		 }elseif($lozinka_korisnika != $potvrda_lozinke){
		 	$errorPoruka['potvrda'] = "Lozinke se ne poklapaju";
		 }else{
		 	header("Location:korisnici.php");	
		 }

		  if($errorPoruka['naziv'] == '' && $errorPoruka['lozinka'] == '' && $errorPoruka['potvrda'] == '' && $lozinka_korisnika == $potvrda_lozinke){

		 if (isset($_GET['izmeni'])) {
		 	$sql = "UPDATE users set userName = ?, userPwd = ? WHERE id = ?";
		 	$stmt =$conn->prepare($sql);
		 	$passwordhash = password_hash($lozinka_korisnika, PASSWORD_DEFAULT);
		 	$stmt->bind_param("sss", $naziv_korisnika, $passwordhash, $_GET['izmeni']);
		 	$stmt->execute();
		 	header("Location: korisnici.php");

		 }else{


		 	$sql = "INSERT INTO users (userName, userPwd, rola) VALUES (?, ?, ?)";
		 	$stmt = $conn->prepare($sql);
		 	$passwordhash = password_hash($lozinka_korisnika, PASSWORD_DEFAULT);
		 	$stmt->bind_param("sss", $naziv_korisnika, $passwordhash, $admin);
		 	$stmt->execute();
		 	$stmt->close();
		 	header("Location: korisnici.php");
		  }

		}
	}else{
		//ukoliko je dugme izmeni pritisnuto iscitaj mi podatke da bi ih prosledio u formu
		if(isset($_GET['izmeni']) && !empty($_GET['izmeni'])){
			$sql = "SELECT * FROM users WHERE id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("s", $_GET['izmeni']);
			$stmt->execute();
			$result = $stmt->get_result();
			$sviKorisnici = $result->fetch_assoc();
			if ($sviKorisnici != NULL) {
				$noviKorisnici = $sviKorisnici;
			}
		}
	}
 ?>


 <div class="d-flex">
 	<div class="form-group col col-md-6 mt-5">
 		<form method="POST" action="korisnici.php?<?php echo ($noviKorisnici != NULL)? 'izmeni='.$noviKorisnici['id'] : ''; ?>">
 			<label for="naziv">Naziv korisnika</label>
 			<input type="text" name="naziv" class="form-control" placeholder="Unesite naziv korisnika" value="<?php echo($noviKorisnici != NULL)? $noviKorisnici['userName'] : '' ?>">
 			<p style="color: red"><?php echo $errorPoruka['naziv']; ?></p>
 			<label for="lozinka">Lozinka korisnika</label>
 			<input type="password" name="lozinka" class="form-control" placeholder="Unesite lozinku korisnika">
 			<p style="color: red"><?php echo $errorPoruka['lozinka']; ?></p>
 			<label for="lozinka">Potvrda lozinke korisnika</label>
 			<input type="password" name="potvrda" class="form-control" placeholder="Unesite ponovo lozinku korisnika">
 			<p style="color: red"><?php echo $errorPoruka['potvrda']; ?></p>
 			<button class="btn btn-success mt-3">Dodaj korisnika</button> 			
 		</form>
 	</div>

 	<div class="col col-md-6 mt-5">
 		<table class="table table-bordered">
 			<thead>
 				<tr>
 					<th scope="col">Naziv korisnika</th>
 					<th scope="col">Rola korisnika</th>
 					<th scope="col">Izmeni / Ukloni</th>
 				</tr>
 			</thead>
 			<tbody>
 				<?php while ($korisnici = $result->fetch_assoc()) {  ?>
 					<tr>
 						<td><?= $korisnici['userName']; ?></td>
 						<td><?= $korisnici['rola']; ?></td>
 						<td>
 							<a href="korisnici.php?izmeni=<?php echo $korisnici['id']; ?>">Izmeni</a>
 							<a href="korisnici.php?ukloni=<?php echo $korisnici['id']; ?>">Ukloni</a>
 						</td>
 					</tr>
 				<?php } ?>
 			</tbody>
 		</table>
 	</div>
 </div>
