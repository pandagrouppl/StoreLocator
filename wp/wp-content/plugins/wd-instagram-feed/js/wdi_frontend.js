if (typeof wdi_front == 'undefined') {
  wdi_front = {
    type: 'not_declared'
  };
}


wdi_front.detectEvent = function ()
{
  var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
  if (isMobile) {
    return "touchend";
  } else {
    return 'click';
  }
}

wdi_front.show_alert = function (message)
{
  if (wdi_front_messages.show_alerts) {
    alert(message);
  }
  else {
    console.log('%c' + message, "color:#cc0000;");
  }
}


wdi_front.globalInit = function ()
{

  var num = wdi_front['feed_counter'];

  if (typeof wdi_ajax.ajax_response != "undefined") {
    var init_feed_counter = wdi_feed_counter_init.wdi_feed_counter_init;
  }
  else {
    var init_feed_counter = 0;
  }
  for (var i = init_feed_counter; i <= num; i++) {

    var currentFeed = new WDIFeed(window['wdi_feed_' + i]);

    /*initializing instagram object which will handle all instagram api requests*/
    currentFeed.instagram = new WDIInstagram();

    /**
     * this object will be passed to filtering function of currentFeed.instagram as second parameter
     * @type {Object}
     */
    currentFeed.instagram.filterArguments = {
      feed: currentFeed
    };

    currentFeed.instagram.filters = [
      {
        'where': 'getUserRecentMedia',
        'what': function (response, args, cArgs)
        {
          return args.feed.conditionalFilter(response, cArgs);
        }
      },
      {
        'where': 'getTagRecentMedia',
        'what': function (response, args, cArgs)
        {
          return args.feed.conditionalFilter(response, cArgs);
        }
      },
      {
        'where': 'getRecentLikedMedia',
        'what': function (response, args, cArgs)
        {
          return args.feed.conditionalFilter(response, cArgs);
        }
      },


      {
        'where': 'requestByUrl',
        'what': function (response, args, cArgs)
        {
          return args.feed.conditionalFilter(response, cArgs);
        }
      },];


    currentFeed.instagram.addToken(currentFeed['feed_row']['access_token']);

    wdi_front.access_token = currentFeed['feed_row']['access_token'];

    currentFeed.dataStorageRaw = []; //stores all getted data from instagram api

    currentFeed.dataStorage = []; //stores all avialable data
    currentFeed.dataStorageList = []; //?
    currentFeed.allResponseLength = 0; //?
    //number of instagram objects which has been got by single request
    currentFeed.currentResponseLength = 0;

    //temprorary usersData which is uses in case when getted data is smaller then needed
    currentFeed.temproraryUsersData = [];

    currentFeed.removedUsers = 0;
    /*flag for indicating that not all images are loaded yet*/
    currentFeed.nowLoadingImages = true;
    currentFeed.imageIndex = 0; //index for image indexes
    currentFeed.resIndex = 0; //responsive indexes used for pagination
    currentFeed.currentPage = 1; //pagination page number
    currentFeed.userSortFlags = []; //array for descripbing user based filter options
    currentFeed.customFilterChanged = false; //flag to notice filter change, onclick on username


    /**
     * This variable describes after how many requests program will stop searching for content
     * this number is very important and should not be set too high, because when feed has conditional filter
     * and filtered items are rare then the program will recursively request new photos and will filter them
     * if no image was fount it will go into infinite loop if feed images are "infinite" ( very huge number )
     * and if requests count in 1 hour exeed 5000 instagram will block access token for one hour
     *
     * @type {Number}
     */
    currentFeed.maxConditionalFiltersRequestCount = 10;

    /**
     * This variable shows us how many times program has been recursively called,
     * it changes it value within filtering function, and resets itself to 0 when feed is being displayed
     *
     * @type {Number}
     */
    currentFeed.instagramRequestCounter = 0;

    /**
     * This array stores data from each request,
     * it is used to determine and remove duplicate photos caused by multiple hashtags
     * it is resetted to its inital [] value after displaying feed
     *
     * @type {Array}
     */
    currentFeed.conditionalFilterBuffer = [];


    currentFeed.stopInfiniteScrollFlag = false;

    if (currentFeed.feed_row.feed_type == 'masonry') {
      currentFeed.displayedData = [];
    }


    //if pagination is on then set pagination parameters
    if (currentFeed.feed_row.feed_display_view == 'pagination') {
      currentFeed.feed_row.resort_after_load_more = 0;
      if (currentFeed.feed_row.feed_type != 'image_browser') {
        currentFeed.feed_row.load_more_number = parseInt(currentFeed.feed_row.pagination_per_page_number);
        currentFeed.feed_row.number_of_photos = (1 + parseInt(currentFeed.feed_row.pagination_preload_number)) * currentFeed.feed_row.load_more_number;
      } else {
        currentFeed.feed_row.number_of_photos = 1 + parseInt(currentFeed.feed_row.image_browser_preload_number);
        currentFeed.feed_row.load_more_number = parseInt(currentFeed.feed_row.image_browser_load_number);
      }


      currentFeed.freeSpaces = (Math.floor(currentFeed.feed_row.pagination_per_page_number / currentFeed.feed_row.number_of_columns) + 1) * currentFeed.feed_row.number_of_columns - currentFeed.feed_row.pagination_per_page_number;
    } else {
      currentFeed.freeSpaces = 0;
    }


    //initializing function for lightbox
    currentFeed.galleryBox = function (image_id)
    {
      wdi_spider_createpopup(wdi_url.ajax_url + '?gallery_id=' + this.feed_row['id'] + '&image_id=' + image_id, wdi_front.feed_counter, this.feed_row['lightbox_width'], this.feed_row['lightbox_height'], 1, 'testpopup', 5, this);
    }
    //calling responive javascript
    wdi_responsive.columnControl(currentFeed);

    //if feed type is masonry then trigger resize event  for building proper column layout
    if (currentFeed.feed_row.feed_type == 'masonry') {
      jQuery(window).trigger('resize');
    }

    wdi_front.bindEvents(currentFeed);

    window['wdi_feed_' + i] = currentFeed;


    //initializing each feed
    wdi_front.init(currentFeed);
  } //endfor



}

wdi_front.init = function (currentFeed)
{

  jQuery('.wdi_js_error').remove();
  //some varables used in code
  currentFeed.photoCounter = currentFeed.feed_row["number_of_photos"];


  if (currentFeed.feed_row.liked_feed == 'liked') {
    currentFeed.feed_users = ['self'];
    // do nothing,
  }
  else
    if (wdi_front.isJsonString(currentFeed.feed_row.feed_users)) {
      /**
       * Contains username and user_id of each user
       * @type {[Array}
       */
      currentFeed.feed_users = JSON.parse(currentFeed.feed_row.feed_users);

      /**
       * Check if feed user has no id for some reason then update user
       * and after updating them initialize feed
       */
      if (wdi_front.updateUsersIfNecessary(currentFeed)) {
        return;
      }
      ;


    } else {
      wdi_front.show_alert(wdi_front_messages.invalid_users_format);
      return;
    }


  //wdi_front.loadInstagramMedia( currentFeed, currentFeed.feed_row.number_of_photos);

  currentFeed.dataCount = currentFeed.feed_users.length;  //1 in case of self feed


  for (var i = 0; i < currentFeed.dataCount; i++) {
    wdi_front.instagramRequest(i, currentFeed);
  }


  if (currentFeed.feed_row["number_of_photos"] > 0) {
    wdi_front.ajaxLoader(currentFeed);
  }


  //setting feed name
  if (currentFeed['feed_row']['display_header'] === '1') {
    wdi_front.show('header', currentFeed);
  }
  if (currentFeed['feed_row']['show_usernames'] === '1') {
    wdi_front.show('users', currentFeed);
  }


}


/**
 * Checks if given string is JSON string
 * @param  {String}  str [string to check]
 * @return {Boolean}     [true or false]
 */
wdi_front.isJsonString = function (str)
{
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}


/**
 * Makes an ajax request for given user from feed_users array
 * if response is ok then calls saveUserData function
 * if liked media to show, feed user is self
 * @param  {Number} id          [index of user in current_feed.feed_users array]
 * @param  {Object} currentFeed
 */
wdi_front.instagramRequest = function (id, currentFeed)
{

  var feed_users = currentFeed.feed_users,
    _this = this;
  if (typeof feed_users[id] === 'string' && feed_users[id] === 'self') { // self liked media
    currentFeed.instagram.getRecentLikedMedia({
      success: function (response)
      {
        response = _this.checkMediaResponse(response);
        if (response != false) {
          _this.saveSelfUserData(response, currentFeed);
        }
      }
    });
  }
  else
    if (this.getInputType(feed_users[id]['username']) == 'hashtag') {
      currentFeed.instagram.getTagRecentMedia(this.stripHashtag(feed_users[id]['username']), {
        success: function (response)
        {
          response = _this.checkMediaResponse(response);
          if (response != false) {
            _this.saveUserData(response, currentFeed.feed_users[id], currentFeed);
          }
        }
      });
    }
    else
      if (this.getInputType(feed_users[id]['username']) == 'user') {
        currentFeed.instagram.getUserRecentMedia(feed_users[id]['id'], {
          success: function (response)
          {
            response = _this.checkMediaResponse(response);
            if (response != false) {
              _this.saveUserData(response, currentFeed.feed_users[id], currentFeed);
            }
          }
        });
      }

}

/**
 * Returns true is given string starts with dash ( # )
 * @param  {String}  str
 * @return {Boolean}     [true or false]
 */
wdi_front.isHashtag = function (str)
{
  return (str[0] === '#');
}


/*
 * Saves each user data on seperate index in currentFeed.usersData array
 * And also checks if all data form all users is already avialable if yes it displays feed
 */
wdi_front.saveUserData = function (data, user, currentFeed)
{

  data['username'] = user.username;
  data['user_id'] = user.id;

  //checking if user type is hashtag then manually add hashtag to each object, for later use
  //hashtag based filters
  if (data['user_id'][0] === '#') {
    data['data'] = wdi_front.appendRequestHashtag(data['data'], data['user_id']);
  }


  currentFeed.usersData.push(data);


  currentFeed.currentResponseLength = wdi_front.getArrayContentLength(currentFeed.usersData, 'data');
  currentFeed.allResponseLength += currentFeed.currentResponseLength;


  if (currentFeed.dataCount == currentFeed.usersData.length) {

    //if getted objects is not enough then recuest new ones
    if (currentFeed.currentResponseLength < currentFeed.feed_row.number_of_photos && !wdi_front.userHasNoPhoto(currentFeed)) {
      //console.log('initial recursion');
      /*here we are calling loadMore function out of recursion cycle, after this initial-keep call
       loadMore will be called with 'initial' recursively until the desired number of photos is reached
       if possible*/

      wdi_front.loadMore('initial-keep', currentFeed);
    } else {


      //display feed
      wdi_front.displayFeed(currentFeed);
      //when all data us properly displayed check for any active filters and then apply them
      wdi_front.applyFilters(currentFeed);


      /*removing load more button of feed has finished*/
      if (!wdi_front.activeUsersCount(currentFeed)) {
        if (currentFeed.feed_row.feed_display_view == 'load_more_btn') {
          var feed_container = jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter);
          feed_container.find('.wdi_load_more').addClass('wdi_hidden');
          feed_container.find('.wdi_spinner').addClass('wdi_hidden');
        }
      }
      ;

    }


  }

}


/*
 * Saves self user data on separate index in currentFeed.usersData array
 * And also checks if all data form all users is already avialable if yes it displays feed
 */
wdi_front.saveSelfUserData = function (data, currentFeed)
{

  //keep empty for self feed
  data['username'] = '';
  data['user_id'] = '';

  currentFeed.usersData.push(data);


  currentFeed.currentResponseLength = wdi_front.getArrayContentLength(currentFeed.usersData, 'data');
  currentFeed.allResponseLength += currentFeed.currentResponseLength;


  if (currentFeed.dataCount == currentFeed.usersData.length) {

    //if retrieved objects are not enough then request new ones

    if (currentFeed.currentResponseLength < currentFeed.feed_row.number_of_photos && !wdi_front.userHasNoPhoto(currentFeed)) {
      //console.log('initial recursion');
      /*here we are calling loadMore function out of recursion cycle, after this initial-keep call
       loadMore will be called with 'initial' recursively until the desired number of photos is reached
       if possible*/

      wdi_front.loadMore('initial-keep', currentFeed);
    } else {


      //display feed
      wdi_front.displayFeed(currentFeed);
      //when all data us properly displayed check for any active filters and then apply them
      wdi_front.applyFilters(currentFeed);


      /*removing load more button of feed has finished*/
      if (!wdi_front.activeUsersCount(currentFeed)) {
        if (currentFeed.feed_row.feed_display_view == 'load_more_btn') {
          var feed_container = jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter);
          feed_container.find('.wdi_load_more').addClass('wdi_hidden');
          feed_container.find('.wdi_spinner').addClass('wdi_hidden');
        }
      }
      ;

    }


  }

}

/**
 * checks weather all feed users have any photos after first time request
 */
wdi_front.userHasNoPhoto = function (currentFeed, cstData)
{

  var counter = 0;
  var data = currentFeed.usersData;
  if (typeof cstData != 'undefined') {
    data = cstData;
  }
  for (var i = 0; i < data.length; i++) {
    if (currentFeed.feed_row.liked_feed === 'liked') {
      if (typeof data[i]['pagination']['next_max_like_id'] == 'undefined') {
        counter++
      }
    }
    else {
      if (typeof data[i]['pagination']['next_max_id'] == 'undefined') {
        counter++
      }
    }

  }
  if (counter == data.length) {
    return 1;
  } else {
    return 0;
  }
}

