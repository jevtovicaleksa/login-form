<?php include 'header.php'; ?>
<?php 


if (isset($_GET['pregled']) && !empty($_GET['pregled'])) {

	$sql = "SELECT * FROM news WHERE slug = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $_GET['pregled']);
	$stmt->execute();
	$result = $stmt->get_result();
	$result_news = $result->fetch_assoc();
	$id_sadasnje = $result_news['id']; //sluzi za preporucene vesti

}

// upit za izvlacenje kategorije vesti
	$sql = "SELECT news.* , kategorije. k_ime FROM news LEFT JOIN kategorije ON news. category_id = kategorije. id WHERE slug = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $_GET['pregled']);
	$stmt->execute();
	$result = $stmt->get_result();
	$SveIzKategorije = $result->fetch_assoc();
	// var_dump($SveIzKategorije);
	// die();

 ?>	


 <div class="container text-center mt-5">
 	<div class="mt-3">
 		<h2><?php echo $result_news['naslov']; ?></h2>
 	</div>
 	<div class="mt-3">
 		<p><?php echo $result_news['vest']; ?></p>
 	</div>
 	<div class="mt-3">
 		<p>
 			<?php
 			 $datum_vesti = $result_news['datum'];
 			 print_r($datum_vesti);
 			?>		
 		</p>
 	</div>
 	<div class="mt-3">
 		<p>
 			<?php if(!is_null($SveIzKategorije['k_ime'])){   
 				echo $SveIzKategorije['k_ime']; 
 			} else{
 				echo "Vest bez kategorije";
 			}?>		
 		</p>
 	</div>
 	<div>
 		<p><?php 
 				$pom  = strip_tags($result_news['vest']);
				$pom1 = explode(" ", $pom);	

				$rezultat = 0;
				foreach ($pom1 as $brojreci) {
					$rezultat++;
				}

				if($rezultat <= 10){
					echo "Potreban je minut za citanje teksta";
				}elseif($brojreci > 10 AND $brojreci <=20){
					echo "Potrebno je 2 minuta za citanje teksta";
				}elseif ($brojreci > 20 AND $brojreci <=30) {
					echo "Potrebno je 3 minuta za citanje teksta";
				}elseif($brojreci > 30 AND $brojreci <=40){
					echo "Potrebno je 4 minuta za citanje teksta";
				}elseif ($brojreci > 40 AND $brojreci <=50) {
					echo "Potrebno je 5 minuta za citanje teksta";
				}else{
					echo "Potrebno je vise od 5 minuta za citanje teksta";
				}
 		 ?></p>
 	</div>
 </div>


<?php 

// upit za sledecu vest
$sql =  "SELECT * FROM news WHERE datum > ? ORDER BY datum ASC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $datum_vesti);
$stmt->execute();
$result = $stmt->get_result();
$sledeca_vest = $result->fetch_assoc();

// upit za prethodnu vest
$sql = "SELECT * FROM news WHERE datum < ? ORDER BY datum DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $datum_vesti);
$stmt->execute();
$result1 = $stmt->get_result();
$prethodna_vest = $result1->fetch_assoc();

?>

<?php

//preporucene vesti

 if(is_null($prethodna_vest) || is_null($sledeca_vest)){
 	$sql = "SELECT * FROM news WHERE id != ? AND id != ? LIMIT 5";
 	$stmt = $conn->prepare($sql);
 	if (is_null($prethodna_vest)) {
 		$stmt->bind_param("ss", $id_sadasnje, $sledeca_vest['id']);
 	}else{
 		$stmt->bind_param("ss", $id_sadasnje, $prethodna_vest['id']);
 	}
	
 }elseif (is_null($sledeca_vest) && is_null($prethodna_vest)) {
 	$sql = "SELECT * FROM news WHERE id != ?";
 	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $id_sadasnje);
 }else{
	$sql = "SELECT * FROM news WHERE id != ? AND id != ? AND id != ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sss", $id_sadasnje, $prethodna_vest['id'], $sledeca_vest['id']);
 }

	$stmt->execute();
	$result = $stmt->get_result();
   ?>



<div class="container d-flex justify-content-between mt-5">
	<?php if($prethodna_vest != NULL){  ?>
		<div>
			<a href="pregled_korisnik.php?pregled=<?php echo $prethodna_vest['slug']; ?>">Prethodna vest</a>
		</div>
	<?php } ?>

	<?php if($sledeca_vest != NULL) { ?>
		<div>
			<a href="pregled_korisnik.php?pregled=<?php echo $sledeca_vest['slug']; ?>">Sledeca vest</a>
		</div>
	<?php } ?>
</div>

<!-- <h2 class="text-center mt-5">Preporucene vesti</h2> -->
<div class="text-center container" style="background-color: white;">
<h2 class="text-center mt-5 py-2">Preporucene vesti</h2>
<table class="table table-bordered table-striped mt-3">
	<thead></thead>
	<tbody>
		<?php while ($vesti_pre = $result->fetch_assoc()) {  ?>
			<tr>
				<td><a href="pregled_korisnik.php?pregled=<?php echo $vesti_pre['slug']; ?>"><?php echo $vesti_pre['naslov']; ?></a></td>
			</tr>
		<?php } ?>
	</tbody>
</table>  
</div>