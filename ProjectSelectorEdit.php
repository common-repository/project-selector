<?php

include_once("ProjectSelector.php");

/* Prints the box content */
function projectSelectorEditHtml() {
	
	global $post;

	$p = new ProjectSelector(); // creation of the ProjectSelector instance
	$p->loadConfig();// load config from wordpress config
	// retrieve the selected version and selected project for the current post/page
	$selectedProject = $p->getSelectedProject($post->ID); 
	$selectedVersion = $p->getSelectedVersion($post->ID);
	// build the HTML for the project selection drop down list
	
	  // Use of the nonce for verification
	wp_nonce_field( plugin_basename(__FILE__), 'projectselector' );
	?>

	<select id="projects_dropdown" name="projects_dropdown" onchange="changeVersions(this.selectedIndex)">
		<OPTION VALUE="" >Project : </OPTION>
	<?php
		$selectedProjectIndex = -1;
		// default value is the selected project
		// elements of the drop down list
		for($idx=0;$idx<count($p->projectArray);$idx++) {
			echo '<OPTION VALUE="'.$p->projectArray[$idx].'"';
			if ($p->projectArray[$idx] == $selectedProject)
			{
				$selectedProjectIndex = $idx;
				echo ' selected ';
			}
			echo '>' . $p->projectArray[$idx] . '</OPTION>';
		}
	?>
	</select>
	
	<script type="text/javascript"> 
	//<![CDATA[
		function changeVersions($selectedIndex) 
		{ 
			//alert($selectedIndex);
			if ($selectedIndex==0)
				changeVersionComboOptions([]);
			else
			{
				changeVersionComboOptions($versionArray[$selectedIndex-1]);
			}
		} 
		function changeVersionComboOptions($optionsArray)
		{
			$combo = document.getElementById("versions_dropdown");
			$combo.innerHTML = '';
			for ($idx = 0; $idx<$optionsArray.length; $idx++)
			{
				//alert($idx + " - "+$combo);
				var opt = document.createElement("option");
				opt.text = $optionsArray[$idx];
				opt.value = $optionsArray[$idx];
				$combo.options.add(opt,null);
			}
				var opt = document.createElement("option");
				opt.value = opt.text = "Version :";
				$combo.options.add(opt,null);
		}
	
		$versionArray = [<?php
		// echo the versions into a javascript variable
		for($idx=0;$idx<count($p->versionArray);$idx++) {
			$versionsForAProject = str_replace(',','","',$p->versionArray[$idx]);
			echo '["'.$versionsForAProject.'"]';
			if ($idx != count($p->versionArray)-1) echo ',';
		}
		// build the HTML for the version selection drop down list
		?>];
	
	//]]>
	</script> 
	
	<select id="versions_dropdown" name="versions_dropdown">
		<OPTION VALUE="">Version : </OPTION>
	<?php
		$versionArrayForSelectedProject = Array();
		if ($selectedProjectIndex>=0)
			$versionArrayForSelectedProject = explode(',',$p->versionArray[$selectedProjectIndex]);
		// elements of the drop down list
		for($idx=0;$idx<count($versionArrayForSelectedProject);$idx++) {
			echo '<OPTION VALUE="'.$versionArrayForSelectedProject[$idx].'"';
			if ($versionArrayForSelectedProject[$idx] == $selectedVersion)
				echo ' selected ';
			echo '>' . $versionArrayForSelectedProject[$idx] . '</OPTION>';
		}
	?>
	</select>

	<?php
}

/*
function ProjectSelectorDisplayError ($str) {
	echo '<div class="error below-h2">You should fill the project selector field - '.$str.'</div>';
}
*/

/**
 * When the post is saved, saves our custom data
 */
function projectSelectorSavePost($post_ID)
{
	// verfification of the nonce because we're worthing it
	if ( !wp_verify_nonce( $_POST['projectselector'], plugin_basename(__FILE__) )) {
		return $post_ID;
	}
	
	// when auto saving the options were deleted so we do that to fix it :
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_ID;
  
	// Check permissions
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_ID ) )
		return $post_ID;
		
		} else {
	if ( !current_user_can( 'edit_post', $post_ID ) )
		return $post_ID;
				}
	
	// if there is no selection, display an error
/*	
	if ($_POST['projects_dropdown'] == "" || $_POST['versions_dropdown'] == "")
	{
		// display a message
		ProjectSelectorDisplayError();
		// do nothing more
		return $post_ID;
	}
*/

	// store the selections
	$mydata_project = $_POST['projects_dropdown'];
	$mydata_version = $_POST['versions_dropdown'];

	$p = new projectSelector(); // creation of the ProjectSelector instance
	$p->setSelectedProject($mydata_project,$post_ID);
	$p->setSelectedVersion($mydata_version,$post_ID);

	return $post_ID;
}

/* Define the custom box */
add_action('add_meta_boxes', 'projectSelectorEditBox');

// add the selection box to the edit posts and pages
function projectSelectorEditBox() {
	add_meta_box('projectSelector', 'Project Selector', 'projectSelectorEditHtml', 'post', 'side', 'high'); 
	add_meta_box('projectSelector', 'Project Selector', 'projectSelectorEditHtml', 'page', 'side', 'high');
}

/* Do something with the data entered */
add_action('save_post', projectSelectorSavePost);
//projectSelectorSavePost();

?>