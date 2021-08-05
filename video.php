<?php include 'header.php'; ?>
<?php 


		if (!isset($_SESSION['prijavljen']) || $_SESSION['rola'] == 'korisnik' ) {
				header("Location: index.php?Nematepravopristupaovojstranici");
				exit();	
		}		

		$video_novi = null;
		$errorMesage = ['link'=> ''];


		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$link = $_POST['link'];

			if(empty($link)){
				$errorMesage['link'] = 'Morate uneti link';	
			}

			if ($errorMesage['link'] == '') {
				if (isset($_GET['izmeni'])){
			
						$sql = "UPDATE linkovi SET link = ? WHERE id = ?";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("ss", $link, $_GET['izmeni']);
						$stmt->execute();
						$stmt->close();
			}
			
				else{
						$sql = "INSERT INTO linkovi (link) VALUES (?)";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("s", $_POST['link']);
						$stmt->execute();
						$stmt->close();	
					}		
			}
			
		}else{

			if(isset($_GET['izmeni']) && !empty($_GET['izmeni'])){	
			$sql = "SELECT * FROM linkovi WHERE id = ?";
			$stmt=$conn->prepare($sql);
			$stmt->bind_param("s", $_GET['izmeni']);
			$stmt->execute();
			$result= $stmt->get_result();
			$video = $result->fetch_assoc();
				if ($video != null) {
					$video_novi = $video;
				}
				$stmt->close();
			}
		}
		$sql = "SELECT * FROM linkovi";
		$stmt=$conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

//brisanje linka

		if (isset($_GET['ukloni']) && !empty($_GET['ukloni'])) {
			$sql = "DELETE FROM linkovi WHERE id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("s", $_GET['ukloni']);
			$stmt->execute();
			header("Location: video.php");
			exit();
		}
 ?>
<div style="max-width: 100%">	

		 <div class=" container col col-md-6 py-4 my-3" style="background-color: white">
		 	<form class="form-group" method="POST" action="video.php?<?php echo ($video_novi != null) ? 'izmeni='.$video_novi['id'] : ''; ?>">
		 		<label for="link">Link</label>
		 		<input type="url" value="<?php echo ($video_novi != null) ? $video_novi['link'] : ''; ?>" name="link" class="form-control" placeholder="unesite link">
		 		<div style="color: red"><?php echo $errorMesage['link']; ?></div>
		 		<button type="submit" name="posalji" class="btn-dark mt-4">Unesi link</button>
		 	</form>
		 </div>
		 <div class=" container col col-md-6 py-4 my-3" style="background-color: white">
		 		<table class="table table-bordered table-striped text-center">	
		 			<thead>
		 				<tr>
		 					<th scope="col">Link</th>
		 					<th scope="col">Ukloni/Izmeni</th>
		 				</tr>	
		 			</thead>
		 			<tbody>
		 				<?php while($linkovi = $result->fetch_assoc()) {?>
		 				<tr>
		 					<td><a href="<?php echo $linkovi['link'];?>" target="_blank" ><?php echo $linkovi['link']; ?></a></td>
		 					<td><a href="video.php?ukloni=<?php echo $linkovi['id']; ?>">Ukloni</a>
		 						<a href="video.php?izmeni=<?php echo $linkovi['id']; ?>">Izmeni</a>
		 					</td>
		 				</tr>	
		 				<?php } ?>
		 			</tbody>
		 		</table>
		 </div>
 </div>