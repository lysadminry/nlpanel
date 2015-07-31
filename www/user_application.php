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

try {

if(file_exists($sql_location)) $sql_conn = new PDO("sqlite:$sql_location");
else panic("SQL Database file not found");

$sql_conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

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

if(!$userdata_array['username']) panic("Fill the form m8");

$sql_conn->beginTransaction();

$sql_conn->exec("
INSERT INTO new_users (username, fname, lname, pname, bday, syear, primary_group, phone, email, ctime)
VALUES ('$userdata_array[username]',
'$userdata_array[fname]',
'$userdata_array[lname]',
'$userdata_array[pname]',
'$userdata_array[bday]',
'$userdata_array[syear]',
'$userdata_array[primary_group]',
'$userdata_array[phone]',
'$userdata_array[email]',
'$userdata_array[date]'
);");

print("
INSERT INTO new_users (username, fname, lname, pname, bday, syear, primary_group, phone, email, ctime)
VALUES ('$userdata_array[username]',
'$userdata_array[fname]',
'$userdata_array[lname]',
'$userdata_array[pname]',
'$userdata_array[bday]',
'$userdata_array[syear]',
'$userdata_array[primary_group]',
'$userdata_array[phone]',
'$userdata_array[email]',
'$userdata_array[date]'
);");

$sql_conn->commit();

}

catch(PDOException $e) {
	echo $e->getMessage()."<br>";
}

$sql_conn = null;

print("User account application saved");

?>
</body>
</html>
