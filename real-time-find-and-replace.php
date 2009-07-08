<?php
/*
Plugin Name: Real-Time Find and Replace
Version: 1.0.2
Plugin URI: http://www.mariosalexandrou.com/wordpress-real-time-find-and-replace.asp
Description: Set up find and replace rules that are executed AFTER a page is generated by WordPress, but BEFORE it is sent to a user's browser.
Author: Marios Alexandrou
Author URI: http://www.mariosalexandrou.com/
*/

/*
* Admin Page
*/

add_action('admin_menu', 'far_add_pages');

function far_add_pages() { // Add a  submenu under Tools
	$page = add_submenu_page( 'tools.php', 'Real-Time Search and Replace', 'Real-Time Search and Replace', 'activate_plugins', 'real-time-find-and-replace', 'far_options_page');
	add_action( "admin_print_scripts-$page", 'far_admin_scripts' );
}
function far_options_page(){
	if (isset($_POST['setup-update'])) {
		$_POST = stripslashes_deep($_POST);
		if (is_array($_POST['farfind'])){ // If atleast one find has been submitted
			foreach ($_POST['farfind'] as $key => $find){
				if (empty($find)){ // if empty ones have been submitted we get rid of the extra data submitted if any.
					unset($_POST['farfind'][$key]);
					unset($_POST['farregex'][$key]);
					unset($_POST['farreplace'][$key]);
					}
				if (!isset($_POST['farregex'][$key])) // convert line feeds on non-regex only
					$_POST['farfind'][$key] = str_replace("\r\n", "\n", $find);
			}
		}
		unset($_POST['setup-update']);
		
		if(empty($_POST['farfind']))
			delete_option('far_plugin_settings'); // delete the option if there are no settings.   Keeps the database clean if they aren't using it and uninstall
		else
			update_option('far_plugin_settings', $_POST);
		
		?><div id="message" class="updated fade">
			<p><strong>Options Updated</strong></p>
		</div><?php
	} ?>
	<div class="wrap" style="padding-bottom:5em">
	<h2>Real-Time Search and Replace</h2>
	<p>Enter your find and replace cases below.</p>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<?php
	$farsettings = get_option('far_plugin_settings');
	if (is_array($farsettings['farfind'])){  //if there are any finds already set
		$i=1;
		foreach ($farsettings['farfind'] as $key => $find){
			if(isset($farsettings['farregex'][$key]))
				$regex = 'CHECKED';
			$replace = $farsettings['farreplace'][$key];
			echo "<p id='row$i'><label for='farfind$i'>Find:&nbsp;</label><textarea rows='3' cols='30' name='farfind[$i]' id='farfind$i'>$find</textarea>&nbsp;&nbsp;<label for='farregex$i'>RegEx?:&nbsp;</label><input type='checkbox' name='farregex[$i]' id='farregex$i' $regex />&nbsp;&nbsp;<label for='farreplace$i'>Replace:&nbsp;</label><textarea rows='3' cols='30' name='farreplace[$i]' id='farreplace$i'>$replace</textarea>&nbsp;&nbsp&nbsp;<a href='#' onClick='removeFormField(\"#row$i\"); return false;'>Remove</a></p>\n"; // this is identical to what the js returns when adding new items
			unset($regex);
			$i++;
		}
	} else {
		echo 'Click "Add" below to begin.';
	}
	?>
		<div id="divTxt"></div>
		<p><a href="#" onClick="addFormField(); return false;">Add</a></p>
		<input type="hidden" id="id" value="<?php echo $i; /*used so javascript returns unique ids*/ ?>" />
		<input type="hidden" name="setup-update" />
		<p><input type="submit" class="button" value="Update Settings" /></p>
	</form>
	</div>
	<?php 
}
function far_admin_scripts(){ // these scripts print on the admin page
	wp_enqueue_script('far_dynamicfields', plugins_url() . '/real-time-find-and-replace/js/jquery.dynamicfields.js', array('jquery'));
	wp_enqueue_script('highlightFade', plugins_url() . '/real-time-find-and-replace/js/jquery.highlightFade.js', array('jquery'));
}

/*
* Core Functionality
*/
function far_ob_call($buffer){ // $buffer contains entire page
	$farsettings = get_option('far_plugin_settings');
	if (is_array($farsettings['farfind'])){
		foreach ($farsettings['farfind'] as $key => $find){
			if(isset($farsettings['farregex'][$key]))
				$buffer = preg_replace($find, $farsettings['farreplace'][$key], $buffer);
			else
				$buffer = str_replace($find, $farsettings['farreplace'][$key], $buffer);
		}
	}
	return $buffer;
}

function far_template_redirect(){
	ob_start();
	ob_start('far_ob_call');
}
add_action('template_redirect', 'far_template_redirect');

?>