/*
 *gives each instagram object custom hashtag parameter, which is used for searching image/video
 */
wdi_front.appendRequestHashtag = function (data, hashtag)
{
  for (var i = 0; i < data.length; i++) {
    data[i]['wdi_hashtag'] = hashtag;
  }
  return data;
}


/*
 * sorts data based on user choice and displays feed
 * also checks if one request is not enough for displaying all images user wanted
 * it recursively calls wdi_front.loadMore() until the desired number of photos is reached
 */
wdi_front.displayFeed = function (currentFeed, load_more_number)
{


  if (currentFeed.customFilterChanged == false) {
    //sorting data...
    var data = wdi_front.feedSort(currentFeed, load_more_number);
  }


  //becomes true when user clicks in frontend filter
  //if isset to true then loadmore recursion would not start
  var frontendCustomFilterClicked = currentFeed.customFilterChanged;


  // if custom filter changed then display custom data
  if (currentFeed.customFilterChanged == true) {
    var data = currentFeed.customFilteredData;

    //parsing data for lightbox
    currentFeed.parsedData = wdi_front.parseLighboxData(currentFeed, true);
  }


  //storing all sorted data in array for later use in user based filters
  if (currentFeed.feed_row.resort_after_load_more != '1') {
    // filter changes when user clicks to usernames in header
    // at that point displayFeed triggers but we don't have any new data so
    // we are not adding new data to our list
    if (currentFeed.customFilterChanged == false) {
      currentFeed.dataStorageList = currentFeed.dataStorageList.concat(data);
    }
  } else {
    // filter changes when user clicks to usernames in header
    // at that point displayFeed triggers but we don't have any new data so
    // we are not adding new data to our list
    if (currentFeed.customFilterChanged == false) {
      currentFeed.dataStorageList = data;
    }
  }

  //checking feed_type and calling proper rendering functions
  if (currentFeed.feed_row.feed_type == 'masonry') {
    wdi_front.masonryDisplayFeedItems(data, currentFeed);
  }
  if (currentFeed.feed_row.feed_type == 'thumbnails' || currentFeed.feed_row.feed_type == 'blog_style' || currentFeed.feed_row.feed_type == 'image_browser') {
    wdi_front.displayFeedItems(data, currentFeed);
  }


  //recursively calling load more to get photos
  var dataLength = wdi_front.getDataLength(currentFeed);


  if (dataLength < currentFeed.photoCounter && !frontendCustomFilterClicked && currentFeed.instagramRequestCounter <= currentFeed.maxConditionalFiltersRequestCount && !wdi_front.allDataHasFinished(currentFeed)) {
    wdi_front.loadMore('', currentFeed);

  } else {
    wdi_front.allImagesLoaded(currentFeed);
  }


  /**
   * if maximum number of requests are reached then stop laoding more images and show images which are available
   * @param  {Number} currentFeed.instagramRequestCounter >             currentFeed.maxConditionalFiltersRequestCount [description]
   * @return {Boolean}
   */
  if (currentFeed.instagramRequestCounter > currentFeed.maxConditionalFiltersRequestCount) {
    wdi_front.allImagesLoaded(currentFeed);

    //if no data was received then
    if (data.length == 0) {
      //if feed_display_view is set to infinite scroll then after reaching the limit once set this flag to false
      //this will stop infinite scrolling and will not load any images even when scrolling
      currentFeed.stopInfiniteScrollFlag = true;
    }
  }

  //checking if display_view is pagination and we are not on the last page then enable
  //last page button
  if (currentFeed.feed_row.feed_display_view == 'pagination' && currentFeed.currentPage < currentFeed.paginator) {
    jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter).find('#wdi_last_page').removeClass('wdi_disabled');
  }


  // reset instagram request counter to zero for next set of requests
  currentFeed.instagramRequestCounter = 0;

  //reset conditional filter buffer for the next bunch of requests
  currentFeed.conditionalFilterBuffer = [];

  //if there are any missing images in header then replace them with new ones if possible
  wdi_front.updateUsersImages(currentFeed);


  // /**
  //  * Enable image lazy laoding if pagination is not enabeled because pagination has option for preloading images
  //  * which is the opposide of lazy load
  //  */
  // if( currentFeed.feed_row.feed_display_view != 'pagination' ){
  // 	jQuery(function() {
  // 		jQuery('img.wdi_img').lazyload({
  // 			skip_invisible : false,
  // 			threshold : 400
  // 		});
  // 	});

  // }

}

/**
 * checks if user images in header have empty source or source is missing.png then if it is available data
 * then update source
 * @param  {Object} currentFeed [description]
 */
wdi_front.updateUsersImages = function (currentFeed)
{
  var elements = jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter).find('.wdi_single_user .wdi_user_img_wrap img');
  elements.each(function ()
  {
    if (jQuery(this).attr('src') == wdi_url.plugin_url + '../images/missing.png' || jQuery(this).attr('src') == '') {
      //console.log('missing');

      if (currentFeed.feed_row.liked_feed == 'liked') {
        return;
      }


      for (var j = 0; j < currentFeed.usersData.length; j++) {
        if (currentFeed.usersData[j]['username'] == jQuery(this).parent().parent().find('h3').text()) {
          if (currentFeed.usersData[j]['data'].length != 0) {
            jQuery(this).attr('src', currentFeed.usersData[j]['data'][0]['images']['thumbnail']['url']);
          }
        }
      }
    }
  });
}


/**
 * Displays data in masonry layout
 * @param  {Object} data        data to be displayed
 * @param  {Object} currentFeed
 */
wdi_front.masonryDisplayFeedItems = function (data, currentFeed)
{
  var masonryColEnds = [];
  var masonryColumns = [];
  if (jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter + " .wdi_feed_wrapper").length == 0) {
    //no feed in DOM, ignore
    return;
  }
  jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' .wdi_masonry_column').each(function ()
  {

    //if resorte after load more is on then reset columns on every load more
    if (currentFeed.feed_row.resort_after_load_more == 1) {
      jQuery(this).html('');
      currentFeed.imageIndex = 0;
    }

    //if custom filter is set or changed then reset masonry columns
    if (currentFeed.customFilterChanged == true) {
      jQuery(this).html('');
      currentFeed.imageIndex = 0;
    }

    //check if pagination is enabled then each page should have resetted colEnds
    //else give previous colEnds
    if (currentFeed.feed_row.feed_display_view == 'pagination') {
      masonryColEnds.push(0);
    } else {
      masonryColEnds.push(jQuery(this).height());
    }

    masonryColumns.push(jQuery(this));
  });

  //if custom filter is set or changed then reset masonry columns
  if (currentFeed.customFilterChanged == true) {
    currentFeed.customFilterChanged = false;
  }


  //loop for displaying items
  for (var i = 0; i < data.length; i++) {

    currentFeed.displayedData.push(data[i]);
    /*carousel feature*/
    if (data[i]['type'] == 'image') {
      var photoTemplate = wdi_front.getPhotoTemplate(currentFeed);
    } else if(data[i].hasOwnProperty('videos')) {
      var photoTemplate = wdi_front.getVideoTemplate(currentFeed);
    }
    else{
      var photoTemplate = wdi_front.getSliderTemplate(currentFeed);
    }

    var rawItem = data[i];
    var item = wdi_front.createObject(rawItem, currentFeed);
    var html = photoTemplate(item);

    //find column with minumum height and append to it new object
    var shortCol = wdi_front.array_min(masonryColEnds);
    var imageResolution = wdi_front.getImageResolution(data[i]);

    masonryColumns[shortCol['index']].html(masonryColumns[shortCol['index']].html() + html);
    masonryColEnds[shortCol['index']] += masonryColumns[shortCol['index']].width() * imageResolution;
    currentFeed.imageIndex++;


    //changing responsive indexes for pagination
    if (currentFeed.feed_row.feed_display_view == 'pagination') {
      if ((i + 1) % currentFeed.feed_row.pagination_per_page_number === 0) {
        currentFeed.resIndex += currentFeed.freeSpaces + 1;
      } else {
        currentFeed.resIndex++;
      }
    }
  }


  //binding onload event for ajax loader
  currentFeed.wdi_loadedImages = 0;
  var columnFlag = false;
  currentFeed.wdi_load_count = i;
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var feed_wrapper = jQuery('#wdi_feed_' + wdi_feed_counter + ' img.wdi_img').on('load', function ()
  {
    currentFeed.wdi_loadedImages++;
    checkLoaded();

    //calls wdi_responsive.columnControl() which calculates column number on page
    //and gives feed_wrapper proper column class
    if (columnFlag === false) {
      wdi_responsive.columnControl(currentFeed, 1);
      columnFlag = true;
    }

    //Binds caption opening and closing event to each image photo_title/mmmmmm
    // if (currentFeed.feed_row.feed_type != 'blog_style') {
    // 	wdi_responsive.bindMasonryCaptionEvent(jQuery(this).parent().parent().parent().parent().find('.wdi_photo_title'), currentFeed);
    // }

  });


  /**
   * if feed type is not blog style then after displaying images assign click evetns to their captions
   * this part of code is a bit differenet from free version because of image lazy loading feature
   *
   * in free version events are assigned directly in onload event, but when lazy loading added it cased duplicate event fireing
   * so event assigning moved to here
   *
   */
  // if ( currentFeed.feed_row.feed_type != 'blog_style' ){
  // 	jQuery('#wdi_feed_'+currentFeed.feed_row.wdi_feed_counter+' .wdi_photo_title').each(function(){
  // 		wdi_responsive.bindMasonryCaptionEvent(jQuery(this),currentFeed);
  // 	});
  // }


  //checks if all iamges have been succesfully loaded then it updates variables for next time use
  function checkLoaded()
  {

    if (currentFeed.wdi_load_count === currentFeed.wdi_loadedImages && currentFeed.wdi_loadedImages != 0) {
      currentFeed.loadedImages = 0;
      currentFeed.wdi_load_count = 0;
      wdi_front.allImagesLoaded(currentFeed);

    }
  }

  //checking if pagination next button was clicked then change page
  if (currentFeed.paginatorNextFlag == true) {
    wdi_front.updatePagination(currentFeed, 'next');
  }

  //check if load more done successfully then set infinite scroll flag to false
  currentFeed.infiniteScrollFlag = false;


}


/*
 * Calcuates image resolution
 */
wdi_front.getImageResolution = function (data)
{

  var originalWidth = data['images']['standard_resolution']['width'];
  var originalHeight = data['images']['standard_resolution']['height'];
  var resolution = originalHeight / originalWidth;
  return resolution;
}

/*
 * Calculates data count on global Storage and if custom storage provied
 * it adds custom storage data count to golbals data count and returns length of all storages
 */
wdi_front.getDataLength = function (currentFeed, customStorage)
{

  var length = 0;
  if (typeof customStorage === 'undefined') {
    for (var j = 0; j < currentFeed.dataStorage.length; j++) {
      length += currentFeed.dataStorage[j].length;
    }
  } else {
    for (var j = 0; j < customStorage.length; j++) {
      length += customStorage[j].length;
    }
  }

  return length;
}

wdi_front.getArrayContentLength = function (array, data)
{
  var sum = 0;
  for (var i = 0; i < array.length; i++) {
    if (array[i]['finished'] == 'finished') {
      continue;
    }
    sum += array[i][data].length;
  }
  return sum;
}


/**
 * Displays data in thumbnail layout
 * @param  {Object} data        data to be displayed
 * @param  {Object} currentFeed
 */
