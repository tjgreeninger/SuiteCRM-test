
<?php

// specify the REST web service to interact with

$url = 'http://localhost/suitecrm/service/v2/rest.php';

// Open a curl session for making the call
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
    'query' => "",   //Where condition without "where" keyword
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
    'response_type' => 'JSON',
    'rest_data' => $json,
    );

curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

$response = curl_exec($curl);

// Convert the result from JSON format to a PHP array

$result = json_decode($response);

print "<pre>";
//print_r ($result);
$i = 0;
while ($i < 6) {
	$phonenumber = $result->entry_list[$i]->name_value_list->phone_work->value;
	$phone_number_stripped = preg_replace("/[^0-9]/","", $phonenumber);
	//echo $phone_number_stripped."<br>";
	$i++;
}
echo $phone_number_stripped;
$duplicates = (array_unique($phone_number_stripped));
print_r($duplicates);
?>
