<?php
	session_start();
	include 'pdo.php';
//WmkQqtJd92m504z>	
	
	$email = '';
	if(isset($_POST['submit']) &&(isset($_SESSION['captcha']))){
		if(isset($_POST['email'])){
			$captcha = $_SESSION['captcha'];
			if(($captcha!=$_POST['captcha'])){
				header('Location: index.php?error=Invalid Captcha');
			}
			else{
				$email = $_POST['email'];
				$user_ip = $_SERVER['REMOTE_ADDR'];
				$sql = "SELECT * FROM user_master WHERE user_email = :email OR user_ip = :up";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':email' => htmlentities($email),
					':up' => $user_ip
				));
				$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			if($rows){
				header('Location: index.php?error=Already Voted');
			}
			else{
				$sql2 = "INSERT INTO user_master (user_email,user_vote,user_num,participant_id,user_ip) VALUES (:email, :vote, :rand, :p_id, :user_ip)";
				$stmt = $pdo->prepare($sql2);
				$vote = 0;
				$random = rand(500,100000);
				$stmt->execute(array(
					':email' => $email,
					':vote' => $vote,
					':rand' => $random,
					':user_ip' => $user_ip,
					':p_id' => htmlentities($_POST['participant'])
				));
				
				$link = 'votingverify.php?key='.$random;
				$decp = "Hello User Thanks To taking part In This event Please Click on the below link to succesfully verification and completion of the voting process.";
				//mail($email,"Email Verification",$msg);
				echo $link;
			}
		}
	}
	else{
		header('Location: index.php');
	}



	$mobilenum = "9824864702";
	$msg = "Hello JAY";
	

	// Authorisation details.
	$username = "jaypatel32157@gmail.com";
	$hash = "103a86a12b03ba8eef1bf75e1bca840b2be2d4c7bde356e30b33652e50976234";

	// Config variables. Consult http://api.textlocal.in/docs for more info.
	$test = "0";

	// Data for text message. This is the text message data.
	$sender = "TXTLCL"; // This is who the message appears to be from.
	$numbers = $mobilenum; // A single number or a comma-seperated list of numbers
	$message = "This is a test message from the PHP API script.";
	// 612 chars or less
	// A single number or a comma-seperated list of numbers
	$message = urlencode($message);
	$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
	$ch = curl_init('http://api.textlocal.in/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	curl_close($ch);
?>