<?php
/*
Plugin Name: COBE Admin
Description: A custom plugin for the Boise State COBE site
Version: 1.5.0
Author: Alan Bonde & OIT/EAS WP-Support Team
Author URI: http://oit.boisestate.edu/

Copyright 2009, 2012 Boise State University

This plugin is for BSU use only.
    
*/

// Site URL
define('COBEURL', get_bloginfo('url'));

// The current directory
define('COBEPATH', dirname(__FILE__));

require_once(COBEPATH . "/cobe_functions.php");
require_once(COBEPATH . "/personnel/personnel_edit.php");
require_once(COBEPATH . "/courses/cobe_courses.php");
require_once(COBEPATH . "/semesters/cobe_semesters.php");
require_once(COBEPATH . "/accr/cobe_accr.php");
require_once(COBEPATH . "/upload_publications/index.php");
require_once(COBEPATH . "/committee/index.php");

add_action('admin_menu', 'cobe_add_menu');


?>