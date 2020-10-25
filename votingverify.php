<?php 
	session_start();
	include 'pdo.php';
	
	if(isset($_GET['key'])){
		$secretKey = htmlentities($_GET['key']);
		$sql = "SELECT * FROM user_master WHERE user_num = :num AND user_vote = :notv";
		$stmt = $pdo->prepare($sql);
		$nv=0;
		$stmt->execute(array(
			':num' => $secretKey,
			':notv' => $nv
		));
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		if($rows){
			$id = $rows['user_id'];
			$sql2 = 'UPDATE user_master SET user_vote = :vote WHERE user_id = :id';
			$stmt2 = $pdo->prepare($sql2);
			$vote = 1;
			$stmt2->execute(array(
				':id' => $id,
				':vote' => $vote
			));
			$p_id = $rows['participant_id'];
			$sql3 = 'UPDATE participant SET total_vote = total_vote + :tvote WHERE participant_id = :id';
			$stmt3 = $pdo->prepare($sql3);
			$pvote = 1;
			$stmt3->execute(array(
				':id' => $p_id,
				':tvote' => $pvote
			));
			header('Location: index.php?success=Succesfully Voted');
		}
		else{
			header('Location: index.php?error=KM BHAI KAI KUSHI MA :)');
		}
	}
	else{
		header('Location: index.php?error=HAHA NICE TRY :)');
	}
?>