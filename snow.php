<?php
/*
 * Plugin Name: Snow
 * Plugin URI: https://wordpress.org/plugins/snow/
 * Description: Professional snow plugin with highly customizable options, no coding knowledge required.
 * Version: 2.0.2
 * Author: Mitch
 * Author URI: https://profiles.wordpress.org/lowest
 * License: GPL-2.0+
 * Text Domain: snow
 * Domain Path:
 * Network:
 * License: GPL-2.0+
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! defined( 'SNW_FILE' ) ) { define( 'SNW_FILE', __FILE__ ); }

if ( ! defined( 'SNW_VERSION' ) ) { define( 'SNW_VERSION', '2.0.2' ); }

/* Set all needed vars */
$snow = get_option('snow');
$snowadvanced = get_option('snowadvanced');
$snowtechnical = get_option('snowtechnical');
$snowdata['version'] = SNW_VERSION;
$snowdata['wp_version'] = get_bloginfo('version');
$snowdata['wp_debug'] = WP_DEBUG;

/* Include all functions */
include_once( 'inc/func.php' );

add_action( 'admin_init', function() {
	register_setting( 'snow_group', 'snow' );
	register_setting( 'snowadvanced_group', 'snowadvanced' );
	register_setting( 'snowtechnical_group', 'snowtechnical' );
});

add_action( 'admin_menu', function() {
	add_menu_page( 'Snow', 'Snow', 'manage_options', 'snow', 'snow_page', 'dashicons-snow', 100  );
	add_submenu_page( 'snow', 'General', 'General', 'manage_options', 'snow', 'snow_page');
	add_submenu_page( 'snow', 'Advanced', 'Advanced', 'manage_options', 'snow_advanced', 'snow_page_advanced');
	add_submenu_page( 'snow', 'Technical', 'Technical', 'manage_options', 'snow_technical', 'snow_page_technical');
});

