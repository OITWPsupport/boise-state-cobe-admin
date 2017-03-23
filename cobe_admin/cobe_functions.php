<?php
/*
 * Functions for COBE Admin plugin
 * 
 */


function cobe_admin() {
  $pageLink = "admin.php?page=";

  echo "<h2>COBE Administration Pages</h2>";
  echo "<a href=\"" . $pageLink . "personnel\">Personnel</a><br>";
  echo "<a href=\"" . $pageLink . "courses\">Courses</a><br>";
  echo "<a href=\"" . $pageLink . "semesters\">Semesters<br></a>";
  echo "<a href=\"" . $pageLink . "committee\">Committee<br></a>";
  echo "<a href=\"" . $pageLink . "accr\">Accreditation<br></a>";

}

function cobe_faculty() {
  global $wpdb;
  $row = cobeGetFacultyUserInfo(get_bloginfo('name'));
  $pageLink = "admin.php?page=";
  $menuLink = get_bloginfo('url') . "/wp-admin/";
  
  if ($row) {
    echo "<h2>COBE Faculty Pages for " . $row->firstName ." ". $row->lastName . "</h2>";
    echo "<a href=\"" . $pageLink . "personnel\">Edit Personnel Profile</a> such as office hours, awards, publications.<br>";
    echo "<a href=\"" . $menuLink . "edit.php\">Edit Posts</a> on News Page.<br>";
    echo "<a href=\"" . $menuLink . "edit.php?post_type=page\">Edit Pages</a> that show up on your profile.<br>";
  } else {
  	echo "You must be on a faculty page in order to edit their pages.<br>";
  	echo "The name of the site page you are currently on is: " . $name;
  }
}

function cobe_add_menu() {
	global $blog_id;
	$objCurrUser = wp_get_current_user();
	$objUser = wp_cache_get($objCurrUser->id, 'users');
	
	if (cobeGetFacultyUserInfo(get_bloginfo('name'))) {
		if (function_exists('add_menu_page') && is_admin($objUser->user_login)) {
			add_menu_page('COBE Faculty', 'COBE Faculty', 8, 'cobe-faculty','cobe_faculty' );
		}
		if (function_exists('add_submenu_page') && is_admin($objUser->user_login)) {
			add_submenu_page('cobe-faculty','Edit Personnel Profile', 'Edit Personnel Profile', 8, 'personnel', 'cobe_personnel'  );
		}
	}
	else {
		if (function_exists('add_menu_page') && is_admin($objUser->user_login)) {
			add_menu_page('COBE Admin', 'COBE Admin', 8, __FILE__,'cobe_admin' );
		}
		if (function_exists('add_submenu_page') && is_admin($objUser->user_login)) {
			add_submenu_page(__FILE__,'Personnel', 'Personnel', 8, 'personnel', 'cobe_personnel'  );
		}
		if (function_exists('add_submenu_page') && is_admin($objUser->user_login)) {
			add_submenu_page(__FILE__,'Courses', 'Courses', 8, 'courses', 'cobe_courses'  );
		}
		if (function_exists('add_submenu_page') && is_admin($objUser->user_login)) {
			add_submenu_page(__FILE__,'Semesters', 'Semesters', 8, 'semesters', 'cobe_semesters'  );
		}
		if (function_exists('add_submenu_page') && is_admin($objUser->user_login)) {
			add_submenu_page(__FILE__,'Committees', 'Committees', 8, 'committee', 'cobe_committee'  );
		}
		if (function_exists('add_submenu_page') && is_admin($objUser->user_login)) {
			add_submenu_page(__FILE__,'Accreditation', 'Accreditation', 8, 'upload_publications', 'cobe_uploadp'  );
		}
	}	
}

/**
 * Escape an array of data
 * By Martin Ronquillo, BSU OIT/EAS Student Web Developer
 * March 2012
 * @param $post: The POST array to escape
 * @return $data: The escaped data
 */
function cobeEscapePost($post) {
	$data = array();
	
	foreach ($post as $key => $item) {
		// IF an array, recursively escape
		// ELSE escape the value
		if (is_array($item))
			$data[$key] = cobeEscapePost($item);
		else
			$data[$key] = mysql_real_escape_string($item);
	}
	
	return $data;
}

