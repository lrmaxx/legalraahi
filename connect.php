
<?php
// print_r($redirect);
// die();
// ini_set('display_errors', 1);
 //ini_set('display_startup_errors', 1);
 //error_reporting(E_ALL);

if (empty($_GET['name']) || empty($_GET['email']) || empty($_GET['contact'])) {
	header('Location: /');
	die();
}
$name = urldecode($_GET['name']);
$redirect = $_GET['redirect'];
$comment =  $_GET['analytics'] ."||message=". $_GET['message'];


print_r($redirect);
// die();
function validateInput() {
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;

	}

	// $name = test_input($_GET['name']);
	// if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
	//  header('Location: /');
	//  die();
	// }

	// validate email
	$email = test_input($_GET['email']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('Location: /');
		die();
	}

	$mobile = test_input($_GET['contact']);
	$mobile = str_replace(' ', '', $mobile);
	if (substr($mobile, 0, 1) == '0') {
		echo $mobile = substr($mobile, 1);
	}

	if (!preg_match('/^(\+91[\-\s]?)?[0]?(91)?[6-9]\d{9}$/', $mobile)) {
		header('Location: /');
		die();
	}

	// $_GET['name'] = $name;
	$_GET['email'] = $email;
	$_GET['contact'] = $mobile;
}
// header('Location: https://www.legalraasta.com/private-limited-registration');
validateInput();

if (!empty($_GET['ref']) && isset($_GET['ref'])) {
	$referralUrl = $_GET['ref'];
} else {
	$referralUrl = $_SERVER['HTTP_HOST'];
}

// trim the source description to 200 characters
// if (strlen(trim($referralUrl)) > 200) {
// 	$referralUrl = substr($referralUrl, 0, 200);
// }

// $Data = array(
// 	'TITLE' => $_GET['title'],
// 	//'COMPANY_TITLE' => $_GET['Regcompany'],
// 	'NAME' => $name,
// 	'EMAIL_WORK' => $_GET['email'],
// 	'PHONE_WORK' => $_GET['contact'],
// 	'SOURCE_DESCRIPTION' => $referralUrl,
// 	 'ADDRESS_CITY' => $_GET['cityfield'],
// 	 'ASSIGNED_BY_ID' => $_GET['Regresp'],
// 	'POST' => $_GET['position'],
// 	'COMMENTS' => $_GET['analytics'],
// 	// 'UF_CRM_1557731305' => $_GET['cityfield'],
// 	//'UF_CRM_1574154679032'=>"web.whatsapp.com/send?phone=91".$_GET['contact'],
// 	'UF_CRM_1557814705' => rand(1, 10),
// 	'MessageData' => array(
// 		'Message_Flag' => "1",
// 		'Message' => $_GET['message'],
// 	),
// );

$Data = array(
	"fields" => array(
		'TITLE' => $_GET['title'],
		//'COMPANY_TITLE' => $_GET['Regcompany'],
		'NAME' => $name,
		"EMAIL" => [["VALUE" => $_GET['email'], "VALUE_TYPE" => "WORK"]],
		"PHONE" => [["VALUE" => $_GET['contact'], "VALUE_TYPE" => "WORK"]],
		'SOURCE_DESCRIPTION' => $referralUrl,
		'ADDRESS_CITY' => $_GET['cityfield'],
		'ASSIGNED_BY_ID' => $_GET['Regresp'],
		'POST' => $_GET['position'],
		'COMMENTS' => $comment,
		// 'UF_CRM_1557731305' => $_GET['cityfield'],
		//'UF_CRM_1574154679032'=>"web.whatsapp.com/send?phone=91".$_GET['contact'],
		'UF_CRM_1557814705' => rand(1, 10),
	),
);
// print_r($Data);
// die();
$leadId = bitrix_funtion($Data, '');

if (!empty($leadId) && !empty($redirect)) {
	header("Location: $redirect ");
	//echo $leadId;
	header('Location: ' . $_GET['redirect'] . '?name=' . $_GET['name'] . '&contact=' . $_GET['contact'] . '&id=' . $leadId . '&email=' . $_GET['email']);
} else {

	header("Location: https://www.legalraasta.com/thanks/");
}

function bitrix_funtion($postParam, $reqHeaders) {
	$timeout = 600;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://crm.legalraasta.in/rest/223/dkwxtxkh0ks7zny7/crm.lead.add');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/59.0.3071.109 Chrome/59.0.3071.109 Safari/537.36');
	if ($reqHeaders != '') {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $reqHeaders);
	} else {
		curl_setopt($ch, CURLOPT_HEADER, 0);
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	if ($postParam != '') {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParam));
	}
	curl_setopt($ch, CURLOPT_COOKIESESSION, false);
	$responsePage = curl_exec($ch);
	curl_close($ch);
	return json_decode($responsePage)->result;

}

// //$link = explode("?", $_GET['redirect'])[0];

// //print_r($_GET);
// $exploded = explode("|", explode("?", $_GET['redirect'])[1]);
// $name = $exploded[0];
// $email = $exploded[1];
// $mobile = $exploded[2];
// // $redirectget = $_GET['redirect'];
// $redirect = "https://www.legalraasta.com?auto_name=$name&auto_email=$email&auto_mobile=$mobile";

// //print_r($Data);
// //die();

// if (bitrix_funtion($Data)) {
// 	//code space if lead generated suceessfully
// 	header('Location: ' . $redirect . '&id=' . $leadId);

// 	if (!empty($_GET['search'])) {
// 		header('Location: https://search.legalraasta.com/search?key=' . $_GET['search']);
// 	} else {
// 		header('Location: ' . $redirect . '&id=' . $leadId);
// 	}

// }

// function bitrix_funtion($Data) {
// 	global $leadId;
// 	$ch = curl_init();
// 	curl_setopt($ch, CURLOPT_URL, "https://www.legalraasta.com/lead.php");
// 	curl_setopt($ch, CURLOPT_POST, 1);
// 	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($Data));
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	$output = curl_exec($ch);
// 	if ($output == "false") {
// 		return false;
// 	} else {
// 		$leadId = $output;
// 		return true;
// 	}
// 	// print_r($output);
// 	// exit();
// }

?>


