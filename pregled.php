 
<?php include 'header.php'; ?>
<?php 
$slug = '';
$errorMessage = array('naslov'=> '', 'tekst'=>'', 'datum'=> '', 'file'=>'');

if (!isset($_SESSION['prijavljen'])) {
	header("Location: index.php");
	exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	$naslov = $_POST['naslov'];
	$tekst_vesti = $_POST['tekst'];
	$datum_vesti = $_POST['datum'];
	$file = $_FILES['file'];

	if(empty($naslov)){
		$errorMessage['naslov'] = "Morate uneti naslov vesti"; 
		}elseif (empty($tekst_vesti)) {
			$errorMessage['tekst'] = "Morate uneti tekst vesti";
		}elseif (empty($datum_vesti)) {
			$errorMessage['datum'] = "Morate uneti datum";		
		}elseif(empty($_FILES['file'])) {
			$errorMessage['file'] = "Morate uneti sliku";
		}
	else{
		$fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
		$fileSize = $file['size'];
		$fileError = $file['error'];
		$fileType = $file['type'];

		$fileEkstenzija = explode(".", $fileName);
		$ekstenzija = strtolower(end($fileEkstenzija));
		$moguceEkst = array('jpg', 'jpeg', 'png');

		if(in_array($ekstenzija, $moguceEkst)){
			if ($fileError === 0) {
				if($fileSize < 1000000){
					$novoImeFajla = uniqid("", true). "." .$ekstenzija;
					$fileLokacija = 'upload/'.$novoImeFajla;
					move_uploaded_file($fileTmpName, $fileLokacija);
				}else{
					echo "Velicina fajla je velika";
				}
			}else{
				echo "Postoji greska sa fajlom";
			}
		}else{
			echo "Ne mozete uneti ovaj format";
		}


		$pom = rand(10, 10000);
		$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $naslov))).'-' . $pom;

		if($_POST['kategorije'] == ""){
		$sql = "INSERT INTO news (naslov, vest, slug, datum, slike) VALUES (?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("sssss", $naslov, $tekst_vesti, $slug, $datum_vesti, $novoImeFajla);
		}else{

		$sql = "INSERT INTO news (naslov, vest, slug, datum, slike, category_id) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssssss", $naslov, $tekst_vesti, $slug, $datum_vesti, $novoImeFajla, $_POST['kategorija']);
		}

		$stmt->execute();
		$stmt->close();
	

	}




 
}

//spajamo dve tabele pomocu JOIN. Ovaj upit treba da nam vrati vrednosti gde se poklapaju id iz tabele kategorije i id_category iz tabele news

	
	$sql = "SELECT news.* , kategorije. k_ime FROM news LEFT JOIN kategorije ON news. category_id = kategorije. id";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result1 = $stmt->get_result();

	//izvlacimo sve iz kolone kategorija i prosledjujemo u select input
	$sql = "SELECT * FROM kategorije";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result2 = $stmt->get_result();

	//delete 
	if (isset($_GET['izbrisi']) && !empty($_GET['izbrisi'])) {
		$id = $_GET['izbrisi'];
		$sql = "DELETE FROM news WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $id);
		$stmt->execute();
		header("Location:pregled.php");
	}	
 ?>

<div>
	<div id="forma_vesti">
		<div class="container form-group py-4 mt-4"  style="background-color: white;">
			<form action="pregled.php" method="POST" enctype="multipart/form-data">
				<label for="naslov">Naslov vesti</label>
				<input type="text" name="naslov" placeholder="Unesi naslov" class="form-control">
				<div style="color: red"><?php echo $errorMessage['naslov']; ?></div>
				<label for="tekst_vesti" class="mt-4">Tekst vesti</label>
				<textarea name="tekst" placeholder="Unesite tekst vesti" class="form-control"></textarea>
				<div style="color: red"><?php echo $errorMessage['tekst']; ?></div>
				<label for="datum" class="mt-2">Datum vesti</label>
				<input type="date" name="datum" class="form-control">
				<div style="color:red"><?php echo $errorMessage['datum']; ?></div>
				<label for="slika" class="mt-2">Unesite sliku vesti</label>
				<input type="file" name="file" class=" form-control"><br>
				<div style="color:red"><?php echo $errorMessage['file']; ?></div>
				<label for="kategorija">Izaberite kategoriju vesti</label>
				<select name="kategorije" class="form-control" >
					<option value="">Bez kategorije</option>
					<?php while($kat = $result2->fetch_assoc()) {?>
					<option value="<?= $kat['id']; ?>"><?= $kat['k_ime']; ?></option>
					<?php } ?>
				</select>
				<button type="submit" name="submit" class=" btn-primary btn-dark mt-4 btn-lg">Unesi vest</button>
			</form>
		</div>
	</div>

	<div class="container pt-5 pb-3" style="background-color: white">
		<table class="table table-bordered table-striped text-center">
		  <thead>
		    <tr>
		      <th scope="col">Naslov vesti</th>
		      <th scope="col">Vest</th>
		      <th scope="col">Kategorija vesti</th>
		      <th scope="col">Izmeni/Ukloni</th>
		      <th scope="col">Datum vesti</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php while($vesti = $result1->fetch_assoc()) { ?>
		    <tr>
		      <td><?php echo $vesti['naslov']; ?></td>
		      <td>
		      	<?php 
		      		$pom = explode(' ', $vesti['vest']);
		      		$pom1 = array_slice($pom, 0,15);

		      		if (count($pom1) >=	 15) {
		      			$pom2 = implode(' ', $pom1);
		      			echo $pom2. '...';	
		      		}else{
		      			$pom2 = implode(' ', $pom1);
		      			echo $pom2;
		      		}
		       	?>
		       	
		      </td>
		      <td>
		      		<?php if($vesti['k_ime'] == NULL) {
		      			echo "Bez kategorije";
		      		}else{
		      			echo $vesti['k_ime'];
		      		} 
		      		?>
		      </td>
		      <td>
		      	<a href="izmeni.php?izmeni=<?php echo $vesti['id']; ?>">Izmeni</a><a href="pregled.php?izbrisi=<?php echo $vesti['id']; ?>">/Ukloni</a><a href="vesti.php?pregled=<?php echo $vesti['id']; ?>">/Pregled vesti</a>
		      </td>
		      <td>
		      	<?php echo $vesti['datum']; ?>
		      </td>
		    </tr>
		      <?php } ?>
		  </tbody>
		</table>
	</div>

</div>