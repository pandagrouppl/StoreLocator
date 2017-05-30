<?php $deactivate_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=wd-instagram-feed/wd-instagram-feed.php', 'deactivate-plugin_wd-instagram-feed/wd-instagram-feed.php'); ?>
<div id="wdi_plugin_uninstalled">
	<div class="wdi_plugin_uninstalled_container">
	<style>
	.wdi_plugin_uninstalled p{
		font-size: 20px;
	}
	.wdi_plugin_uninstalled p span{
		font-weight: bold;
	}
	.wdi_plugin_uninstalled p span.wdi_regards{
		font-size: 25px;
	}
	.wdi_plugin_uninstalled img{
		width: 100%;
	}
	.wdi_plugin_uninstalled{
		width: 75%;
		display: inline-block;
	}
	#wdi_plugin_uninstalled{
		text-align: center;
	}

	
</style>
		<div class="wdi_plugin_uninstalled">
			<p>Dear <span><?php echo (wp_get_current_user()->user_firstname!='') ?  wp_get_current_user()->user_firstname .' '. wp_get_current_user()->user_lastname : wp_get_current_user()->user_login?></span> <?php _e('you have uninstalled','wd-instagram-feed') ?> <span>Instagram Feed WD </span><?php _e('plugin,if you want to use it again simply deactivate and activate it again','wd-instagram-feed') ?>
			  <span class="wdi_regards">Best regards Web-Dorado Team.</span></p>
			  <a href="<?php echo $deactivate_url?>">Deactivate Plugin</a>
			  <p id="wdi_redirect"></p>
		</div>
	</div>
</div>
