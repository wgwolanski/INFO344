<?php
try {
	$host = "mydb.cogl1pm1ejx2.us-west-2.rds.amazonaws.com";
	$dbname = "mydb";
	$user = "wolanwg";
	$pass = "Blue1234";
	$mydb = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	
	$search = $_GET['search'];
	$stmt = $mydb->prepare("SELECT * FROM NBA WHERE PlayerName LIKE :search");
	$stmt->execute(array(':search' => '%' . $search . '%'));
	$result = $stmt->fetchAll();
} catch(PDOException $e) {
	echo 'ERROR: ' . $e -> getMessage();
}
?>
<html>
	<head>
		<title>NBA Stats</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<h1>NBA Player Stat Search</h1>
		<?php
		
		if (count($result)) {
			foreach ($result as $row) {
				echo $row['PlayerName'] . '<br>';
			}
		} else {
			echo 'Now players found.';
		}

		$mydb = null;
		?>
	</body>
</html>