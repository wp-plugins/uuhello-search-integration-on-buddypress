<?php

/*

Created by: Leonardo Martinez
Date Created: 10-01-2009
Date Updated: 12-30-2009

==================================================================================
1) Displays all images in the directory(s) or subdiretory(s) specified. 
2) Sorts the images alphabetically
2) Creates an html as "<this_filename>.html" and savs it in the same directory that 
   this php file is located.
==================================================================================

*/

function current_url() {

	// gets the current url of the domain name that refers to this file. 
	// The file name is removed from the url.
	
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	$curr_url = str_replace( current_filename(), '', $pageURL);

	return $curr_url;

}


function current_filename() {

	// gets the current file name for this file.
	
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	return $parts[count($parts) - 1];

}


function array_two_key_swap( $two_dimensional_array ) {

	/*
		Writen by: Leonardo Martinez
		Contact: http://www.leonardomartinez.com/contact/

		Created: 10-10-2009
		Modified: 10-15-2009

		This function takes a two dimensional array[x][y], swaps the first
		key with the second key so the values can be referenced using array[y][x].

		Example:
					samplearray['directiory'][0] = "myicons";
					samplearray['directiory'][1] = "myicons";
					samplearray['directiory'][2] = "myiconsold";
					samplearray['directiory'][3] = "myiconsold";

					samplearray['filename'][0] = "hat.png";
					samplearray['filename'][1] = "dog.png";
					samplearray['filename'][2] = "mice.png";
					samplearray['filename'][3] = "rat.png";

		The above array converts to:

					newarray[0]['filename'] 	= "hat.png";
					newarray[0]['directiory'] 	= "myicons";
					newarray[1]['filename'] 	= "dog.png";
					newarray[1]['directiory'] 	= "myicons";
					newarray[2]['filename'] 	= "mice.png";
					newarray[2]['directiory'] 	= "myiconsold";
					newarray[3]['filename'] 	= "rat.png";
					newarray[3]['directiory'] 	= "myiconsold";

	*/

	$keys = array_keys( $two_dimensional_array );

	$array_swaped = array();

	foreach( $two_dimensional_array[$keys[0]] as $key_counter => $value1 ) {

		$temp_array = array();

		foreach( $keys as $key) {
			$temp_array[$key] = $two_dimensional_array[$key][$key_counter];
		}

		$array_swaped[] = $temp_array;
	}

	return $array_swaped;
}


function create_fileinfo_lists ( $directories = array() ) {
	/*
	
	retrieves all the file names from each directory listed in $directories,
	sorts them and then returns an array with two values, 'filename', and 'directory'

	@directories - array(), list the names of the directories you would like it to search for icons

	return value: <list of file names from all $directories >
	*/

	$arr_directory_list = array();
	
	foreach ( $directories as $directory_name ) {

		$directory  = getcwd() . '/' . $directory_name;
		$directory_filenames = scandir( $directory );

		foreach ($directory_filenames as $filename ) {
			$arr_directory_list['filename'][] =  $filename;
			$arr_directory_list['directory'][] =  $directory_name;
		}

	}
	
	array_multisort( $arr_directory_list['filename'], SORT_ASC, SORT_STRING, 
					 $arr_directory_list['directory'], SORT_ASC, SORT_STRING 
					);

	$joined_array = array_two_key_swap( $arr_directory_list,  $arr_directory_list );

	return $joined_array;

}

function create_icon_html( $directories, $rowcount ) {
	
	/*
		@directories - array(), list the names of the directories you would like it to search for icons 
		@rowcount - number of icons per row.  In our existing setup we were displaying 4 icons per row.
	*/
	
	$file_information_list = create_fileinfo_lists ( $directories );

	// initalize variables used inside foreach loop.
	$total_files = count($file_information_list);
	$website_url = current_url();
	$num_displayed = 0;
	$html = '';


	foreach( $file_information_list as $file_info ) {

		$filename = $file_info['filename'];
		$directory = $file_info['directory'];

		// create html code for image list.

		// filename is not an image skip it.
		
		if ( str_replace('.png', '', $filename ) == $filename)  
		{
			// if images have been displayed close the </div>
			if ( $num_displayed ) { $html .= '<div class="icon-sample">'; $html .= "</div>\n\r"; }
			
			$num_displayed = 0; // reset the display counter
			
			continue; // go to the next image
		}
    	
		$num_displayed += 1;
		
		$imageinfo =  getimagesize( getcwd() . '/' .$directory . '/' . $filename );
		$width  = $imageinfo[0];
		$height = $imageinfo[1];

		switch ($num_displayed) {
			
		case 1:
			$html .= '<div class="icon-sample">'; 
			
		case (true):
			$html .= '<img src="' . $website_url . $directory . '/' . $filename . '" alt="' . $filename . '" width="'.$width.'" height="'.$height.'" />';
			if ($num_displayed !== $rowcount) { break; }
			
		case (($num_displayed == $rowcount) or ($num_displayed = $total_files)):
			$html .= "</div>\n\r"; 
			$num_displayed = 0;
			break;	
		}
	}
	
	return $html;
}

?>



<?php

ob_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Tango Icon Theme 0.8.90</title>
</head>

<style>
	.icon-sample { width:120px; border:1px; border-color:#000000; float:left; padding:3px; text-align:center; }
	.icon-sample img { padding-right:2px; }
	.spacing { margin-top:50px; }
</style>

<body>
	
	<h1>Thumbnail Gallery - <I>Organized by Leonardo Martinez</I></h1>
	<p>This thumbnail gallery is provided to you for personal use.  It was compiled for use with an API/Framework plugin I created called, "Simple Wordpress Framwork".  It contains both 16 x 16 and 32 x 32 pixel images @ 32 bit colors. 
	You can download a zip file with the complete collection, including the index.html, and php file that generates the webpage by clicking on the link below.  I will try to add more icons every month.</p>
	<p><a href="<?php echo current_url(); ?>icons-16-32x32bits.zip">Download</a></p>
	
	<h1><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Icon Theme 0.8.90</a></h1>
	<p>These icons were obtained by freedesktop.org an opensource solution for icons. <br /></p>
	
	
	<h3><a href="http://tango.freedesktop.org/Tango_Desktop_Project">http://tango.freedesktop.org/Tango_Desktop_Project</a></h3>
	
	<h2>32, 16 Pixels (16bit Black/White)</h2>
	
	<!-- greyscale and color icons start -->
	<?php 
		echo( create_icon_html( array('icons-tango-grey', 'icons-tango-color'), 4 ) );
	?>
	<div class="spacing"><BR></div>
	<!-- greyscale and color icons end -->
	
	<p>&nbsp;</p>
	<h1>Copyrighted: Corporate Logos, and System Icons</h1>
	<p>Most icons come from Microsoft, Adobe, OpenSource Projects, and many more. They have been organized in groups according to their specifications.</p>
	<h3>32, 16 Pixels</h3>

	<!-- greyscale and color icons start -->
	<?php 
		echo( create_icon_html( array('icons-other-grey', 'icons-other-color'), 4 ) );
	?>
	<div class="spacing"><BR></div>
	<!-- greyscale and color icons end -->

</body>
</html>




<?php

$page = ob_get_contents();
ob_end_flush();
$fp = fopen( getcwd() . '/' . str_replace( '.php', '', current_filename() ) . ".html","w");  
fwrite($fp,$page);
fclose($fp);

?>
