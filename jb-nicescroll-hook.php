<?php
/*
Plugin Name: JB Nice Scroll
Plugin URI:http://jobairbd.com
Description: this is nicescroll plugin for wordpress theme.
Author:jobair
Version:1.0
Author URI:http://jobairbd.com
*/
function jb_nicescroll_jquery_call(){
	wp_enqueue_script('jquery');
	
}
add_action('init','jb_nicescroll_jquery_call');

/*Some Set-up*/
define('JB_NICESCROLL_PLUGIN_WP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

function jb_nice_scroll_js_css_file(){
	wp_enqueue_script('jb-nicescroll-js', JB_NICESCROLL_PLUGIN_WP . '/js/jquery.nicescroll.min.js',array('jquery'));
	
}
add_action('wp_enqueue_scripts','jb_nice_scroll_js_css_file');


function nice_scroll_active_js(){?>
<?php global $jbnicescroll_options; $jbnicescroll_settings = get_option( 'jbnicescroll_options', $jbnicescroll_options ); ?>
<script type="text/javascript">
	jQuery(document).ready(
	  function() { 
		jQuery("html").niceScroll({
			cursorcolor: '<?php echo $jbnicescroll_settings['cursor_color']; ?>',
			cursorwidth: '<?php echo $jbnicescroll_settings['cursor_width']; ?>',
			cursorborderradius: '<?php echo $jbnicescroll_settings['cursor_border_radius']; ?>',
			scrollspeed: '<?php echo $jbnicescroll_settings['scrool_speed']; ?>',
			cursorborder :false,
			autohidemode:false
		});
	  }
	);
</script>
<?php
}
add_action('wp_head','nice_scroll_active_js');
function add_jb_nicescroll_options_framwrork()  
{  
	add_menu_page('JB Scrollbar Options', 'JB Scrollbar Options','manage_options', 'nicescrollbar-settings', 'jbnicescroll_options_framwrork');
	//add_options_page	
}  
add_action('admin_menu', 'add_jb_nicescroll_options_framwrork');
// color picker js
	
		function jb_add_color_picker( $hook_suffix ) {
		
		define('DEWDROP_SCROLLBAR_WP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
		// Add the color picker css file      
		wp_enqueue_script( 'wp-color-picker' );
		// load the minified version of custom script
		wp_enqueue_script( 'my-color-field', plugins_url( 'js/color-picker.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), false, true );
		wp_enqueue_style( 'wp-color-picker' );
    
}
add_action( 'admin_enqueue_scripts', 'jb_add_color_picker' );
// Default options values
$jbnicescroll_options = array(
	'cursor_color' => '#1bf7f7',
	'cursor_width' => '5px',
	'cursor_border_radius' =>'0px',
	'scrool_speed' =>  '60'
);
if ( is_admin() ) : // Load only if we are viewing an admin page

function jb_nicescroll_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'jbnicescroll_p_options', 'jbnicescroll_options', 'jbnicescroll_validate_options' );
}

add_action( 'admin_init', 'jb_nicescroll_register_settings' );


// Function to generate options page
function jbnicescroll_options_framwrork() {
	global $jbnicescroll_options;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

	<div class="wrap">

	
	<h2>Custom Scrollbar Options</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'jbnicescroll_options', $jbnicescroll_options ); ?>
	
	<?php settings_fields( 'jbnicescroll_p_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>

	
	<table class="form-table"><!-- Grab a hot cup of coffee, yes we're using tables! -->
	
	
		<tr valign="top">
			<th scope="row"><label for="cursor_color">Scroll Bar Color</label></th>
			<td>				
				<input id="cursor_color" type="text" name="jbnicescroll_options[cursor_color]" 
				value="<?php echo stripslashes($settings['cursor_color']); ?>" class="color-field" />
				<p class="description">Select Scroll Bar Color here.</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="cursor_width">Scroll Bar Width</label></th>
			<td>				
				<input id="cursor_width" type="text" name="jbnicescroll_options[cursor_width]" 
				value="<?php echo stripslashes($settings['cursor_width']); ?>" class="my-color-field" />
				<p class="description">Select Scroll Bar Width here.Like 5px</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="cursor_border_radius">Scroll Bar Border Radius</label></th>
			<td>				
				<input id="cursor_border_radius" type="text" name="jbnicescroll_options[cursor_border_radius]" 
				value="<?php echo stripslashes($settings['cursor_border_radius']); ?>" class="my-color-field"/>
				<p class="description">Select Scroll Bar Border Radius here. You can also add html HEX color code.</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="scrool_speed">Scroll Bar Speed</label></th>
			<td>				
				<input id="scrool_speed" type="text" name="jbnicescroll_options[scrool_speed]" 
				value="<?php echo stripslashes($settings['scrool_speed']); ?>" class="my-color-field" />
				<p class="description">SelectScroll Bar Speed here. You can also add 60,50,80</p>
			</td>
		</tr>

	</table>

	<tr>
			
			<td colspan="2"><input type="submit" class="button-primary" value="Save Settings" /></td>
		</tr>

	</form>

	</div>

	<?php
}
function jbnicescroll_validate_options( $input ) {
	global $jbnicescroll_options;

	$settings = get_option( 'jbnicescroll_options', $jbnicescroll_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS
	$input['cursor_color'] = wp_filter_post_kses( $input['cursor_color'] );
	$input['cursor_width'] = wp_filter_post_kses( $input['cursor_width'] );
	$input['cursor_border_radius'] = wp_filter_post_kses( $input['cursor_border_radius'] );
	$input['scrool_speed'] = wp_filter_post_kses( $input['scrool_speed'] );
	return $input;
}
endif;  // EndIf is_admin()
?>