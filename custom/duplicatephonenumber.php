
<?php
function suitecrmQuery($query) {
$url = 'http://localhost/suitecrm/service/v2/rest.php'; // Open a curl session for making the call
$curl = curl_init($url);


// Tell curl to use HTTP POST

curl_setopt($curl, CURLOPT_POST, true);

// Tell curl not to return headers, but do return the response

curl_setopt($curl, CURLOPT_HEADER, false);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



// Set the POST arguments to pass to the Sugar server

$parameters = array(

    'user_auth' => array(

        'user_name' => 'tjgreeninger',

        'password' => md5('Snapple pie dog*()'),

        ),

    );
 $json = json_encode($parameters);
$postArgs = array(
    'method' => 'login',
    'input_type' => 'JSON',
    'response_type' => 'JSON',
    'rest_data' => $json,
    );
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
$response = curl_exec($curl);
// Convert the result from JSON format to a PHP array
$result = json_decode($response);
if ( !is_object($result) ) {
    die("Error handling result.\n");
}
if ( !isset($result->id) ) {
    die("Error: {$result->name} - {$result->description}\n.");
}
// Echo out the session id
//echo $result->id."<br />";

$session = $result->id;

$fields_array = array('first_name','last_name','phone_work','phone_mobile');

$query_variable = "phone_work = '$query' OR phone_mobile = '$query'";


print_r ($query_variable);


$parameters = array(

    'session' => $session,                                 //Session ID
    'module_name' => 'Contacts',                             //Module name
    'query' => "$query_variable",
    'order_by' => " contacts.last_name ",                 //$order_by
    'offset'  => 0,                                               //offset
    'select_fields' => $fields_array,                      //select_fields
    'link_name_to_fields_array' => array(array()),//optional
    'max_results' => 15,                                        //max results
    'deleted' => 'false',                                        //deleted
);
$json = json_encode($parameters);

$postArgs = array(
    'method' => 'get_entry_list',
    'input_type' => 'JSON',
    'response_type' => 'JSON', 'rest_data' => $json,);

curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

$response = curl_exec($curl);

// Convert the result from JSON format to a PHP array
$result = json_decode($response);
print "<pre>";
print_r ($result);

return $result;

require_once('include/SugarPHPMailer.php');
$emailObj = new Email();
$defaults = $emailObj->getSystemDefaultEmail();
$mail = new SugarPHPMailer();
$mail->setMailerForSystem();
$mail->From = $defaults['email'];
$mail->FromName = $defaults['name'];
$mail->Subject = 'Duplicate Contacts';
$mail->Body = 'It looks like these ID numbers have duplicate phone numbers';
$mail->prepForOutbound();
$mail->AddAddress('tjgreeninger@gmail.com');
@$mail->Send();
}







// specify the REST web service to interact with

$url = 'http://localhost/suitecrm/service/v2/rest.php'; // Open a curl session for making the call
$curl = curl_init($url);


// Tell curl to use HTTP POST

curl_setopt($curl, CURLOPT_POST, true);

// Tell curl not to return headers, but do return the response

curl_setopt($curl, CURLOPT_HEADER, false);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



// Set the POST arguments to pass to the Sugar server

$parameters = array(

    'user_auth' => array(

        'user_name' => 'tjgreeninger',

        'password' => md5('Snapple pie dog*()'),

        ),

    );
 $json = json_encode($parameters);
$postArgs = array(
    'method' => 'login',
    'input_type' => 'JSON',
    'response_type' => 'JSON',
    'rest_data' => $json,
    );
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

// Make the REST call, returning the result
$response = curl_exec($curl);
// Convert the result from JSON format to a PHP array
$result = json_decode($response);
if ( !is_object($result) ) {
    die("Error handling result.\n");
}
if ( !isset($result->id) ) {
    die("Error: {$result->name} - {$result->description}\n.");
}
// Echo out the session id
//echo $result->id."<br />";

$session = $result->id;

$fields_array = array('first_name','last_name','phone_work','phone_mobile');

$parameters = array(

    'session' => $session,                                 //Session ID
    'module_name' => 'Contacts',                             //Module name
    'query' => "",
    'order_by' => " contacts.last_name ",                 //$order_by
    'offset'  => 0,                                               //offset
    'select_fields' => $fields_array,                      //select_fields
    'link_name_to_fields_array' => array(array()),//optional
    'max_results' => 15,                                        //max results
    'deleted' => 'false',                                        //deleted
);

$json = json_encode($parameters);

$postArgs = array(
    'method' => 'get_entry_list',
    'input_type' => 'JSON',
    'response_type' => 'JSON', 'rest_data' => $json,);

curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

$response = curl_exec($curl);

// Convert the result from JSON format to a PHP array
$result = json_decode($response);
print "<pre>";
//print_r ($result);
$i = 0;
while ($i < 20) {
	$phonenumber_work = $result->entry_list[$i]->name_value_list->phone_work->value;
	$phonenumber_mobile = $result->entry_list[$i]->name_value_list->phone_mobile->value;
	$phonenumber_home = $result->entry_list[$i]->name_value_list->phone_home->value;
		if (!empty($phonenumber_mobile)) { $all_phone_numbers[] = $phonenumber_mobile; }
		if (!empty($phonenumber_home)) { $all_phone_numbers[] = $phonenumber_home; }
                if (!empty($phonenumber_work)) { $all_phone_numbers[] = $phonenumber_work; }
	$phone_number_stripped = preg_replace("/[^0-9]/","", $all_phone_numbers);
	//echo $phone_number_stripped."<br>";
	$i++;
}
//print_r($phone_number_stripped);
$noduplicates = array_unique($phone_number_stripped);
$duplicates = array_diff($phone_number_stripped, $noduplicates);
//echo "It looks like these phone numbers have duplicates ";
//echo "<pre>";
foreach ($duplicates as $val) {
   echo $val;
   print "<br>";
}

//Make array of all contact numbers
$i = 0;
foreach ($duplicates as $key => $value) {
	$duplicate_contact = array_search($value, $phone_number_stripped);
//	print_r($duplicate_contact);
	print "<br>";
	$i++;
}

foreach ($duplicate_contact as $key => $value) {
	$duplicate_id = $result->entry_list[$value]->id;
	//print_r($duplicate_id);
}
//Make array of all duplicate phone number id's

$i=0;
while ($i < 20) {
	$idnumber = $result->entry_list[$i]->id;
	if (!empty($idnumber)) {$contact_array[] = $idnumber; }
	$i++;
}
//print_r($contact_array);


$noduplicates1 = array_unique($all_phone_numbers);
$duplicates1 = array_diff_assoc($all_phone_numbers, $noduplicates1);

foreach ($duplicates1 as $value) {
	$query[] = $value;
}
//foreach ($almostquery as $value) {
//	$query[] = ("'" . $value . "'");
//}

//print_r($query);
foreach ($query as $value) {
	suitecrmQuery($value);
}





?>
