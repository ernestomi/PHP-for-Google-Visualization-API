<?php
	include '../php/googleJSONconverter.php';
	$SQLString= "SELECT 'HELLO WORLD' STRING_COLUMN, 1234 NUMBER_COLUMN, SYSDATE DATE_COLUMN FROM DUAL";
	$db = '(DESCRIPTION=(CID=DCA)(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=127.0.0.1)(PORT=1521)))(CONNECT_DATA=(SID=DCA)))' ;
	$un = 'DCA_WEB' ;
	$pw = 'Web0' ;	
	getSQLDataTable($un,$pw,$db,$SQLString);
?>
