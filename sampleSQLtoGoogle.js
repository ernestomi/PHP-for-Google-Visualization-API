/*
 * This JS is part of https://github.com/ernestomonroy/PHP-for-Google-Visualization-API
 * 
 * If you havent already, check out the Google Visualization API documentation:
 * 			https://developers.google.com/chart/interactive/docs/reference#DataTable
 * As well as the chart gallery!
 * 			https://developers.google.com/chart/interactive/docs/gallery
 * 
 *
 * I like to keep the table, data and options global so that I can later on add listeners and buttos to make the charts interactive
 */
var table;
var data;
var options;
google.load("visualization", "1.1", {packages:["table"]});
google.setOnLoadCallback(drawTable);

function drawTable(shipmentFilter) {
	// Use AJAX to import the data with a GET
	var jsonData = $.ajax({
		url: "sampleSQLtoGoogle.php",
		dataType: "json",
		async: false
	}).responseText;
	//Use the response text (which is in JSON already) to create the dataTable
	data = new google.visualization.DataTable(jsonData);
	//Your options HERE
    options = {width: '100%', height: '80%'};
    //Create the chart!
	table = new google.visualization.Table(document.getElementById('chart_div'));
	table.draw(data, options);
}
