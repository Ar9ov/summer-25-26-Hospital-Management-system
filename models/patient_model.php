<?php
function get_patients($conn, $search='') {
    $search = "%" . $search . "%";
    $stmt = $conn->prepare('SELECT * FROM patients WHERE patient_name LIKE ? OR phone LIKE ? ORDER BY id DESC');
    $stmt->bind_param('ss', $search, $search); $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function add_patient($conn, $name, $phone, $age, $gender, $wheelchair) {
    $stmt=$conn->prepare('INSERT INTO patients(patient_name,phone,age,gender,wheelchair) VALUES(?,?,?,?,?)');
    $stmt->bind_param('ssisi',$name,$phone,$age,$gender,$wheelchair); return $stmt->execute();
}
function update_patient($conn, $id, $name, $phone, $age, $gender, $wheelchair) {
    $stmt=$conn->prepare('UPDATE patients SET patient_name=?,phone=?,age=?,gender=?,wheelchair=? WHERE id=?');
    $stmt->bind_param('ssisii',$name,$phone,$age,$gender,$wheelchair,$id); return $stmt->execute();
}
function delete_patient($conn, $id) { $stmt=$conn->prepare('DELETE FROM patients WHERE id=?'); $stmt->bind_param('i',$id); return $stmt->execute(); }
function find_patient($conn,$id) { $stmt=$conn->prepare('SELECT * FROM patients WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); return $stmt->get_result()->fetch_assoc(); }
?>