wdi_front.displayFeedItems = function (data, currentFeed)
{
  if (jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter + " .wdi_feed_wrapper").length == 0) {
    //no feed in DOM, ignore
    return;
  }

  //gets ready data, gets data template, and appens it into feed_wrapper
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var feed_wrapper = jQuery('#wdi_feed_' + wdi_feed_counter + ' .wdi_feed_wrapper');

  //if resort_after_pagination is on then rewrite feed data
  if (currentFeed.feed_row['resort_after_load_more'] === '1') {
    feed_wrapper.html('');
    currentFeed.imageIndex = 0;
  }

  //if custom filter is set or changed then reset masonry columns
  if (currentFeed.customFilterChanged == true) {
    feed_wrapper.html('');
    currentFeed.imageIndex = 0;
    currentFeed.customFilterChanged = false;
  }


  var lastIndex = wdi_front.getImgCount(currentFeed) - data.length - 1;

  /**
   * if feed display view is set to pagination then check if the current page has not enough photos to be a complete page then
   * --currentPage so that after loading new images we stay on the same page and see new images which will be located in that page
   * also do the same thing when recievied data has lenght equal to zero
   */
  if (currentFeed.feed_row.feed_display_view == 'pagination') {
    if (jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter + ' [wdi_page="' + (currentFeed.currentPage - 1) + '"]').length < currentFeed.feed_row.load_more_number || data.length == 0) {
      currentFeed.currentPage = (--currentFeed.currentPage <= 1) ? 1 : currentFeed.currentPage;
    }
  }


  for (var i = 0; i < data.length; i++) {

    if (data[i]['type'] == 'image') {
      var photoTemplate = wdi_front.getPhotoTemplate(currentFeed);
    } else if(data[i].hasOwnProperty('videos')) {
      var photoTemplate = wdi_front.getVideoTemplate(currentFeed);
    }
    else{
      var photoTemplate = wdi_front.getSliderTemplate(currentFeed);
    }

    var rawItem = data[i];
    var item = wdi_front.createObject(rawItem, currentFeed);
    var html = photoTemplate(item);
    feed_wrapper.html(feed_wrapper.html() + html);

    currentFeed.imageIndex++;


    //changing responsive indexes for pagination
    if (currentFeed.feed_row.feed_display_view == 'pagination') {
      if ((i + 1) % currentFeed.feed_row.pagination_per_page_number === 0) {
        currentFeed.resIndex += currentFeed.freeSpaces + 1;
      } else {
        currentFeed.resIndex++;
      }

    }

  }


  //fixing last row in case of full caption is open
  //for that triggering click twice to open and close caption text that will fix last row
  /*ttt 1.1.12*/
  //jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' .wdi_feed_wrapper [wdi_index=' + lastIndex + '] .wdi_photo_title').trigger(wdi_front.clickOrTouch);
  //jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' .wdi_feed_wrapper [wdi_index=' + lastIndex + '] .wdi_photo_title').trigger(wdi_front.clickOrTouch);


  //binding onload event for ajax loader
  currentFeed.wdi_loadedImages = 0;
  var columnFlag = false;
  currentFeed.wdi_load_count = i;
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var feed_wrapper = jQuery('#wdi_feed_' + wdi_feed_counter + ' img.wdi_img').on('load', function ()
  {
    currentFeed.wdi_loadedImages++;
    checkLoaded();

    //calls wdi_responsive.columnControl() which calculates column number on page
    //and gives feed_wrapper proper column class
    if (columnFlag === false) {


      wdi_responsive.columnControl(currentFeed, 1);
      columnFlag = true;
    }


    // //Binds caption opening and closing event to each image photo_title/mmmmmm
    // if (currentFeed.feed_row.feed_type != 'blog_style') {
    // 	wdi_responsive.bindCaptionEvent(jQuery(this).parent().parent().parent().parent().find('.wdi_photo_title'), currentFeed);
    // }
  });


  /**
   * if feed type is not blog style then after displaying images assign click evetns to their captions
   * this part of code is a bit differenet from free version because of image lazy loading feature
   *
   * in free version events are assigned directly in onload event, but when lazy loading added it cased duplicate event fireing
   * so event assigning moved to here
   *
   */
  // if ( currentFeed.feed_row.feed_type != 'blog_style' ){
  // 	jQuery('#wdi_feed_'+currentFeed.feed_row.wdi_feed_counter+' .wdi_photo_title').each(function(){
  // 		wdi_responsive.bindCaptionEvent(jQuery(this),currentFeed);
  // 	});

  // }


  //checks if all iamges have been succesfully loaded then it updates variables for next time use
  function checkLoaded()
  {
    if (currentFeed.wdi_load_count === currentFeed.wdi_loadedImages && currentFeed.wdi_loadedImages != 0) {
      currentFeed.loadedImages = 0;
      currentFeed.wdi_load_count = 0;
      wdi_front.allImagesLoaded(currentFeed);

    }
  }

  //checking if pagination next button was clicked then change page
  if (currentFeed.paginatorNextFlag == true) {
    wdi_front.updatePagination(currentFeed, 'next');
  }

  //check if load more done successfully then set infinite scroll flag to false
  currentFeed.infiniteScrollFlag = false;

}

wdi_front.checkFeedFinished = function (currentFeed)
{
  for (var i = 0; i < currentFeed.usersData.length; i++) {
    if (typeof currentFeed.usersData[i]['finished'] == 'undefined') {
      return false;
    }
  }
  return true;
}

wdi_front.sortingOperator = function (sortImagesBy, sortOrder)
{
  var operator;
  switch (sortImagesBy) {
    case 'date':
    {
      switch (sortOrder) {
        case 'asc':
        {
          operator = function (a, b)
          {
            return (a['created_time'] > b['created_time']) ? 1 : -1;
          }
          break;
        }
        case 'desc':
        {
          operator = function (a, b)
          {
            return (a['created_time'] > b['created_time']) ? -1 : 1;
          }
          break;
        }
      }
      break;
    }
    case 'likes':
    {
      switch (sortOrder) {
        case 'asc':
        {
          operator = function (a, b)
          {
            return (a['likes']['count'] < b['likes']['count']) ? -1 : 1;
          }
          break;
        }
        case 'desc':
        {
          operator = function (a, b)
          {
            return (a['likes']['count'] < b['likes']['count']) ? 1 : -1;
          }
          break;
        }
      }
      break;
    }
    case 'comments':
    {
      switch (sortOrder) {
        case 'asc':
        {
          operator = function (a, b)
          {
            return (a['comments']['count'] < b['comments']['count']) ? -1 : 1;
          }
          break;
        }
        case 'desc':
        {
          operator = function (a, b)
          {
            return (a['comments']['count'] < b['comments']['count']) ? 1 : -1;
          }
          break;
        }
      }
      break;
    }
    case 'random':
    {
      operator = function (a, b)
      {
        var num = Math.random();
        return (num > 0.5) ? 1 : -1;
      }
      break;
    }
  }
  return operator;
}

/*
 * Calls smart picker method and then after receiving data it sorts data based on user choice
 */
wdi_front.feedSort = function (currentFeed, load_more_number)
{

  var sortImagesBy = currentFeed.feed_row['sort_images_by'];
  var sortOrder = currentFeed.feed_row['display_order'];

  if (currentFeed.feed_row['resort_after_load_more'] === '1') {
    currentFeed['data'] = currentFeed['data'].concat(wdi_front.smartPicker(currentFeed, load_more_number));
  } else {
    currentFeed['data'] = wdi_front.smartPicker(currentFeed, load_more_number);
  }


  var operator = wdi_front.sortingOperator(sortImagesBy, sortOrder);
  currentFeed['data'].sort(operator);
  return currentFeed['data'];

}

/*
 * Filters all requested data and takes some amount of data for each user
 * and stops picking when it reaches number_of_photos limit
 */
wdi_front.smartPicker = function (currentFeed, load_more_number)
{

  var dataStorage = [];
  var dataLength = 0;
  var readyData = [];
  var perUser = Math.ceil(currentFeed['feed_row']['number_of_photos'] / currentFeed['usersData'].length);
  var number_of_photos = parseInt(currentFeed['feed_row']['number_of_photos']);
  var remainder = 0;

  //check if loadmore was clicked
  if (load_more_number != '' && typeof load_more_number != 'undefined' && load_more_number != null) {
    number_of_photos = parseInt(load_more_number);
    perUser = Math.ceil(number_of_photos / wdi_front.activeUsersCount(currentFeed));
  }


  var sortOperator = function (a, b)
  {
    return (a['data'].length > b['data'].length) ? 1 : -1;
  }

  var sortOperator1 = function (a, b)
  {
    return (a.length() > b.length()) ? 1 : -1;
  }


  // storing user data in global dataStoreageRaw variable
  currentFeed.storeRawData(currentFeed.usersData, 'dataStorageRaw');

  //dataStorageRaw
  var dataStorageRaw = currentFeed['dataStorageRaw'].sort(sortOperator1);

  //sorts user data desc
  var usersData = currentFeed['usersData'].sort(sortOperator);


  //picks data from users and updates pagination in request json
  //for next time call
  for (var i = 0; i < usersData.length; i++) {

    remainder += perUser;

    /* if data is less then amount for each user then pick all data */
    if (dataStorageRaw[i].length() <= remainder) {

      /* update remainder */
      remainder -= dataStorageRaw[i].length();

      /* get and store data */
      dataStorage.push(dataStorageRaw[i].getData(dataStorageRaw[i].length()));
      /* update data length */
      dataLength += dataStorage[dataStorage.length - 1].length;


    } else {
      if (dataLength + remainder > number_of_photos) {
        remainder = number_of_photos - dataLength;
      }

      var pickedData = [];


      if (currentFeed['auto_trigger'] === false) {
        pickedData = pickedData.concat(dataStorageRaw[i].getData(remainder));
      } else {
        if (pickedData.length + wdi_front.getDataLength(currentFeed) + wdi_front.getDataLength(currentFeed, dataStorage) < currentFeed['feed_row']['number_of_photos']) {
          pickedData = pickedData.concat(dataStorageRaw[i].getData(remainder));
        }
      }

      remainder = 0;

      dataLength += pickedData.length;
      dataStorage.push(pickedData);


    }


    /*if (usersData[i]['data'].length <= remainder) {

     var pagination = usersData[i]['pagination']['next_url'];
     if (usersData[i]['finished'] === undefined) {
     dataStorage.push(usersData[i]['data']);
     remainder -= usersData[i]['data'].length;
     dataLength += usersData[i]['data'].length;
     }

     if (usersData[i]['finished'] === undefined) {
     if (pagination === undefined || pagination === '' || pagination === null) {
     usersData[i]['finished'] = 'finished';
     }
     }
     } else {
     if ((dataLength + remainder) > number_of_photos) {
     remainder = number_of_photos - dataLength;
     }
     var pickedData = [];
     var indexPuller = 0;
     for (var j = 0; j < remainder; j++) {
     if (currentFeed['auto_trigger'] === false) {
     if (usersData[i]['finished'] === undefined) {
     pickedData.push(usersData[i]['data'][j]);
     }
     } else {
     if (pickedData.length + wdi_front.getDataLength(currentFeed) + wdi_front.getDataLength(currentFeed, dataStorage) < currentFeed['feed_row']['number_of_photos']) {
     if (usersData[i]['finished'] === undefined) {
     pickedData.push(usersData[i]['data'][j]);
     }
     } else {
     indexPuller++;
     }
     }

     }
     j -= indexPuller;

     remainder = 0;



     //updating pagination



     //pushes picked data into local storage
     dataLength += pickedData.length;
     dataStorage.push(pickedData);

     }*/
  }
  //checks if in golbal storage user already exisit then it adds new data to user old data
  //else it simple puches new user with it's data to global storage
  for (i = 0; i < dataStorage.length; i++) {
    if (typeof currentFeed.dataStorage[i] === 'undefined') {
      currentFeed.dataStorage.push(dataStorage[i]);
    } else {
      currentFeed.dataStorage[i] = currentFeed.dataStorage[i].concat(dataStorage[i]);
    }
  }

  //parsing data for lightbox
  currentFeed.parsedData = wdi_front.parseLighboxData(currentFeed);

  //combines together all avialable data in global storage and returns it
  for (i = 0; i < dataStorage.length; i++) {
    readyData = readyData.concat(dataStorage[i]);
  }

  return readyData;
}

/*
 * returns json object for inserting photo template
 */
wdi_front.createObject = function (obj, currentFeed)
{

  var caption = (obj['caption'] != null) ? obj['caption']['text'] : '&nbsp';

  var image_url = '';
  var videoUrl = '';

  if (window.innerWidth >= currentFeed.feed_row.mobile_breakpoint) {
    image_url = obj['images']['standard_resolution']['url'];
    if (currentFeed.feed_row.feed_type == 'blog_style' || currentFeed.feed_row.feed_type == 'image_browser') {
      image_url = obj['link'] + 'media?size=l';
    }
    if (obj['type'] == 'video') {
      /*if pure video, not carousel*/
      videoUrl = obj.hasOwnProperty('videos') ? obj['videos']['standard_resolution']['url'] : '';
    }
  }
  if (window.innerWidth >= currentFeed.feed_row.mobile_breakpoint / 4 && window.innerWidth < currentFeed.feed_row.mobile_breakpoint) {
    image_url = obj['images']['low_resolution']['url'];
    if (currentFeed.feed_row.feed_type == 'blog_style' || currentFeed.feed_row.feed_type == 'image_browser') {
      image_url = obj['link'] + 'media?size=l';
    }
    if (obj['type'] == 'video') {
      /*if pure video, not carousel*/
      videoUrl = obj.hasOwnProperty('videos') ? obj['videos']['low_bandwidth']['url'] : '';
    }
  }
  if (window.innerWidth < currentFeed.feed_row.mobile_breakpoint / 4) {
    image_url = obj['images']['thumbnail']['url'];
    if (currentFeed.feed_row.feed_type == 'blog_style' || currentFeed.feed_row.feed_type == 'image_browser') {
      image_url = obj['link'] + 'media?size=m';
    }
    if (obj['type'] == 'video') {
      /*if pure video, not carousel*/
      videoUrl = obj.hasOwnProperty('videos') ? obj['videos']['low_resolution']['url'] : '';
    }
  }


  var imageIndex = currentFeed.imageIndex;

  var wdi_shape = 'square';
  var media_standard_h = obj['images']['standard_resolution']['height'];
  var media_standard_w = obj['images']['standard_resolution']['width'];
  if(media_standard_h > media_standard_w){
    wdi_shape = 'portrait';
  }
  else if(media_standard_h < media_standard_w){
    wdi_shape = 'landscape';
  }
  var photoObject = {
    'id': obj['id'],
    'caption': caption,
    'image_url': image_url,
    'likes': obj['likes']['count'],
    'comments': obj['comments']['count'],
    'wdi_index': imageIndex,
    'wdi_res_index': currentFeed.resIndex,
    'wdi_media_user': obj['user']['username'],
    'link': obj['link'],
    'video_url': videoUrl,
    'wdi_username': obj['user']['username'],
    'wdi_shape': wdi_shape
  };
  return photoObject;
}

/*
 * If pagination is on sets the proper page number
 */