function snow_page() {
	if ( !is_snow_admin() )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} elseif (isset($_GET['reset_nonce']) && wp_verify_nonce($_GET['reset_nonce'], 'reset')) {
		$delete_options = array('snow','snowadvanced','snowtechnical');
		foreach ( $delete_options as $option ) {
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
		}
		?>
<div class="wrap">
	<h1><?php _e('Snow has been reset'); ?></h1>
	<p><?php _e('Snow has been reset successfully.'); ?></p>
	<a href="<?php echo admin_url('admin.php?page=snow'); ?>" class="button"><?php _e('OK'); ?></a>
</div>
		<?php
	} else {
		include_once( 'inc/header.php' );
		?>
	<form method="post" action="options.php">
		<?php settings_fields( 'snow_group' ); ?>
		<table class="form-table">
			<tr id="snow_flakecount">
				<th scope="row"><label for="snow[flakecount]"><?php _e( 'Flake Count' ); ?></label></th>
				<td>
					<input id="snow[flakecount]" name="snow[flakecount]" type="number" value="<?php if(empty($snow['flakecount'])) { echo '35'; } else { echo $snow['flakecount']; } ?>" /> <?php _e('flakes'); ?>
					<div class="hidden-notice" id="count-max"><strong><?php _e('Notice'); ?></strong>: <?php _e('We do not recommend having more than 300 snowflakes because this might slow down devices.'); ?></div>
				</td>
			</tr>
			<tr id="snow_image">
				<th scope="row"><label for="image-url"><?php _e( 'Flake Image' ); ?></label><span class="helpicon" title="<?php _e('This option allows you to set your own snowflake. Your custom snowflakes will replace the default ones. You can also use third party URLs.'); ?>"></span></th>
				<td><input id="image-url" name="snow[image]" type="text" placeholder="http://" value="<?php if(!empty($snow['image'])) { echo $snow['image']; } ?>" /> <input type="button" class="button" id="clear" value="<?php _e('Clear'); ?>"<?php if(empty($snow['image'])) { ?>style="display:none"<?php } ?> /> <input id="upload-button" type="button" class="button" value="<?php _e('Upload Image'); ?>" /></td>
			</tr>
			<tr<?php if(!empty($snow['image'])) { echo ' class="item-disabled"'; } ?> id="snow_flakecolor">
				<th scope="row"><label for="snow[flakecolor]"><?php _e( 'Flake Color' ); ?></label></th>
				<td><input id="snow[flakecolor]" name="snow[flakecolor]" class="color-field" type="text" value="<?php if(empty($snow['flakecolor'])) { echo '#ffffff'; } else { echo $snow['flakecolor']; } ?>" /></td>
			</tr>
			<tr id="snow_scrollwithscreen">
				<th scope="row"><label for="snow[scrollwithscreen]"><?php _e( 'Snow Scroll with Screen' ); ?></label></th>
				<td><select id="snow[scrollwithscreen]" name="snow[scrollwithscreen]"><option value="fixed"<?php if($snow['scrollwithscreen'] == 'fixed') { echo ' selected'; } ?>><?php _e('Enabled'); ?></option><option value="absolute"<?php if($snow['scrollwithscreen'] == 'absolute') { echo ' selected'; } ?>><?php _e('Disabled'); ?></option></select></td>
			</tr>
			<tr id="snow_minsize">
				<th scope="row"><label for="snow[minsize]"><?php _e( 'Minimum Flake Size' ); ?></label></th>
				<td><input id="snow[minsize]" name="snow[minsize]" type="number" value="<?php if(empty($snow['minsize'])) { echo '1'; } else { echo $snow['minsize']; } ?>" /></td>
			</tr>
			<tr id="snow_maxsize">
				<th scope="row"><label for="snow[maxsize]"><?php _e( 'Maximum Flake Size' ); ?></label></th>
				<td><input id="snow[maxsize]" name="snow[maxsize]" type="number" value="<?php if(empty($snow['maxsize'])) { echo '2'; } else { echo $snow['maxsize']; } ?>" /></td>
			</tr>
			<tr id="snow_minspeed">
				<th scope="row"><label for="snow[minspeed]"><?php _e( 'Minimum Flake Speed' ); ?></label></th>
				<td><input id="snow[minspeed]" name="snow[minspeed]" type="number" value="<?php if(empty($snow['minspeed'])) { echo '1'; } else { echo $snow['minspeed']; } ?>" /></td>
			</tr>
			<tr id="snow_maxspeed">
				<th scope="row"><label for="snow[maxspeed]"><?php _e( 'Maximum Flake Speed' ); ?></label></th>
				<td><input id="snow[maxspeed]" name="snow[maxspeed]" type="number" value="<?php if(empty($snow['maxspeed'])) { echo '5'; } else { echo $snow['maxspeed']; } ?>" /></td>
			</tr>
			<tr<?php if(!empty($snow['image'])) { echo ' class="item-disabled"'; } ?> id="snow_round">
				<th scope="row"><label for="snow[round]"><?php _e( 'Round Flakes' ); ?></label></th>
				<td><select id="snow[round]" name="snow[round]"><option value="true"<?php if($snow['round'] == 'true') { echo ' selected'; } ?>><?php _e('Enabled'); ?></option><option value="false"<?php if($snow['round'] == 'false') { echo ' selected'; } ?>><?php _e('Disabled'); ?></option></select></td>
			</tr>
			<tr<?php if(!empty($snow['image'])) { echo ' class="item-disabled"'; } ?> id="snow_shadow">
				<th scope="row"><label for="snow[shadow]"><?php _e( 'Shadow Flakes' ); ?></label></th>
				<td><select id="snow[shadow]" name="snow[shadow]"><option value="true"<?php if($snow['shadow'] == 'true') { echo ' selected'; } ?>><?php _e('Enabled'); ?></option><option value="false"<?php if($snow['shadow'] == 'false') { echo ' selected'; } ?>><?php _e('Disabled'); ?></option></select></td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
		<?php
		include_once( 'inc/footer.php' );
	}
}

