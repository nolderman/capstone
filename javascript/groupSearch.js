//function for searching using the php page and %QUERY which is a typeahead constant for the user input
			$(document).ready(function() {//start looking for this after we have loaded everything on the page
			$('.groupTypeahead').typeahead({ //input field of typeahead with value of f_name!
				name: 'groupTypeahead',
				displayKey: 'g_name',
				valueKey: 'g_name',
				remote: 'php/functions.php?groupSearchInput=%QUERY'
			})
			.on('typeahead:opened', onOpened)
			.on('typeahead:selected', onAutocompleted)
			.on('typeahead:autocompleted', onSelected);
 
			function onOpened($e) {
				console.log('opened');
			}
 
			function onAutocompleted($e, datum) {
				console.log('autocompleted');
				console.log(datum["g_name"]);
				console.log(datum["gID"]);
				document.getElementById('groupID').value = datum["gID"];
			}
 
			function onSelected($e, datum) {
				console.log('selected');
				console.log(datum);
			}		
		})