wdi_front.setPage = function (currentFeed)
{
  var display_type = currentFeed.feed_row.feed_display_view;
  var feed_type = currentFeed.feed_row.feed_type;
  if (display_type != 'pagination') {
    return '';
  }
  var imageIndex = currentFeed.imageIndex;
  if (feed_type == 'image_browser') {
    var divider = 1;
  } else {
    var divider = Math.abs(currentFeed.feed_row.pagination_per_page_number);
  }

  currentFeed.paginator = Math.ceil((imageIndex + 1) / divider);


  return currentFeed.paginator;
}

/*
 * Template for all feed items which have type=image
 */
wdi_front.getPhotoTemplate = function (currentFeed)
{
  var page = wdi_front.setPage(currentFeed);
  var customClass = '';
  var pagination = '';
  var onclick = '';
  var overlayCustomClass = '';
  var thumbClass = 'fa-arrows-alt';
  var showUsernameOnThumb = '';
  if (currentFeed.feed_row.feed_type == 'blog_style' || currentFeed.feed_row.feed_type == 'image_browser') {
    thumbClass = '';
  }
  if (page != '') {
    pagination = 'wdi_page="' + page + '"';
    sourceAttr = 'src';
  } else {
    sourceAttr = 'src';
  }

  if (page != '' && page != 1) {
    customClass = 'wdi_hidden';
  }


  if (currentFeed.feed_row.show_username_on_thumb == '1') {
    showUsernameOnThumb = '<span class="wdi_media_user">@<%= wdi_username%></span>';
  }

  //checking if caption is opend by default then add wdi_full_caption class
  //only in masonry
  if (currentFeed.feed_row.show_full_description == 1 && currentFeed.feed_row.feed_type == 'masonry') {
    customClass += ' wdi_full_caption';
  }

  var onclickevent = "";
  if (currentFeed.feed_row.feed_type !== "blog_style") {
    if (currentFeed.feed_row.feed_type == 'masonry') {
      onclickevent = "wdi_responsive.showMasonryCaption(jQuery(this)," + currentFeed.feed_row.wdi_feed_counter + ");"
    } else {
      onclickevent = "wdi_responsive.showCaption(jQuery(this)," + currentFeed.feed_row.wdi_feed_counter + ");";
    }

  }


  //creating onclick string for different options
  switch (currentFeed.feed_row.feed_item_onclick) {
    case 'lightbox':
    {
      onclick = "onclick=wdi_feed_" + currentFeed.feed_row.wdi_feed_counter + ".galleryBox('<%=id%>')";
      break;
    }
    case 'instagram':
    {
      onclick = 'onclick="window.open (\'<%= link%>\',\'_blank\')"';
      overlayCustomClass = 'wdi_hover_off';
      thumbClass = '';
      break;
    }
    case 'none':
    {
      onclick = '';
      overlayCustomClass = 'wdi_cursor_off wdi_hover_off';
      thumbClass = '';
    }
  }

  var wdi_shape_class = "<%= wdi_shape == 'square' ? 'wdi_shape_square' : (wdi_shape == 'portrait' ? 'wdi_shape_portrait' : (wdi_shape == 'landscape' ? 'wdi_shape_landscape' : 'wdi_shape_square') ) %>";
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var source = '<div class="wdi_feed_item ' + customClass + '"  wdi_index=<%= wdi_index%>  wdi_res_index=<%= wdi_res_index%> wdi_media_user=<%= wdi_media_user%> ' + pagination + ' wdi_type="image" id="wdi_' + wdi_feed_counter + '_<%=id%>">' +
    '<div class="wdi_photo_wrap">' +
    '<div class="wdi_photo_wrap_inner">' +
    '<div class="wdi_photo_img ' + wdi_shape_class + '">' +
    '<img class="wdi_img" ' + sourceAttr + '="<%=image_url%>" alt="feed_image" onerror="wdi_front.brokenImageHandler(this);">' +
    '<div class="wdi_photo_overlay ' + overlayCustomClass + '" >' + showUsernameOnThumb +
    '<div class="wdi_thumb_icon" ' + onclick + ' style="display:table;width:100%;height:100%;">' +
    '<div style="display:table-cell;vertical-align:middle;text-align:center;color:white;">' +
    '<i class="fa ' + thumbClass + '"></i>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';
  if (currentFeed['feed_row']['show_likes'] === '1' || currentFeed['feed_row']['show_comments'] === '1' || currentFeed['feed_row']['show_description'] === '1') {
    source += '<div class="wdi_photo_meta">';
    if (currentFeed['feed_row']['show_likes'] === '1') {
      source += '<div class="wdi_thumb_likes"><i class="fa fa-heart-o">&nbsp;<%= likes%></i></div>';
    }
    if (currentFeed['feed_row']['show_comments'] === '1') {
      source += '<div class="wdi_thumb_comments"><i class="fa fa-comment-o">&nbsp;<%= comments%></i></div>';
    }
    source += '<div class="wdi_clear"></div>';
    if (currentFeed['feed_row']['show_description'] === '1') {
      source += '<div class="wdi_photo_title" onclick=' + onclickevent + ' >' +
        '<%=caption%>' +
        '</div>';
    }
    source += '</div>';
  }

  source += '</div>';
  var template = _.template(source);
  return template;
}


/*
 * Template for all feed items which have type=image
 */
wdi_front.getSliderTemplate = function (currentFeed)
{
  var page = wdi_front.setPage(currentFeed);
  var customClass = '';
  var pagination = '';
  var onclick = '';
  var overlayCustomClass = '';
  var thumbClass = 'fa-clone';
  var showUsernameOnThumb = '';
  if (currentFeed.feed_row.feed_type == 'blog_style' || currentFeed.feed_row.feed_type == 'image_browser') {
    thumbClass = '';
  }
  if (page != '') {
    pagination = 'wdi_page="' + page + '"';
    sourceAttr = 'src';
  } else {
    sourceAttr = 'src';
  }

  if (page != '' && page != 1) {
    customClass = 'wdi_hidden';
  }


  if (currentFeed.feed_row.show_username_on_thumb == '1') {
    showUsernameOnThumb = '<span class="wdi_media_user">@<%= wdi_username%></span>';
  }

  //checking if caption is opend by default then add wdi_full_caption class
  //only in masonry
  if (currentFeed.feed_row.show_full_description == 1 && currentFeed.feed_row.feed_type == 'masonry') {
    customClass += ' wdi_full_caption';
  }

  var onclickevent = "";
  if (currentFeed.feed_row.feed_type !== "blog_style") {
    if (currentFeed.feed_row.feed_type == 'masonry') {
      onclickevent = "wdi_responsive.showMasonryCaption(jQuery(this)," + currentFeed.feed_row.wdi_feed_counter + ");"
    } else {
      onclickevent = "wdi_responsive.showCaption(jQuery(this)," + currentFeed.feed_row.wdi_feed_counter + ");";
    }

  }


  //creating onclick string for different options
  switch (currentFeed.feed_row.feed_item_onclick) {
    case 'lightbox':
    {
      onclick = "onclick=wdi_feed_" + currentFeed.feed_row.wdi_feed_counter + ".galleryBox('<%=id%>')";
      break;
    }
    case 'instagram':
    {
      onclick = 'onclick="window.open (\'<%= link%>\',\'_blank\')"';
      overlayCustomClass = 'wdi_hover_off';
      thumbClass = 'fa-clone';
      break;
    }
    case 'none':
    {
      onclick = '';
      overlayCustomClass = 'wdi_cursor_off wdi_hover_off';
      thumbClass = '';
    }
  }

  var wdi_shape_class = "<%= wdi_shape == 'square' ? 'wdi_shape_square' : (wdi_shape == 'portrait' ? 'wdi_shape_portrait' : (wdi_shape == 'landscape' ? 'wdi_shape_landscape' : 'wdi_shape_square') ) %>";
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var source = '<div class="wdi_feed_item ' + customClass + '"  wdi_index=<%= wdi_index%>  wdi_res_index=<%= wdi_res_index%> wdi_media_user=<%= wdi_media_user%> ' + pagination + ' wdi_type="slideshow" id="wdi_' + wdi_feed_counter + '_<%=id%>">' +
    '<div class="wdi_photo_wrap">' +
    '<div class="wdi_photo_wrap_inner">' +
    '<div class="wdi_photo_img ' + wdi_shape_class + '">' +
    '<img class="wdi_img" ' + sourceAttr + '="<%=image_url%>" alt="feed_image" onerror="wdi_front.brokenImageHandler(this);">' +
    '<div class="wdi_photo_overlay ' + overlayCustomClass + '" >' + showUsernameOnThumb +
    '<div class="wdi_thumb_icon" ' + onclick + ' style="display:table;width:100%;height:100%;">' +
    '<div style="display:table-cell;vertical-align:middle;text-align:center;color:white;">' +
    '<i class="fa ' + thumbClass + '"></i>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';
  if (currentFeed['feed_row']['show_likes'] === '1' || currentFeed['feed_row']['show_comments'] === '1' || currentFeed['feed_row']['show_description'] === '1') {
    source += '<div class="wdi_photo_meta">';
    if (currentFeed['feed_row']['show_likes'] === '1') {
      source += '<div class="wdi_thumb_likes"><i class="fa fa-heart-o">&nbsp;<%= likes%></i></div>';
    }
    if (currentFeed['feed_row']['show_comments'] === '1') {
      source += '<div class="wdi_thumb_comments"><i class="fa fa-comment-o">&nbsp;<%= comments%></i></div>';
    }
    source += '<div class="wdi_clear"></div>';
    if (currentFeed['feed_row']['show_description'] === '1') {
      source += '<div class="wdi_photo_title" onclick=' + onclickevent + ' >' +
        '<%=caption%>' +
        '</div>';
    }
    source += '</div>';
  }

  source += '</div>';
  var template = _.template(source);
  return template;
}

wdi_front.replaceToVideo = function (url, index, feed_counter)
{

  overlayHtml = "<video style='width:auto !important; height:auto !important; max-width:100% !important; max-height:100% !important; margin:0 !important;' controls=''>" +
    "<source src='" + url + "' type='video/mp4'>" +
    "Your browser does not support the video tag. </video>";

  jQuery('#wdi_feed_' + feed_counter + ' [wdi_index="' + index + '"] .wdi_photo_wrap_inner').html(overlayHtml);
  jQuery('#wdi_feed_' + feed_counter + ' [wdi_index="' + index + '"] .wdi_photo_wrap_inner video').get(0).play();
}

/*
 * Template for all feed items which have type=video
 */
wdi_front.getVideoTemplate = function (currentFeed)
{
  var page = wdi_front.setPage(currentFeed);
  var customClass = '';
  var pagination = '';
  var thumbClass = 'fa-play';
  var onclick = '';
  var overlayCustomClass = '';
  var sourceAttr;
  var showUsernameOnThumb = '';


  if (page != '') {
    pagination = 'wdi_page="' + page + '"';
    sourceAttr = 'src';
  } else {
    sourceAttr = 'src';
  }
  if (page != '' && page != 1) {
    customClass = 'wdi_hidden';
  }

  if (currentFeed.feed_row.show_username_on_thumb == '1') {
    showUsernameOnThumb = '<span class="wdi_media_user">@<%= wdi_username%></span>';
  }

  //checking if caption is opend by default then add wdi_full_caption class
  //only in masonry
  if (currentFeed.feed_row.show_full_description == 1 && currentFeed.feed_row.feed_type == 'masonry') {
    customClass += ' wdi_full_caption';
  }

  var onclickevent = "";
  if (currentFeed.feed_row.feed_type !== "blog_style") {
    if (currentFeed.feed_row.feed_type == 'masonry') {
      onclickevent = "wdi_responsive.showMasonryCaption(jQuery(this)," + currentFeed.feed_row.wdi_feed_counter + ");"
    } else {
      onclickevent = "wdi_responsive.showCaption(jQuery(this)," + currentFeed.feed_row.wdi_feed_counter + ");";
    }

  }

  //creating onclick string for different options
  switch (currentFeed.feed_row.feed_item_onclick) {
    case 'lightbox':
    {
      onclick = "onclick=wdi_feed_" + currentFeed.feed_row.wdi_feed_counter + ".galleryBox('<%=id%>')";
      break;
    }
    case 'instagram':
    {
      onclick = 'onclick="window.open (\'<%= link%>\',\'_blank\')"';
      overlayCustomClass = 'wdi_hover_off';
      thumbClass = 'fa-play';
      break;
    }
    case 'none':
    {
      overlayCustomClass = 'wdi_cursor_off wdi_hover_off';
      thumbClass = '';
      if (currentFeed.feed_row.feed_type == 'blog_style' || currentFeed.feed_row.feed_type == 'image_browser') {
        onclick = "onclick=wdi_front.replaceToVideo('<%= video_url%>','<%= wdi_index%>'," + currentFeed.feed_row.wdi_feed_counter + ")";
        overlayCustomClass = '';
        thumbClass = 'fa-play';
      }
    }
  }
  var wdi_shape_class = "<%= wdi_shape == 'square' ? 'wdi_shape_square' : (wdi_shape == 'portrait' ? 'wdi_shape_portrait' : (wdi_shape == 'landscape' ? 'wdi_shape_landscape' : 'wdi_shape_square') ) %>";

  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var source = '<div class="wdi_feed_item ' + customClass + '"  wdi_index=<%= wdi_index%> wdi_res_index=<%= wdi_res_index%> wdi_media_user=<%= wdi_media_user%> ' + pagination + ' wdi_type="image" id="wdi_' + wdi_feed_counter + '_<%=id%>">' +
    '<div class="wdi_photo_wrap">' +
    '<div class="wdi_photo_wrap_inner">' +
    '<div class="wdi_photo_img ' +wdi_shape_class + '">' +
    '<img class="wdi_img" ' + sourceAttr + '="<%=image_url%>" alt="feed_image" onerror="wdi_front.brokenImageHandler(this);">' +
    '<div class="wdi_photo_overlay ' + overlayCustomClass + '" ' + onclick + '>' + showUsernameOnThumb +
    '<div class="wdi_thumb_icon" style="display:table;width:100%;height:100%;">' +
    '<div style="display:table-cell;vertical-align:middle;text-align:center;color:white;">' +
    '<i class="fa ' + thumbClass + '"></i>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';
  if (currentFeed['feed_row']['show_likes'] === '1' || currentFeed['feed_row']['show_comments'] === '1' || currentFeed['feed_row']['show_description'] === '1') {
    source += '<div class="wdi_photo_meta">';
    if (currentFeed['feed_row']['show_likes'] === '1') {
      source += '<div class="wdi_thumb_likes"><i class="fa fa-heart-o">&nbsp;<%= likes%></i></div>';
    }
    if (currentFeed['feed_row']['show_comments'] === '1') {
      source += '<div class="wdi_thumb_comments"><i class="fa fa-comment-o">&nbsp;<%= comments%></i></div>';
    }
    source += '<div class="wdi_clear"></div>';
    if (currentFeed['feed_row']['show_description'] === '1') {
      source += '<div class="wdi_photo_title" onclick=' + onclickevent + ' >' +
        '<%=caption%>' +
        '</div>';
    }
    source += '</div>';
  }
  source += '</div>';
  var template = _.template(source);
  return template;
}

