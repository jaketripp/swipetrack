<?php

/* *************************************************************************
 * Welcome to the SwipeTrack PIZZA PIZZA code exercise.  We'd like to get to
 * know your skillset and style.  We have this "application", designed to
 * let a user create a list of the toppings they want on their pizza.  We
 * store this list on the backend in case the user leaves and returns to the
 * site.  Normally this would be done in a database, but for this exercise
 * we are using a PHP session.
 * 
 * There is one problem -- it doesn't allow the user to remove a topping if
 * they change their mind.  We'd like you to put that functionality in.
 * We would prefer you stick to AJAX so that the imaginary rest of the
 * application interface does not have to be reloaded.  Aside from that,
 * you have complete creative freedom.
 * 
 * If you have any questions, need any help or explanation of any of the
 * code below, please don't hesitate to contact tammy@swipetrack.com
 * *************************************************************************
 */


// if GET action not set, set it to empty string
if (!isset($_GET['action'])) {
	$_GET['action'] = '';
}
session_start();

if ($_GET['action'] == 'addTopping') {
	// initialize $result as an array
	// set error msg to empty string and success number is 0
	$result = array();
	$result['errormsg'] = '';
	$result['success'] = 0;

	if (isset($_GET['topping'])) {
		// if $_SESSION['toppings'] isn't set, set it to an array
		if (!isset($_SESSION['toppings'])) {
			$_SESSION['toppings'] = array();
		}
		// set session toppings to the result of get toppings
		$_SESSION['toppings'][] = $_GET['topping'];
		// change success number to 1
		$result['success'] = 1;
	} else {
		// set the error msg to 'No Toppings Entered'
		$result['errormsg'] = 'No Topping Entered';
	}

	echo json_encode($result);
	exit;

} else if ($_GET['action'] == 'removeToppings') {
	// initialize $result as an array
	// set error msg to empty string and success number is 0
	$result = array();
	$result['errormsg'] = '';
	$result['success'] = 0;

	if (isset($_GET['newToppings'])) {

		// set session toppings to the result of get toppings
		$_SESSION['toppings'] = $_GET['newToppings'];

		// change success number to 1
		$result['success'] = 1;

	} else {
		// set the error msg to 'No Toppings Entered'
		$result['errormsg'] = 'No Topping Clicked';
	}

	echo json_encode($result);
	exit;

} else if ($_GET['action'] == 'getToppings') {

	$result = array();
	$result['errormsg'] = '';
	$result['success'] = 1;
	$result['toppings'] = array();

	if (isset($_SESSION['toppings'])) {
		$result['toppings'] = $_SESSION['toppings'];
		$result['success'] = 1;
	}
	
	echo json_encode($result);
	exit;

} else {
	printForm();
}


function printForm() {?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Pizza Pizza</title>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<script src="jquery.min.js"></script>
			<link rel="stylesheet" href="semantic/semantic.min.css">
			<link rel="stylesheet" href="style.css">
			<script src="pizza.js"></script>
		</head>
		<body>
			
			<div class="ui raised segment" id="main">
				<h1 class="ui header">Pizza Pizza</h1>
				<div class="ui form">
					<div class="inline field">
						<label>What topping would you like?</label>
						<input type="text" id="topping" name="topping" value="">
						<button type="button" class="ui green button" id="btnSubmit" name="btnSubmit">Submit</button>
					</div>
				</div>

				<h3 class="ui header">Your Pizza:</h3>
				<ul id="listToppings"></ul>
			</div>

		</body>
	</html>
<?php
}
