<?php
require_once 'connectDB.php';



$response = array();
$cnt=0;

if(isset($_GET['src'],$_GET['dst']))
{
 if(isAvailable(array('src','dst')))
  { $source = $_GET['src'];
    $dest = $_GET['dst'];
    //Calling the Queries
    $stmt = $conn->prepare("CALL `getRouteFare`(?,?)");
    $stmt->bind_param("ss",$source,$dest);
    if($stmt->execute())
    {
        $list=$stmt->get_result();
		$response['result'] = array();
        while($data = $list->fetch_assoc())
        { 
		  
		  
		  array_push($response['result'],($data));
          $cnt = 1;
        }
		$response['message']=1;

    }

    $stmt->close();
    
	//Checking if no rows where returned
    if($cnt ==0){
		
		$response['message']=0;
		
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
