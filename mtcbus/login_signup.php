<?php

	$mysqli = new mysqli("localhost", "root", "admin", "mtc_bus_application");
	
	$option = $_GET["option"];
	
	
	if($option >0){
		$uname = $_GET["user_name"];
	
	
		$result = $mysqli->query("SELECT * FROM user_details where username = $uname");
		
		$row = mysqli_fetch_array($result);
		
		
		$aadhar_number = $row["aadhar_number"];
		
		
		$balance = $mysqli->query("SELECT balance from wallet where aadhar_number = $aadhar_number");
		$bal_row = mysqli_fetch_array($balance);
		
		
		if(mysqli_num_rows($result)>0){
			
			$response = array();
			
			$response["username"] = $row["username"];
			$response["wallet_balance"] = $bal_row["balance"];
			$response["success"] = 1;
			
			echo json_encode($response);
			
			
			



		}
		
		else{
			$response["success"] = 0;
			echo json_encode($response);
			
		}
			
		
	}
	
	else{
		
		$response = array();
		
		$uname = $_POST["user_name"];
	
		$aadhar_number = $_POST["aanum"];
		$balance = $_POST["bal"];
		$name = $_POST["name"];
		
		
		
		$result = $mysqli->query("Insert into user_details values(name, username, aadhar_number) values('$name', '$uname', '$aadhar_number')");
		
		echo $result;
		
		
		$bal_result = $mysqli->query("Insert into wallet values(aadhar_number, balance) values ('$aadhar_number', '$balance')");
		
		
		echo $bal_result;
		
		if($result && $bal_result){
			
			$response["success"] = 1;
			echo json_encode($response);
		}
			
		else{
			$response["success"] = 0;
			echo json_encode($response);
			
		}
	}
	
		











?>