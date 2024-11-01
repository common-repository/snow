<?php
// Check if we're on a Snow settings page
function is_snow() {
	if(isset($_GET['page']) && substr($_GET['page'],0,4) == 'snow') {
		return true;
	}
	
	return false;
}

// Check if we're on a specific entered page
function __is_snow( $page ) {
	if(isset($_GET['page']) && $_GET['page'] == $page) {
		return true;
	}
	
	return false;
}

// Check if the user has admin rights
function is_snow_admin() {
	if (current_user_can( 'manage_options' ) && is_admin() && is_snow()) {
		return true;
	}
	
	return false;
}

// Set a default option
function snow_setdefault( $prefix, $item, $default ) {
	global $snow;
	global $snowadvanced;
	global $snowtechnical;
	
	if($prefix == 'snow' && empty($snow[$item])) {
		$snow[$item] = $default;
	} elseif($prefix == 'snowadvanced' && empty($snow[$item])) {
		$snowadvanced[$item] = $default;
	} elseif($prefix == 'snowtechnical' && empty($snow[$item])) {
		$snowtechnical[$item] = $default;
	}
}

// Generate debugging information
function snow_debug($debugarray, $arrayname) {
	if(isset($debugarray)) {
		if(is_array($debugarray) == true) {
			return $debugarray;
		} else {
			return array_fill_keys(array($arrayname), 'empty');
		}
	}
}

// Create image snowflake JS var
function snow_image() {
	global $snow;
	
	if(!empty($snow['image'])) {
		return '
		image: "' . $snow['image'] . '"';
	}
}

// Create JS vars
function snow_item( $default, $item, $arg, $cat, $comma, $runifimage ) {
	global $snow;
	global $snowtechnical;
	
	if(!empty($snow['image']) && $runifimage === true) {
		return;
	}
	
	if(!empty($cat) && $cat == 'snow') {
		if(!empty($snow[$item]) && $snow[$item] !== $default) {
			if($comma === true) {
				return '
	' . $arg . ': "' . $snow[$item] . '",';
			} else {
				return '
	' . $arg . ': ' . $snow[$item] . ',';
			}
		}
	} elseif(!empty($cat) && $cat == 'snowtechnical') {
		if(!empty($snowtechnical[$item]) && $snowtechnical[$item] !== $default) {
			if($comma === true) {
				return '
	' . $arg . ': "' . $snowtechnical[$item] . '",';
			} else {
				return '
	' . $arg . ': ' . $snowtechnical[$item] . ',';
			}
		}
	}

}

// Check Snow transient
function snow_transient( $transient, $page ) {
	if(get_transient('snow_' . $transient) && isset($_GET['page']) && $_GET['page'] == 'snow' . $page) {
		return true;
	}
	
	return false;
}