wdi_front.bindEvents = function (currentFeed)
{

  if (jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter + " .wdi_feed_wrapper").length == 0) {
    //no feed in DOM, ignore
    return;
  }

  if (currentFeed.feed_row.feed_display_view == 'load_more_btn') {
    //binding load more event
    jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' .wdi_load_more_container').on(wdi_front.clickOrTouch, function ()
    {
      //do the actual load more operation
      wdi_front.loadMore(jQuery(this).find('.wdi_load_more_wrap'));

    });
  }

  if (currentFeed.feed_row.feed_display_view == 'pagination') {
    //binding pagination events
    jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' #wdi_next').on(wdi_front.clickOrTouch, function ()
    {
      wdi_front.paginatorNext(jQuery(this), currentFeed);
    });
    jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' #wdi_prev').on(wdi_front.clickOrTouch, function ()
    {
      wdi_front.paginatorPrev(jQuery(this), currentFeed);
    });
    jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' #wdi_last_page').on(wdi_front.clickOrTouch, function ()
    {
      wdi_front.paginationLastPage(jQuery(this), currentFeed);
    });
    jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' #wdi_first_page').on(wdi_front.clickOrTouch, function ()
    {
      wdi_front.paginationFirstPage(jQuery(this), currentFeed);
    });
    //setting pagiantion flags
    currentFeed.paginatorNextFlag = false;
  }
  if (currentFeed.feed_row.feed_display_view == 'infinite_scroll') {
    //binding infinite scroll Events
    jQuery(window).on('scroll', function ()
    {
      wdi_front.infiniteScroll(currentFeed);
    });
    //infinite scroll flags
    currentFeed.infiniteScrollFlag = false;
  }


}

wdi_front.infiniteScroll = function (currentFeed)
{

  if (jQuery(window).scrollTop() <= jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' #wdi_infinite_scroll').offset().top) {
    if (currentFeed.infiniteScrollFlag === false && currentFeed.stopInfiniteScrollFlag == false) {
      currentFeed.infiniteScrollFlag = true;
      wdi_front.loadMore(jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' #wdi_infinite_scroll'), currentFeed);
    } else
      if (currentFeed.stopInfiniteScrollFlag) {
        wdi_front.allImagesLoaded(currentFeed);
      }

  }
}


wdi_front.paginationFirstPage = function (btn, currentFeed)
{
  if (currentFeed.paginator == 1 || currentFeed.currentPage == 1) {
    btn.addClass('wdi_disabled');
    return;
  }
  var oldPage = currentFeed.currentPage;
  currentFeed.currentPage = 1;
  wdi_front.updatePagination(currentFeed, 'custom', oldPage);

  //enable last page button
  var last_page_btn = btn.parent().find('#wdi_last_page');
  last_page_btn.removeClass('wdi_disabled');

  //disabling first page button
  btn.addClass('wdi_disabled');

}

wdi_front.paginationLastPage = function (btn, currentFeed)
{
  if (currentFeed.paginator == 1 || currentFeed.currentPage == currentFeed.paginator) {
    return;
  }
  var oldPage = currentFeed.currentPage;
  currentFeed.currentPage = currentFeed.paginator;
  wdi_front.updatePagination(currentFeed, 'custom', oldPage);

  //disableing last page button
  btn.addClass('wdi_disabled');

  //enabling first page button
  var first_page_btn = btn.parent().find('#wdi_first_page');
  first_page_btn.removeClass('wdi_disabled');
}

wdi_front.paginatorNext = function (btn, currentFeed)
{
  var last_page_btn = btn.parent().find('#wdi_last_page');
  var first_page_btn = btn.parent().find('#wdi_first_page');
  currentFeed.paginatorNextFlag = true;
  if (currentFeed.paginator == currentFeed.currentPage && !wdi_front.checkFeedFinished(currentFeed)) {
    currentFeed.currentPage++;
    var number_of_photos = currentFeed.feed_row.number_of_photos;
    wdi_front.loadMore(btn, currentFeed, number_of_photos);
    //on the last page don't show got to last page button
    last_page_btn.addClass('wdi_disabled');
  } else
    if (currentFeed.paginator > currentFeed.currentPage) {
      currentFeed.currentPage++;
      wdi_front.updatePagination(currentFeed, 'next');
      //check if new page isn't the last one then enable last page button
      if (currentFeed.paginator > currentFeed.currentPage) {
        last_page_btn.removeClass('wdi_disabled');
      } else {
        last_page_btn.addClass('wdi_disabled');
      }
    }

  //enable first page button
  first_page_btn.removeClass('wdi_disabled');


}

wdi_front.paginatorPrev = function (btn, currentFeed)
{
  var last_page_btn = btn.parent().find('#wdi_last_page');
  var first_page_btn = btn.parent().find('#wdi_first_page');
  if (currentFeed.currentPage == 1) {
    first_page_btn.addClass('wdi_disabled');
    return;
  }

  currentFeed.currentPage--;
  wdi_front.updatePagination(currentFeed, 'prev');

  //enable last page button
  last_page_btn.removeClass('wdi_disabled');

  if (currentFeed.currentPage == 1) {
    first_page_btn.addClass('wdi_disabled');
  }

}

//displays proper images for specific page after pagination buttons click event
wdi_front.updatePagination = function (currentFeed, dir, oldPage)
{
  var currentFeedString = '#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'];
  jQuery(currentFeedString + ' [wdi_page="' + currentFeed.currentPage + '"]').each(function ()
  {
    jQuery(this).removeClass('wdi_hidden');
  });
  switch (dir) {
    case 'next':
    {
      var oldPage = currentFeed.currentPage - 1;
      jQuery(currentFeedString + ' .wdi_feed_wrapper').height(jQuery('.wdi_feed_wrapper').height());
      jQuery(currentFeedString + ' [wdi_page="' + oldPage + '"]').each(function ()
      {
        jQuery(this).addClass('wdi_hidden');
      });
      break;
    }
    case 'prev':
    {
      var oldPage = currentFeed.currentPage + 1;
      jQuery(currentFeedString + ' .wdi_feed_wrapper').height(jQuery('.wdi_feed_wrapper').height());
      jQuery(currentFeedString + ' [wdi_page="' + oldPage + '"]').each(function ()
      {
        jQuery(this).addClass('wdi_hidden');
      });
      break;
    }
    case 'custom':
    {
      var oldPage = oldPage;
      if (oldPage != currentFeed.currentPage) {
        jQuery(currentFeedString + ' .wdi_feed_wrapper').height(jQuery('.wdi_feed_wrapper').height());
        jQuery(currentFeedString + ' [wdi_page="' + oldPage + '"]').each(function ()
        {
          jQuery(this).addClass('wdi_hidden');
        });
      }

      break;
    }
  }
  currentFeed.paginatorNextFlag = false;

  jQuery(currentFeedString + ' .wdi_feed_wrapper').css('height', 'auto');
  jQuery(currentFeedString + ' #wdi_current_page').text(currentFeed.currentPage);
}


wdi_front.loadMore = function (button, _currentFeed)
{


  var dataCounter = 0;
  if (button != '' && typeof button != 'undefined' && button != 'initial' && button != 'initial-keep') {
    var currentFeed = window[button.parent().parent().parent().parent().attr('id')];
  }
  if (typeof _currentFeed != 'undefined') {
    var currentFeed = _currentFeed;
  }
  //check if any filter is enabled and filter user images has finished
  //then stop any load more action
  var activeFilter = 0,
    finishedFilter = 0;
  for (var i = 0; i < currentFeed.userSortFlags.length; i++) {
    if (currentFeed.userSortFlags[i].flag === true) {
      activeFilter++;
      for (var j = 0; j < currentFeed.usersData.length; j++) {
        if (currentFeed.userSortFlags[i]['id'] === currentFeed.usersData[j]['user_id']) {
          if (currentFeed.usersData[j]['finished'] === 'finished') {
            finishedFilter++;
          }
        }
      }
    }
  }
  if (activeFilter === finishedFilter && activeFilter != 0) {
    return;
  }


  //if button is not provided than it enables auto_tiggering and recursively loads images
  if (button === '') {
    currentFeed['auto_trigger'] = true;
  } else {
    currentFeed['auto_trigger'] = false;
  }
  //ading ajax loading
  wdi_front.ajaxLoader(currentFeed);


  //check if masonry view is on and and feed display type is pagination then
  //close all captions before loading more pages for porper pagination rendering
  if (currentFeed.feed_row.feed_type === 'masonry' && currentFeed.feed_row.feed_display_view == 'pagination') {
    jQuery('#wdi_feed_' + wdi_front.feed_counter + ' .wdi_full_caption').each(function ()
    {
      jQuery(this).find('.wdi_photo_title').trigger(wdi_front.clickOrTouch);
    });
  }


  //check if all data loaded then remove ajaxLoader
  for (var i = 0; i < currentFeed.usersData.length; i++) {
    if (currentFeed.usersData[i]['finished'] === 'finished') {
      dataCounter++;
    }
  }
  if (dataCounter === currentFeed.usersData.length) {
    wdi_front.allImagesLoaded(currentFeed);
    jQuery('#wdi_feed_' + currentFeed['feed_row']['wdi_feed_counter'] + ' .wdi_load_more').remove();

  }

  var usersData = currentFeed['usersData'];

  currentFeed.loadMoreDataCount = currentFeed.feed_users.length;

  for (var i = 0; i < usersData.length; i++) {

    var pagination = usersData[i]['pagination'];
    var user = {
      user_id: usersData[i]['user_id'],
      username: usersData[i]['username']
    }

    //checking if pagination url exists then load images, else skip
    if (pagination['next_url'] != '' && pagination['next_url'] != null && typeof pagination['next_url'] != 'undefined') {
      var next_url = pagination['next_url'];
      wdi_front.loadMoreRequest(user, next_url, currentFeed, button);
    } else {

      if (button == 'initial-keep') {
        currentFeed.temproraryUsersData[i] = currentFeed.usersData[i];
      }
      currentFeed.loadMoreDataCount--;


      wdi_front.checkForLoadMoreDone(currentFeed, button);
      continue;
    }
  }


}

/*
 * Requests images based on provided pagination url
 */
wdi_front.loadMoreRequest = function (user, next_url, currentFeed, button)
{

  var usersData = currentFeed['usersData'];
  var errorMessage = '';

  currentFeed.instagram.requestByUrl(next_url, {
    success: function (response)
    {
      if (response === '' || typeof response == 'undefined' || response == null) {
        errorMessage = wdi_front_messages.network_error;
        currentFeed.loadMoreDataCount--;
        wdi_front.show_alert(errorMessage);
        return;
      }
      if (response['meta']['code'] != 200) {
        errorMessage = response['meta']['error_message'];
        currentFeed.loadMoreDataCount--;
        wdi_front.show_alert(errorMessage);
        return;
      }

      response['user_id'] = user.user_id;
      response['username'] = user.username;

      for (var i = 0; i < currentFeed['usersData'].length; i++) {
        if (response['user_id'] === currentFeed['usersData'][i]['user_id']) {

          ///mmm!!!
          if (response['user_id'][0] === '#') {
            response['data'] = wdi_front.appendRequestHashtag(response['data'], response['user_id']);
          }
          ////////////////
          /*if button is initial-keep then we will lose currentFeed['usersData'][i]
           for not loosing it we keep it in currentFeed.temproraryUsersData, which value will be
           used later in wdi_front.checkForLoadMoreDone(), in other cases when button is set to
           initial we already keep data in that variable, so we don't deed to keep it again, it will give us duplicate value
           */

          if (button == 'initial-keep') {
            currentFeed.temproraryUsersData[i] = currentFeed.usersData[i];
          }
          currentFeed['usersData'][i] = response;

          currentFeed.loadMoreDataCount--;
        }
      }

      //checks if load more done then displays feed
      wdi_front.checkForLoadMoreDone(currentFeed, button);
    }
  })

}

