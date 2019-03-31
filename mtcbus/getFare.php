<?php
require_once 'connectDB.php';

$response = array();

if(isset($_GET['dst'],$_GET['src']))
{

 if(isAvailable(array('src','dst')))
  { $src = $_GET['src'];
    $dst = $_GET['dst'];
	
	$src = str_replace("'","",$src);
	
	$dst = str_replace("'","",$dst);
	
    $stmt = $conn->prepare("CALL `getRouteFare`(?,?)");
    $stmt->bind_param("ss",$src,$dst);
    if($stmt->execute())
    { 
      $list=$stmt->get_result();
	  $response['result']=array();
      while($data = $list->fetch_assoc())

      { 
		
		array_push($response['result'],$data);
      }

	  $response["message"] = 1;
    }
    $stmt->close();
    echo json_encode($response);
  }
}
else {
  $response["message"] = 0;
  echo json_encode($response);
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
