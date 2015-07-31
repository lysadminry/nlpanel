<!doctype html>
<html>
<body>
<center>
<?php
$settings_folder = "/usr/local/nlpanel/etc";

#Parse settings
$sql_location = parse_ini_file($settings_folder."/sql.conf")['database'];

function panic($reason) {
print('<h2 style="color:red">'.$reason.'</h2></body></html">');
die();
}

$userdata_array = array(
	"username" => preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['username']),
	"fname" => $_POST['fname'],
	"lname" => $_POST['lname'],
	"pname" => $_POST['pname'],
	"bday" => $_POST['bday'],
	"syear" => $_POST['syear'],
	"primary_group" => $_POST['group'],
	"phone" => $_POST['phone'],
	"email" => $_POST['email'],
	"date" => date('Y-m-d H:i:s')
	);

#Sanity checks for user submitted data

foreach($userdata_array as $key => $userdata) {
	if(!$userdata && $key !== "email" && $key !== "phone") panic("Fill all the required fields");
}
if(strlen($userdata_array['bday'] == "6" && is_numeric($userdata_array['bday']))) panic("Incorrect birthday");
if(strlen($userdata_array['syear']) == "4" && is_numeric($userdata_array['syear'])) panic("Incorrect start year");
if($userdata_array['primary_group'] !== "lyseo" AND $userdata_array['primary_group'] !== "opettaja") panic("Incorrect group");
if(!is_numeric($userdata_array['phone'])) panic("Incorrect phone number");

#Try and enter the data to the SQLite database
try {

if(file_exists($sql_location)) $sql_conn = new PDO("sqlite:$sql_location");
else panic("SQL Database file not found");

$sql_conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$sql_conn->beginTransaction();

$database_query = $sql_conn->prepare("INSERT INTO new_users (username, fname, lname, pname, bday, syear, primary_group, phone, email, ctime) VALUES ( :username, :fname, :lname, :pname, :bday, :syear, :primary_group, :phone, :email, :date )");
$database_query->execute($userdata_array);
$sql_conn->commit();
}

catch(PDOException $e) {
	#If pdo exception is caught, close connection and die.
	$sql_conn = null;
	panic($e->getMessage());
}

$sql_conn = null;

print('<h2 style="color:green">User account application saved</h2>');

?>
</center>
</body>
</html>