wdi_front.checkForLoadMoreDone = function (currentFeed, button)
{
  var load_more_number = currentFeed.feed_row['load_more_number'];
  var number_of_photos = currentFeed.feed_row['number_of_photos'];

  if (currentFeed.loadMoreDataCount == 0) {

    currentFeed.temproraryUsersData = wdi_front.mergeData(currentFeed.temproraryUsersData, currentFeed.usersData);
    var gettedDataLength = wdi_front.getArrayContentLength(currentFeed.temproraryUsersData, 'data');
    /*this will happen when we call loadMore first time
     initial-keep is the same as initial except that if loadMore is called
     with initial-keep we store data on currentFeed.temproraryUsersData before checkLoadMoreDone()
     function call*/
    if (button == 'initial-keep') {
      button = 'initial';
    }
    //if button is set to inital load number_of_photos photos
    if (button == 'initial') {

      /*if existing data length is smaller then load_more_number then get more objects until desired number is reached
       also if it is not possible to reach the desired number (this will happen when all users has no more photos) then
       displayFeed()*/
      if (gettedDataLength < number_of_photos && !wdi_front.userHasNoPhoto(currentFeed, currentFeed.temproraryUsersData) && currentFeed.instagramRequestCounter <= currentFeed.maxConditionalFiltersRequestCount) {
        //console.log('checkForLoadMoreDone recursion');

        wdi_front.loadMore('initial', currentFeed);
      } else {

        currentFeed.usersData = currentFeed.temproraryUsersData;

        wdi_front.displayFeed(currentFeed);
        //when all data us properly displayed check for any active filters and then apply them
        wdi_front.applyFilters(currentFeed);

        //resetting temprorary users data array for the next loadmoer call
        currentFeed.temproraryUsersData = [];


      }

    } else {
      //else load load_more_number photos
      //if existing data length is smaller then load_more_number then get more objects until desired number is reached

      if (gettedDataLength < load_more_number && !wdi_front.userHasNoPhoto(currentFeed, currentFeed.temproraryUsersData) && currentFeed.instagramRequestCounter <= currentFeed.maxConditionalFiltersRequestCount) {
        //console.log('load more recursion');
        wdi_front.loadMore(undefined, currentFeed);
      } else {

        currentFeed.usersData = currentFeed.temproraryUsersData;

        if (!wdi_front.activeUsersCount(currentFeed)) {
          return;
        }

        wdi_front.displayFeed(currentFeed, load_more_number);
        //when all data us properly displayed check for any active filters and then apply them
        wdi_front.applyFilters(currentFeed);

        //resetting temprorary users data array for the next loadmoer call
        currentFeed.temproraryUsersData = [];
      }
    }


  }
}

wdi_front.allDataHasFinished = function (currentFeed)
{
  var c = 0;
  for (var j = 0; j < currentFeed.dataStorageRaw.length; j++) {
    if (currentFeed.dataStorageRaw[j].length() == 0 && currentFeed.dataStorageRaw[j].locked == true) {
      c++;
    }
  }

  return (c == currentFeed.dataStorageRaw.length);
}


wdi_front.mergeData = function (array1, array2)
{


  for (var i = 0; i < array2.length; i++) {
    if (typeof array1[i] != 'undefined') {
      if (array2[i]['finished'] == 'finished') {
        continue;
      }

      //if user data is finished then dont add duplicate data
      if (typeof array1[i]['pagination']['next_max_id'] == 'undefined' &&
        typeof array1[i]['pagination']['next_max_like_id'] == 'undefined') {
        continue;
      }
      //extend data
      array1[i]['data'] = array1[i]['data'].concat(array2[i]['data']);
      array1[i]['pagination'] = array2[i]['pagination'];
      array1[i]['user_id'] = array2[i]['user_id'];
      array1[i]['username'] = array2[i]['username'];
      array1[i]['meta'] = array2[i]['meta'];
    } else {
      array1.push(array2[i]);
    }
  }
  return array1;
}


//broken image handling
wdi_front.brokenImageHandler = function (source)
{
  source.src = wdi_url.plugin_url + "../images/missing.png";
  source.onerror = "";
  return true;

}


//ajax loading
wdi_front.ajaxLoader = function (currentFeed)
{
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];

  var feed_container = jQuery('#wdi_feed_' + wdi_feed_counter);
  if (currentFeed.feed_row.feed_display_view == 'load_more_btn') {
    feed_container.find('.wdi_load_more').addClass('wdi_hidden');
    feed_container.find('.wdi_spinner').removeClass('wdi_hidden');
  }
  /////////////////////////////////////////////////////
  if (currentFeed.feed_row.feed_display_view == 'infinite_scroll') {
    var loadingDiv;
    if (feed_container.find('.wdi_ajax_loading').length == 0) {
      loadingDiv = jQuery('<div class="wdi_ajax_loading"><div><div><img class="wdi_load_more_spinner" src="' + wdi_url.plugin_url + '../images/ajax_loader.png"></div></div></div>');
      feed_container.append(loadingDiv);
    } else {
      loadingDiv = feed_container.find('.wdi_ajax_loading');
    }
    loadingDiv.removeClass('wdi_hidden');
  }


  ////////////////////////////////////////////////////

}

//if all images loaded then clicking load more causes it's removal
wdi_front.allImagesLoaded = function (currentFeed)
{
  ////////////////////////////////////////////////////////////////////
  //clearInterval(currentFeed.loadingInterval);
  //jQuery('#wdi_feed_'+currentFeed.feed_row['wdi_feed_counter']+' .wdi_ajax_loading').remove();

  ////////////////////////////////////////////////////

  var dataLength = wdi_front.getDataLength(currentFeed);
  /* display message if feed contains no image at all */
  if (dataLength == 0 && (currentFeed.feed_row.conditional_filters.length == 0 || currentFeed.feed_row.conditional_filter_enable == 0)) {
    jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter + " .wdi_feed_wrapper").append("<p>" + wdi_front_messages.feed_nomedia + "</p>");
  }

  //if all images loaded then enable load more button and hide spinner
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var feed_container = jQuery('#wdi_feed_' + wdi_feed_counter);

  if (currentFeed.feed_row.feed_display_view == 'load_more_btn') {
    feed_container.find('.wdi_load_more').removeClass('wdi_hidden');
    feed_container.find('.wdi_spinner').addClass('wdi_hidden');
  }

  if (currentFeed.feed_row.feed_display_view == 'infinite_scroll') {
    jQuery('#wdi_feed_' + currentFeed.feed_row['wdi_feed_counter'] + ' .wdi_ajax_loading').addClass('wdi_hidden');
  }


  //custom event fired for user based custom js
  feed_container.trigger('wdi_feed_loaded');


}


//shows different parts of the feed based user choice
wdi_front.show = function (name, currentFeed)
{
  var wdi_feed_counter = currentFeed.feed_row['wdi_feed_counter'];
  var feed_container = jQuery('#wdi_feed_' + wdi_feed_counter + ' .wdi_feed_container');
  var _this = this;
  switch (name) {
    case 'header':
    {
      show_header();
      break;
    }
    case 'users':
    {
      show_users(currentFeed);
      break;
    }

  }

  function show_header()
  {

    var templateData = {
      'feed_thumb': currentFeed['feed_row']['feed_thumb'],
      'feed_name': currentFeed['feed_row']['feed_name'],
    };

    var headerTemplate = wdi_front.getHeaderTemplate(),
      html = headerTemplate(templateData),
      containerHtml = feed_container.find('.wdi_feed_header').html();

    feed_container.find('.wdi_feed_header').html(containerHtml + html);


  }

  function show_users(currentFeed)
  {
    feed_container.find('.wdi_feed_users').html('');
    var users = currentFeed['feed_users'];
    var access_token = currentFeed['feed_row']['access_token'];
    var i = 0;
    currentFeed.headerUserinfo = [];
    getThumb();
    //recursively calls itself until all user data is ready then displyes it with escapeRequest
    function getThumb()
    {

      if (currentFeed.headerUserinfo.length == users.length) {
        escapeRequest(currentFeed.headerUserinfo, currentFeed);
        return;
      }
      var _user = users[currentFeed.headerUserinfo.length];


      if (typeof _user === 'string' && _user === 'self') {
        currentFeed.instagram.getSelfInfo({
          success: function (response)
          {
            response = _this.checkMediaResponse(response);
            if (response != false) {
              var obj = {
                id: response['data']['id'],
                name: response['data']['username'],
                url: response['data']['profile_picture'],
                bio: response['data']['bio'],
                counts: response['data']['counts'],
                website: response['data']['website'],
                full_name: response['data']['full_name']
              }
              currentFeed.headerUserinfo.push(obj);
              i++;
              getThumb();
            }
          },
          args: {
            ignoreFiltering: true,
          }
        });
      }
      else
        if (_this.getInputType(_user.username) == 'hashtag') {
          currentFeed.instagram.searchForTagsByName(_this.stripHashtag(_user.username), {
            /*currentFeed.instagram.getTagRecentMedia(_this.stripHashtag(_user.username), {*/
            success: function (response)
            {
              response = _this.checkMediaResponse(response);
              if (response != false) {
                if (response['data'].length == 0) {
                  var thumb_img = '';
                  var counts = {media: ''};
                } else {
                  var thumb_img = '';// we will get image src later when will have all the sources
                  //thumb_img = response['data'][0]['images']['thumbnail']['url'];
                  var counts = {media: response['data'][0]['media_count']};
                }

                var obj = {
                  name: users[i]['username'],
                  url: thumb_img,
                  counts: counts,
                };
                i++;
                currentFeed.headerUserinfo.push(obj);
                getThumb();
              }
            },
            args: {
              ignoreFiltering: true,
            }
          });
        }
        else
          if (_this.getInputType(_user.username) == 'user') {
            currentFeed.instagram.getUserInfo(_user.id, {
              success: function (response)
              {
                response = _this.checkMediaResponse(response);
                if (response != false) {
                  var obj = {
                    id: response['data']['id'],
                    name: response['data']['username'],
                    url: response['data']['profile_picture'],
                    bio: response['data']['bio'],
                    counts: response['data']['counts'],
                    website: response['data']['website'],
                    full_name: response['data']['full_name']
                  }
                  currentFeed.headerUserinfo.push(obj);
                  i++;
                  getThumb();
                }
              },
              args: {
                ignoreFiltering: true,
              }
            });
          }

    }

    //when all user data is ready break recursion and create user elements
    function escapeRequest(info, currentFeed)
    {
      feed_container.find('.wdi_feed_users').html('');
      for (var k = 0; k < info.length; k++) {
        //setting all user filters to false

        var userFilter = {
          'flag': false,
          'id': info[k]['id'],
          'name': info[k]['name']
        };


        //user inforamtion
        var hashtagClass = (info[k]['name'][0] == '#') ? 'wdi_header_hashtag' : '';

        var templateData = {
          'user_index': k,
          'user_img_url': info[k]['url'],
          'counts': info[k]["counts"],
          'feed_counter': currentFeed.feed_row.wdi_feed_counter,
          'user_name': info[k]['name'],
          'bio': info[k]['bio'],
          'usersCount': currentFeed.feed_row.feed_users.length,
          'hashtagClass': hashtagClass

        };

        var userTemplate = wdi_front.getUserTemplate(currentFeed, info[k]['name']),
          html = userTemplate(templateData),
          containerHtml = feed_container.find('.wdi_feed_users').html();

        feed_container.find('.wdi_feed_users').html(containerHtml + html);


        currentFeed.userSortFlags.push(userFilter);

        var clearFloat = jQuery('<div class="wdi_clear"></div>');

      }
      feed_container.find('.wdi_feed_users').append(clearFloat);
      wdi_front.updateUsersImages(currentFeed);
    };
  }

}


wdi_front.getUserTemplate = function (currentFeed, username)
{

  var usersCount = currentFeed.dataCount,
    instagramLink, instagramLinkOnClick, js;

  switch (username[0]) {
    case '#':
    {
      instagramLink = '//instagram.com/explore/tags/' + username.substr(1, username.length);
      break;
    }
    default:
    {
      instagramLink = '//instagram.com/' + username;
      break;
    }
  }
  js = 'window.open("' + instagramLink + '","_blank")';
  instagramLinkOnClick = "onclick='" + js + "'";

  var source = '<div class="wdi_single_user" user_index="<%=user_index%>">' +
    '<div class="wdi_header_user_text <%=hashtagClass%>">' +

    '<div class="wdi_user_img_wrap">' +
    '<img onerror="wdi_front.brokenImageHandler(this);" src="<%= user_img_url%>">';
  if (usersCount > 1) {
    source += '<div  title="' + wdi_front_messages.filter_title + '" class="wdi_filter_overlay">' +
      '<div  class="wdi_filter_icon">' +
      '<span onclick="wdi_front.addFilter(<%=user_index%>,<%=feed_counter%>);" class="fa fa-filter"></span>' +
      '</div>' +
      '</div>';
  }
  source += '</div>';
  source += '<h3 ' + instagramLinkOnClick + '><%= user_name%></h3>';

  if (username[0] !== '#') {
    if (currentFeed.feed_row.follow_on_instagram_btn == '1') {
      source += '<div class="wdi_user_controls">' +
        '<div class="wdi_follow_btn" onclick="window.open(\'//instagram.com/<%= user_name%>\',\'_blank\')"><span> '+ wdi_front_messages.follow + '</span></div>' +
        '</div>';
    }
    source += '<div class="wdi_media_info">' +
      '<p class="wdi_posts"><span class="fa fa-camera-retro"><%= counts.media%></span></p>' +
      '<p class="wdi_followers"><span class="fa fa-user"><%= counts.followed_by%></span></p>' +
      '</div>';
  } else {
    source += '<div class="wdi_user_controls">' +
      '</div>' +
      '<div class="wdi_media_info">' +
      '<p class="wdi_posts"><span class="fa fa-camera-retro"><%= counts.media%></span></p>' +
      '<p class="wdi_followers"><span></span></p>' +
      '</div>';
  }
  source += '<div class="wdi_clear"></div>';

  if (usersCount == 1 && username[0] !== '#' && currentFeed.feed_row.display_user_info == '1') {
    source += '<div class="wdi_bio"><%= bio%></div>';

  }


  source += '</div>' +
    '</div>';

  var template = _.template(source);
  return template;
}


