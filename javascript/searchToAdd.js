//function for searching using the php page and %QUERY which is a typeahead constant for the user input
$(document).ready(function() {//start looking for this after we have loaded everything on the page
$('.typeaheadToAdd').typeahead({ //input field of typeahead with value of f_name!
	name: 'typeaheadToAdd',
	displayKey: 'f_name',
	valueKey: 'f_name',
	remote: 'php/functions.php?searchInput=%QUERY'
})
.on('typeahead:opened', onOpened)
.on('typeahead:selected', onAutocompleted)
.on('typeahead:autocompleted', onSelected);

function onOpened($e) {
	console.log('opened');
}

function onAutocompleted($e, datum) {
	console.log('autocompleted');
	console.log(datum["f_name"]);
	console.log(datum["uID"]);
	document.getElementById('userIDToAdd').value = datum["uID"];
}

function onSelected($e, datum) {
	console.log('selected');
	console.log(datum);
}
})