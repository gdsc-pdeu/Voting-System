<?php
	session_start();
	include 'pdo.php';
	
	
?>
<html>
<head>
	<title>VOTING SYSTEM</title>
	  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
	.result{
		margin-bottom:50px;
	}
	.result > div {
		height:150px
	}
	.bar_container{
		position:relative;
	}
	.bar_container div{
		position:absolute;
		bottom:0%
	}
  </style>
</head>
<!--SIGNUP Section PHP-->

<body>
	<div class="jumbotron text-center">
		<h2>Voting System</h2>
	</div>
	<div class="container">
	<?php 
		if(isset($_GET)){
			if(isset($_GET['error'])){
				echo '
				<div class="alert alert-danger alert-dismissible">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				'.$_GET['error'].'
				</div>';
			}
			elseif(isset($_GET['success'])){
				echo '
				<div class="alert alert-success alert-dismissible">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				'.$_GET['success'].'
				</div>';
			}
		}
	?>
	<form action="verification.php" method="post">
		<fieldset>
		<div class="form-group">
			<label for="email_id">Enter The Email Addresses : </label>
			<input type="email" class="form-control" placeholder="Enter email" name="email" id="email_id" required>
		</div>
		<div class="form-group">
			<?php
					$sql = "Select * FROM participant";
					$stmt = $pdo->query($sql);
					echo "<label for='participant_dd' >Select The Participant : </label>";
					echo "<select class='form-control' name='participant'>";
						while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo "<option value='".$rows['participant_id']."' >".$rows['participant_name']."</option>";
						}
					echo "</select>";
			?>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-sm-2">
				  <img src="captch.php">
				</div>
				<div class="col-sm-10">
				  <input type="text" class="form-control" placeholder="Enter Captcha" name="captcha" required>
				  <a href="index.php" onclick="">Refresh Captcha</a>
				</div>
			</div>
		</div>
			<input class="btn btn-primary" type="submit" name="submit" value="Submit">
		</fieldset>
	</form>
	<div class="result">
		<h3>Live Result :</h3>
		<div style="height:200px;" class="row container">
		<?php
			$sqlp = "SELECT * FROM participant";
			$stmtp = $pdo->query($sqlp);
			$max = "SELECT total_vote FROM participant;";
			$stmtmax = $pdo->query($max);	
			$maxVote = 0;
			while($rowsmax = $stmtmax->fetch(PDO::FETCH_ASSOC)){
				$maxVote += $rowsmax['total_vote'];
			}
			
			while($rows = $stmtp->fetch(PDO::FETCH_ASSOC)){
				$height = ($rows['total_vote']/$maxVote)*100;
				$height = number_format($height, 2);
				echo '<div class="col-sm-6 align-bottom d-inline-block">
					<div class=" d-inline-block align-bottom"><strong>'.strtoupper($rows['participant_name']).' : </strong></div>
					<div class="h-100  d-inline-block  p-2 bg-white align-bottom bar_container">
						<div class="d-inline-block  p-2 bg-warning " style="height:'.$height.'%">'.$height.'%</div>
					</div>
				</div>';
			}
		?>
			</div>
	</div>
	</div>
</body>
	<script>
		

	</script>
</html>
