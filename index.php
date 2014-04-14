<html>
	<head>
		<title>NBA Stats</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<input type="text" id="query" autofocus="autofocus"/>
		<input type="submit" id="submit" value="Search"/>
		
		<?php 
			try {
				$name = "";
				$name = $_GET["name"];
				
				$host = "mydb.cogl1pm1ejx2.us-west-2.rds.amazonaws.com:3306";
				$dbname = "mydb";
				$user = "wolanwg";
				$pass = "Blue1234";
				
				$mydb = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
				$results = $mydb->prepare("SELECT * FROM NBA WHERE PlayerName = :name");
			} catch(PDOException $exception) {
				echo $exception->getMessage();
			}
		?>
		
		<script src="js/jquery-2.1.0.min.js"></script>
		<script src="js/index.js"></script>
	</body>
</html>