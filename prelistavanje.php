<?php include "header.php"; ?>

<?php 

	$sql = "SELECT news.* , kategorije. k_ime FROM news LEFT JOIN kategorije ON news. category_id = kategorije. id";	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
?>
 <div class="container mt-5" >
 	<table class="table table-bordered table-striped text-center" style="background-color: white;">
 		<thead>
 			<tr>
 				<th>Naslov vesti</th>
 				<th>Kategorija vesti</th>
 				<th>Datum vesti</th>
 			</tr>
 		</thead>
 		<tbody>
 			<?php while($vesti_pregled = $result->fetch_assoc()) { ?>
 			<tr>
 				<td>
 					<a href="pregled_korisnik.php?pregled=<?php echo $vesti_pregled['slug']; ?>"><?php echo $vesti_pregled['naslov']; ?>			
 					</a>
 				</td>
 				<td><?php if(is_null($vesti_pregled['k_ime'])) {
 					echo "bez kategorije";
 				}else{
 					echo $vesti_pregled['k_ime'];
 				}?>	
 				</td>
 				<td><?php echo $vesti_pregled['datum']; ?></td>
 			</tr>
 			<?php  }; ?>
 		</tbody>	
 	</table>
 </div>




