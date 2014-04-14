<?php 
	try {

		$host = "mydb.cogl1pm1ejx2.us-west-2.rds.amazonaws.com:3306";
		$dbname = "mydb";
		$user = "wolanwg";
		$pass = "Blue1234";
				
		$mydb = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		
		$search = $_GET['search'];
		$results = $mydb->prepare("SELECT * FROM NBA WHERE PlayerName LIKE :name");
		$results->setFetchMode(PDO::FETCH_ASSOC);
		
		$results->execute(array(':search' => '%' . $search . '%'));
		
		foreach ($results AS $each) {
			echo $each[0];
		}
		
		$mydb = null;
	} catch(PDOException $exception) {
		echo $exception->getMessage();
	}
?>