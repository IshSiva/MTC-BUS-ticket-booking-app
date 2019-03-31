<?php
require_once 'connectDB.php';

$response = array();

if(isset($_GET['aadharnumber']))
{
 if(isAvailable(array('aadharnumber')))
  { $aadhar = $_GET['aadharnumber'];
    //Calling the Queries
    $stmt = $conn->prepare("DELETE FROM user_details WHERE aadhar_number = ?");
    $stmt->bind_param("i",$aadhar);
    if($stmt->execute())
    {   $stmt->close();
        $r = $conn->prepare("DELETE FROM wallet WHERE aadhar_number = ?");
        $r->bind_param("i",$aadhar);
        $response['message'] = 1;
    }
    else
    {  $response['message']=0;

	   }
    echo json_encode($response);

  }
}
else {
  $response['message']=0;		
  echo $response;
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
