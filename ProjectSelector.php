<?php
/**
 * this class represents a project selector to which we can add projects, add versions to the projects
 * it is in charge to generate the front end HTML to display which project and version is selected for a given post
 * it is in charge of generating a form of the admin page
 * it is in charge of generating a form for the "edit post"/"edit page" admin page
 * it is in charge of loading/saving the config from wordpress
 */

class ProjectSelector
{

	/**
	 * content of the project array
	 */
	var $projectArray;

	/**
	 * content of the version array
	 * each element is a String of versions, coma separated, e.g. $versionArray[1] could be "v1.1,v1.2,v1.3"
	 * each element corresponds to the element of $projectArray at the same indice
	 */
	var $versionArray;
	
	/**
	 * load config from wordpress config
	 * fill up the projects and versions arrays
	 */
	function loadConfig() {
		// get the list of projects
		$this->projectArray = get_option("projectArray");
		if ($this->projectArray == '')
			$this->projectArray = Array();

		// get the list of versions
		$this->versionArray = get_option("versionArray");
		if ($this->versionArray == '')
			$this->versionArray = Array();
	}
	
	/**
	 * saves in WP options the projects and versions arrays
	 */
	function saveConfig() {
		update_option("projectArray",$this->projectArray);
		update_option("versionArray",$this->versionArray);
	}
	/**
	 * write in the post meta the selected project for a given post ID
	 */
	function setSelectedProject($selectedProject,$post_ID) {

		update_post_meta($post_ID, 'selectedProject', $selectedProject);

	}
	/**
	 * write in the post meta and returns the selected version for a given post ID
	 */
	function setSelectedVersion($selectedVersion,$post_ID) {
		update_post_meta($post_ID, 'selectedVersion', $selectedVersion);
	}
	/**
	 * look in the post meta and returns the selected project for a given post ID
	 */
	function getSelectedProject($post_ID) {
		$result = get_post_meta($post_ID,'selectedProject');
		return $result[0];
	}
	/**
	 * look in the post meta and returns the selected version for a given post ID
	 */
	function getSelectedVersion($post_ID) {
		$result = get_post_meta($post_ID,'selectedVersion');
		return $result[0];
	}
}
?>