<?php
// Connecting to the aws RDS instance to pull NBA information
try {
	$host = "mydb.cogl1pm1ejx2.us-west-2.rds.amazonaws.com";
	$dbname = "mydb";
	$user = "wolanwg";
	$pass = "Blue1234";
	$mydb = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	// Gets specific results based on search input
	$search = $_GET['search'];
	$stmt = $mydb->prepare("SELECT * FROM NBA WHERE PlayerName LIKE :search");
	$stmt->execute(array(':search' => '%' . $search . '%'));
	$result = $stmt->fetchAll(); // final search results
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
		<a href="index.html">Search again</a>
		<h2>Search Results</h2>
		<?php
		// Check to see if there are results, otherwise states none were found
		if (count($result)) {
			// for each row, displays player information
			foreach ($result as $row) {
				echo $row['PlayerName'] . '<br>';
				echo 'GP: ' . $row['GP'] . '   ' . 'FGP: ' . $row['FGP'] . '   ' . 'TPP: ' . $row['TPP'] . '   ' . 'FTP: ' . $row['FTP'] . '   ' . 'PPG: ' . $row['PPG'] . '<br><br>';
			}
		} else {
			echo 'No players found.';
		}
		// Releasing database instance PDO
		$mydb = null;
		?>
	</body>
</html>