<?php
require_once('connection.php');
session_start();
echo "welcome ".$_SESSION['name'];
echo "</br>";
if(!isset($_SESSION['name'])){
	header('location: login.php');
	exit();
}
if(isset($_POST['logout'])){
	session_destroy();
	header('location: login.php?msg=logout successfully');
	exit();
}
if(isset($_POST['update'])){
	if(isset($_POST['newpass']) && !empty(trim($_POST['newpass']))){
		if(isset($_POST['confpass']) && !empty(trim($_POST['confpass']))){
			if($_POST['newpass'] != $_POST['confpass']){
				echo "password n conf pass donot match";
				die;
			}
			$newpass = $_POST['newpass'];
			$npass = password_hash($newpass, PASSWORD_DEFAULT);
		} else {
			die('confirm password error');
		}
	} else {
		die('new password error');
	}
	$name = $_SESSION['name'];
	if(isset($_POST['oldpass']) && !empty(trim($_POST['oldpass']))){
		$pass = $_POST['oldpass'];
		$sql = 'SELECT * from users WHERE username = "'.$name.'"';
	
		$results = mysqli_query($connect, $sql);
		//print_r($results);
		if($results){
			if(mysqli_num_rows($results)){
				while($row = mysqli_fetch_assoc($results)){
					$hashpass = $row['password'];
				}
					if(password_verify($pass, $hashpass)){
						
						$sql = "UPDATE users SET password = '$npass' WHERE username = '".$name."'";
						$change = mysqli_query($connect, $sql);
						echo 'password changed successfully';
						//echo mysqli_error($connect);
						
					} else {
						echo "password doesn't match";
					}
				
			} else {
				echo "username is incorrect";
			}
		} else {
			echo mysqli_error($connect);
		}
	}
}
?>

<form method='post' action='#'>
	<input name='logout' type='submit' value='Logout' />
</form>

<form method='post' action='#'>
	<label>New password:<input type='password' name='newpass' /></label>
	<label>Confirm password:<input type='password' name='confpass' /></label>
	<label>Old password:<input type='password' name='oldpass' /></label>
	<input name='update' type='submit' />
</form>