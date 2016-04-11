# PHP-for-Google-Visualization-API
PHP code that takes a Query result from an Oracle connection and converts it to a JSON that can be directly plugged in to the Google Visualization API to create a Data Table.

The code is split into 3 functions:

-getSQLDataTable:
    This is the main code that takes in an SQL Query String, Database Connection details and generates two arrays, one for columns and one for rows.
    
-arrayToGoogleDataTable:
    This simply takes the arrays from the previous function and outputs the JSON string in the Google Visualization API format
    
-convertGoogleDate:
    This takes a date and creates a string that the google API recognizes as a date



I have also included a sample for the front end integration:

-sampleSQLtoGoogle.js:
  JS to generate the chart
  
-sampleSQLtoGoogle.html:
  HTML document to bring it all together!



I prefer to keep the php, js and html separate. This way, I can have many php files that relate to different information I want to distribute,
whilst keeping the client side simple and neat with as few JS as possible. The other approach that some people prefer is to have the JS be generated 
by PHP and output the JSON directly on the JS code. It really depends on what you want.  

If you havent already, take a look at the Google Visualization API documentation:
  https://developers.google.com/chart/interactive/docs/reference#datatable-class
  
