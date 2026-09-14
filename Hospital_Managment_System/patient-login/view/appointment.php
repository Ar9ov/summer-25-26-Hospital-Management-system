<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Book Appointment - Hospital Patient System</title>
<link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">

<div class="topbar">
<h1>Appointments</h1>
<div class="topbar-actions">
<a class="btn-link" href="dashboard.php">Back to Dashboard</a>
<a class="btn-link" href="logout.php">Logout</a>
</div>
</div>

<div class="card">

<p class="card-title">Book an Appointment</p>

<form method="post" class="inline-form" id="appointmentForm" novalidate>

<div class="field">
<label for="doctor_id">Doctor</label>
<select id="doctor_id" name="doctor_id">
<?php foreach($doctors as $doctor){ ?>
<option value="<?php echo $doctor['doctor_id']; ?>">
<?php echo $doctor['full_name']; ?> &mdash; <?php echo $doctor['department']; ?>
</option>
<?php } ?>
</select>
</div>

<div class="field">
<label for="appointment_date">Date</label>
<input
type="date"
id="appointment_date"
name="appointment_date">
</div>

<input
type="submit"
value="Book">

</form>

</div>

<div class="card">

<p class="card-title">My Appointments</p>

<table>

<tr>
<th>Doctor</th>
<th>Date</th>
<th>Status</th>
</tr>

<?php foreach($appointments as $app){
    $status = $app["status"];
    $statusClass = strtolower(str_replace(" ", "-", $status));
?>

<tr>
<td><?php echo $app["full_name"]; ?></td>
<td><?php echo $app["appointment_date"]; ?></td>
<td><span class="status <?php echo $statusClass; ?>"><?php echo $status; ?></span></td>
</tr>

<?php } ?>

</table>

</div>

</div>

<script src="../assets/script.js"></script>

</body>
</html>
