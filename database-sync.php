<?php
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);


/* SETUP */
include_once("../globals/filemaker_init.php");
$fm = db_connect("GUCCHD");
$request = $fm->newFindCommand('evidence_mapping');
$request->addFindCriterion('web_ready', 1);
$result = $request->execute();

/* ERRORS */
if (!FileMaker::isError($result)) {  
	$bibliofetchcount = $result->getFetchCount();
	$bibliototalcount = $result->getFoundSetCount();
	$records = $result->getRecords();


	// connect to wp sql database
	$servername = "******";
	$username = "******";
	$password = "******";
	$dbname = "******";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}


	// clear out existing data
	$conn->query("TRUNCATE `".$dbname."`.`evidence_mapping`");


	foreach($records as $record){
		$abstract = htmlspecialchars($record->getField('abstract'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$annotation = htmlspecialchars($record->getField('annotation'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$citation = htmlspecialchars($record->getField('citation'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$design = htmlspecialchars($record->getField('design'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$disability = htmlspecialchars($record->getField('disaggregated_by_disability'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$linguistics = htmlspecialchars($record->getField('disaggregated_by_linguistics'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$race = htmlspecialchars($record->getField('disaggregated_by_race'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$measures = htmlspecialchars($record->getField('measures'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$outcomes = htmlspecialchars($record->getField('outcomes'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$part = htmlspecialchars($record->getField('part'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$setting = htmlspecialchars($record->getField('setting'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");
		$type = htmlspecialchars($record->getField('type'),ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, "UTF-8");


		$sql = "INSERT INTO `evidence_mapping` (`abstract`, `annotation`, `citation`, `design`, `disability`, `linguistics`, `race`, `measures`, `outcomes`, `part`, `setting`, `type`, `web_ready`) VALUES ('$abstract', '$annotation', '$citation', '$design', '$disability', '$linguistics', '$race', '$measures', '$outcomes', '$part', '$setting', '$type', '1')";

		// echo $sql;

		if ($conn->query($sql) === TRUE) {
		  echo "record synced successfully<br>";
		} else {
		  echo "<pre>" . $sql . "</pre>";
		  echo $conn->error."<hr>";
		}
	}


	$conn->close();

} else {
	echo $result->code;
	echo $result->getMessage();
}
?>