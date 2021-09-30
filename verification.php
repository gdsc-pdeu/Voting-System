<?php
	session_start();
	include 'pdo.php';
	use PHPMailer\PHPMailer\PHPMailer; 
	use PHPMailer\PHPMailer\Exception; 
	
	require './vendor/phpmailer/phpmailer/src/Exception.php';
	require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require './vendor/phpmailer/phpmailer/src/SMTP.php';
	
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

				$mail = new PHPMailer; 
 
				$mail->isSMTP();                      // Set mailer to use SMTP 
				$mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
				$mail->SMTPAuth = true;               // Enable SMTP authentication 
				$mail->Username = 'xxxx@gmail.com';   // Your Email
				$mail->Password = 'xxxx';   //  password 
				$mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
				$mail->Port = 587;                    // TCP port to connect to 
				
				// Sender info 
				$mail->setFrom('liveVoting@opensource.com', 'liveVoting'); 
				$mail->addReplyTo('reply@opensource.com', 'liveVoting'); 
				
				// Add a recipient 
				$mail->addAddress($email); 
				
				//$mail->addCC('cc@example.com'); 
				//$mail->addBCC('bcc@example.com'); 
				
				// Set email format to HTML 
				$mail->isHTML(true); 
				
				// Mail subject 
				$mail->Subject = 'Voting Verfication Mail'; 
				
				// Mail body content 
				$bodyContent .= '<p>Hello User Thanks To taking part In This event Please Click on the below link to succesfully verification and completion of the voting process.</b></p><a href="'.$link.'">'.$link.'</a>'; 
				$mail->Body    = $bodyContent; 
				
				// Send email 
				if(!$mail->send()) { 
					echo $link;
				} else { 

				}
				header('Location: index.php?success=Mail Sent to your email Id please verify!!');
			}
		}
	}
	else{
		header('Location: index.php');
	}

?>