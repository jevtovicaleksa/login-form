<?php include 'header.php'; ?>
<?php 


	if (isset($_GET['pregled'])) {

		$id = $_GET['pregled'];
		echo $id;
		// da li id postoji u bazi 
		$sql = "SELECT * FROM news WHERE id = ?";		
		$stmt = $conn->prepare($sql);		
		$stmt->bind_param("s", $id);		
		$stmt->execute();	
		$result = $stmt->get_result();
		$result2 = $result->fetch_assoc();		
		if(is_null($result2)){
			header("Location:error.php");
			exit();
		}
	}
 ?>
<div class="d-flex flex-column justify-content-center text-center">
	<div class="container mt-5">
		<h2><?php echo $result2['naslov']; ?></h2>
	</div>
	<div class="container mt-3">
		<p class="text-center"><?php echo $result2['vest']; ?></p> 
 	</div>
 	<div>
 		<img style="max-height: 300px" src="http://localhost/kreni/upload/<?php echo $result2['slike']; ?>">
 	</div>
</div>