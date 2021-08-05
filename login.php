<?php 
include 'header.php';
 ?>


 <?php 

	$errorMesage = array('username'=>'', 'password'=>'', 'login'=>'', 'exist' => ''); 

 	if ($_SERVER['REQUEST_METHOD'] === 'POST'){

 		$username = $_POST['username'];
 		$password = $_POST['password'];

 		if (isset($_POST['submit'])) {

 			if (empty($username)) {
 				$errorMesage['username'] = "Polje username ne sme biti prazno!";
 			}elseif (empty($password)) {
 				$errorMesage['password'] = "Polje password ne sme biti prazno!";
 			}
 			else{

 				$sql = "SELECT * FROM users WHERE userName = ?";
 				$stmt = $conn->prepare($sql);
 				$stmt->bind_param("s", $username);
 				$stmt->execute();
 				$result = $stmt->get_result();
 				$user = $result->fetch_assoc(); 
 				echo "<br>";

 				if ($user != null) {
 						if(password_verify($_POST['password'], $user['userPwd'])){
 							$_SESSION['id'] = $user['id'];
 							$_SESSION['name'] = $user['userName'];
 							$_SESSION['rola'] = $user['rola'];
 							$_SESSION['prijavljen'] = true;
 							header("Location: pregled.php?uspesnaprijava");
 							exit();
 						}
 						$errorMesage['login'] = "Podaci nisu tacni";	
 					}else{
 						$errorMesage['exist'] =  "Neuspesna prijava";
 				}
 			}
	 		
 		}else{
 			header("Location: index.php?error=morajuseunetipodaci");
 			exit();
 		}
 	}
  ?>
<div class="d-flex justify-content-center py-5">
 	<div class="container" id="log">
 		<form action="login.php" method="POST">
 			<div id="naslov">
 				<h3 class="text-center mt-2" style="color: #595959">Login</h3>
 			</div>
 			<div class="form-group">
 				<label for="name" class="text-black mb-0">Username</label>
 				<input type="text" name="username" placeholder="Upišite vaš username" class="form-control">
 				<div style="color: red"><?php echo $errorMesage['username']; ?></div>
 				<label for="password" class="text-black mb-0 mt-3">Password</label>
 				<input type="password" name="password" placeholder="Upišite vaš password" class="form-control">
 				<div style="color:red"><?php echo $errorMesage['password']; ?></div>
 			</div>
 			<div class="form-group text-center">
 				<button type="submit" name="submit" class="btn-lg btn-dark" style="border-radius: 5px">Login!</button>
 				<div style="color:red" class="pt-2"><?php echo $errorMesage['login']; ?></div>
 				<label style="color: red"><?php echo $errorMesage['exist']; ?></label>
 			
 			</div>
 		</form>
	</div>
 </div>
 <?php include 'footer.php'; ?>