/**
 * Upload files using a single function to perform the grunt work
 * By Martin Ronquillo, BSU OIT/EAS Student Web Developer
 * March 2012
 * @param $file: The $_FILES array with the upload data
 * @param $uploadDir: The target directory (e.g. /some/random/directory). No trailing slash '/' necessary
 * @param $fileName (optional, default: uploaded file name): Specify an explicit name for the uploaded file
 * @param $uploadElement (optional, default: first element): If form contains more than one 'file' element, select the one to use
 * @param $fileTypes (optional, default: doc|docx|gif|jpg|pdf|png|rtf|txt|xls|xlsx): Specify what file types to allow
 * @return A message on error, or true on success
 */
function cobeUpload($files, $uploadDir, $fileName = '', $uploadElement = '', $fileTypes = array()) {
	$fileElement = '';
	$types = array(
		'doc',
		'docx',
		'gif',
		'jpg',
		'pdf',
		'png',
		'rtf',
		'txt',
		'xls',
		'xlsx');
	$name = '';
	
	// If no file element is provided, use the first array in the $_FILES array
	if (empty($uploadElement)) {
		$elements = array_keys($files);
		$fileElement = $elements[0];
	}
	elseif (array_key_exists($uploadElement, $files))
		$fileElement = $uploadElement;
	else
		return 'Error: file data not provided.';
		
	// No file uploaded, break out
	if ($files[$fileElement]['error'] == 4)
		return 'No file uploaded.';
		
	// Set the file name
	$name = (!empty($fileName)) ? $name = $fileName : $files[$fileElement]['name'];
	
	// If provided with an array of file types to accept, override the default
	if (!empty($fileTypes) && is_array($fileTypes))
		$types = $fileTypes;
	
	$fileType = substr($name, strrpos($name, '.') + 1);
	
	// If file type is allowable, attempt to process the file
	if (in_array($fileType, $types)) {		
		if ($files[$fileElement]['error'])
			return 'Upload not successful due to file error.';
		elseif ($files[$fileElement]['name'] != '') {
			if (move_uploaded_file($files[$fileElement]['tmp_name'], $uploadDir . '/' . $name))
				return true;
			else
				return 'Error: file could not be saved.';
		}
	}
	else
		return 'Error: file type cannot be uploaded for security reasons.';
}

/**
 * Display the WordPress message
 * By Martin Ronquillo, BSU OIT/EAS Student Web Developer
 * March 2012
 * @param $message: The message to display
 * @param $type (optional, default 'updated'): The type of message to display (updated|error)
 * @return An HTML string with the message to display
 */
function cobeWPMessage($message, $type = 'updated') {
	if ($type == 'updated' || $type == 'error')
		return '<div class="' . $type . '"><p>' . $message . '</p></div>';
}

/**
 * Wrapper function to retrieve user info for faculty, based on the current site name.
 * Used for faculty subsites.
 * @param $siteName: The name of the site to check
 * @return $row | false: Return an object with the faculty info or false on failure
 */
function cobeGetFacultyUserInfo($siteName) {
	$rows = getFaculty2($siteName); 
	
	if ($rows)
		return $rows[0];
	else
		return false;
}

function getFaculty2($facultyName) {
	global $wpdb;
	preg_match_all('/^(.+?) (.+)$/',$facultyName,$results);
	$facID = $wpdb->escape(getFacultyID2($results));
	
	$query  = "Select wp_personnel.*";
	$query .= " From wp_personnel ";
	$query .= "Where wp_personnel.employeeID ='".$facID."'";

	$facresults = $wpdb->get_results($query);
	
	if (!empty($facresults))
		return $facresults;
	else
		return false;
}

function getFacultyID2($results) {
	global $wpdb;
	$first = $wpdb->escape($results[1][0]);
	$last = $wpdb->escape($results[2][0]);

	$query = "SELECT wp_personnel.employeeID FROM wp_personnel WHERE wp_personnel.firstName='{$first}' AND wp_personnel.lastName='{$last}'";
	$facReturn = $wpdb->get_results($query);
	$facID = $facReturn[0]->employeeID;
	
	if (!empty($facID))
		return $facID;
	else
		return 0;
}