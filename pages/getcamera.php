<?php
	
	if(!empty($_POST['name']))
	{
		$name = $_POST['name'];
	}
	else
		$name = '';
	
	if(!empty($name))
	{
		$directoryName = "/xampp/htdocs/pages/mqtt/".$name; 

		//Check if the directory already exists.
		if(!is_dir($directoryName)){
		//Directory does not exist, so lets create it.
			mkdir($directoryName, 0755);
		}		
		
		$array = array();
		$newDirectory2 = opendir("/xampp/htdocs/pages/mqtt/".$name);
		// get each entry
		while($entryNames2 = readdir($newDirectory2)) {
			$dirArrays2[] = $entryNames2;
		}

		// close directory
		closedir($newDirectory2);

		//	count elements in array
		$indexCounts2 = count($dirArrays2);
				
		// loop through the array of files and print them all in a list
		for($index=0; $index < $indexCounts2; $index++) {
			$extensions2 = substr($dirArrays2[$index], -3);
			if ($extensions2 == 'png'){ // list only jpgs	
				array_push($array, $dirArrays2[$index].'');
			}	
		}
		
		echo json_encode($array);
	}
		
	

				
?>
