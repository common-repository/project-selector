<?php
/**
 * Plugin Name: Project Selector
 * Plugin URI: http://exchange.silexlabs.org/
 * Description: Add project compatibility and versions to posts or pages
 * Version: 1.0
 * Author: Alexandre Hoyau & Camille Gérard-Hirne
 * Author URI: http://silexlabs.org
 * License: GPL2
*/

/**
 * @package 	project_selector
 * @version		1.0
 * @author      Alexandre Hoyau : he did it
 * @author      Camille Gérard-Hirne : he learned a lot trying to do it thanks to Alex
 * @author      Silex team
 */ 


// display the edit settings screen
include_once("ProjectSelectorAdmin.php");

// display the selected project/versions on your blog
include_once("ProjectSelectorFront.php");

// display the edit posts and pages boxes
include_once("ProjectSelectorEdit.php");


?>