wdi_front.getHeaderTemplate = function ()
{
  var source = '<div class="wdi_header_wrapper">' +
    '<div class="wdi_header_img_wrap">' +
    '<img src="<%=feed_thumb%>">' +
    '</div>' +
    '<div class="wdi_header_text"><%=feed_name%></div>' +
    '<div class="wdi_clear">' +
    '</div>';
  var template = _.template(source);
  return template;
}


//sets user filter to true and applys filter to feed
wdi_front.addFilter = function (index, feed_counter)
{
  var currentFeed = window['wdi_feed_' + feed_counter];
  var usersCount = currentFeed.dataCount;
  if (usersCount < 2) {
    return;
  }

  if (currentFeed.nowLoadingImages != false) {
    return;
  } else {

    var userDiv = jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter + '_users [user_index="' + index + '"]');
    userDiv.find('.wdi_filter_overlay').toggleClass('wdi_filter_active_bg');
    userDiv.find('.wdi_header_user_text h3').toggleClass('wdi_filter_active_col');
    userDiv.find('.wdi_media_info').toggleClass('wdi_filter_active_col');
    userDiv.find('.wdi_follow_btn').toggleClass('wdi_filter_active_col');

    currentFeed.customFilterChanged = true;
    //setting filter flag to true
    if (currentFeed.userSortFlags[index]['flag'] == false) {
      currentFeed.userSortFlags[index]['flag'] = true;
    } else {
      currentFeed.userSortFlags[index]['flag'] = false;
    }
    //getting active filter count
    var activeFilterCount = 0;
    for (var j = 0; j < currentFeed.userSortFlags.length; j++) {
      if (currentFeed.userSortFlags[j]['flag'] == true) {
        activeFilterCount++;
      }
    }


    if (currentFeed.feed_row.feed_display_view == 'pagination') {
      //reset responsive indexes because number of feed images may change after using filter
      currentFeed.resIndex = 0;
    }

    //applying filters
    if (activeFilterCount != 0) {
      wdi_front.filterData(currentFeed);
      wdi_front.displayFeed(currentFeed);
    } else {
      currentFeed.customFilteredData = currentFeed.dataStorageList;
      wdi_front.displayFeed(currentFeed);
    }


    if (currentFeed.feed_row.feed_display_view == 'pagination') {
      //reset paginator because while filtering images become more or less so pages also become more or less
      currentFeed.paginator = Math.ceil((currentFeed.imageIndex) / parseInt(currentFeed.feed_row.pagination_per_page_number));
      //setting current page as the last loaded page when filter is active
      currentFeed.currentPage = currentFeed.paginator; //pagination page number
      //when feed is displayed we are by default in the first page
      //so we are navigating from page 1 to current page using custom navigation method
      wdi_front.updatePagination(currentFeed, 'custom', 1);

      jQuery('#wdi_first_page').removeClass('wdi_disabled');
      jQuery('#wdi_last_page').addClass('wdi_disabled');
    }

  }
}

wdi_front.filterData = function (currentFeed)
{

  var users = currentFeed.userSortFlags;
  currentFeed.customFilteredData = [];
  for (var i = 0; i < currentFeed.dataStorageList.length; i++) {
    for (var j = 0; j < users.length; j++) {
      if ((currentFeed.dataStorageList[i]['user']['id'] == users[j]['id'] || currentFeed.dataStorageList[i]['wdi_hashtag'] == users[j]['name']) && users[j]['flag'] == true) {
        currentFeed.customFilteredData.push(currentFeed.dataStorageList[i]);
      }

    }
  }

}

wdi_front.applyFilters = function (currentFeed)
{
  for (var i = 0; i < currentFeed.userSortFlags.length; i++) {
    if (currentFeed.userSortFlags[i]['flag'] == true) {
      var userDiv = jQuery('#wdi_feed_' + currentFeed.feed_row.wdi_feed_counter + '[user_index="' + i + '"]');
      wdi_front.addFilter(i, currentFeed.feed_row.wdi_feed_counter);
      wdi_front.addFilter(i, currentFeed.feed_row.wdi_feed_counter);
    }
  }

}

//gets data Count from global storage
wdi_front.getImgCount = function (currentFeed)
{
  var dataStorage = currentFeed.dataStorage;
  var count = 0;
  for (var i = 0; i < dataStorage.length; i++) {
    count += dataStorage[i].length;
  }
  return count;
}

//parses image data for lightbox popup
wdi_front.parseLighboxData = function (currentFeed, filterFlag)
{

  var dataStorage = currentFeed.dataStorage;
  var sortImagesBy = currentFeed.feed_row['sort_images_by'];
  var sortOrder = currentFeed.feed_row['display_order'];
  var sortOperator = wdi_front.sortingOperator(sortImagesBy, sortOrder);
  var data = [];

  var popupData = [];
  var obj = {};

  //if filterFlag is true, it means that some filter for frontend content is enabled so give
  //lightbox only those images which are visible at that moment else give all avialable
  if (filterFlag == true) {
    data = currentFeed.customFilteredData;
  } else {
    for (var i = 0; i < dataStorage.length; i++) {
      for (var j = 0; j < dataStorage[i].length; j++) {
        data.push(dataStorage[i][j]);
      }
    }
    data.sort(sortOperator);
  }


  for (i = 0; i < data.length; i++) {
    obj = {
      'alt': '',
      'avg_rating': '',
      'comment_count': data[i]['comments']['count'],
      'date': wdi_front.convertUnixDate(data[i]['created_time']),
      'description': wdi_front.getDescription((data[i]['caption'] !== null) ? data[i]['caption']['text'] : ''),
      'filename': wdi_front.getFileName(data[i]),
      'filetype': wdi_front.getFileType(data[i]),
      'hit_count': '0',
      'id': data[i]['id'],
      'image_url': data[i]['link'],
      'number': 0,
      'rate': '',
      'rate_count': '0',
      'username': data[i]['user']['username'],
      'profile_picture': data[i]['user']['profile_picture'],
      'thumb_url': data[i]['link'] + 'media/?size=t',
      'comments_data': data[i]['comments']['data']
    }
    popupData.push(obj);
  }
  return popupData;
}

wdi_front.convertUnixDate = function (date)
{
  var utcSeconds = parseInt(date);
  var newDate = new Date(0);
  newDate.setUTCSeconds(utcSeconds);
  var str = newDate.getFullYear() + '-' + newDate.getMonth() + '-' + newDate.getDate();
  str += ' ' + newDate.getHours() + ':' + newDate.getMinutes();
  return str;
}

wdi_front.getDescription = function (desc)
{
  desc = desc.replace(/\r?\n|\r/g, ' ');


  return desc;
}


/**
 * use this data for lightbox
 * **/

wdi_front.getFileName = function (data)
{
  var link = data['link'];
  var type = data['type'];
  /*if pure video, not carousel*/
  if (type === 'video' && data.hasOwnProperty('videos')) {
    return data['videos']['standard_resolution']['url'];
  } else {
    var linkFragments = link.split('/');
    return linkFragments[linkFragments.length - 2];
  }

}

wdi_front.getFileType = function (data)
{
  /*if pure video, not carousel*/
  if (data['type'] == 'video' && data.hasOwnProperty('videos')) {
    return "EMBED_OEMBED_INSTAGRAM_VIDEO";
  }
  else {
    return "EMBED_OEMBED_INSTAGRAM_IMAGE";
  }
}


wdi_front.array_max = function (array)
{
  var max = array[0];
  var minIndex = 0;
  for (var i = 1; i < array.length; i++) {
    if (max < array[i]) {
      max = array[i];
      minIndex = i;
    }
  }
  return {
    'value': max,
    'index': minIndex
  };
}

wdi_front.array_min = function (array)
{
  var min = array[0];
  var minIndex = 0;
  for (var i = 1; i < array.length; i++) {
    if (min > array[i]) {
      min = array[i];
      minIndex = i;
    }
  }
  return {
    'value': min,
    'index': minIndex
  };
}

/*
 * Returns users count whose feed is not finished
 */
wdi_front.activeUsersCount = function (currentFeed)
{
  var counter = 0;
  for (var i = 0; i < currentFeed.usersData.length; i++) {
    if (currentFeed.usersData[i].finished != 'finished') {
      counter++;
    }
  }
  return counter;
}


/**
 * Return response if it is valid else returns boolean false
 * @param  {Object} response [instagram API response]
 * @return {Object or Boolean}          [false: if invalid response, object: if valid]
 */
wdi_front.checkMediaResponse = function (response)
{

  if (response == '' || typeof response == 'undefined' || response == null) {
    errorMessage = wdi_front_messages.connection_error;
    wdi_front.show_alert(errorMessage);
    return false;
  }
  if (response['meta']['code'] != 200) {
    errorMessage = response['meta']['error_message'];
    wdi_front.show_alert(errorMessage);
    return false;
  }
  return response;
}


/**
 * Removes # from string if it is first char
 * @param  {String} hashtag
 * @return {String}
 */
wdi_front.stripHashtag = function (hashtag)
{
  switch (hashtag[0]) {
    case '#':
    {
      return hashtag.substr(1, hashtag.length);
      break;
    }
    default:
    {
      return hashtag;
      break;
    }
  }
}

/**
 * Returns type of given input
 * @param  {String} input [this is username or hashtag]
 * @return {String}       [input type]
 */
