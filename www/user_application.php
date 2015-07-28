<!doctype html>
<html>
<body>
<?php
$settings_folder = "/usr/local/nlpanel/etc";

#parse settings
$sql_location = parse_ini_file($settings_folder."/sql.conf")['database'];

#panic motherfuckers
function panic($reason) {
echo '<h2 style="color:red">'.$reason.'</h2></body></html">';
die();
}

#initialize SQL connection
if(file_exists($sql_location)) $sql_conn = new PDO('sqlite:'.$sql_location);
else panic("SQL Database file not found");

$username = $_POST['username'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$pname = $_POST['pname'];
$bday = $_POST['bday'];
$syear = $_POST['syear'];
$group = $_POST['group'];
$phone = $_POST['phone'];
$email = $_POST['email'];



?>
</body>
</html>
