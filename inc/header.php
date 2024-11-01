<?php
		global $snow;
		global $snowadvanced;
		global $snowtechnical;
		global $snowdata;
		
		add_thickbox();
		
		snow_setdefault( 'snow', 'round', 'false' );
		snow_setdefault( 'snow', 'shadow', 'false' );
		snow_setdefault( 'snow', 'scrollwithscreen', 'absolute' );
		snow_setdefault( 'snowtechnical', 'deviceorientation', 'false' );
		
		$snowdate = date('Y-m-d');
		$snowdate = date('Y-m-d', strtotime($snowdate));

		$snowStart = date('Y-m-d', strtotime($snowadvanced['period_past']));
		$snowEnd = date('Y-m-d', strtotime($snowadvanced['period_future']));
?>
<div class="wrap">
	<h1>Snow<span class="version_ribbon">v<?php echo SNW_VERSION; ?></span></h1>
	<?php
	if( isset($_GET['settings-updated']) ) {
	?>
    <div class="notice notice-success is-dismissible">
        <p><strong><?php _e( 'Settings updated.', 'snow' ); ?></strong></p>
    </div>
	<?php
	}
	
	if( snow_transient( 'intro_general', '' ) ){
	?>
    <div class="notice notice-intro is-dismissible">
        <p><strong><?php _e( 'Introduction of the General tab' ); ?></strong></p>
		<p><?php _e('General is where you need to be to modify and customize your snowfall. This tab does not contain any technical options so if you just want snow and nothing more, we recommend only using this tab.'); ?></p>
    </div>
	<?php
		delete_transient( 'snow_intro_general' );
	} elseif( snow_transient( 'intro_advanced', '_advanced' ) ) {
	?>
    <div class="notice notice-intro is-dismissible">
        <p><strong><?php _e( 'Introduction of the Advanced tab' ); ?></strong></p>
		<p><?php _e('Advanced are the \'under the hood\' options. They won\'t make any visual changes, but they\'re helpful! If you simply want to schedule the snowfall or disable it all together, you find it all here.'); ?></p>
    </div>
	<?php
		delete_transient( 'snow_intro_advanced' );
	} elseif( snow_transient( 'intro_technical', '_technical' ) ) {
	?>
    <div class="notice notice-intro is-dismissible">
        <p><strong><?php _e( 'Introduction of the Technical tab' ); ?></strong></p>
		<p><?php _e('A regular person might not know what these options are and can do. We recommend not changing them if you don\'t know what these options can affect.'); ?></p>
    </div>
	<?php
		delete_transient( 'snow_intro_technical' );
	}
	
	if(isset($snowadvanced['disabled']) && $snowadvanced['disabled'] == '1') {
		if (($snowdate >= $snowStart) && ($snowdate <= $snowEnd)) {
		?>
		<div class="notice notice-warning">
			<p><?php _e( 'Snow is currently disabled.' ); ?></p>
		</div>
		<?php
		}
	} elseif(!empty($snowadvanced['period_past']) && !empty($snowadvanced['period_future'])) {
		?>
		<div class="notice notice-info">
			<p><?php _e( 'Snow is currently scheduled.' ); ?></p>
		</div>
		<?php
	}
	?>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab<?php if(isset($_GET['page']) && $_GET['page'] == 'snow') { echo ' nav-tab-active'; } ?>" href="<?php echo admin_url( 'admin.php?page=snow' ); ?>"><?php _e('General'); ?></a>
		<a class="nav-tab<?php if(isset($_GET['page']) && $_GET['page'] == 'snow_advanced') { echo ' nav-tab-active'; } ?>" href="<?php echo admin_url( 'admin.php?page=snow_advanced' ); ?>"><?php _e('Advanced'); ?></a>
		<a class="nav-tab<?php if(isset($_GET['page']) && $_GET['page'] == 'snow_technical') { echo ' nav-tab-active'; } ?>" href="<?php echo admin_url( 'admin.php?page=snow_technical' ); ?>"><?php _e('Technical'); ?></a>
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2VYPRGME8QELC" target="_blank" rel="noopener noreferrer" class="nav-tab-donate"><?php _e('Donate'); ?></a>
		<a href="https://wordpress.org/support/plugin/snow/reviews/?rate=5#new-post" target="_blank" rel="noopener noreferrer" class="nav-tab-review"><?php _e('Review'); ?></a>
	</h2>