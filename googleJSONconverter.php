<?php
	/*
	 * @author Ernesto Monroy <ehmizmg@gmail.com>
	 * @version 1.0
	 * This function takes the Connection Details and the SQL String and creates an two arrays, one for the column info
	 * and one for the row data. This is then passed to the arrayToGoogleDataTable that builds and outputs the JSON string
	 * 
	 * Input for this is:
	 * 		$un: User Name
	 * 		$pw: Password
	 * 		$db: Connection String for the DB
	 * 				(e.g. '(DESCRIPTION=(CID=MyDB)(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=127.0.0.1)(PORT=1521)))(CONNECT_DATA=(SID=MyDB)))' )
	 * 				(tip, if you are running your PHP and Oracle Instance on the same server 127.0.0.1 should be used) 
	 * 
	 * WARNINGS:
	 * 		-I have not included any Oracle Error Handling
	 * 		-I have not included any row limit, so if your query returns an unmanageable amount of rows, it may become an issue for you or your web users.
	 *FUTURE OPPORTUNITIES:
	 *		-This functions only return type and label properties. If you want to use pattern, id or p (for styling) you can add a check  when looping through the columns
	 *		and try to detect a particular column name that you define as the property (EG. if oci_field_name($stid, $i)=="GOOGLE_P_DATA" then ....)
	 */
	function getSQLDataTable($un,$pw,$db,$SQLString){
		$conn=oci_connect($un,$pw,$db);
		$stid= oci_parse($conn, "ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD HH24:MI:SS'");
		oci_execute($stid);
		$stid= oci_parse($conn, $SQLString);
		oci_execute($stid);
		$ncols = oci_num_fields($stid);
		$cols = array();
		$rows = array();
		$cell = array();
		for ($i = 1; $i <= $ncols; $i++) {
	   		$column_label  = '"'.oci_field_name($stid, $i).'"';
			$column_type  = oci_field_type($stid, $i);
			
			switch($column_type) {
				case 'CHAR': case 'VARCHAR2':
					$column_type='"string"';
				break;
				case 'DATE':
					$column_type='"datetime"';
				break;
				case 'NUMBER':
					$column_type='"number"';
				break;
			}
			$cols[$i-1]=array('"type"'=>$column_type,'"label"'=>$column_label);
		}
		$j=0;
		while (($row = oci_fetch_array($stid, OCI_NUM+OCI_RETURN_NULLS)) != false) {
			for ($i = 0; $i <= $ncols-1; $i++) {	
				switch(oci_field_type($stid, $i+1)) {
					case 'CHAR': case 'VARCHAR2':
						$cellValue='"'.$row[$i].'"';
						$cellFormat='"'.$row[$i].'"';
					break;
					case 'DATE':
						if($row[$i]==null){
							$cellValue='""';
							$cellFormat='""';
						} else {
							$cellValue=convertGoogleDate(date_create($row[$i]));
							$cellFormat='"'.date_format(date_create($row[$i]), 'd/m/Y H:i:s').'"';
						}
						
					break;
					case 'NUMBER':
						$cellValue=number_format($row[$i], 2, '.', '');
						$cellFormat='"'.$row[$i].'"';
					break;
					
					
				} //end of switch
				$cell[$i]=array('"v"'=>$cellValue,'"f"'=>$cellFormat);	
				
			}	
			$rows[$j]=$cell;		
			$j++;
		}		
		arrayToGoogleDataTable($cols, $rows);
	}
	
	/*This function takes in the columns and rows created in the previous function and returns the JSON String*/
	function arrayToGoogleDataTable($cols, $rows) {
		//Convert column array into google string literal
		echo "{\n";
		echo "\t".'"cols"'.": [\n";
		for($i = 0; $i < count($cols)-1; $i++) {
			echo "\t\t{";
			$n=count($cols[$i]);
			foreach($cols[$i] as $arrayKey => $arrayValue) {
    				echo $arrayKey . ":" . $arrayValue;
				$n--;
				if ($n>0) {echo ",";}
			}
			echo "},\n";
		}
		//Last column without ending comma (},)
		echo "\t\t{";
		$n=count($cols[$i]);
		foreach($cols[$i] as $arrayKey => $arrayValue) {
    			echo $arrayKey . ":" . $arrayValue;
			$n--;
			if ($n>0) {echo ",";}
		}
		echo "}\n\t]";
	
		//Now do the rows
		//Check if empty first
		if (count($rows)>0){
			echo ",\n\t".'"rows"'.": [\n";
			//For each row
			for($j = 0; $j < count($rows)-1; $j++) {
				echo "\t\t{".'"c":[';
				//For each cell
				for($i = 0; $i < count($rows[$j])-1; $i++) {
					echo "{";
					$n=count($rows[$j][$i]);
					foreach($rows[$j][$i] as $arrayKey => $arrayValue) {
	    					echo $arrayKey . ":" . $arrayValue;
						$n--;
						if ($n>0) {echo ",";}
					}
					echo "},";
				}
				//Last column without ending comma (},)
				$n=count($rows[$j][$i]);
				echo "{";
					foreach($rows[$j][$i] as $arrayKey => $arrayValue) {
	    				echo $arrayKey . ":" . $arrayValue;
					$n--;
					if ($n>0) {echo ",";}
				}
				echo "}]},\n";
			}
			//Last row
			echo "\t\t{".'"c":[';
			//For each cell
			for($i = 0; $i < count($rows[$j])-1; $i++) {
				echo "{";
				$n=count($rows[$j][$i]);
				foreach($rows[$j][$i] as $arrayKey => $arrayValue) {
	    				echo $arrayKey . ":" . $arrayValue;
					$n--;
					if ($n>0) {echo ",";}
				}
				echo "},";
			}
			$n=count($rows[$j][$i]);
			echo "{";
				foreach($rows[$j][$i] as $arrayKey => $arrayValue) {
	    			echo $arrayKey . ":" . $arrayValue;
				$n--;
				if ($n>0) {echo ",";}
			}
			echo "}]}\n";
			echo "\t]";
		}	
		echo"\n}";

	}
	/*This simply takes in a Date and converts it to a string that the google API recognizes as a Date*/
	function convertGoogleDate(DateTime $inDate) {
		$googleString='"Date(';
		$googleString=$googleString.date_format($inDate, 'Y').',';
		$googleString=$googleString.(date_format($inDate, 'm')-1).',';
		$googleString=$googleString.(date_format($inDate, 'd')*1).',';
		$googleString=$googleString.(date_format($inDate, 'H')*1).',';
		$googleString=$googleString.(date_format($inDate, 'i')*1).',';
		$googleString=$googleString.(date_format($inDate, 's')*1).')"';
		return $googleString;
	}

?>
