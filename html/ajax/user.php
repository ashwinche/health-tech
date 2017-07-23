<?php
  /** -- GET
  * 
  *  Get data about user.
  *  INPUT: u: user id
  *         q: space separated list of fields requested.
  *  OUTPUT:
  *   JSON response.
  *  SECURITY:
  *   verify that sensitive profile elements are owned by the user.
  */

function require_param_in( $parm, $array, $msg ){
  if(!array_key_exists($parm, $array)){
    header($msg);
    exit;
  }
}

require_once($_SERVER['DOCUMENT_ROOT']."/php/util/global.php");
import('/php/model/doctor.php');
import('/php/model/user.php');
import('/php/util/sanitize.php');

session_start();
$params = json_decode(file_get_contents("php://input"), $assoc=true);

require_param_in('u', $_GET, 'HTTP/1.1 400 Bad Request');
require_param_in('q', $_GET, 'HTTP/1.1 400 Bad Request');

if(!$_SESSION['valid']){
  header('HTTP/1.1 403 Forbidden');
  return;
}
$uid = sanitize_number($_GET['u']);

$user = new User($_SESSION['user_id']);
$want = new User($uid);

if(!$want->exists()){
  header('HTTP/1.1 403 Forbidden');
  return;
}

// don't allow patients to see other patients' information.
if(    !$want->vals[0]['user_is_doctor']
    && !$user->vals[0]['user_is_doctor']
    &&  $user->user_id != $want->user_id){

  header('HTTP/1.1 403 Forbidden');
  return;
}

$asking = explode(' ',$_GET['q']);
$output = array();
foreach($asking as $term){
  if($term =='fname'){
    $output[$term] = $user->vals[0]['user_first_name'];
  }
  if($term =='lname'){
    $output[$term] = $user->vals[0]['user_last_name'];
  }
}

echo json_encode($output);

// echo $req;
// echo($user->exists());
// echo 'and';
// echo($want->exists());

// $uid = $_GET['u'];
// $nature = $_GET['n'];
// if(!($nature==='profile_picture' || $nature==='certification')){
//   header( "HTTP/1.0 404 Not Found ");
// }
// $request = new FileDownloadRequest($_SESSION, $nature, $uid);
// $request->serve();


?>