function snow_page_advanced() {
	if ( !is_snow_admin() )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} else {
		include_once( 'inc/header.php' );
	?>
	<form method="post" action="options.php">
		<?php settings_fields( 'snowadvanced_group' ); ?>
		<table class="form-table">
			<tr id="snow_disabled">
				<th scope="row"><label for="snowadvanced[disabled]"><?php _e( 'Disable Snow' ); ?></label><span class="helpicon" title="<?php _e('This option allows you to disable all the snow, even when it is scheduled.'); ?>"></span></th>
				<td><input id="snowadvanced[disabled]" name="snowadvanced[disabled]" class="input_disabled" type="checkbox" value="1" <?php checked( true, isset( $snowadvanced['disabled'] ) ); ?>/><label for="snowadvanced[disabled]" id="label_disabled"></label></td>
			</tr>
			<tr id="snow_desktop">
				<th scope="row"><label for="snowadvanced[desktop]"><?php _e( 'Desktop only' ); ?></label><span class="helpicon" title="<?php _e('This option will only have the snow appear on desktops.'); ?>"></span></th>
				<td><input id="snowadvanced[desktop]" name="snowadvanced[desktop]" class="input_desktop" type="checkbox" value="1" <?php checked( true, isset( $snowadvanced['desktop'] ) ); ?>/><label for="snowadvanced[desktop]" id="label_desktop"></label></td>
			</tr>
			<tr id="snow_period">
				<th scope="row"><label for="snowadvanced[period_past]"><?php _e( 'Schedule Snow' ); ?></label><span class="helpicon" title="<?php _e('This option allows you to set a date for the snow to fall. Snow will only fall on and between those two dates. Leave blank to make it always snow.'); ?>"></span></th>
				<td><input id="snowadvanced[period_past]" name="snowadvanced[period_past]" placeholder="<?php _e('Start date'); ?>" type="text" value="<?php if(!empty($snowadvanced['period_past'])) { echo $snowadvanced['period_past']; } ?>" class="datepicker" /> <?php _e('until'); ?> <input id="snowadvanced[period_future]" name="snowadvanced[period_future]" placeholder="<?php _e('End date'); ?>" type="text" value="<?php if(!empty($snowadvanced['period_future'])) { echo $snowadvanced['period_future']; } ?>" class="datepicker" /></td>
				<?php
				if(empty($snowadvanced['period_past']) && !empty($snowadvanced['period_future']) ) {
					echo '<td>Start date is empty, therefore snow cannot be scheduled.</td>';
				} elseif(!empty($snowadvanced['period_past']) && empty($snowadvanced['period_future']) ) {
					echo '<td>End date is empty, therefore snow cannot be scheduled.</td>';
				}
				?>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
	<?php
		include_once( 'inc/footer.php' );
	}
}

