<?php
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../helpers/helpers.php';
require_once __DIR__.'/../models/patient_model.php';
require_once __DIR__.'/../models/invoice_model.php';
$action=$_POST['action'] ?? $_GET['action'] ?? '';
if ($action==='patients') json_response(true,'Patients loaded',get_patients($conn,clean($_GET['search']??'')));
if ($action==='add_patient' || $action==='update_patient') {
 $id=(int)($_POST['id']??0); $name=clean($_POST['patient_name']??''); $phone=clean($_POST['phone']??''); $age=(int)($_POST['age']??-1); $gender=clean($_POST['gender']??''); $wheelchair=isset($_POST['wheelchair'])?1:0;
 if(!valid_patient($name,$phone,$age,$gender)) json_response(false,'Please enter valid patient information.');
 $ok=$action==='add_patient'?add_patient($conn,$name,$phone,$age,$gender,$wheelchair):update_patient($conn,$id,$name,$phone,$age,$gender,$wheelchair);
 json_response($ok,$ok?'Patient saved.':'Could not save patient.');
}
if($action==='delete_patient') json_response(delete_patient($conn,(int)$_POST['id']),'Patient deleted.');
if($action==='invoices') json_response(true,'Invoices loaded',get_invoices($conn,clean($_GET['search']??'')));
if($action==='add_invoice') { $patient=(int)$_POST['patient_id']; $fee=(float)$_POST['consultation_fee']; if($patient<1||$fee<0) json_response(false,'Invalid invoice data.'); $no='INV-'.date('YmdHis').rand(10,99); $ok=add_invoice($conn,$no,$patient,$fee); json_response($ok,$ok?'Invoice created.':'Could not create invoice.',['invoice_no'=>$no]); }
if($action==='payment') { $status=$_POST['payment_status']??''; if(!in_array($status,['Paid','Pending'],true)) json_response(false,'Invalid payment status.'); json_response(update_payment($conn,(int)$_POST['id'],$status),'Payment status updated.'); }
if($action==='invoice') json_response(true,'Invoice loaded',find_invoice($conn,(int)$_GET['id']));
json_response(false,'Unknown action.');
?>
