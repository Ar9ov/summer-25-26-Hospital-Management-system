<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard - Hospital Patient System</title>
<link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">

<div class="topbar">
<h1>Doctors</h1>
<div class="topbar-actions">
<a class="btn-link" href="appointment.php">Book Appointment</a>
<a class="btn-link" href="logout.php">Logout</a>
</div>
</div>

<div class="card">

<table>

<tr>
<th>Name</th>
<th>Department</th>
<th>Status</th>
</tr>

<?php foreach($doctors as $doctor){
    $availability = $doctor["availability"];
    $statusClass = strtolower(str_replace(" ", "-", $availability));
?>

<tr>
<td><?php echo $doctor["full_name"]; ?></td>
<td><?php echo $doctor["department"]; ?></td>
<td><span class="status <?php echo $statusClass; ?>"><?php echo $availability; ?></span></td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>