function snow_page_technical() {
	if ( !is_snow_admin() )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} else {
		include_once( 'inc/header.php' );
	?>
	<form method="post" action="options.php">
		<?php settings_fields( 'snowtechnical_group' ); ?>
		<table class="form-table">
			<tr id="snow_file">
				<th scope="row"><label for="snowtechnical[file]"><?php _e( 'Use Minified File' ); ?></label><span class="helpicon" title="<?php _e('This option allows you to minify the JavaScript file, improving the loading time of your website.'); ?>"></span></th>
				<td><input id="snowtechnical[file]" name="snowtechnical[file]" class="input_file" type="checkbox" value="1" <?php checked( true, isset( $snowtechnical['file'] ) ); ?> /><label for="snowtechnical[file]" id="label_file"></label></td>
			</tr>
			<tr id="snow_script">
				<th scope="row"><label for="snowtechnical[script]"><?php _e( 'Use Minified Script' ); ?></label><span class="helpicon" title="<?php _e('This option allows you to minify the source code script, improving the loading time of your website.'); ?>"></span></th>
				<td><input id="snowtechnical[script]" name="snowtechnical[script]" class="input_script" type="checkbox" value="1" <?php checked( true, isset( $snowtechnical['script'] ) ); ?> /><label for="snowtechnical[script]" id="label_script"></label></td>
			</tr>
			<tr id="snow_flakeindex">
				<th scope="row"><label for="snowtechnical[flakeindex]"><?php _e( 'Flake Index' ); ?></label><span class="helpicon" title="<?php _e('This option lets you edit the index of the flakes. Lowering the number might cause the snowflakes to load under the site content.'); ?>"></span></th>
				<td><input id="snowtechnical[flakeindex]" name="snowtechnical[flakeindex]" type="number" value="<?php if(empty($snowtechnical['flakeindex'])) { echo '999999'; } else { echo $snowtechnical['flakeindex']; } ?>" min="1" max="999999" /></td>
			</tr>
			<tr id="snow_opacity">
				<th scope="row"><label for="snowtechnical[opacity]"><?php _e( 'Opacity' ); ?></label><span class="helpicon" title="<?php _e('This option allows you edit the opacity, making the snowflakes transparent.'); ?>"></span></th>
				<td><input id="snowtechnical[opacity]" name="snowtechnical[opacity]" type="text" value="<?php if(empty($snowtechnical['opacity'])) { echo '1'; } else { echo $snowtechnical['opacity']; } ?>" /></td>
			</tr>
			<tr id="snow_deviceorientation">
				<th scope="row"><label for="snowtechnical[deviceorientation]"><?php _e( 'Device Orientation' ); ?></label><span class="helpicon" title="<?php _e('Warning: experimental technology: this technology\'s specification has not been stabilized.'); ?>"></span></th>
				<td><select id="snowtechnical[deviceorientation]" name="snowtechnical[deviceorientation]"><option value="true"<?php if($snowtechnical['deviceorientation'] == 'true') { echo ' selected'; } ?>><?php _e('Enabled'); ?></option><option value="false"<?php if($snowtechnical['deviceorientation'] == 'false') { echo ' selected'; } ?>><?php _e('Disabled'); ?></option></select></td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
	<?php
		include_once( 'inc/footer.php' );
	}
}

add_action( 'admin_enqueue_scripts', function($hook) {
	if(is_snow()) {
		/* jQuery */
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
			
		/* Colorpicker */
		wp_enqueue_style( 'wp-color-picker' );
		
		/* Thickbox */
		wp_enqueue_script( 'thickbox' );
		
		/* Snow Scripts */
		wp_enqueue_script( 'snow-admin-js', plugins_url( 'admin/admin.js', SNW_FILE ), array( 'wp-color-picker' ), false, true );
		wp_enqueue_style( 'snow-admin-css', plugins_url( 'admin/admin.css', SNW_FILE ), false, '1.0.0' );
		
		/* Shortcut.js */
		wp_enqueue_script( 'shortcut-js', plugins_url( 'admin/shortcut.js', SNW_FILE ), false, true );
		
		/* Media Uploader */
		wp_enqueue_media();
		wp_enqueue_script( 'media-lib-uploader-snow', plugins_url( 'admin/media-lib-uploader.js' , SNW_FILE ), array('jquery') );
	}
	
	/* Dashboard, will always be enqueued */
	wp_enqueue_style( 'snow-dashboard', plugins_url( 'admin/dashboard.css', SNW_FILE ), false, '1.0.0' );
	
});

