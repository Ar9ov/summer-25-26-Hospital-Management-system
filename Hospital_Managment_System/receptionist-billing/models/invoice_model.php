<?php
function get_invoices($conn, $search='') {
    $search="%".$search."%";
    $stmt=$conn->prepare('SELECT i.*,p.patient_name FROM invoices i JOIN reception_patients p ON p.id=i.patient_id WHERE i.invoice_no LIKE ? OR p.patient_name LIKE ? ORDER BY i.id DESC');
    $stmt->bind_param('ss',$search,$search); $stmt->execute(); return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function add_invoice($conn,$invoice_no,$patient_id,$fee) {
    $stmt=$conn->prepare('INSERT INTO invoices(invoice_no,patient_id,consultation_fee,total_bill) VALUES(?,?,?,?)');
    $stmt->bind_param('sidd',$invoice_no,$patient_id,$fee,$fee); return $stmt->execute();
}
function update_payment($conn,$id,$status) { $stmt=$conn->prepare('UPDATE invoices SET payment_status=? WHERE id=?'); $stmt->bind_param('si',$status,$id); return $stmt->execute(); }
function find_invoice($conn,$id) { $stmt=$conn->prepare('SELECT i.*,p.patient_name,p.phone FROM invoices i JOIN reception_patients p ON p.id=i.patient_id WHERE i.id=?'); $stmt->bind_param('i',$id); $stmt->execute(); return $stmt->get_result()->fetch_assoc(); }
?>
