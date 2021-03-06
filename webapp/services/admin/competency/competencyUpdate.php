<?php
header('Content-type: application/json');

require "../../conn.php";

//variable creation
$stageData = array();
$gstData = 0;
$menteeID = 0;
$userType = 0;

//collecting input
//$stageData = mysqli_real_escape_string($db, $_GET['stageData']);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$stageData = json_encode($request->stageData);
$gstData = ($request->gstData);
$menteeID = ($request->menteeID);
$userType = ($request->usertype);
$stageNum = "stage".$gstData;

//Collect REVIEW Flag
$review = ($request->review);

//check if the submission is from mentee and update
if($userType == '2'){
    if ($gstData <= 5){
        $query = ("UPDATE menteeworkbook
        SET ".($stageNum)."='".base64_encode($stageData)."', na=1
        WHERE menteeid=".$menteeID);
            
        $executeQuery = mysqli_query($db,$query);
        
        if(mysqli_affected_rows($db) >0 ){
            $success = array(
                       'data'=>'Update successful', 'error'=>null
                       );
            echo json_encode($success);
        }   
        else {
                $error = array(
                'data'=>'null', 'error'=>array('msg'=>'Failed to update status','code'=>'713')
                );
                echo json_encode($error);
        }
    }
}

//check if the submission is from mentor and update
else if($userType == '1'){
    
    if ($gstData < 6){
        
        $currentGST = (($review==1 || $review=="1") ? $gstData : ++$gstData);
        
        $query = ("UPDATE menteeworkbook
        SET ".($stageNum)."='".base64_encode($stageData)."', gst=".$currentGST.", na=0
        WHERE menteeid=".$menteeID);     
        
        $executeQuery = mysqli_query($db,$query);
        
        if(mysqli_affected_rows($db) >0 ){            
            
            if($gstData =='6' || $gstData == 6){
                                $success = array(
                       'data'=>'All stages completed', 'error'=>null
                       );
                echo json_encode($success);
                } 
            else {
                $success = array(
                       'data'=>'Update successful', 'error'=>null
                       );
                echo json_encode($success);
            }
        }   
        else {
             $error = array(
                      'data'=>0, 'error'=>array('msg'=>'Failed to update status','code'=>'714')
                      );
             echo json_encode($error);
        }

    }
}
?>