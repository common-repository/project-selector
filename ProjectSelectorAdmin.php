<?php

include_once("ProjectSelector.php");

/**
 * display a project details
 * @attribute
 */
function the_projects()
{
	global $projectSelector;

	for($idx=0;$idx<count($projectSelector->projectArray);$idx++) {
?>
<br />
		<table class="widefat">
			<thead>
			<tr>
				<th WIDTH="50%">
					<form method="post" action="options-general.php?page=project-selector/ProjectSelectorAdmin.php">
						<input type="hidden" name="psDeleteProject" value="<?php echo $idx; ?>" />			
						<?php echo $projectSelector->projectArray[$idx]; ?><input type="submit" class="button-primary" value="<?php _e('Delete'); ?>" />
					</form>
				</th>
				<th WIDTH="50%">
					<form method="post" action="options-general.php?page=project-selector/ProjectSelectorAdmin.php">
						<input type="hidden" name="psAddVersionIdx" value="<?php echo $idx; ?>" />			
						Add a version <input type="text" name="psAddVersion" value="" />
					<input type="submit" class="button-primary" value="<?php _e('Add'); ?>" />
					</form>
				</th>
			</tr>
			</thead>
			<tbody>
				<?php the_versions($idx, $projectSelector->versionArray[$idx]); ?>
			</tbody>
		</table>
<?php
	}
}

/**
 * display a project versions
 * @attribute	$idx	index in the versions array
 * @attribute	
 */
function the_versions($idxProject, $versionsString)
{
	if (!$versionsString)
		return;
		
	$versions = preg_split('/,/',$versionsString);
	
	// display the versions and a remove button
	for($idxVersion=0;$idxVersion<count($versions);$idxVersion++) {
	?>
	<tr>
		<form method="post" action="options-general.php?page=project-selector/ProjectSelectorAdmin.php">
			<input type="hidden" name="psDeleteVersion" value="<?php echo $idxProject; ?>-<?php echo $idxVersion; ?>">
			<td><?php echo $versions[$idxVersion]; ?></td>
			<td><input type="submit" class="button-secondary" value="<?php _e('Delete'); ?>" /></td>
		</form>
	</tr>
	<?php
	}
}

/**
 * add the project selector options to the admin panel
 */ 
function projectSelectorAdmin() {
	//create new top-level menu
	add_options_page('Project Selector options', 'Project Selector', 'manage_options', __FILE__, projectSelectorAdminHtml);
}

/**
 * display the project selector options
 */ 
function projectSelectorAdminHtml() {
	?>
		<div id="icon-tools" class="icon32"></div>
		<div class="wrap">
			<h2>Project Selector Settings</h2>
		<form method="post" action="options-general.php?page=project-selector/ProjectSelectorAdmin.php">
			<?php settings_fields('projectSelectorAdminHtml'); ?>
			<p>Add a project <input type="text" name="psAddProject" value="" /><input type="submit" class="button-primary" value="<?php _e('Add') ?>" /></p>
			<hr />
		</form>
	<?php
	// display the projects and versions
	the_projects();
}

/**
 * remove / add projects
 */
function doProjectManagementActions()
{
	global $projectSelector;

	// remove a project
	if (isset($_POST['psDeleteProject']) && $_POST['psDeleteProject']!='')
	{
		$idxProject = $_POST['psDeleteProject'];
		// remove project
		array_splice($projectSelector->projectArray, $idxProject, 1);
		// remove versions
		array_splice($projectSelector->versionArray, $idxProject, 1);
		// save the 2 arrays
		$projectSelector->saveConfig();
	}

	// add a project
	if (isset($_POST['psAddProject']) && $_POST['psAddProject']!='')
	{
		array_push($projectSelector->projectArray, $_POST['psAddProject']);
		$projectSelector->saveConfig();
	}
}

/**
 * remove / add versions
 */
function doVersionManagementActions()
{
	global $projectSelector;
	
	// add a version
	if (isset($_POST['psAddVersion']) && $_POST['psAddVersion']!='')
	{
		// if needed, add a ','
		if ($projectSelector->versionArray[$_POST['psAddVersionIdx']]!='')
			$projectSelector->versionArray[$_POST['psAddVersionIdx']] .= ',';
		// add the version to the list
		$projectSelector->versionArray[$_POST['psAddVersionIdx']] .= $_POST['psAddVersion'];
		// save config
		$projectSelector->saveConfig();
	}

	// remove a version
	if (isset($_POST['psDeleteVersion']) && $_POST['psDeleteVersion']!='')
	{
		// index array, 
		// $idxArray[0] = 1st element = project index, 
		// $idxArray[1] = 2d element = version index
		$idxArray = preg_split('/-/',$_POST['psDeleteVersion']);
		// retrieve index of the project and the version we want to delete
		$idxProject = $idxArray[0];
		$idxVersion = $idxArray[1];
		// make an array of versions
		$versionSplitedArray = preg_split('/,/',$projectSelector->versionArray[$idxProject]);
		// delete the desired version
		array_splice($versionSplitedArray, $idxVersion, 1);
		// store the result in the PS
		$projectSelector->versionArray[$idxProject] = implode(',',$versionSplitedArray);
		// save in WP DB
		$projectSelector->saveConfig();
	}
}

// creation of the ProjectSelector instance
global $projectSelector;
$projectSelector = new ProjectSelector(); 

// load config from wordpress config
$projectSelector->loadConfig();


doProjectManagementActions();
doVersionManagementActions();


// the admin menu box settings
add_action('admin_menu', projectSelectorAdmin);

?>