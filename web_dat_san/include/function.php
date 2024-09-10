<?php


function loadClass($c)
{
	include ROOT."/classes/".$c.".class.php";
}

function getIndex($index, $value='')
{
	$data = isset($_GET[$index])? $_GET[$index]:$value;
	return $data;
}

function postIndex($index, $value='')
{
	$data = isset($_POST[$index])? $_POST[$index]:$value;
	return $data;
}

function requestIndex($index, $value='')
{
	$data = isset($_REQUEST[$index])? $_REQUEST[$index]:$value;
	return $data;
}

function isValidVietnamPhoneNumber($phone) {
    $pattern = "/^(03|05|07|08|09)[0-9]{8}$/";
    return preg_match($pattern, $phone);
}

function isValidPassword($password) {
	$pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/";
	return preg_match($pattern, $password);
}

function isValidateNumber($number){
	$pattern = "/^[0-9]+$/";
	return preg_match($pattern, $number);
}

function randomString($length) {
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = '';
	$currentYear = date('Y');
	for ($i = 0; $i < $length; $i++) {
		$string .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $string . $currentYear;
}
