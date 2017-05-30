jQuery(document).ready(function() {



	/*Feeds page*/
	wdi_controller.bindSaveFeedEvent();
	wdi_controller.bindAddNewUserOrHashtagEvent();
	jQuery('.display_type input').on('click', function() {
		wdi_controller.displaySettingsSection(jQuery(this));
	});
	/*-----------Conditional Filters-----------*/
	wdi_controller.conditionalFiltersTabInit();
	/*Themes page*/
	wdi_controller.bindSaveThemeEvent();

	jQuery('#wdi_add_user_ajax').after(jQuery("<br><label class='wdi_pro_only' for='wdi_add_user_ajax_input'>" + wdi_messages.username_hashtag_multiple + "</label>"));


});

function wdi_controller() {};

/**
 * Gets query parameter by name
 * @param  {String} name [parameter name]
 * @return {String}      [parameter value]
 */
wdi_controller.getParameterByName = function(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

/**
 * Was tirggerd when redirected from api page
 * Sets access token from query string to input
 */
wdi_controller.apiRedirected = function() {
	var access_token_raw = this.getParameterByName('access_token');
	var arr = access_token_raw.split('.');
	var validRegEx = /^[^\\\/&?><"']*$/;
	for (i = 0; i < arr.length; i++) {
		if (arr[i].match(validRegEx) === null) {
			return;
		}
	}
	var access_token = arr.join('.');
	jQuery(document).ready(function() {
		jQuery('#wdi_access_token').attr('value', access_token);
	});

	//if access token is getted then overwrite it
	wdi_controller.instagram.addToken(access_token);
	wdi_controller.getUserInfo(access_token);
}



/**
 * Used in Settings page for finding access token owners username
 * and and for filling it in username input field
 *
 * @param  {String} access_token [Instagram API access token]
 */
wdi_controller.getUserInfo = function(access_token) {
	this.instagram.getSelfInfo({
		success: function(response) {
			jQuery('#wdi_user_name').attr('value', response['data']['username']);
			jQuery('#wdi_user_id').attr('value', response['data']['id']);
			jQuery(document).trigger('wdi_settings_filled');
		}
	})
}



wdi_controller.oldDisplayType = {};
wdi_controller.displayTypeMemory = {};


/*
 * Switches between feeds admin page tabs
 */
wdi_controller.switchFeedTabs = function(tabname, section) {

	//add tabname in hidden field
	jQuery('#wdi_refresh_tab').attr('value', tabname);

	//hiding options of other tabs
	jQuery('[tab]').each(function() {
		if (jQuery(this).attr('tab') != tabname) {
			jQuery(this).parent().parent().parent().parent().parent().filter('tr').css('display', 'none');
		} else {
			jQuery(this).parent().parent().parent().parent().parent().filter('tr').css('display', 'block');
		}
	});
	//hiding all display_type elements
	jQuery('.display_type').css('display', 'none');
	//showing only requested display_type tab elements
	jQuery('.display_type[tab="' + tabname + '"]').css('display', 'block');

	//swap active tab class
	jQuery('.wdi_feed_tabs').filter('.wdi_feed_tab_active').each(function() {
		jQuery(this).removeClass('wdi_feed_tab_active');
	});
	jQuery('#wdi_' + tabname).addClass('wdi_feed_tab_active');
	var selectedSection = jQuery();
	var sectionSelectedFLag = false;
	if (section != undefined && section != '') {
		//check value which came from backend
		selectedSection = jQuery('.display_type #' + section).prop('checked', true);
		jQuery('#wdi_feed_type').attr('value', section);
		//sectionSelectedFLag = true;
	}
	//find the selected feed_type option
	if (!sectionSelectedFLag) {
		selectedSection = jQuery('.display_type[tab="' + tabname + '"] input[name="feed_type"]:checked');
		if (selectedSection.length != 0) {
			sectionSelectedFLag = true;
		}
	}
	//if there are no selected feed_type option then set default option
	if (!sectionSelectedFLag) {
		//make default section as selected
		selectedSection = jQuery('.display_type[tab="' + tabname + '"] #thumbnails');
		if (selectedSection.length != 0) {
			sectionSelectedFLag = true;
			selectedSection.prop('checked', true);
			jQuery('#wdi_feed_type').attr('value', 'thumbnails');
		};

	}
	//if under currect tab we have feed_type section then show it
	if (sectionSelectedFLag) {
		wdi_controller.displaySettingsSection(selectedSection);
	}

	if( tabname != 'conditional_filters' ){
		jQuery( '#wdi-conditional-filters-ui' ).addClass('wdi_hidden');
		jQuery( '#wdi_save_feed_apply' ).removeClass( 'wdi_hidden' );
		jQuery( '#wdi_cancel_changes' ).removeClass( 'wdi_hidden' );
		jQuery( '#wdi_save_feed_submit' ).removeClass( 'wdi_hidden' );
	}else{
		jQuery( '#wdi-conditional-filters-ui' ).removeClass('wdi_hidden');
		jQuery( '#wdi_save_feed_apply' ).addClass( 'wdi_hidden' );
		jQuery( '#wdi_cancel_changes' ).addClass( 'wdi_hidden' );
		jQuery( '#wdi_save_feed_submit' ).addClass( 'wdi_hidden' );
	}

}

/*
 * Displays Settings Section for admin pages
 */
wdi_controller.displaySettingsSection = function($this) {
	var sectionName = $this.attr('id').toLowerCase().trim();
	var tab = $this.parent().parent().attr('tab');
	var sectionHiddenField = jQuery('#wdi_refresh_section');
	wdi_controller.oldDisplayType = {
		'section': sectionName,
		'tab': tab
	};
	wdi_controller.displayTypeMemory[tab] = wdi_controller.oldDisplayType;
	//works only in theme page, because only theme page has #wdi_refresh_section hidden field
	if (sectionHiddenField != undefined) {
		sectionHiddenField.attr('value', sectionName);
	}

	var formTable = jQuery('.wdi_border_wrapper .form-table');
	jQuery('#wdi_feed_type').attr('value', sectionName);
	var i = 0,
		j = 0;
	var sectionFlag = false;
	formTable.find('tr').each(function() {
		i++;

		var sectionStr = jQuery(this).children().children().children().children().children().attr('section');

		if (sectionStr !== undefined) {
			sectionFlag = false;
			var sections = sectionStr.toLowerCase().trim().split(',');
			for (j = 0; j < sections.length; j++) {
				if (sections[j] === sectionName) {
					jQuery(this).css('display', 'block');
					sectionFlag = true;
				}
			}
			if (sectionFlag === false) {
				jQuery(this).css('display', 'none');
			}
		}
	});
}

/*
 * Switches between themes admin page tabs
 */
wdi_controller.switchThemeTabs = function(tabname, section) {


	//swap active tab class
	jQuery('.wdi_feed_tabs').filter('.wdi_feed_tab_active').each(function() {
		jQuery(this).removeClass('wdi_feed_tab_active');
	});
	jQuery('#wdi_' + tabname).addClass('wdi_feed_tab_active');


	//hiding options of other tabs
	jQuery('[tab]').each(function() {
		if (jQuery(this).attr('tab') != tabname) {
			jQuery(this).parent().parent().parent().parent().parent().filter('tr').css('display', 'none');
		} else {
			jQuery(this).parent().parent().parent().parent().parent().filter('tr').css('display', 'block');
		}
	});

	//hiding all display_type elements
	jQuery('.display_type').css('display', 'none');
	//showing only requested display_type tab elements
	jQuery('.display_type[tab="' + tabname + '"]').css('display', 'block');


	//add tabname in hidden field
	jQuery('#wdi_refresh_tab').attr('value', tabname);
	//add sectionname in hidden field
	if (section != undefined && section != '') {
		jQuery('#wdi_refresh_section').attr('value', section);
	}

	//check if any section was previously clicked then set to that section
	if (section == undefined && section != '') {
		if (wdi_controller.displayTypeMemory[tabname] != undefined) {
			jQuery('.display_type #' + wdi_controller.displayTypeMemory[tabname]['section']).trigger('click');
		} else {
			//default section
			jQuery('.display_type[tab="' + tabname + '"]').first().find('input').trigger('click');
		}
	} else {
		jQuery('.display_type #' + section).trigger('click');
	}

}


/**
 * Binds events to control buttons
 */
wdi_controller.bindSaveFeedEvent = function() {
	var _this = this;

	jQuery('#wdi_save_feed_submit').on('click', function() {
		_this.save_feed('save_feed')
	});
	jQuery('#wdi_save_feed_apply').on('click', function() {
		_this.save_feed('apply_changes')
	});

	jQuery('#wdi_cancel_changes').on('click', function() {
		_this.save_feed('cancel')
	});
}


/**
 * Submits form baset on given task
 * if task is cancel then it reloades the page
 * @param  {String} task [this is self explanatory]
 */
wdi_controller.save_feed = function(task) {

	if ("cancel" == task) {
		window.location = window.location.href;
	}

	//check if user input field is not empty then cancel save process and make an ajax request
	//add user in input field and then after it trigger save,apply or whatever
	wdi_controller.checkIfUserNotSaved(task);
	if (wdi_controller.waitingAjaxRequestEnd.button != 0) {
		return;
	};

	jQuery('#task').attr('value', task);
	var feed_users = this.feed_users,
		feed_users_json,
		id,
		username,
		errorInfo,
		profile_picture,
		defaultUser = {
			username: jQuery('#wdi_default_user').val(),
			id: jQuery('#wdi_default_user_id').val()
		};

	if (feed_users.length == 0) {
		feed_users.push(defaultUser);
		this.updateFeaturedImageSelect(defaultUser['username'], 'add', 'selected');
	}

	feed_users_json = this.stringifyUserData(feed_users);

	jQuery('#WDI_feed_users').val(feed_users_json);


	if (task == 'apply_changes' || task == 'save_feed') {
		id = jQuery('#wdi_add_or_edit').attr('value');
		jQuery('#wdi_current_id').attr('value', id);
	}



	username = jQuery('#WDI_thumb_user').val();
	//errorInfo = '';
	profile_picture = this.getUserProfilePic(username);

	//if username is selected default user and we don't have default users image then request it
	//typeof profile_picture will be undefined when user without adding any user submits form
	if ('false' == profile_picture || typeof profile_picture == 'undefined') {
		var _this = this;
		this.instagram.searchForUsersByName(username, {
			success: function(response) {
				var vObj = _this.isValidResponse(response),
					user = _this.findUser(username, response),
					profile_picture;
				if (vObj.valid && _this.hasData(response) && user) {
					profile_picture = user['profile_picture'];
				} else {
					profile_picture = '';
				}
				jQuery('#wdi_feed_thumb').attr('value', profile_picture);
				jQuery('#wdi_save_feed').submit();
			}
		});
	} else {
		jQuery('#wdi_feed_thumb').attr('value', profile_picture);
		jQuery('#wdi_save_feed').submit();
	}
}



/**
 * Takes user input as argument and makes an
 * instagram request for getting meta info such as username and user id
 * stores getted data in wdi_controller.feed_users array and updates some admin elements which
 * depend on users
 *
 * @param  {String} user_input [username or hashtag, Note. hashtags should start with #]
 * @param {String} backend [if is set to 'backend' all confirms will be ignored while making requests]
 * @return {Void}
 */
wdi_controller.makeInstagramUserRequest = function(user_input, ignoreConfirm) {

	var newUser, input_type, _this = this,
		selected;

	input_type = this.getInputType(user_input);

	if (wdi_version.is_pro == 'false') {
		if (jQuery('.wdi_user').length == 1) {
			alert(wdi_messages.only_one_user_or_hashtag);
			return;
		}
	}



	switch (input_type) {
		case 'user':
			{
				this.instagram.searchForUsersByName(user_input, {
					success: function(response) {

						//contains information about response such as error messages and if
						//response is valid or not
						var vObj = _this.isValidResponse(response),
							//this is the user object we are searching for, of user does not exists then it is false
							user = _this.findUser(user_input, response);
						if (vObj.valid && _this.hasData(response) && user) {
							_this.addUser(user);

							if (wdi_version.is_pro == 'false') {
								if (jQuery('.wdi_user').length == 1) {
									jQuery('#wdi_add_user_ajax_input').attr('disabled', 'disabled');
									jQuery('#wdi_add_user_ajax_input').attr('placeholder', wdi_messages.available_in_pro);
								}
							}

						} else {
							if (!user) {
								alert( wdi_messages.user_not_exist.replace("%s",'"'+ user_input + '"'))
							} else {
								alert(vObj.msg);
							}

						}


					}
				});
				break;
			}
		case 'hashtag':
			{

				var tagname = user_input.substr(1, user_input.length);
				tagname = tagname.replace(" ",'');
				this.instagram.getTagRecentMedia(tagname, {
					success: function(response) {
						//contain information about response such as error messages and if
						//response is valid or not
						var vObj = _this.isValidResponse(response);
						if (vObj.valid && _this.hasData(response)) {
							_this.addHashtag(tagname, response);

							if (wdi_version.is_pro == 'false') {
								if (jQuery('.wdi_user').length == 1) {
									jQuery('#wdi_add_user_ajax_input').attr('disabled', 'disabled');
									jQuery('#wdi_add_user_ajax_input').attr('placeholder', wdi_messages.available_in_pro);
								}
							}

						} else {
							if (!_this.hasData(response) && vObj.msg == 'success') {
								if (ignoreConfirm != true) {
									if (confirm(wdi_messages.hashtag_no_data)) {
										_this.addHashtag(tagname, response);

										if (wdi_version.is_pro == 'false') {
											if (jQuery('.wdi_user').length == 1) {
												jQuery('#wdi_add_user_ajax_input').attr('disabled', 'disabled');
												jQuery('#wdi_add_user_ajax_input').attr('placeholder', wdi_messages.available_in_pro);
											}
										}

									} else {
										jQuery('#wdi_add_user_ajax_input').val('');
									};
								} else {
									_this.addHashtag(tagname, response);

									if (wdi_version.is_pro == 'false') {
										if (jQuery('.wdi_user').length == 1) {
											jQuery('#wdi_add_user_ajax_input').attr('disabled', 'disabled');
											jQuery('#wdi_add_user_ajax_input').attr('placeholder', wdi_messages.available_in_pro);
										}
									}
								}
							} else {
								alert(vObj.msg);
							}

						}

					}
				});

				break;
			}
	}



}

/**
 * Scans wdi_controller.feed_users array and if duplicate matched then returns false else true
 * @param  {String} username [name of user we want to check]
 * @return {Boolean}
 */
wdi_controller.checkForDuplicateUser = function(username) {
	for (var i = 0; i < this.feed_users.length; i++) {
		if (username == this.feed_users[i]['username']) {
			return true;
		}
	}
	return false;
}

wdi_controller.getInputType = function(input) {
	switch (input[0]) {
		case '#':
			{
				return 'hashtag';
				break;
			}
		case '%':
			{
				return 'location';
				break;
			}
		default:
			{
				return 'user';
				break;
			}

	}
}



/**
 * Makes username and id pairs from users array and return json_encoded string
 * @param  {Array} feed_users [array of feed_users containing username and id and other parameters]
 * @return {String}           [JSON encoded data]
 */
wdi_controller.stringifyUserData = function(feed_users) {
	var users = [];
	for (var i = 0; i < feed_users.length; i++) {
		users.push({
			username: feed_users[i]['username'],
			id: feed_users[i]['id']
		})
	}
	return JSON.stringify(users);
}



/**
 * Binds 'click' and 'enter' event to add user button
 *
 */
wdi_controller.bindAddNewUserOrHashtagEvent = function() {
	jQuery('#wdi_add_user_ajax').on('click', function() {
		var user_input = jQuery('#wdi_add_user_ajax_input').val().trim().toLowerCase();
		wdi_controller.makeInstagramUserRequest(user_input);
	});
	jQuery('#wdi_add_user_ajax_input').on("keypress", function(e) {
		if (e.keyCode == 13) {
			var user_input = jQuery('#wdi_add_user_ajax_input').val().trim().toLowerCase();
			wdi_controller.makeInstagramUserRequest(user_input);
			return false; // prevent the button click from happening
		}
	});

}

/**
 * Removes users from internal wdi_controller.feed_users array and also
 * updates GUI (by removing user elements)
 *
 * @param  {Object} $this [jQuery object of remove user button]
 */
wdi_controller.removeFeedUser = function($this) {


	var username = $this.parent().find('a span').text();
	if ($this.parent().find('a span').hasClass('wdi_hashtag')) {
		username = '#' + username;
	}

	for (var i = 0; i < this.feed_users.length; i++) {
		if (this.feed_users[i]['username'] == username) {
			this.feed_users.splice(i, 1);
			break;
		}
	}

	$this.parent().remove();
	//wdi_controller.updateHiddenField();
	if (username !== jQuery('#wdi_default_user').val()) {
		wdi_controller.updateFeaturedImageSelect(username, 'remove');
	}

	if(wdi_version.is_pro == 'false'){
		if( jQuery('.wdi_user').length == 0 ){
			jQuery('#wdi_add_user_ajax_input').removeAttr('disabled');
			jQuery('#wdi_add_user_ajax_input').attr('placeholder','');
		}
	}

}


/**
 * Adds or removes users from featured image select
 *
 * @param  {String} username [username of user we want to add/remove]
 * @param  {String} action   [valid options are 'add' and 'remove']
 * @param  {String} selected [if is set 'selected' then user will be marked as selected in select element]
 */
wdi_controller.updateFeaturedImageSelect = function(username, action, selected) {
	var select = jQuery('#WDI_thumb_user');
	if (selected != 'selected') {
		selected = '';
	}
	switch (action) {
		case 'add':
			{
				//check if there is no duplicate then add
				var flag = select.find('option[value="' + username + '"]').length;
				if (!flag) {

					var option = jQuery('<option ' + selected + ' value="' + username + '">' + username + '</option>');
					select.append(option);
				}

				break;
			}
		case 'remove':
			{
				select.find('option[value="' + username + '"]').remove();
				break;
			}
	}

}



////////////////////////////////////////////////////////////////////////////////
///////////////////////////////Themes Page//////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
wdi_controller.bindSaveThemeEvent = function() {
	jQuery('#wdi_save_theme_submit').on('click', function() {
		jQuery('#task').attr('value', 'save_feed');
		jQuery('#wdi_save_feed').submit();
	});
	jQuery('#wdi_save_theme_apply').on('click', function() {
		jQuery('#task').attr('value', 'apply_changes');
		var id = jQuery('#wdi_add_or_edit').attr('value');
		jQuery('#wdi_current_id').attr('value', id);
		jQuery('#wdi_save_feed').submit();
	});
	jQuery('#wdi_save_theme_reset').on('click', function() {
		jQuery('#task').attr('value', 'reset_changes');
		var id = jQuery('#wdi_add_or_edit').attr('value');
		jQuery('#wdi_current_id').attr('value', id);
		jQuery('#wdi_save_feed').submit();
	});
}


/**
 * This function is called when one of controll buttons are being clicked
 * it checks if user has typed any username in unsername input
 * but forgetted to add it then it creates an object called wdi_controller.waitingAjaxRequestEnd
 * which previous task
 *
 * @param  {String} task [how to save element save/apply/reset]
 * @return {Boolean}     [1 if user forgotted to save and 0 if input field was empty]
 */
wdi_controller.checkIfUserNotSaved = function(task) {
	switch (task) {
		case 'save_feed':
			{
				task = 'submit';
				break;
			}
		case 'apply_changes':
			{
				task = "apply";
				break;
			}
		case 'reset_changes':
			{
				task = 'reset';
				break;
			}
	}

	//checking if user has typed username in input field but didn't saved it, trigger add action
	if (jQuery('#wdi_add_user_ajax_input').val().trim() != '') {
		var user_input = jQuery('#wdi_add_user_ajax_input').val().trim().toLowerCase();
		wdi_controller.waitingAjaxRequestEnd = {
			button: task
		};
		//making request
		wdi_controller.makeInstagramUserRequest(user_input);
		return 1;
	} else {
		wdi_controller.waitingAjaxRequestEnd = {
			button: 0
		};
		return 0;
	}
}

/**
 * if user was clicked save before ajax request then trigger save after getting info
 *
 * @param  {String} correctUserFlag [if set to false form wouldn't be submitted]
 */
wdi_controller.saveFeedAfterAjaxWait = function(correctUserFlag) {

	if (wdi_controller.waitingAjaxRequestEnd != undefined) {
		//if save button was clicked before ajax request then trigger save button
		var save_type_btn = wdi_controller.waitingAjaxRequestEnd.button;
		if (correctUserFlag && save_type_btn != 0) {
			jQuery('#wdi_save_feed_' + save_type_btn).trigger('click');
		}
		wdi_controller.waitingAjaxRequestEnd = undefined;
	}
}


/**
 * Gets cookie value by name
 * @param  {String} name [cookie name]
 * @return {String}      [cookie value]
 */
wdi_controller.getCookie = function(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length == 2) return parts.pop().split(";").shift();
}

/**
 * Checks if response has meta code other then 200 or if it has not any data in it
 * then returns false
 * @param  {Object}  response [Instagram API response]
 * @return {Boolean}
 */
wdi_controller.isValidResponse = function(response) {

	var obj = {};
	if (typeof response == 'undefined' || typeof response['meta']['code'] == 'undefined' || response['meta']['code'] != 200) {
		obj.valid = false;
		if (typeof response == 'undefined') {
			obj.msg = wdi_messages.instagram_server_error;
		} else if (response['meta']['code'] !== 200) {
			obj.msg = response['meta']['error_message'];
		} else {
			obj.msg = '';
		}
	} else {
		obj.valid = true;
		obj.msg = 'success';
	}
	return obj;
}


/**
 * Return true if response has data object which is not empty
 * @param  {Onject}  response [instagram API response]
 * @return {Boolean}          [true or false]
 */
wdi_controller.hasData = function(response) {
	if (typeof response != 'undefined' && typeof response['data'] != 'undefined' && response['data'].length != 0) {
		return true;
	} else {
		return false;
	}
}


/**
 * Return true if user is featured user
 * @param {String} [user] username we want to check
 * @return {Boolean} true or false
 */
wdi_controller.thumbUser = function(user) {
	return (jQuery('#wdi_thumb_user').val() == user) ? true : false;
}


/**
 * finds user by username in instagram api request object
 * if user is found then returns user object otherwise returns false
 *
 * @param  {String} username [username we are searching for]
 * @param  {Object} response [instagram API response]
 * @return {Boolenan || Object}
 */
wdi_controller.findUser = function(username, response) {
	var data = [];
	if (typeof response != 'undefined' && typeof response['data'] != 'undefined') {
		data = response['data'];
	}

	for (var i = 0; i < data.length; i++) {
		if (data[i]['username'] == username) {
			return data[i];
		}
	}
	return false;
}

/**
 * Sanitizes hashtag and if it's ok then add it to internal wdi_controller.feed_users array
 * besodes that it also updates GUI
 *
 * @param {String} tagname  [name of hashtag to add without '#']
 * @param {Object} response [instagram API response]
 */
wdi_controller.addHashtag = function(tagname, response) {
	//if tagname doesn't contain invalid characters
	if (tagname.match(/[~!@$%&*#^()<>?]/) == null) {
		if (this.checkForDuplicateUser('#' + tagname) == false) {
			var newHashtag = jQuery('<div class="wdi_user"><a target="_blank" href="https://instagram.com/explore/tags/' + tagname + '">' + '<img class="wdi_profile_pic" src="' + wdi_url.plugin_url + '/images/hashtag.png"><span class="wdi_hashtag">' + tagname + '</span><i style="display:table-cell;width:25px;"></i></a><img class="wdi_remove_user" onclick="wdi_controller.removeFeedUser(jQuery(this))" src="' + wdi_url.plugin_url + '/images/delete_user.png"></div>');
			jQuery('#wdi_feed_users').append(newHashtag);
			jQuery('#wdi_add_user_ajax_input').attr('value', '');
			var profile_picture;
			if (typeof response != 'undefined') {
				profile_picture = (response['data'].length != 0) ? response['data'][0]['images']['thumbnail']['url'] : '';
			} else {
				profile_picture = '';
			}

			this.feed_users.push({
				username: '#' + tagname,
				id: '#' + tagname,
				profile_picture: profile_picture
			});

			var user_input = '#' + tagname;
			selected = this.thumbUser(user_input) ? 'selected' : '';

			wdi_controller.updateFeaturedImageSelect(user_input, 'add', selected);
		} else {
			alert('#' + tagname + ' ' + wdi_messages.already_added);
		}
	} else {
		alert(wdi_messages.invalid_hashtag);
	}

	this.updateConditionalFiltersUi();

	wdi_controller.saveFeedAfterAjaxWait(true);

}


/**
 * Adds given user to internal array wdi_controller.feed_users and also updates GUI
 *
 * @param {Object} user [Object conatining user information such as id, username and profile picture]
 */
wdi_controller.addUser = function(user) {

	if (this.checkForDuplicateUser(user.username) == false) {
		newUser = jQuery('<div class="wdi_user"><a target="_blank" href="http://www.instagram.com/' + user.username + '">' + '<img class="wdi_profile_pic" src="' + user['profile_picture'] + '"><span class="wdi_username">' + user.username + '</span><i style="display:table-cell;width:25px;"></i></a><img class="wdi_remove_user" onclick="wdi_controller.removeFeedUser(jQuery(this))" src="' + wdi_url.plugin_url + '/images/delete_user.png"></div>');
		jQuery('#wdi_feed_users').append(newUser);
		jQuery('#wdi_add_user_ajax_input').attr('value', '');
		this.feed_users.push({
			username: user['username'],
			id: user['id'],
			profile_picture: user['profile_picture']
		});

	} else {
		alert(user.username + ' ' + wdi_messages.already_added);
	}

	//_this.updateHiddenField();
	selected = this.thumbUser(user.username) ? 'selected' : '';

	this.updateFeaturedImageSelect(user.username, 'add', selected);

	this.updateConditionalFiltersUi();

	wdi_controller.saveFeedAfterAjaxWait(true);
}


/**
 * Scans internal wdi_controller.feed_users array and return profile picture url of given user
 * if there is no profile picture then returns blank string
 *
 * @param  {String} username
 * @return {String}    ['profile picture url of user']
 */
wdi_controller.getUserProfilePic = function(username) {
	for (var i = 0; i < this.feed_users.length; i++) {
		if (username == this.feed_users[i]['username']) {
			return this.feed_users[i]['profile_picture'];
		}
	}
	return 'false';
}



/*-------------------------------------------------------------
----------------Conditional Filters Tab Methods----------------
-------------------------------------------------------------*/

/**
 * Initiailizes conditional filter tabs with variables and methods
 */
wdi_controller.conditionalFiltersTabInit = function() {
	//get data from textarea and display it
	this.setInitialFilters();
	this.updateFiltersUi();

	var _this = this;
	jQuery('#wdi_add_filter').on('click', function() {
		_this.addConditionalFilter();
		jQuery('#wdi_filter_input').val('');
	})


	jQuery('.wdi_filter_radio').on('click', function() {
		jQuery('#wdi_filter_input').trigger('focus');
	});

	jQuery('#wdi_filter_input').on('keypress', function(e) {
		if (e.keyCode == 13) {
			_this.addConditionalFilter();
			jQuery(this).val('');
			return false; // prevent the button click from happening
		}
	});





	conditional_filters_toggler();
	jQuery('#WDI_wrap_conditional_filter_enable input').on('change',function(){
		conditional_filters_toggler();
	})

	function conditional_filters_toggler(){
		switch(jQuery('#WDI_wrap_conditional_filter_enable input:checked').val()){
			case '0':{
				jQuery('#WDI_conditional_filters').parent().parent().addClass('wdi_hidden');
				jQuery('#WDI_conditional_filter_type').parent().parent().parent().parent().parent().addClass('wdi_hidden');
				jQuery('#wdi_final_condition').addClass('wdi_hidden');
				jQuery('#WDI_filter_source').addClass('wdi_hidden');
				break;
			}
			case '1':{
				jQuery('#WDI_conditional_filters').parent().parent().removeClass('wdi_hidden');
				jQuery('#WDI_conditional_filter_type').parent().parent().parent().parent().parent().removeClass('wdi_hidden');
				jQuery('#wdi_final_condition').removeClass('wdi_hidden');
				jQuery('#WDI_filter_source').removeClass('wdi_hidden');
				break;
			}
		}
	}

	jQuery('#WDI_conditional_filter_type').on('change',function(){
		if(jQuery(this).val() == 'none'){

		}else{
			jQuery('#WDI_conditional_filters').css('display','block');
		}

		jQuery(this).parent().find('label').css({
			'line-height' : '24px',
			'height' : '24px',
			'padding' : '2px 5px',
			'display' : 'inline-block',
			'font-size': '15px',
			'color': 'black',
			'font-weight': '500',
			'-webkit-user-select': 'none', /* Chrome/Safari */
			'-moz-user-select': 'none', /* Firefox */
			'-ms-user-select': 'none', /* IE10+ */

			/* Rules below not implemented in browsers yet */
			'-o-user-select': 'none',
			'user-select': 'none',
		});

		switch( jQuery(this).val()){
			case 'AND':{
				jQuery('#WDI_conditional_filters').css('display','block');
				jQuery(this).parent().find('label').html(wdi_messages.and_descr);
				break;
			}
			case 'OR':{
				jQuery('#WDI_conditional_filters').css('display','block');
				jQuery(this).parent().find('label').html(wdi_messages.or_descr);
				break;
			}
			case 'NOR':{
				jQuery('#WDI_conditional_filters').css('display','block');
				jQuery(this).parent().find('label').html(wdi_messages.nor_descr);
				break;
			}
		}

		wdi_controller.updateFiltersUi();
	});
	//triggering change for updating first time
	jQuery('#WDI_conditional_filter_type').trigger('change');

}

/**
 * Takes user input and adds new filter based on filter type and user input
 */
wdi_controller.addConditionalFilter = function() {
	var input = jQuery('#wdi_filter_input').val(),
		//filter_type = jQuery('input[name="wdi_filter_type"]:checked').val(),
		filter_type = jQuery('#wdi_filter_type').val(),
		filter = {};

	if( input == '' ) { return; }

	input = input.trim();

	switch(filter_type){
 		case 'username':{
 			if( input[0] == '@' ){
 				input = input.substr(1,input.length);
 			}
 			break;
 		}
 		case 'mention':{
 			if( input[0] == '@' ){
 				input = input.substr(1,input.length);
 			}
 			break;
 		}
 		case 'hashtag':{
 			if( input[0] == '#' ){
 				input = input.substr(1,input.length);
 			}
 			break;
 		}
 		case 'url':{
 			var urlRegex = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
 			if(!urlRegex.test(input)){
 				alert(wdi_messages.invalid_url);
 				return;
 			}
 			break;
 		}
 	}


	filter = {
		'filter_type': filter_type,
		'filter_by': input,
		'id': this.randomId()
	};

	if( filter_type != null ){
 		if( !this.filterExists(filter) ){
 			this.conditionalFilters.push(filter);
 			this.updateFiltersUi();
 		}else{
 			alert(input + ' ' + wdi_messages.already_added);
 		}
 	}else{
 		alert(wdi_messages.selectConditionType);
 	}

}

/**
 * Returns true if filter exists else returns false
 * @param  {Object} filter [Filter objecr]
 * @return {Booleans}        [true or false]
 */
wdi_controller.filterExists = function(filter) {
	for (var i = 0; i < this.conditionalFilters.length; i++) {
		if (this.conditionalFilters[i].filter_type == filter.filter_type && this.conditionalFilters[i].filter_by == filter.filter_by) {
			return true;
		}
	}
	return false;
}


/**
 * Updates #wdi_filters_ui div to the latest version of filters according wdi_controller.conditionalFilters
 */
wdi_controller.updateFiltersUi = function(){
	var uiElement = jQuery('#wdi_filters_ui').html('');
	for( var i = 0; i < this.conditionalFilters.length; i++ ){

		if( i == 0 ){
			if( this.conditionalFilters.length != 1 ){
				switch( jQuery('#WDI_conditional_filter_type').val() ){
					case 'AND':{

						break;
					}
					case 'OR':{
						uiElement.append(jQuery('<span class="wdi_logic">'+wdi_messages.either+'</span>'));
						break;
					}
					case 'NOR':{
						uiElement.append(jQuery('<span class="wdi_logic">'+wdi_messages.neither+'</span>'));
						break;
					}
				}
			}else{
				switch( jQuery('#WDI_conditional_filter_type').val() ){
					case 'AND':{
						break;
					}
					case 'OR':{
						break;
					}
					case 'NOR':{
						uiElement.append(jQuery('<span class="wdi_logic">'+wdi_messages.not+'</span>'));
						break;
					}
				}
			}

		}

		var glue;
		switch(jQuery('#WDI_conditional_filter_type').val()){
			case 'AND':{
				glue = wdi_messages.and;
				break;
			}
			case 'OR':{
				glue = wdi_messages.or;
				break;
			}
			case 'NOR': {
				glue = wdi_messages.nor;
				break;
			}
		}

		if( i>=1 ){
			uiElement.append(jQuery('<span class="wdi_logic">'+glue+'</span>'));
		}

		uiElement.append(this.createUiElement(this.conditionalFilters[i]));

	}
	this.updateFilterTextarea();
}

/**
 * Creates jQuery element for filter
 * @param  {Object} filter [filter object]
 * @return {Object}        [jQuery Object]
 */
wdi_controller.createUiElement = function(filter){
	var specialChar;
	switch(filter['filter_type']){
		case 'mention':{
			specialChar = '@';
			break;
		}
		case 'hashtag':{
			specialChar = '#';
			break;
		}
		case 'location':{
			specialChar = '%';
			break;
		}
		default:{
			specialChar = '';
			break;
		}
	}

	var filter_item = jQuery('<span data-id="'+filter['id']+'" class="wdi_filter_item wdi_filter_by_'+ filter['filter_type'] +'"></span>').
					html(specialChar + filter['filter_by'] + '<span onclick="wdi_controller.removeConditionalFilter(jQuery(this));" class="wdi_remove_filter">X</span>');
 	return filter_item;
 }

/**
 * Used for generating random ids
 * @return {String} [random 5 length string]
 */
wdi_controller.randomId = function() {
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	for (var i = 0; i < 5; i++)
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	return text;
}

/**
 * Removes filter from wdi_controller.conditionalFilters array and updates #wdi_filters_ui
 * @param  {Object} element [jQuery object]
 */
wdi_controller.removeConditionalFilter = function(element) {
	var id = element.parent().attr('data-id');
	for (var i = 0; i < this.conditionalFilters.length; i++) {
		if (this.conditionalFilters[i]['id'] == id) {
			this.conditionalFilters.splice(i, 1);
		}
	}
	this.updateFiltersUi();
}

/**
 * Updates textarea to the latest version of conditionalFilters json
 */
wdi_controller.updateFilterTextarea = function() {
	var json,
		filters = this.conditionalFilters;

	json = JSON.stringify(filters);
	jQuery('#wdi_conditional_filters_textarea').val(json);
}

/**
 * Gets json from textarea and sets them as conditionalfilters array
 */
wdi_controller.setInitialFilters = function() {
	var filters = [],
		json = jQuery('#wdi_conditional_filters_textarea').val();

	if (this.isJsonString(json)) {
		filters = JSON.parse(json);
	}

	this.conditionalFilters = filters;
}



/**
 * Updates Conditional Filter User interfaces
 */
wdi_controller.updateConditionalFiltersUi = function(){
	wdi_controller.updateFilterSource();
}

/**
 * Updates Conditinal filter source
 */
wdi_controller.updateFilterSource = function(){

	if(jQuery('input[name="wdi_feed_settings[liked_feed]"]:checked').val() == 'liked'){
		var sourceDiv = jQuery('#wdi_filter_source').html('');
		var singleUserHtml = "<div class='wdi_source_user'><span class='wdi_source_username'>Media I liked</span></div>";
		sourceDiv.html(sourceDiv.html() + singleUserHtml);
		return;
	}

	var users = [],
		username,
		userThumb;

	jQuery('.wdi_user').each(function(){
		if ( jQuery( this ).find( '.wdi_username' ).length != 0 ) {
			username = jQuery( this ).find( '.wdi_username' ).text();
		}else{
			username = jQuery( this ).find( '.wdi_hashtag' ).text();
		}
		userThumb = jQuery( this ).find( 'img' ).attr('src');
		users.push( {
			'username' : username,
			'image'    : userThumb
		} )
	});

	var sourceDiv = jQuery('#wdi_filter_source').html('');
	for ( var i = 0; i < users.length; i++ ){
		var singleUserHtml = "<div class='wdi_source_user'><span class='wdi_source_img'><img src='" + users[i].image + "'></span><span class='wdi_source_username'>"+users[i].username+"</span></div>";
		sourceDiv.html( sourceDiv.html() + singleUserHtml );
	}

}


/**
 * Checks if given string is JSON string
 * @param  {String}  str [string to check]
 * @return {Boolean}     [true or false]
 */
wdi_controller.isJsonString = function(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}


///////////////////////////////////////////////////////////////////////////////
///////////////Feeds and themes first view functions///////////////////////////
////////////////////////////////////////////////////////////////////////////////


function wdi_spider_select_value(obj) {
	obj.focus();
	obj.select();
}

// Set value by id.
function wdi_spider_set_input_value(input_id, input_value) {
	if (input_value === 'add') {
		if (jQuery('#wdi_access_token').attr('value') == '') {
			alert('Please get your access token');
		}
	}
	if (document.getElementById(input_id)) {
		document.getElementById(input_id).value = input_value;
	}
}

// Submit form by id.
function wdi_spider_form_submit(event, form_id) {
	if (document.getElementById(form_id)) {
		document.getElementById(form_id).submit();
	}
	if (event.preventDefault) {
		event.preventDefault();
	} else {
		event.returnValue = false;
	}
}



// Check all items.
function wdi_spider_check_all_items() {
	wdi_spider_check_all_items_checkbox();
	// if (!jQuery('#check_all').attr('checked')) {
	jQuery('#check_all').trigger('click');
	// }
}

function wdi_spider_check_all_items_checkbox() {
	if (jQuery('#check_all_items').attr('checked')) {
		jQuery('#check_all_items').attr('checked', false);
		jQuery('#draganddrop').hide();
	} else {
		var saved_items = (parseInt(jQuery(".displaying-num").html()) ? parseInt(jQuery(".displaying-num").html()) : 0);
		var added_items = (jQuery('input[id^="check_pr_"]').length ? parseInt(jQuery('input[id^="check_pr_"]').length) : 0);
		var items_count = added_items + saved_items;
		jQuery('#check_all_items').attr('checked', true);
		if (items_count) {
			jQuery('#draganddrop').html("<strong><p>Selected " + items_count + " item" + (items_count > 1 ? "s" : "") + ".</p></strong>");
			jQuery('#draganddrop').show();
		}
	}
}

function wdi_spider_check_all(current) {
	if (!jQuery(current).attr('checked')) {
		jQuery('#check_all_items').attr('checked', false);
		jQuery('#draganddrop').hide();
	}
}

// Set value by id.
function wdi_spider_set_input_value(input_id, input_value) {
	if (input_value === 'add') {
		if (jQuery('#wdi_access_token').attr('value') == '') {
			alert('Please get your access token');
		}
	}
	if (document.getElementById(input_id)) {
		document.getElementById(input_id).value = input_value;
	}
}