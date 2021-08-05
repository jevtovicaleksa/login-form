<?php include 'header.php'; ?>

<?php 
$errorMesage = '';
$k = null;

	if (!isset($_SESSION['prijavljen']) || $_SESSION['rola'] == 'korisnik') {
		header("Location:index.php");
		exit();
	}


	if ($_SERVER['REQUEST_METHOD'] === 'POST'){

		$ime_kategorije = $_POST['imeKateg']; //input polje

		if (empty($ime_kategorije)) {
			$errorMesage = "Morate uneti naziv kategorije"; //prijavljuje gresku ukoliko je prazno input polje
		}
		


		if (isset($_GET['izmeni'])) { //ukoliko je izmeni dugme pritisnuto

		$sql = 'UPDATE kategorije SET k_ime = ? WHERE id = ?';
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $ime_kategorije, $_GET['izmeni']);
		$stmt->execute();
		$stmt->close();
			
		}else{ // ako nije setovano, dodaj u bazu
			$sql = "INSERT INTO kategorije (k_ime) VALUES (?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("s", $ime_kategorije);
			$stmt->execute();
			$stmt->close();
		}
		
	}else {
		if (isset($_GET['izmeni']) && !empty($_GET['izmeni'])) {
				$sql = "SELECT * FROM kategorije WHERE id = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s", $_GET['izmeni']);
				$stmt->execute();
				$result = $stmt->get_result();
				$kateg = $result->fetch_assoc();
				if ($kateg != null) {
					$k = $kateg;
				}
				$stmt->close();
		}
	}

	$sql = "SELECT * FROM kategorije";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();


//brisanje kategorije

	if (isset($_GET['izbrisi']) && !empty($_GET['izbrisi'])) {

		$id = $_GET['izbrisi'];
		$sql = "DELETE FROM kategorije WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $id);
		$stmt->execute();
		header("Location: kategorije.php");
	}

 ?>


<div>
	 <div class="container form-group col col-md-6 py-3 mt-5" style="background-color: white">
	 	<form action="kategorije.php?<?php echo ($k != null) ? 'izmeni='.$k['id'] : ''; ?>" method="POST">
		 		<label for="imeKateg">Ime kategorije</label>
		 		<input type="text" name="imeKateg" placeholder="Unesite ime kategorije" class="form-control" value="<?php echo($k != null)? $k['k_ime'] : ''; ?>">
		 		<p style="color: red"><?php echo $errorMesage; ?></p>
		 		<button type="submit" name="submit" class="btn btn-dark mt-2 pb-2">Unesi</button>
		 </form>
	 </div>

	 <div class="container col col-md-6 py-3" style="background-color: white">
	 	<table class="table table-bordered table-striped ">
	 		<thead>
	 			<tr>
	 				<th>Naziv kategorije</th>
	 				<th>Izbrisi</th>
	 				<th>Izmeni</th>
	 			</tr>
	 		</thead>
	 		<?php while($kategorije = $result->fetch_assoc()) { ?>
	 		<tbody>
	 			<tr>
	 				<td><?php echo $kategorije['k_ime']; ?></td>
	 				<td><a href="kategorije.php?izbrisi=<?php echo $kategorije['id']; ?>">Izbrisi</a></td>
	 				<td><a href="kategorije.php?izmeni=<?php echo $kategorije['id']; ?>">Izmeni</a></td>
	 			</tr>
	 		<?php }; ?>
	 		</tbody>
	 	</table>
	 </div>
</div>