wdi_front.getInputType = function (input)
{

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
 * Makes a regex search of a given word returns true if symbol before and after word is space
 * or word is in the beggining or in the end of string
 * @param  {String} captionText [String where search needs to be done]
 * @param  {String} searchkey   [word or phrazee to search]
 * @return {Boolean}
 */
wdi_front.regexpTestCaption = function (captionText, searchkey)
{
  var flag1 = false,
    flag2 = false,
    matchIndexes = [],
    escKey = searchkey.replace(/[-[\]{}()*+?.,\\^$|]/g, "\\$&"),
    regexp1 = new RegExp("(?:^|\\s)" + escKey + "(?:^|\\s)"),
    regexp2 = new RegExp("(?:^|\\s)" + escKey, 'g');
  if (regexp1.exec(captionText) != null) {
    flag1 = true;
  }

  while (( match = regexp2.exec(captionText) ) != null) {
    if (match.index == captionText.length - searchkey.length - 1) {
      flag2 = true;
    }
  }

  if (flag1 == true || flag2 == true) {
    return true;
  } else {
    return false;
  }

}


/**
 * replaces single new-lines with space
 * if multiple new lines are following each other then replaces all newlines with single space
 * @param  {String} string [input string]
 * @return {String}        [output string]
 */
wdi_front.replaceNewLines = function (string)
{
  var delimeter = "vUkCJvN2ps3t",
    matchIndexes = [],
    regexp;
  string = string.replace(/\r?\n|\r/g, delimeter);

  regexp = new RegExp(delimeter, 'g');
  while (( match = regexp.exec(string) ) != null) {
    matchIndexes.push(match.index);
  }

  var pieces = string.split(delimeter);
  var foundFlag = 0;

  for (var i = 0; i < pieces.length; i++) {

    if (pieces[i] == '') {
      foundFlag++;
    } else {
      foundFlag = 0;
    }

    if (foundFlag > 0) {
      pieces.splice(i, 1);
      foundFlag--;
      i--;
    }

  }
  string = pieces.join(' ');
  return string;
}


wdi_front.isEmptyObject = function (obj)
{
  for (var prop in obj) {
    if (obj.hasOwnProperty(prop))
      return false;
  }
  return true
}


var WDIFeed = function (obj)
{
  this['data'] = obj['data'];
  this['dataCount'] = obj['dataCount'];
  this['feed_row'] = obj['feed_row'];
  this['usersData'] = obj['usersData'];
  _this = this;

  this.set_images_loading_flag = function (_this)
  {
    window.addEventListener('load', function ()
    {
      //debugger;
      _this.nowLoadingImages = false;
    });
  }

  this.set_images_loading_flag(_this);


};

/**
 * Iterates through all filter objects and filters response accroding to them
 * @param  {Object} response    [instagram API response]
 * @param  {Object} args        [some custom arguments ]
 * @return {Object}             [instagram API response]
 */
WDIFeed.prototype.conditionalFilter = function (response, args)
{

  var currentFeed = this,
    conditional_filter_type = currentFeed.feed_row.conditional_filter_type,
    filters = currentFeed.feed_row.conditional_filters;


  if (args.ignoreFiltering == true) {

  } else {

    /**
     * Get rid of duplicate media
     */
    response = this.avoidDuplicateMedia(response);
  }


  //if filters json is invalid then return response without filtering
  if (!wdi_front.isJsonString(filters)) {
    return response;
  } else {
    filters = JSON.parse(filters);
    if (filters.length == 0) {
      return response;
    }
  }


  if (currentFeed.feed_row.conditional_filter_enable == '0') {
    return response;
  }


  //console.log('filtering');
  //increase counter for determing request count if this counter is more then
  //currentFeed.maxConditionalFiltersRequestCount then program will terminate recursion loop
  currentFeed.instagramRequestCounter++;

  switch (conditional_filter_type) {
    case 'AND':
    {
      response = this.applyANDLogic(response, filters, currentFeed);
      break;
    }
    case 'OR':
    {
      response = this.applyORLogic(response, filters, currentFeed)
      break;
    }
    case 'NOR':
    {
      response = this.applyNORLogic(response, filters, currentFeed)
      break;
    }
    default:
    {
      break;
    }
  }


  return response;
}


/**
 * Return those elements which meet filters conditions
 * @param  {Object} response [instagram API response]
 * @param  {Array} filters   [Array of filter objects]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.applyANDLogic = function (response, filters)
{
  var currentFeed = this;
  for (var i = 0; i < filters.length; i++) {
    response = this.filterResponse(response, filters[i]);
  }

  return response;
}

/**
 * Return those elements which meet filters conditions
 * @param  {Object} response [instagram API response]
 * @param  {Array} filters   [Array of filter objects]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.applyORLogic = function (response, filters)
{
  var currentFeed = this;
  var allData = [],
    res,
    mergedData = [],
    returnObject,
    media;

  for (var i = 0; i < filters.length; i++) {
    res = this.filterResponse(response, filters[i]);
    allData = allData.concat(res['data']);
    res = {};
  }

  for (i = 0; i < allData.length; i++) {
    media = allData[i];
    if (!this.mediaExists(media, mergedData) && !this.mediaExists(media, currentFeed.dataStorageList)) {
      mergedData.push(media);
    }
  }

  returnObject = {
    data: mergedData,
    meta: response['meta'],
    pagination: response['pagination']
  }
  return returnObject;
}


/**
 * Return those elements which meet filters conditions
 * @param  {Object} response [instagram API response]
 * @param  {Array} filters   [Array of filter objects]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.applyNORLogic = function (response, filters)
{

  var res = response,
    currentFeed = this,
    matchedData = this.applyORLogic(response, filters, currentFeed),
    mergedData = [],
    returnObject;

  for (var i = 0; i < res['data'].length; i++) {
    if (!this.mediaExists(res['data'][i], matchedData['data'])) {
      mergedData.push(res['data'][i]);
    }
  }

  returnObject = {
    data: mergedData,
    meta: res['meta'],
    pagination: res['pagination']
  }
  return returnObject;
}


WDIFeed.prototype.mediaExists = function (media, array)
{

  for (var i = 0; i < array.length; i++) {
    if (media['id'] == array[i]['id']) {
      return true;
    }
  }
  return false;
}


/**
 * checks filter type and calls corresponding function for filtering
 * @param  {Object} response                [instagram API response]
 * @param  {Object} filter                  [filter object created in backend]
 * @return {Object}                         [filtered response]
 */
WDIFeed.prototype.filterResponse = function (response, filter)
{

  switch (filter.filter_type) {
    case 'hashtag':
    {
      return this.filterByHashtag(response, filter);
      break;
    }
    case 'username':
    {
      return this.filterByUsername(response, filter);
      break;
    }
    case 'mention':
    {
      return this.filterByMention(response, filter);
      break;
    }
    case 'description':
    {
      return this.filterByDescription(response, filter);
      break;
    }
    case 'location':
    {
      return this.filterByLocation(response, filter);
      break;
    }
    case 'url':
    {
      return this.filterByUrl(response, filter);
      break;
    }
  }
}


/**
 * filters data by given hastag filter and returns filtered response
 * @param  {Object} response [instagram API response]
 * @param  {Object} filter   [hashtag filter object]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.filterByHashtag = function (response, filter)
{
  var filteredResponse = [],
    currentTag,
    media,
    returnObject;

  for (var i = 0; i < response['data'].length; i++) {
    media = response['data'][i];
    for (var j = 0; j < media['tags'].length; j++) {
      tag = media['tags'][j];
      if (tag.toLowerCase() == filter.filter_by.toLowerCase()) {

        filteredResponse.push(media);
      }
    }
  }


  returnObject = {
    data: filteredResponse,
    meta: response['meta'],
    pagination: response['pagination']
  }
  return returnObject;
}

/**
 * filters data by given username filter and returns filtered response
 * @param  {Object} response [instagram API response]
 * @param  {Object} filter   [hashtag filter object]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.filterByUsername = function (response, filter)
{
  var filteredResponse = [],
    media,
    returnObject;

  for (var i = 0; i < response['data'].length; i++) {
    media = response['data'][i];
    if (media.user.username.toLowerCase() == filter.filter_by.toLowerCase()) {
      filteredResponse.push(media);
    }
  }


  returnObject = {
    data: filteredResponse,
    meta: response['meta'],
    pagination: response['pagination']
  }
  return returnObject;
}


/**
 * filters data by given mention filter and returns filtered response
 * @param  {Object} response [instagram API response]
 * @param  {Object} filter   [hashtag filter object]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.filterByMention = function (response, filter)
{
  var filteredResponse = [],
    media, captionText, returnObject;
  for (var i = 0; i < response['data'].length; i++) {
    media = response['data'][i];
    if (media['caption'] !== null) {
      captionText = media['caption']['text'].toLowerCase();
      if (captionText.indexOf('@' + filter.filter_by.toLowerCase()) != -1) {

        filteredResponse.push(media);
      }
    }
  }

  returnObject = {
    data: filteredResponse,
    meta: response['meta'],
    pagination: response['pagination']
  }
  return returnObject;
}


/**
 * filters data by given description filter and returns filtered response
 * @param  {Object} response [instagram API response]
 * @param  {Object} filter   [hashtag filter object]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.filterByDescription = function (response, filter)
{
  var filteredResponse = [],
    media, captionText, returnObject;

  for (var i = 0; i < response['data'].length; i++) {
    media = response['data'][i];
    if (media['caption'] !== null) {

      captionText = media['caption']['text'].toLowerCase();
      captionText = wdi_front.replaceNewLines(captionText);
      var searchkey = filter.filter_by.toLowerCase();

      if (wdi_front.regexpTestCaption(captionText, searchkey)) {
        filteredResponse.push(media);
      }
    }
  }

  returnObject = {
    data: filteredResponse,
    meta: response['meta'],
    pagination: response['pagination']
  }

  return returnObject;
}


/**
 * filters data by given location filter and returns filtered response
 * @param  {Object} response [instagram API response]
 * @param  {Object} filter   [hashtag filter object]
 * @return {Object}          [filtered response]
 */
WDIFeed.prototype.filterByLocation = function (response, filter)
{
  var filteredResponse = [],
    media, locationId, returnObject;
  for (var i = 0; i < response['data'].length; i++) {
    media = response['data'][i];

    if (media['location'] !== null) {
      locationId = media['location']['id'];
      if (locationId == filter.filter_by) {
        filteredResponse.push(media);
      }
    }
  }

  returnObject = {
    data: filteredResponse,
    meta: response['meta'],
    pagination: response['pagination']
  }
  return returnObject;
}


/**
 * filters data by given url filter and returns filtered response
 * @param  {Object} response [instagram API response]
 * @param  {Object} filter   [hashtag filter object]
 * @return {Object}          [filtered response]
 */

WDIFeed.prototype.filterByUrl = function (response, filter)
{
  var filteredResponse = [],
    media, id, returnObject, filter_by;

  filter.filter_by = this.getIdFromUrl(filter.filter_by);

  for (var i = 0; i < response['data'].length; i++) {
    media = response['data'][i];

    if (media['link'] !== null) {
      id = this.getIdFromUrl(media['link']);
      if (id == filter.filter_by) {
        filteredResponse.push(media);
      }
    }
  }

  returnObject = {
    data: filteredResponse,
    meta: response['meta'],
    pagination: response['pagination']
  }
  return returnObject;
}

/**
 * gets id of media from url, this id is not the one which comes with api request
 * @param  {String} url [media url]
 * @return {String}
 */
WDIFeed.prototype.getIdFromUrl = function (url)
{
  var url_parts = url.split('/'),
    id = false;
  for (var i = 0; i < url_parts.length; i++) {
    if (url_parts[i] == 'p') {
      if (typeof url_parts[i + 1] != 'undefined') {
        id = url_parts[i + 1];
        break;
      }
    }
  }
  ;
  return id;
}


/**
 * Iterates throught response data and remove duplicate media
 * @param  {Object} response [Instagram API request]
 * @return {Object}          [response]
 */
WDIFeed.prototype.avoidDuplicateMedia = function (response)
{
  var data = response['data'],
    uniqueData = [],
    returnObject = {};
  if (typeof data == "undefined") {
    data = [];
  }

  for (var i = 0; i < data.length; i++) {
    if (!this.mediaExists(data[i], this.dataStorageList) && !this.mediaExists(data[i], uniqueData) && !this.mediaExists(data[i], this.conditionalFilterBuffer)) {
      uniqueData.push(data[i]);
    }
  }

  this.conditionalFilterBuffer = this.conditionalFilterBuffer.concat(uniqueData);

  returnObject = {
    data: uniqueData,
    meta: response['meta'],
    pagination: response['pagination']
  }

  return returnObject;

}


/* stores data from objects array into global variable */
WDIFeed.prototype.storeRawData = function (objects, variable)
{
  var _this = this;
  if (typeof this[variable] == "object" && typeof this[variable].length == "number") {
    //checks if in golbal storage user already exisit then it adds new data to user old data
    //else it simple puches new user with it's data to global storage
    for (var i = 0; i < objects.length; i++) {


      var hash_id = "";
      if (wdi_front.isHashtag(objects[i].user_id)) {
        hash_id = objects[i].pagination.next_max_tag_id;
      }
      else
        if (_this.feed_row.liked_feed == 'liked') {
          hash_id = objects[i].pagination.next_max_like_id;
          if (typeof hash_id == "undefined") {
            hash_id = "";
          }
        }
        else {

          /*strange bug sometimes happening in instagram API when user feed pagination is null*/
          if (objects[i].pagination == null) {
            objects[i].pagination = [];
          }

          hash_id = objects[i].pagination.next_max_id;
          if (typeof hash_id == "undefined") {
            hash_id = "";
          }


        }

      if (typeof this[variable][i] == "undefined") {
        this[variable].push({
          data: objects[i].data,
          index: 0,
          locked: false,
          hash_id: hash_id,
          usersDataFinished: false,
          userId: objects[i].user_id,
          length: function ()
          {
            return this.data.length - this.index;
          },
          getData: function (num)
          {
            var data = this.data.slice(this.index, this.index + num);
            this.index += Math.min(num, this.length());

            if (this.index == this.data.length && this.locked == true && this.usersDataFinished == false) {

              for (var j = 0; j < _this.usersData.length; j++) {
                if (_this.usersData[j]['user_id'] == this.userId) {
                  _this.usersData[j].finished = "finished";
                  this.usersDataFinished = true;
                  break;
                }
              }
            }
            return data;
          }
        });
      } else {
        if (this[variable][i].locked == false) {

          if (hash_id != this[variable][i].hash_id) {
            this[variable][i].data = this[variable][i].data.concat(objects[i].data);
            this[variable][i].hash_id = hash_id;
          } else {
            this[variable][i].locked = true;

          }
        }

      }
    }
  }

}


wdi_front.updateUsersIfNecessary = function (currentFeed)
{
  var users = currentFeed.feed_users;
  var ifUpdateNecessary = false;

  for (var i = 0; i < users.length; i++) {
    if ("#" == users[i].username.substr(0, 1)) {
      users[i].id = users[i].username;
      continue;
    }
    if ("" == users[i].id || 'username' == users[i].id) {

      ifUpdateNecessary = true;
      currentFeed.instagram.searchForUsersByName(users[i].username, {
        success: function (res)
        {
          if (res.meta.code == 200 && res.data.length > 0) {

            var found = false;

            for (var k = 0; k < res.data.length; k++) {
              if (res.data[k].username == res.args.username) {
                found = true;
                break;
              }
            }

            if (found) {
              for (var j = 0; j < users.length; j++) {
                if (res.data[k].username == users[j].username) {
                  users[j].id = res.data[k].id;
                }
              }
            }


          }

          var noid_user_left = false;
          for (var m = 0; m < users.length; m++) {
            if (users[m].id == "" || users[m].id == "username") {
              noid_user_left = true;
              break;
            }
          }
          if (!noid_user_left) {
            currentFeed.feed_row.feed_users = JSON.stringify(users);
            wdi_front.init(currentFeed);
          }

        },
        username: users[i].username
      });
    }
  }

  return ifUpdateNecessary;
}

if (typeof wdi_ajax.ajax_response != "undefined") {
  jQuery(document).one('ajaxStop', function ()
  {
    if (wdi_front['type'] != 'not_declared') {

      wdi_front.clickOrTouch = wdi_front.detectEvent();
      //initializing all feeds in the page
      wdi_front.globalInit();
    } else {
      return;
    }
  });


}
else {
  jQuery(document).ready(function ()
  {

    if (wdi_front['type'] != 'not_declared') {
      wdi_front.clickOrTouch = wdi_front.detectEvent();
      //initializing all feeds in the page
      wdi_front.globalInit();
    } else {
      return;
    }

  });
}


