</div>

<div id="reset" style="display:none;">
	<p><?php _e('Do you want to reset all options to their default value?'); ?></p>
	<a href="<?php
	$reset_url = wp_nonce_url(admin_url('admin.php?page=snow'), 'reset', 'reset_nonce');
	echo $reset_url;
	?>" class="button-primary corner-button"><?php _e('Reset'); ?></a>
</div>
<div id="debugging" style="display:none;">
	<div class="debugging-code">
		<pre><?php
			$merge_debug = array_merge($snowdata, snow_debug($snow, 'snow'), snow_debug($snowadvanced, 'snowadvanced'), snow_debug($snowtechnical, 'snowtechnical'));
			print_r($merge_debug);
			?></pre>
	</div>
</div>