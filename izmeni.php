<?php 
	include 'header.php'; 

$Unaslov = '';
$errorMessage = array('velicina' => '', 'fajl' => '', 'format'=> '');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$file = $_FILES['file'];
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
					$errorMessage['velicina'] = 'Velicina fajla je velika';
				}
				
			}else{
				$errorMessage['fajl'] = 'Postoji greska sa fajlom';
			}

		}else{
			$errorMessage['format'] =  'Ne mozete uneti ovaj format';
		}
		if (!isset($novoImeFajla)){
			$sql = "UPDATE news SET naslov = ?, vest = ?, datum = ?, category_id = ? WHERE id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('sssss',  $_POST['naslov'], $_POST['tekst'], $_POST['datum'], $_POST['kategorija'] ,$_POST['id_vesti']);
		}else{
			$sql = "UPDATE news SET naslov = ?, vest = ?, datum = ?, slike = ?, category_id = ? WHERE id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ssssss", $_POST['naslov'], $_POST['tekst'], $_POST['datum'], $novoImeFajla, $_POST['kategorija'] ,$_POST['id_vesti']);		
		}
			$stmt->execute();
			header("Location:pregled.php");

	}else{	
		// $id = $_GET['izmeni'];
		$sql = "SELECT * FROM news WHERE id = ?";
		$stmt =$conn->prepare($sql);
		$stmt->bind_param("s", $_GET['izmeni']);
		$stmt->execute();
		$result = $stmt->get_result();
	
	while($Unews = $result->fetch_assoc()){
		$Uid = $Unews['id'];
		$Unaslov = $Unews['naslov'];
		$Uvest = $Unews['vest'];
		$Udatum = $Unews['datum'];
		$USlika = $Unews['slike'];
		$Ukat = $Unews['category_id'];
	}	
}

	$sql = "SELECT * FROM kategorije";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result2 = $stmt->get_result();
?>
 


<div class="d-flex">

<div class="form-group py-5 col col-md-6">
	<form action="izmeni.php" method="POST" enctype="multipart/form-data">
		<label for="naslov">Naslov vesti</label>
		<input type="text" name="naslov" placeholder="Unesi naslov" class="form-control" value="<?php echo $Unaslov; ?>">
		<label for="tekst_vesti" class="mt-4">Tekst vesti</label>
		<div>
			<textarea name="tekst" placeholder="Unesite tekst vesti" class="form-control"><?php echo $Uvest; ?></textarea>
			<label for="brojreci" style="color: red;" class="mt-2">
				<?php 
					$pom = strip_tags($Uvest);//promenljiva pom sadrzi tekst bez karaktera
					$pom1 = explode(" ", $pom);	

					$rezultat = 0;
					foreach ($pom1 as $brojreci) {
						$rezultat++;
					}
						echo "Broj reci u tekstu je: $rezultat";
		 		?>
		 	
			</label>
		</div>
		<input type="text" hidden name="id_vesti" value="<?php echo $_GET['izmeni'];  ?>">
		<label for="datum" class="mt-4">Datum vesti</label>
		<input type="date" name="datum" class="form-control" value="<?php echo $Udatum; ?>">
		<input type="file" name="file" class="mt-4"><br>
		<label style="color: red"><?php print_r($errorMessage['velicina']); ?></label><br>
		<label for="kategorija" class="mt-4">Izaberite kategoriju vesti</label>
		<select name="kategorija" class="form-control	">
			<?php while ($rezultatk = $result2->fetch_assoc()) { ?>
				<option value="">Bez kategorije</option>
				<option <?php if($Ukat == $rezultatk['id']) echo 'selected="selected"'; ?> value="<?= $rezultatk['id']; ?>">
					<?php echo $rezultatk['k_ime']; ?>
				</option>
			<?php } ?>
		</select>

		<button type="submit" name="edit" class="btn-success mt-4 btn-lg">Izmeni</button>
	
	</form>
</div>
<div class="col col-md-6 py-5 text-center">
	<label for="trenutna_slika">Trenutna slika vesti</label>
	<div>
 		<img style="max-height: 300px" src="http://localhost/kreni/upload/<?php echo $USlika; ?>">
 	</div>
</div>



