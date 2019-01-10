# PHP Oracle SQL to JSON for Google Visualization API
The intent of this repo is to provide a solution to creating a Google Visualization Data Table from an Oracle database and PHP. This can then be used to create Google Charts. The way it works is the PHP connects to the DB and executes the desired SQL and outputs a JSON string that can be directly plugged in to the Google Visualization API to create a Data Table. I havent tried using other database types (sorry), but I assume it should be relatively straight forward adapting the code to fit your DB.

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
  
Key words:
PHP, Oracle, Google Charts, Google Visualization API, JavaScript, JSON, SQL, Query, Database
