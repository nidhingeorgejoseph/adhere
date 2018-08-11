<?php
header('Content-type: application/json');

$mentorList = "";
$mentors = array();

$db = new mysqli("localhost", "root", "", "adhere") or die($this->db->error);

$query = "SELECT uid, CONCAT(firstname, ' ', lastname) AS fullname FROM users WHERE usertype = '1'";
$results = mysqli_query($db, $query);

if($results){
    while($mentorList = mysqli_fetch_assoc($results)) {
        $mentors[] = $mentorList;       
    }
    echo json_encode($mentors);
} else {
    $error = array(
            'data'=>'null', 'error'=>array('msg'=>'Error retrieving mentorlist details','code'=>'102')
        );
        echo json_encode($error);
}
?>