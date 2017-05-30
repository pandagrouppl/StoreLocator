<?php
/**
 * @param version without first '1' or '2'
 *
 */

function wdi_update_diff($new_v, $old_v = 0.0){
	global $wpdb;
	@ini_set('max_execution_time', 60);

	if(version_compare($old_v, "0.6", '<')){

		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_FEED_TABLE . " ADD `conditional_filters` varchar(10000) NOT NULL DEFAULT ''");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_FEED_TABLE . " ADD `conditional_filter_type` varchar(32) NOT NULL DEFAULT 'none'");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_FEED_TABLE . " ADD `show_username_on_thumb` varchar(32) NOT NULL DEFAULT '0'");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_FEED_TABLE . " ADD `conditional_filter_enable` varchar(32) NOT NULL DEFAULT '0'");

		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_THEME_TABLE . " ADD `th_thumb_user_bg_color` varchar(32) NOT NULL DEFAULT '#429FFF'");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_THEME_TABLE . " ADD `th_thumb_user_color` varchar(32) NOT NULL DEFAULT '#FFFFFF'");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_THEME_TABLE . " ADD `mas_thumb_user_bg_color` varchar(32) NOT NULL DEFAULT '#429FFF'");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_THEME_TABLE . " ADD `mas_thumb_user_color` varchar(32) NOT NULL DEFAULT '#FFFFFF'");

		require_once(WDI_DIR . '/framework/WDILibraryEmbed.php');

		/*set master user id*/
		$wdi_options = get_option(WDI_OPT);
		$master_username = isset($wdi_options['wdi_user_name']) ? $wdi_options['wdi_user_name'] : false;
		if($master_username){
			$master_user_id = WDILibraryEmbed::get_instagram_id_by_username($master_username);
		}
		$wdi_options["wdi_user_id"] = $master_user_id ? $master_user_id : '';
		update_option(WDI_OPT, $wdi_options);

		/*set ids in feeds*/
		$feeds = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . WDI_FEED_TABLE, ARRAY_A );
		foreach ($feeds as $feed) {
			$users_new = array();
			$users = trim($feed['feed_users']);
			$usersArr = explode(',',$users);
			foreach ($usersArr as $username) {
				if(substr($username, 0, 1) == "#"){
					$current_user_id = $username;
				}
				else{
					$current_user_id = WDILibraryEmbed::get_instagram_id_by_username($username);
				}

				$current_user = new stdClass();
				$current_user->username = $username;
				$current_user->id = $current_user_id;
				array_push($users_new, $current_user);
			}
			$users_new_json = json_encode($users_new);
			/*save current feed data into WPDB*/
			$wpdb->update(
				$wpdb->prefix . WDI_FEED_TABLE,
				array(
					'feed_users' => $users_new_json,
				),
				array( 'id' => $feed['id'] )
			);
		}
	}

	if(version_compare($old_v, "0.7", '<')){
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_THEME_TABLE . " ADD `th_photo_img_hover_effect` varchar(32) NOT NULL DEFAULT 'none'");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_THEME_TABLE . " ADD `mas_photo_img_hover_effect` varchar(32) NOT NULL DEFAULT 'none'");
	}
	if(version_compare($old_v, "1.0", '<')){
		/*add api update notice*/
		$admin_notices_option = get_option('wdi_admin_notice', array());
		$admin_notices_option['api_update_token_reset'] = array(
			'start' => current_time("n/j/Y"),
			'int'   => 0,
			//'dismissed' => 1,
		);
		update_option('wdi_admin_notice', $admin_notices_option);
	}
	if(version_compare($old_v, "1.2", '<')){
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_THEME_TABLE . " convert to character set latin1 collate latin1_general_ci");
	}
	if(version_compare($old_v, "1.12", '<')){
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_FEED_TABLE . " ADD `liked_feed` varchar(30) NOT NULL DEFAULT 'userhash'");
	}
	if(version_compare($old_v, "1.17", '<')){
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . WDI_FEED_TABLE . " ADD `mobile_breakpoint` varchar(10) NOT NULL DEFAULT '640'");
	}



}

