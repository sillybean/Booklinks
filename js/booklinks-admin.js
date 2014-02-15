jQuery(document).ready(function($){
	// -- Clone table rows
	$(".cloneTableRows").live('click', function(){

		// this tables id
		var thisTableId = $(this).parents("table").attr("id");
	
		// lastRow
		var lastRow = $('#'+thisTableId + " tbody tr:last");
		
		// clone last row
		var newRow = lastRow.clone(true);

		// SCL: this is disabled because we want to be able to delete any row, not just the ones we've added here
		// make the delete image visible
		//$('#'+thisTableId + " tbody tr:last td:first img").css("visibility", "visible");		
		
		var newID = $('#'+thisTableId + " tr").length - 1;
		newRow.attr('id', newID);
		
		// append row to this table
		$('#'+thisTableId).append(newRow);
		
		$('#'+thisTableId + " tbody tr:last td input.name").attr('name', 'mybooks_stores[newstore-'+ newID +'][name]');
		$('#'+thisTableId + " tbody tr:last td input.code").attr('name', 'mybooks_stores[newstore-'+ newID +'][code]');
		$('#'+thisTableId + " tbody tr:last").removeClass('hidden');
		
		// clear the inputs (Optional)
		$('#'+thisTableId + " tbody tr:last td input").val('');
		
		return false;
	});
	
	// Delete a table row
	$(".delRow").click(function(){
		$(this).parents("tr").remove();
		return false;
	});

});
