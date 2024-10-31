<?php

include_once("ProjectSelector.php");

function projectSelectorAddImage($title) 
{
	$ps = new ProjectSelector();
	$image = $ps->getSelectedProject(get_the_ID());
	$version = $ps->getSelectedVersion(get_the_ID());
	
	return $title.$image.$version;
}
/**
 * add_filter('the_title', projectSelectorAddImage);
 *
 * for now use <?php echo get_post_meta($post->ID, "selectedProject", true); ?> in your theme to display the selected project 
 * and or <?php echo get_post_meta($post->ID, "selectedVersion", true); ?> to display the selected version
 *
 */
?>