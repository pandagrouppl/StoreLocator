/**
 * WDIInstagram is jQuery based plugin which handles communication
 * with instagram API endpoints
 *
 * Plugin Version: 1.0.0
 * Author: Melik Karapetyan
 * License: GPLv2 or later
 *
 *
 *
 *
 *
 * Methods:
 *    getSelfInfo = function( args ) : Get information about the owner of the access_token.
 *    searchForUsersByName = function( username, args ) : Get a list of users matching the query.
 *      searchForTagsByName = function(tagname, args) : Search for tags by name.
 *      getTagRecentMedia = function(tagname, args) : Gets recent media based on tagname
 *
 */



/**
 * example of arg
 * @type {Object}
 */
// var args = {
//  access_tokens: ['227416602.145c5c2.302096fa9b3b4a8bbe0cee9341a6d7f5'],
//  filters: [{
//  where: 'getTagRecentMedia',
//  what: function(r) {
//    return r;
//  },
// }, {
//  where: 'searchForUsersByName',
//    what: 'bbb',
//  }],
// }


/**
 * WDIInstagram object constructor
 * @param {Object} args
 *
 * @param {Array}           [args.access_tokens] [array of lavid instagram access tokens]
 * @param {Array}           [args.filters] [array of object defining filters]
 * @param {Object}          [args.filters[i] ] [ filter object which contain 'where' : 'what' pair ]
 * @param {String}          [args.filters.filter[i].where] [name of function where filter must be applied]
 * @param {String or Array} [args.filters.filter[i].what] [name of filtering function,
 *                       if function is in global scope then it should be name of the funtion
 *                         else if function in method of some object then it should be an array
 *                                ['parent_object_name','filtering_function_name']]
 */
