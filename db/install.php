<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>Regix Install</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<style type="text/css">
body{
	background-color:rgb(50,50,50);
	font-family: 'Helvetica', sans-serif;
	margin:0;
}
input{
	box-sizing: border-box;
}

.regix-form-container {
	min-width: 300px;
	display: table;
	margin: auto;
	margin-bottom: 20px;
	margin-top: 20px;
	padding: 20px;
	width: auto;
	background: #f4f4f4;
	border-bottom-color: #142647;
}

.regix-form h1 {
	word-wrap: break-word;
	margin-bottom: 20px;
	font-size: 30px;
	font-weight: bold;
	color: #5c5c5c;
	text-align: center;
}

.regix-form {
	margin:0;
}

.regix-form-input {
	display: block;
	width: 100%;
	height: 37px;
	font-weight: bold;
	margin-bottom: 10px;
	padding: 0 9px;
	color: #3b3b3b;
	background: #eae8e8;
	border: 1px solid #5a5a5a;
	border-top-color: #5a5a5a;
}

.regix-form-input:focus {
	outline: 0;
	background-color: #f5f5f5;
}
			
.form-label-container{
	color: #5c5c5c;
	margin-bottom: 5px;
}

button{
	display: inline-block;
	color: white;
	height: 37px;
	font-size: 14px;
	font-weight: bold;
	margin: 0;
	cursor: pointer;
	width: 100%;
    background: #56db6f;
	border: 1px solid #0d9b08;
}
			
button:active {
	background: #40c95b;
}
		</style>
	</head>
	<body>
		<div class="regix-form-container">
		<form action="#" method="post" class="regix-form">
			<h1>Regix Installer</h1>
			<div class="form-label-container"><label for="host_name_input">Host name: </label></div>
			<div><input id="host_name_input" name="host_name" type="text" class="regix-form-input" value="localhost"/></div>
			<div class="form-label-container"><label for="username_input">Username: </label></div>
			<div><input id="username_input" name="username" type="text" class="regix-form-input" value="test_user" /></div>
			<div class="form-label-container"><label for="password_input">Password: </label></div>
			<div><input id="password_input" name="password" type="password" class="regix-form-input" value="test_password"/></div>
			<div class="form-label-container"><label for="db_name_input">Database name: </label></div>
			<div><input id="db_name_input" name="db_name" type="text" class="regix-form-input" value="test_user"/></div>
			<div><button name="submit" type="submit">OK</button></div>
		</form>
		</div>
<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

function startsWith($haystack, $needle)
{
	$length = strlen($needle);
	return (substr($haystack, 0, $length) === $needle);
}

function run_sql_file($location, $con){
	if($con){
		//load file
		$commands = file_get_contents($location);
		//delete comments
		$lines = explode("\n",$commands);
		$commands = '';
		foreach($lines as $line){
			$line = trim($line);
			if($line && !startsWith($line,'--')){
				$commands .= $line . "\n";
			}
		}
		//convert to array
		$commands = explode(";", $commands);
		mysqli_query($con, "USE ".$_POST["db_name"]);
		//run commands
		$total = $success = 0;
		foreach($commands as $command){
			if(trim($command)){
				if(mysqli_query($con, $command)){
					echo "<div style=\"padding:5px; background-color: #56db6f; border: 1px solid #0d9b08;\">OK ".$command."</div>"; 
					$success += 1;
				}
				else{
					echo "<div style=\"padding:5px; background-color: hsl(0, 65%, 60%); border: 1px solid hsl(0, 90%, 32%);\">FAIL ".$command."</div>"; 
				}
				$total += 1;
			}
		}
		//return number of successful queries and total number of queries found
		return array(
			"success" => $success,
			"total" => $total
		);
	}
}

if(isset($_POST["host_name"])&&isset($_POST["username"])&&isset($_POST["password"])){

	$con = mysqli_connect($_POST["host_name"],$_POST["username"],$_POST["password"]);
	$files = scandir("test_data");
	run_sql_file("create.sql", $con);
	for ($i = 2 ; $i < count($files) ; $i++) {
		run_sql_file("test_data/".$files[$i], $con);
	}
	mysqli_close($con);
}
?>
		
	</body>
</html>


