<?php
  /**
   * 
   *  Doctor search functionality, serviced by GET request.
   *  INPUT:  q=search_term
   *  OUTPUT:
   *    if logged in: JSON representation of all fitting doctors.
   *    else:         limited JSON representation.
   *
   */

require_once($_SERVER['DOCUMENT_ROOT']."/php/util/global.php");
import('php/util/sanitize.php');

  $database = new mysqli("localhost", "ec2-user", "", "HealthTechSchema");
  $query = sanitize_plaintext($_GET['q']);
  $query2 = sanitize_plaintext($_GET['c']);
  $name = explode(' ', $query);
  if(count($name)>1){
      $fn = $name[0];
      $ln = $name[1];
  }
  else{
      $fn = $name[0];
      $ln = '';
  }
  
  $db_1 =
    sprintf("select user_first_name, user_last_name, doctor_speciality, specialities.speciality_name, users.user_id,".
            "doctor_qualifications, doctor_affiliations from doctors,users,specialities where "
            . "(doctor_speciality in (select speciality from speciality_keywords where keyword like '%s')"
            . " or specialities.speciality_name like '%s%%'"
            . " or doctor_location = '%s' or user_first_name like '%s'"
            . " or user_last_name like '%s' or (user_first_name = '%s' and user_last_name = '%s')) ".
            "and doctors.user_id=users.user_id and specialities.speciality=doctor_speciality"
            . " and doctor_cert_status='verified'",$query, $query, $query2, $query, $query, $fn, $ln);
  
 /* 
  $db_1 =
    sprintf("select user_first_name, user_last_name,".
                   "doctor_speciality, specialities.speciality_name, users.user_id,".
                   "doctor_qualifications, doctor_affiliations ".
      "from doctors,users,specialities where doctor_speciality in (select speciality from ".
      "speciality_keywords where keyword like '%s') ".
      "and doctors.user_id=users.user_id and specialities.speciality=doctor_speciality ".
      "and doctor_cert_status='verified'",$query);
*/
  $dq_1 = mysqli_query($database, $db_1);
  $dr_1 = [];
  $ds_1 = '';

  while ($row = $dq_1->fetch_assoc()) {
    array_push($dr_1, $row);
  }
  echo json_encode($dr_1);
  // echo ".<br />";
?>