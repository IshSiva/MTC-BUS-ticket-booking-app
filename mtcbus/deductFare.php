<?php
require_once 'connectDB.php';

$response = array();

if(isset($_GET['dst'],$_GET['src'],$_GET['rt'],$_GET['aadharnumber']))
{

 if(isAvailable(array('src','dst','rt','aadharnumber')))
  { $src = $_GET['src'];
    $dst = $_GET['dst'];
    $rt = $_GET['rt'];
    $aadhar_num = $_GET['aadharnumber'];

    $stmt = $conn->prepare("SELECT `deductFare`(?,?,?,?)");
    $stmt->bind_param("isss",$aadhar_num,$rt,$src,$dst);
    if($stmt->execute())
    { $stmt->store_result();
      $stmt->bind_result($bal);
      $stmt->fetch();
      $reponse['error'] = False;
      $response['balance']=$bal;
    }
    $stmt->close();
    echo json_encode($response);

  }
}
else
{
  $reponse['error'] = True;
  echo json_encode($reponse);
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
