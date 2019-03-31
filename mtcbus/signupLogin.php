<?php


require_once 'connectDB.php';

$response = array();
if(isset($_GET['call']))
{

if(($_GET['call'])== 'signup')
{
		if(isAvailable(array('name','aadharnumber','username','password')))
		{

			$name = $_POST['name'];
			$aadhar_num = $_POST['aadharnumber'];
			$uname = $_POST['username'];
			$password = $_POST['password'];
			//Checking the length of aadhar_number
			if($aadhar_num >100000000000 && $aadhar_num <=999999999999)
			{
				$stmt = $conn->prepare("SELECT aadhar_number FROM user_details WHERE aadhar_number = ? or uname = ?");
				$stmt->bind_param("is", $aadhar_num, $uname);
				$stmt->execute();
				$stmt->store_result();

				if($stmt->num_rows > 0)
				{	$response['error'] = true;
					$response['message'] = 'User already registered';
					$stmt->close();
				}
				else
				{	$stmt = $conn->prepare("INSERT INTO user_details(name,aadhar_number,uname) VALUES (?, ?, ?)");
					$stmt->bind_param("sis", $name, $aadhar_num,$uname);

					$min_bal = 50.0;
      		$r = $conn->prepare("INSERT INTO wallet (aadhar_number,balance) values (?,?)");
					$r->bind_param("id",$aadhar_num,$min_bal);
        	$r->execute();
					if($stmt->execute())
					{	$user = array(
						'aadhar number'=>$aadhar_num,
						'name'=>$name,
						'username' =>$uname,
						);

						$stmt->close();

						$response['error'] = false;
						$response['message'] = 'User registered successfully';
						$response['user'] = $user;
					}
				}

			}
			else
			{ $response['error']=true;
					$response['message']='Aadhar Number not of Required Length';
			}
		}
		else
		{		$response['error'] = true;
				$response['message'] = 'required parameters are not available';
		}


}

elseif(($_GET['call']) == 'login')
{
		if(isAvailable(array('username')))
		{

			$uname = $_POST['username'];


			//Checking the length of aadhar_number
			$stmt = $conn->prepare("SELECT aadhar_number FROM user_details WHERE uname = ? ");
			$stmt->bind_param("s", $uname);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($value);
		 	$stmt->fetch();

			if($stmt->num_rows <= 0)
			{	$response['error'] = true;
				$response['message'] = 'Username or Password is Incorrect';
				$stmt->close();
			}
			else
		  {
				if($stmt->execute())
				{ //Closing connection
					$stmt->close();
					$r = $conn->prepare("SELECT balance FROM wallet WHERE aadhar_number = ?");
					$r->bind_param("i", $value);
					$r->execute();
					$r->store_result();
					$r->bind_result($balance);
					$r->fetch();

					$user = array(
					'username'=>$uname,
					'balance' =>$balance,
					'aadhar_number'=>$value,
					);
					$r->close();


					$response['error'] = false;
					$response['message'] = 'Login Successful';
					$response['user'] = $user;
				}


			}

		}
    else
		{		$response['error'] = true;
				$response['message'] = 'Values are still yet to be entered';
		}

}

else
{
	$response['error'] = true;
	$response['message'] = 'Invalid call';
}

}



echo json_encode($response);

function isAvailable($params)
{	foreach($params as $param)
	{	if(!isset($_POST[$param]))
		{
				return false;
		}
	}
	return true;
}
?>
