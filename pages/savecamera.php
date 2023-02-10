<?php

if (isset($_POST['panda']) && isset($_POST['username']))
{
	$panda = $_POST['panda'];
	$username = $_POST['username'];
}
else
{
	$panda = '';
	$username = '';
}

if(!empty($panda) && !empty($username))
{
	$directoryName = "/xampp/htdocs/pages/mqtt/".$username; 
	//Check if the directory already exists.
	if(!is_dir($directoryName)){
	//Directory does not exist, so lets create it.
		mkdir($directoryName, 0755);
	}
	
	date_default_timezone_set('Asia/Singapore');
	$today = date('Y-m-d_H-i-s');
	$filepath = "/xampp/htdocs/pages/mqtt/".$username."/".$today.".png"; // or image.jpg
	$data = base64_decode($panda);
	
	// Save the image in a defined path
	file_put_contents($filepath, $data);
	
	$myDirectory = opendir("/xampp/htdocs/pages/mqtt/".$username);

	// get each entry
	while($entryName = readdir($myDirectory)) {
		$dirArray[] = $entryName;
	}

	// close directory
	closedir($myDirectory);

	//	count elements in array
	$indexCount	= count($dirArray);
				
	//echo $indexCount;
	asort($dirArray, SORT_NUMERIC);

	for ($index = 0; $index < ($indexCount - 5); $index++)
	{
		$extension = substr($dirArray[$index], -3);
		if ($extension == 'png'){ // list only jpgs
		unlink('/xampp/htdocs/pages/mqtt/'.$username.'/'.$dirArray[$index]);
		}	
	}
	
	echo "Success";
}




?>