function WDIInstagram(args)
{

  this.access_tokens = [];
  this.filters = [];
  if (typeof args != 'undefined') {
    if (typeof args.access_tokens != 'undefined') {
      this.access_tokens = args.access_tokens;
    }
    if (typeof args.filters != 'undefined') {
      this.filters = args.filters;
    }
  }


  var _this = this;

  /**
   * Default object for handling status codes
   * @type {Object}
   */
  this.statusCode = {
    429: function ()
    {
      console.log(' 429: Too many requests. Try after one hour');
    },
  }

  /**
   * gets filter function defined for specific method
   * this function is internal function and cannot be called outside of this object
   *
   * @param  {String} methodName   [name of WDIInstagram method]
   * @return {Function}            [filtering function for {methodName}]
   */
  this.getFilter = function (methodName)
  {
    var filters = _this.filters;
    if (typeof filters == "undefined") {
      return false;
    }

    for (var i = 0; i < filters.length; i++) {
      if (filters[i].where == methodName) {

        if (typeof filters[i].what == 'object' && filters[i].what.length == 2) {
          if (typeof window[filters[i].what[0]] != 'undefined') {
            if (typeof window[filters[i].what[0]][filters[i].what[1]] == 'function') {
              return window[filters[i].what[0]][filters[i].what[1]];
            }
          }
        } else
          if (typeof filters[i].what == 'string') {
            if (typeof window[filters[i].what] == 'function') {
              return window[filters[i].what];
            }
          } else
            if (typeof filters[i].what == 'function') {
              return filters[i].what;
            } else {
              return false;
            }
      }
    }
    return false;
  }

  function getAccessToken()
  {
    var access_tokens = _this.access_tokens,
      index = parseInt(Math.random(0, 1) * access_tokens.length);
    return access_tokens[index];
  }

  /**
   * Adds access token to this.access_tokens array
   * non string values are not allowed
   * @param {String} token [Instagram API access token]
   */
  this.addToken = function (token)
  {
    if (typeof token == 'string') {
      _this.access_tokens.push(token);
    }
  }

  /**
   * Gets recent media based on tagname
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition media_count      => number of media to request
   * @definition min_tag_id       => Return media before this min_tag_id.
   * @definition max_tag_id       => Return media after this max_tag_id.
   * @definition statusCode       => StatusCode object.
   *
   * @param tagname               =>  A valid tag name without a leading #. (eg. snowy, nofilter)
   * @param args = {
   *       success    :   'success_callback',
   *       error    :   'error_callback',
   *       statusCode : statusCode
   *       count      :   'media_count',
   *     min_tag_id :   'min_tag_id',
   *     max_tag_id :   'max_tag_id',
   *     args : arguments to be passed to filtering function
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded media
   */
  this.getTagRecentMedia = function (tagname, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode,
      errorFlag = false,
      argFlag = false,
      filter = this.getFilter('getTagRecentMedia'),

      baseUrl = 'https://api.instagram.com/v1/tags/' + tagname + '/media/recent?access_token=' + getAccessToken();


    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {

      if ('success' in args) {
        successFlag = true;
      }
      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
      if ('error' in args) {
        errorFlag = true;
      }
//
      if ('args' in args) {
        argFlag = true;
      } else {
        args.args = {};
      }
//
      if ('count' in args) {
        args['count'] = parseInt(args['count']);
        if (!Number.isInteger(args['count']) || args['count'] <= 0) {
          args.count = 33;
        }
      } else {
        args.count = 33;
      }

      baseUrl += '&count=' + args.count;

      if ('min_tag_id' in args) {
        baseUrl += '&min_tag_id=' + args.min_tag_id;
      }

      if ('max_tag_id' in args) {
        baseUrl += '&max_tag_id=' + args.max_tag_id;
      }
    }

    jQuery.ajax({
      type: 'POST',
      url: baseUrl,
      dataType: 'jsonp',
      success: function (response)
      {
        if (typeof response["data"] === "undefined") response["data"] = [];

        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }

          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode
    });

  }


  /**
   * Search for tags by name.
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   *
   * @param tagname               =>  A valid tag name without a leading #. (eg. snowy, nofilter)
   * @param args = {
   *       success: 'success_callback',
   *       error:   'error_callback',
   *       statusCode : statusCode,
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded media
   */

  this.searchForTagsByName = function (tagname, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode;
    errorFlag = false;
    filter = this.getFilter('searchForTagsByName');

    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }
      if ('error' in args) {
        errorFlag = true;
      }
      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
    }


    jQuery.ajax({
      type: 'POST',
      url: 'https://api.instagram.com/v1/tags/search?q=' + tagname + '&access_token=' + getAccessToken(),
      dataType: 'jsonp',
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode
    });
  }


  /**
   * Get a list of users matching the query.
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   *
   * @param username
   * @param args = {
   *       success: 'success_callback',
   *       error:   'error_callback',
   *       statusCode : statusCode
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded users
   */
  this.searchForUsersByName = function (username, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode,
      errorFlag = false,
      filter = this.getFilter('searchForUsersByName');

    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }
      if ('error' in args) {
        errorFlag = true;
      }
      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
    }


    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: 'https://api.instagram.com/v1/users/search?q=' + username + '&access_token=' + getAccessToken(),
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                response.args = args;
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                response.args = args;
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                response.args = args;
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: this.statusCode

    });
  }


  /**
   * Get the list of recent media liked by the owner of the access_token.
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   * @param args = {
   *       success: 'success_callback',
   *       error:   'error_callback',
   *       statusCode : statusCode
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   * @return object of founded media
   */

  this.getRecentLikedMedia = function (args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode,
      errorFlag = false,
      filter = this.getFilter('getRecentLikedMedia'),
      baseUrl = 'https://api.instagram.com/v1/users/self/media/liked?access_token=' + getAccessToken();

    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }
      if ('error' in args) {
        errorFlag = true;
      }
      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
      if ('args' in args) {
        argFlag = true;
      } else {
        args.args = {};
      }

      if ('count' in args) {
        args['count'] = parseInt(args['count']);
        if (!Number.isInteger(args['count']) || args['count'] <= 0) {
          args.count = 20;
        }
      } else {
        args.count = 20;
      }

      baseUrl += '&count=' + args.count;

      if ('next_max_like_id' in args) {
        baseUrl += '&next_max_like_id=' + args.next_max_like_id;
      }


    }


    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: baseUrl,
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });
  }


  /**
   * Get the most recent media published by a user.
   * This endpoint requires the public_content scope if the user-id is not the owner of the access_token.
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition media_count      => number of media to request
   * @definition min_id           => Return media before this min_id.
   * @definition max_id           => Return media after this max_id.
   * @definition statusCode       => StatusCode object.
   *
   * @param args = {
   *       success : 'success_callback',
   *       error   : 'error_callback',
   *       statusCode : statusCode,
   *       count   : 'media_count',
   *       min_id  : 'min_id',
   *     max_id  : 'max_id',
   *     args: arguments to be passed to filtering function
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded media
   */
  this.getUserRecentMedia = function (user_id, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      argFlag = false,
    //internal default object for statusCode handling
      statusCode = this.statusCode,
      errorFlag = false,
      filter = this.getFilter('getUserRecentMedia'),
      baseUrl = 'https://api.instagram.com/v1/users/' + user_id + '/media/recent/?access_token=' + getAccessToken();


    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }

      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }

      if ('args' in args) {
        argFlag = true;
      } else {
        args.args = {};
      }

      if ('error' in args) {
        errorFlag = true;
      }

      if ('count' in args) {
        args['count'] = parseInt(args['count']);
        if (!Number.isInteger(args['count']) || args['count'] <= 0) {
          args.count = 33;
        }
      } else {
        args.count = 33;
      }

      baseUrl += '&count=' + args.count;

      if ('min_id' in args) {
        baseUrl += '&min_id=' + args.min_id;
      }

      if ('max_id' in args) {
        baseUrl += '&max_id=' + args.max_id;
      }
    }


    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: baseUrl,
      success: function (response)
      {
        if (typeof response["data"] === "undefined") response["data"] = [];

        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });

  }


  /**
   * Get the most recent media published by the owner of the access_token.
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition media_count      => number of media to request
   * @definition min_id           => Return media before this min_id.
   * @definition max_id           => Return media after this max_id.
   * @definition statusCode       => StatusCode object.
   *
   * @param args = {
   *       success : 'success_callback',
   *       error   : 'error_callback',
   *       count   : 'media_count',
   *       min_id  : 'min_id'
   *     max_id  : 'max_id'
   *     statusCode : statusCode
   *
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded media
   */
  this.getSelfRecentMedia = function (args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode;
    errorFlag = false,
      filter = this.getFilter('getSelfRecentMedia'),
      baseUrl = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' + getAccessToken();

    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }

      if ('error' in args) {
        errorFlag = true;
      }

      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }

      if ('count' in args) {
        args['count'] = parseInt(args['count']);
        if (!Number.isInteger(args['count']) || args['count'] <= 0) {
          args.count = 33;
        }
      } else {
        args.count = 33;
      }

      baseUrl += '&count=' + args.count;

      if ('min_id' in args) {
        baseUrl += '&min_id=' + args.min_id;
      }

      if ('max_id' in args) {
        baseUrl += '&max_id=' + args.max_id;
      }
    }

    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: baseUrl,
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });
  }


  /**
   * Get information about a user.
   * This endpoint requires the public_content scope if the user-id is not the owner of the access_token.
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   *
   * @param args = {
   *       success : 'success_callback',
   *       error   : 'error_callback'
   *       statusCode : statusCode
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded info
   */
  this.getUserInfo = function (user_id, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode,
      errorFlag = false,
      filter = this.getFilter('getUserInfo');

    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }

      if ('error' in args) {
        errorFlag = true;
      }

      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
    }
    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: 'https://api.instagram.com/v1/users/' + user_id + '/?access_token=' + getAccessToken(),
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });
  }


  /**
   * Get information about the owner of the access_token.
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   *
   * @param args = {
   *       success : 'success_callback',
   *       error   : 'error_callback'
   *       statusCode : statusCode
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded info
   */
  this.getSelfInfo = function (args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode,
      errorFlag = false,
      filter = this.getFilter('getSelfInfo');
    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }

      if ('error' in args) {
        errorFlag = true;
      }

      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
    }
    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: 'https://api.instagram.com/v1/users/self/?access_token=' + getAccessToken(),
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });
  }


  /**
   * Get a list of recent comments on a media object.
   * The public_content permission scope is required to get comments for a media
   * that does not belong to the owner of the access_token.
   *
   * @media_id                    => id of the media which comments must be getted
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   *
   * @param args = {
   *       success : 'success_callback',
   *       error   : 'error_callback'
   *       statusCode : statusCode
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded comments
   */
  this.getRecentMediaComments = function (media_id, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode,
      errorFlag = false,
      filter = this.getFilter('getRecentMediaComments');
    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }

      if ('error' in args) {
        errorFlag = true;
      }

      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
    }
    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: 'https://api.instagram.com/v1/media/' + media_id + '/comments?access_token=' + getAccessToken(),
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });
  }


  /**
   * Get a list of users who have liked this media.
   *
   * @media_id                    => id of the media which comments must be getted
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   *
   * @param args = {
   *       success : 'success_callback',
   *       error   : 'error_callback'
   *       statusCode : statusCode
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded comments
   */
  this.getRecentMediaLikes = function (media_id, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      statusCode = this.statusCode,
      errorFlag = false,
      filter = this.getFilter('getRecentMediaLikes');

    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }

      if ('error' in args) {
        errorFlag = true;
      }

      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
    }
    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: 'https://api.instagram.com/v1/media/' + media_id + '/likes?access_token=' + getAccessToken(),
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });
  }


  /**
   * make an ajax request based on url
   *
   *
   * @definition success_callback => which function to call in case of success
   * @definition error_callback   => which function to call in case of error
   * @definition statusCode       => StatusCode object.
   *
   * @param args = {
   *       success : 'success_callback',
   *       error   : 'error_callback',
   *       statusCode : statusCode,
   *       args.args : arguments to be passed to filter function
   *  }
   *
   *
   * if callback function is property of any other object just give it as array [ 'parent_object', 'callback_function']
   * or you can pass as callback function an anonymous function
   *
   *
   * @return object of founded media
   */
  this.requestByUrl = function (requestUrl, args)
  {
    var instagram = this,
      noArgument = false,
      successFlag = false,
      errorFlag = false,
      argFlag = false,
      statusCode = this.statusCode,
      filter = this.getFilter('requestByUrl'),
      urlParts,
      urlPart;

    //changing access token to random one
    urlParts = requestUrl.split('?')[1].split('&');
    for (var i = 0; i < urlParts.length; i++) {
      urlParts[i] = urlParts[i].split('=');
      if (urlParts[i][0] == 'access_token') {
        urlParts[i][1] = getAccessToken();
      }
      urlParts[i] = urlParts[i].join('=');
    }
    urlParts = urlParts.join('&');
    requestUrl = requestUrl.split('?')[0] + '?' + urlParts;


    if (typeof args == 'undefined' || args.length === 0) {
      noArgument = true;
    } else {
      if ('success' in args) {
        successFlag = true;
      }

      if ('args' in args) {
        argFlag = true;
      } else {
        args.args = {};
      }


      if ('error' in args) {
        errorFlag = true;
      }

      if ('statusCode' in args) {
        statusCode = args['statusCode'];
      }
    }


    jQuery.ajax({
      type: 'POST',
      dataType: 'jsonp',
      url: requestUrl,
      success: function (response)
      {
        if (successFlag) {
          if (typeof args.success == 'object' && args.success.length == 2) {
            if (typeof window[args.success[0]] != 'undefined') {
              if (typeof window[args.success[0]][args.success[1]] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success[0]][args.success[1]](response);
              }
            }
          } else
            if (typeof args.success == 'string') {
              if (typeof window[args.success] == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                window[args.success](response);
              }
            } else
              if (typeof args.success == 'function') {
                if (filter) {
                  response = filter(response, instagram.filterArguments, args.args);
                }
                args.success(response);
              }
        }
      },
      error: function (response)
      {
        if (errorFlag) {
          if (typeof args['error'] == 'object' && args['error'].length == 2) {
            if (typeof window[args['error'][0]][args['error'][1]] == 'function') {
              window[args['error'][0]][args['error'][1]](response);
            }
          } else
            if (typeof args['error'] == 'string') {
              if (typeof window[args['error']] == 'function') {
                window[args['error']](response);
              }
            } else
              if (typeof args['error'] == 'function') {
                args['error'](response);
              }
        }
      },
      statusCode: statusCode

    });

  }
}


