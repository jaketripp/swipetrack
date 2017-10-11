$(document).ready(function() {
	getToppings();
});

$(document).on("keydown", function(e) {
	// if user presses enter, add topping
	var key = e.which || e.keyCode || e.charCode;
	if (key === 13 && $('#topping').val().length > 0) {
		addTopping();
	}
});

$(document).on("click", "#btnSubmit", function(e) {
	e.stopPropagation();
	e.preventDefault();
	if ($('#topping').val().length > 0) {
		addTopping();
	}
});

$(document).on('click', 'span', function(event) {
	var id = $(this).parent().attr('data-toppingid');

	// remove topping is the only one that needs a way to id it
	removeTopping(id);

	// animation and remove from DOM
	$(this).parent().fadeOut(500, function() {
		$(this).remove();
	});

	event.stopPropagation();
});

function addTopping() {
	$.ajax({
		url: 'pizzapizza.php?action=addTopping',
		dataType: 'json',
		data: {
			topping: $("#topping").val()
		},
		success: function(result) {

			// if topping wasn't added, show the error message
			if (result.success === "0") {
				showError(result.errormsg);

			// clear the input and update (getToppings from the server)
			} else {
				$("#topping").val("");
				getToppings();
			}
		},
		// if ajax didn't work, say you couldn't reach the extension
		error: function(error) {
			showError('Error Reaching pizzapizza.php');
		}
	});
}

function getToppings() {
	$.ajax({
		url: 'pizzapizza.php?action=getToppings',
		dataType: 'json',
		success: function(result) {
			// if topping not added, show error msg (which was set to an empty string? will just alert "Error:")
			if (result.success === "0") {
				showError(result.errormsg);
			} else {
				updateDOMToppings(result.toppings);
			}
		},
		error: function(error) {
			showError('Error Reaching Server');
		}
	});
}

function updateDOMToppings(toppings) {
	// if there is at least one topping
	if (Object.keys(toppings).length > 0) {

		// clear the UL
		$("#listToppings").empty();

		// for each topping, append an li with an id of the number, a value, and a span with a trashcan icon
		$.each(toppings, function(key, value) {
			$("#listToppings").append("<li data-toppingid='" + key + "'><span><i class='trash icon' aria-hidden='true'></i></span> " + value + "</li>");
		});

	} else {
		$("#listToppings").html("");
	}
}

// returns an array of the current toppings
function parseDOMForToppings() {
	var arr = [];
	$('li').each(function(i, li) {
		var liWithoutSpace = (li.textContent).substring(1);
		arr.push(liWithoutSpace);
	});
	return arr;
}

function removeTopping(e) {

	var toppings = parseDOMForToppings();
	// remove clicked item from array
	if (toppings.length > 1) {
		toppings.splice(e, 1);
	// or set array to empty string (because php uses isset and empty string is valid json) 
	} else {
		toppings = '';
	}

	// send array with item removed or an empty string, php sets the $_SESSION['toppings'] to newToppings
	$.ajax({
		url: 'pizzapizza.php?action=removeToppings',
		data: {
			newToppings: toppings
		},
		dataType: 'json',
		success: function(result) {
			// if topping wasn't removed, show the error message
			if (result.success === "0") {
				showError(result.errormsg);
			} 
		},
		// if ajax didn't work, say you couldn't reach the extension
		error: function(error) {
			showError('Error Reaching pizzapizza.php');
		},
	});
}

// alerts a minimally formatted error
function showError(message) {
	alert("ERROR: " + message);
}