add_action('wp_head', function() {
	global $snow;
	global $snowtechnical;
	global $snowadvanced;

    $snowdate = date('Y-m-d');
    $snowdate = date('Y-m-d', strtotime($snowdate));

    $snowStart = date('Y-m-d', strtotime($snowadvanced['period_past']));
    $snowEnd = date('Y-m-d', strtotime($snowadvanced['period_future']));

	$snow_return = '
jQuery(document).ready(function(){
	jQuery(document).snowfall({' . snow_item( '35', 'flakecount', 'flakeCount', 'snow', false, false ) . snow_item( '#ffffff', 'flakecolor', 'flakeColor', 'snow', true, true ) . snow_item( '999999', 'flakeindex', 'flakeIndex', 'snowtechnical', false, false ) . snow_item( 'absolute', 'scrollwithscreen', 'flakePosition', 'snow', true, false ) . snow_item( '1', 'minsize', 'minSize', 'snow', false, false ) . snow_item( '2', 'maxsize', 'maxSize', 'snow', false, false ) . snow_item( '1', 'minspeed', 'minSpeed', 'snow', false, false ) . snow_item( '5', 'maxspeed', 'maxSpeed', 'snow', false, false ) . snow_item( '1', 'opacity', 'opacity', 'snowtechnical', false, false ) . snow_item( 'false', 'round', 'round', 'snow', false, true ) . snow_item( 'false', 'shadow', 'shadow', 'snow', false, true ) . snow_item( 'false', 'deviceorientation', 'deviceorientation', 'snowtechnical', false, false ) . snow_image() . '
	});
});
';

	if(empty($snowadvanced['disabled']) || $snowadvanced['disabled'] == '0') {
		if(!empty($snowadvanced['period_past']) && !empty($snowadvanced['period_future'])) {
			if (($snowdate >= $snowStart) && ($snowdate <= $snowEnd))  {
				if(isset($snowtechnical['script']) && $snowtechnical['script'] == '1') {
					$snow_return = str_replace(' ', '', $snow_return);
					$snow_return = preg_replace('/\s+/', '', $snow_return);
					
					echo '<script type="text/javascript">';
					echo $snow_return;
					echo '</script>
	';
				} else {
					echo '<script type="text/javascript">';
					echo $snow_return;
					echo '</script>
	';
				}
			}
		} else {
			if(isset($snowtechnical['script']) && $snowtechnical['script'] == '1') {
				$snow_return = str_replace(' ', '', $snow_return);
				$snow_return = preg_replace('/\s+/', '', $snow_return);
				
				echo '<script type="text/javascript">';
				echo $snow_return;
				echo '</script>
';
			} else {
				echo '<script type="text/javascript">';
				echo $snow_return;
				echo '</script>
';
			}
		}
	}
});

add_action('wp_enqueue_scripts', function() {
	global $snowtechnical;
	global $snowadvanced;

	require_once 'inc/Mobile_Detect.php';
	$detect = new Mobile_Detect;
	
	wp_enqueue_script( 'jquery' );
	
	if(isset($snowadvanced['desktop']) && $snowadvanced['desktop'] == '1') {
		if(!$detect->isMobile() && !$detect->isTablet()) {
			if(isset($snowtechnical['file']) && $snowtechnical['file'] == '1') {
				wp_register_script( 'snow', plugins_url( 'assets/snow.min.js', SNW_FILE ), array('jquery'), '1.0.0', false );
				wp_enqueue_script( 'snow' );
			} else {
				wp_register_script( 'snow', plugins_url( 'assets/snow.js', SNW_FILE ), array('jquery'), '1.0.0', false );
				wp_enqueue_script( 'snow' );
			}
		}
	} else {
		if(isset($snowtechnical['file']) && $snowtechnical['file'] == '1') {
			wp_register_script( 'snow', plugins_url( 'assets/snow.min.js', SNW_FILE ), array('jquery'), '1.0.0', false );
			wp_enqueue_script( 'snow' );
		} else {
			wp_register_script( 'snow', plugins_url( 'assets/snow.js', SNW_FILE ), array('jquery'), '1.0.0', false );
			wp_enqueue_script( 'snow' );
		}
	}
});

add_action( 'admin_notices', function() {
	if( get_transient( 'snow_hello' ) ){
		?>
		<div class="updated notice is-dismissible">
			<p><?php _e('Look, it\'s snowing! You might want to configure Snow on the'); ?> <a href="<?php echo admin_url( 'admin.php?page=snow' ); ?>"><?php _e('settings page'); ?></a>.</p>
		</div>
		<?php
		delete_transient( 'snow_hello' );
	}
});

register_activation_hook( SNW_FILE, function() {
	set_transient( 'snow_hello', true, 5 );
	set_transient( 'snow_intro_general', true, 0 );
	set_transient( 'snow_intro_advanced', true, 0 );
	set_transient( 'snow_intro_technical', true, 0 );
});

add_filter( 'body_class', function( $classes ) {
    return array_merge( $classes, array( 'snow' ) );
} );

add_filter( 'plugin_action_links_' . plugin_basename( SNW_FILE ), function($link) {
	return array_merge( $link, array('<a href="' . admin_url( 'admin.php?page=snow' ) . '">Settings</a>','<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2VYPRGME8QELC" target="_blank" rel="noopener noreferrer">Donate</a>') );
} );