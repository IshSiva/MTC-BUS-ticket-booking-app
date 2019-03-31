<?php
require_once 'connectDB.php';

$response = array();

if(isset($_GET['aadharnumber'],$_GET['amt']))
{
 if(isAvailable(array('aadharnumber','amt')))
  { $aadhar = $_GET['aadharnumber'];
    $amt = $_GET['amt'];
    //Calling the Queries
    $stmt = $conn->prepare("UPDATE wallet SET balance = balance + ? WHERE aadhar_number = ?");
    $stmt->bind_param("di",$amt,$aadhar);
    if($stmt->execute())
    {   $stmt->close();
        $response['message'] = 1;

    }
   else
   {  $response['message']=0;

	   }
    echo json_encode($response);

  }
}
else {
  echo "not feasible";
}

function isAvailable($params)
{	foreach($params as $param)
	{	if(!isset($_GET[$param]))
		{
				return false;
		}
	}
	return true;
}

?>
