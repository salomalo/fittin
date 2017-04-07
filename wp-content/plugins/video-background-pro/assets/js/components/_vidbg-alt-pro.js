/**
 * Video Backgrond Pro (vidbg component)
 * Copyright 2016 Blake Wilson and Push Labs
 */
!(function(root, factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    factory(require('jquery'));
  } else {
    factory(root.jQuery);
  }
})(this, function($) {

  'use strict';

  /**
   * Name of the plugin
   * @private
   * @const
   * @type {String}
   */
  var PLUGIN_NAME = 'vidbg';

  /**
   * Default settings
   * @private
   * @const
   * @type {Object}
   */
  var DEFAULTS = {
    volume: 1,
    playbackRate: 1,
    muted: true,
    loop: true,
    autoplay: true,
    position: '50% 50%',
    overlay: false,
    overlayColor: '#000',
    overlayAlpha: 0.3,
    overlayTexture: '',
    isFrontendPlay: false,
    isFrontendMute: false,
    frontendPosition: 'bottom-right',
    resizing: true
  };

  /**
   * Not implemented error message
   * @private
   * @const
   * @type {String}
   */
  var NOT_IMPLEMENTED_MSG = 'Not implemented';

  /**
   * Parse a string with options
   * @private
   * @param {String} str
   * @returns {Object|String}
   */
  function parseOptions(str) {
    var obj = {};
    var delimiterIndex;
    var option;
    var prop;
    var val;
    var arr;
    var len;
    var i;

    // Remove spaces around delimiters and split
    arr = str.replace(/\s*:\s*/g, ':').replace(/\s*,\s*/g, ',').split(',');

    // Parse a string
    for (i = 0, len = arr.length; i < len; i++) {
      option = arr[i];

      // Ignore urls and a string without colon delimiters
      if (
        option.search(/^(http|https|ftp):\/\//) !== -1 ||
        option.search(':') === -1
      ) {
        break;
      }

      delimiterIndex = option.indexOf(':');
      prop = option.substring(0, delimiterIndex);
      val = option.substring(delimiterIndex + 1);

      // If val is an empty string, make it undefined
      if (!val) {
        val = undefined;
      }

      // Convert a string value if it is like a boolean
      if (typeof val === 'string') {
        val = val === 'true' || (val === 'false' ? false : val);
      }

      // Convert a string value if it is like a number
      if (typeof val === 'string') {
        val = !isNaN(val) ? +val : val;
      }

      obj[prop] = val;
    }

    // If nothing is parsed
    if (prop == null && val == null) {
      return str;
    }

    return obj;
  }

  /**
   * Parse a position option
   * @private
   * @param {String} str
   * @returns {Object}
   */
  function parsePosition(str) {
    str = '' + str;

    // Default value is a center
    var args = str.split(/\s+/);
    var x = '50%';
    var y = '50%';
    var len;
    var arg;
    var i;

    for (i = 0, len = args.length; i < len; i++) {
      arg = args[i];

      // Convert values
      if (arg === 'left') {
        x = '0%';
      } else if (arg === 'right') {
        x = '100%';
      } else if (arg === 'top') {
        y = '0%';
      } else if (arg === 'bottom') {
        y = '100%';
      } else if (arg === 'center') {
        if (i === 0) {
          x = '50%';
        } else {
          y = '50%';
        }
      } else {
        if (i === 0) {
          x = arg;
        } else {
          y = arg;
        }
      }
    }

    return { x: x, y: y };
  }

  /*
   * Hex to RGB
   */
  function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
  }

  function isResSmall() {
    if( $(window).width() < 1000 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Vidbg constructor
   * @param {HTMLElement} element
   * @param {Object|String} path
   * @param {Object|String} options
   * @constructor
   */
  function Vidbg(element, path, options) {
    this.$element = $(element);

    // Parse path
    if (typeof path === 'string') {
      path = parseOptions(path);
    }

    // Parse options
    if (!options) {
      options = {};
    } else if (typeof options === 'string') {
      options = parseOptions(options);
    }

    this.settings = $.extend({}, DEFAULTS, options);
    this.path = path;

    try {
      this.init();
    } catch (e) {
      if (e.message !== NOT_IMPLEMENTED_MSG) {
        throw e;
      }
    }
  }

  /**
   * Initialization
   * @public
   */
  Vidbg.prototype.init = function() {
    var vidbg = this;
    var path = vidbg.path;
    var poster = path;
    var sources = '';
    var $element = vidbg.$element;
    var settings = vidbg.settings;
    var position = parsePosition(settings.position);
    var $video;
    var video;
    var $wrapper;
    var $overlay;
    var $overlayTexture;
    var $frontendButtonsEl;
    var $frontendButtonPlay;
    var $frontendButtonPlayAnchor;
    var $frontendButtonMute;
    var $frontendButtonMuteAnchor;

    // Set styles of a video wrapper
    $wrapper = vidbg.$wrapper = $('<div class="vidbg-container">').css({
      position: 'absolute',
      'z-index': -1,
      top: 0,
      left: 0,
      bottom: 0,
      right: 0,
      overflow: 'hidden',
      '-webkit-background-size': 'cover',
      '-moz-background-size': 'cover',
      '-o-background-size': 'cover',
      'background-size': 'cover',
      'background-repeat': 'no-repeat',
      'background-position': position.x + ' ' + position.y
    });

    // Get a poster path
    if (typeof path === 'object') {
      if (path.poster) {
        poster = path.poster;
      } else {
        if (path.mp4) {
          poster = path.mp4;
        } else if (path.webm) {
          poster = path.webm;
        }
      }
    }

    // Set a video poster
    if ( isResSmall() === true ) {
      $wrapper.css('background-image', 'url(' + poster + ')');
    } else {
      if ( path.mp4 || path.webm ) {
        setTimeout( function() {
          if ( $wrapper.css( 'background-image' ) === null ) {
            $wrapper.css('background-image', 'url(' + poster + ')');
          }
        }, 1250 );
      } else {
        $wrapper.css('background-image', 'url(' + poster + ')');
      }
    }

    // If a parent element has a static position, make it relative
    if ($element.css('position') === 'static') {
      $element.css('position', 'relative');
    }
    $element.css('z-index', '1');

    // If the element is set to body make the wrapper position fixed
    if($element.is("body")) {
      $wrapper.css({
        position: 'fixed'
      });
    }

    $element.prepend($wrapper);

    if (typeof path === 'object') {
      if (path.mp4) {
        sources += '<source src="' + path.mp4 + '" type="video/mp4">';
      }

      if (path.webm) {
        sources += '<source src="' + path.webm + '" type="video/webm">';
      }

      $video = vidbg.$video = $('<video>' + sources + '</video>');
    } else {
      $video = vidbg.$video = $('<video>' +
        '<source src="' + path + '" type="video/mp4">' +
        '<source src="' + path + '" type="video/webm">' +
        '</video>');
    }

    try {
      $video

        // Set video properties
        .prop({
          autoplay: settings.autoplay,
          loop: settings.loop,
          volume: settings.volume,
          muted: settings.muted,
          defaultMuted: settings.muted,
          playbackRate: settings.playbackRate,
          defaultPlaybackRate: settings.playbackRate
        });
    } catch (e) {
      throw new Error(NOT_IMPLEMENTED_MSG);
    }

    // Video alignment
    $video.css({
      margin: 'auto',
      position: 'absolute',
      'z-index': -1,
      top: position.y,
      left: position.x,
      '-webkit-transform': 'translate(-' + position.x + ', -' + position.y + ')',
      '-ms-transform': 'translate(-' + position.x + ', -' + position.y + ')',
      '-moz-transform': 'translate(-' + position.x + ', -' + position.y + ')',
      transform: 'translate(-' + position.x + ', -' + position.y + ')',
      'max-width' : 'none',

      // Disable visibility, while loading
      visibility: 'hidden',
      opacity: 0
    })


    // Resize a video, when it's loaded
    .one('canplaythrough.' + PLUGIN_NAME, function() {
      vidbg.resize();
    })

    // Make it visible, when it's already playing
    .one('playing.' + PLUGIN_NAME, function() {
      $video.css({
        visibility: 'visible',
        opacity: 1
      });
      $frontendButtonsEl.css('opacity', 1);
      $wrapper.css('background-image', 'none');
    });

    // Resize event is available only for 'window'
    // Use another code solutions to detect DOM elements resizing
    $element.on('resize.' + PLUGIN_NAME, function() {
      if (settings.resizing) {
        vidbg.resize();
      }
    });

    // Append a video
    $wrapper.append($video);


    $overlay = vidbg.$overlay = $('<div class="vidbg-overlay">').css({
      position: 'absolute',
      top: 0,
      left: 0,
      right: 0,
      bottom: 0,
    });

    if(settings.overlay === true ) {
      $overlay.css({
        background: 'rgba(' + hexToRgb(settings.overlayColor).r + ', ' + hexToRgb(settings.overlayColor).g + ', ' + hexToRgb(settings.overlayColor).b + ', ' + settings.overlayAlpha + ')',
      });
    }

    $wrapper.append($overlay);

    $overlayTexture = vidbg.$overlayTexture = $('.vidbg-overlay');

    if( settings.overlayTexture !== '' && settings.overlay === true ) {
      $element.find($overlayTexture).css({
        'background' : 'url(' + settings.overlayTexture + ')',
      });
    }


    if( settings.isFrontendPlay === true || settings.isFrontendMute === true ) {
      $element.append('<ul class="vidbg-frontend-buttons"></ul>');
    }

    $frontendButtonsEl = vidbg.$frontendButtonsEl = $element.find('.vidbg-frontend-buttons');
    var $videoEl = $element.find('video');

    /**
     * Frontend buttons position
     */
    if ($.inArray(settings.frontendPosition, ['top-right', 'bottom-right', 'bottom-left', 'top-left']) >= 0) {
      $frontendButtonsEl.addClass( settings.frontendPosition );
    }

    if( settings.isFrontendPlay ) {
      $frontendButtonsEl.append('<li class="vidbg-frontend-button vidbg-play-button"><a class="vidbg-toggle-pause"></a></li>');
      $frontendButtonPlayAnchor = vidbg.$frontendButtonPlayAnchor = $element.find('.vidbg-play-button > a');
      $frontendButtonPlay = vidbg.$frontendButtonPlay = $element.find('.vidbg-play-button').click(function() {
        if( $videoEl.get(0).paused === false ) {
          $videoEl.get(0).pause();
          $frontendButtonPlayAnchor.removeClass('vidbg-toggle-pause');
          $frontendButtonPlayAnchor.addClass('vidbg-toggle-play');
        } else {
          $videoEl.get(0).play();
          $frontendButtonPlayAnchor.removeClass('vidbg-toggle-play');
          $frontendButtonPlayAnchor.addClass('vidbg-toggle-pause');
        }
      });

      $videoEl.on('ended', function() {
        $frontendButtonPlayAnchor.removeClass('vidbg-toggle-pause');
        $frontendButtonPlayAnchor.addClass('vidbg-toggle-play');
      });
    }

    if( settings.isFrontendMute ) {
      if( settings.muted === false ) {
        var currentVideoAudioStatus = 'unmute';
      } else {
        var currentVideoAudioStatus = 'mute';
      }
      $frontendButtonsEl.append('<li class="vidbg-frontend-button vidbg-mute-button"><a class="vidbg-toggle-' + currentVideoAudioStatus + '"></a></li>');
      $frontendButtonMuteAnchor = vidbg.$frontendButtonMuteAnchor = $element.find('.vidbg-mute-button > a');
      $frontendButtonMute = vidbg.$frontendButtonMute = $element.find('.vidbg-mute-button').click(function() {
        if( $videoEl.prop('muted') ) {
          $videoEl.prop('muted', false);
          $frontendButtonMuteAnchor.removeClass('vidbg-toggle-mute');
          $frontendButtonMuteAnchor.addClass('vidbg-toggle-unmute');
        } else {
          $videoEl.prop('muted', true);
          $frontendButtonMuteAnchor.removeClass('vidbg-toggle-unmute');
          $frontendButtonMuteAnchor.addClass('vidbg-toggle-mute');
        }
      });
    }

  };

  /**
   * Get a video element
   * @public
   * @returns {HTMLVideoElement}
   */
  Vidbg.prototype.getVideoObject = function() {
    return this.$video[0];
  };

  /**
   * Resize a video background
   * @public
   */
  Vidbg.prototype.resize = function() {
    if (!this.$video) {
      return;
    }

    var $wrapper = this.$wrapper;
    var $video = this.$video;
    var video = $video[0];

    // Get a native video size
    var videoHeight = video.videoHeight;
    var videoWidth = video.videoWidth;

    // Get a wrapper size
    var wrapperHeight = $wrapper.height();
    var wrapperWidth = $wrapper.width();

    if (wrapperWidth / videoWidth > wrapperHeight / videoHeight) {
      $video.css({

        // +2 pixels to prevent an empty space after transformation
        width: wrapperWidth + 2,
        height: 'auto'
      });
    } else {
      $video.css({
        width: 'auto',

        // +2 pixels to prevent an empty space after transformation
        height: wrapperHeight + 2
      });
    }
  };

  /**
   * Destroy a video background
   * @public
   */
  Vidbg.prototype.destroy = function() {
    delete $[PLUGIN_NAME].lookup[this.index];
    this.$video && this.$video.off(PLUGIN_NAME);
    this.$element.off(PLUGIN_NAME).removeData(PLUGIN_NAME);
    this.$wrapper.remove();
  };

  /**
   * Special plugin object for instances.
   * @public
   * @type {Object}
   */
  $[PLUGIN_NAME] = {
    lookup: []
  };

  /**
   * Plugin constructor
   * @param {Object|String} path
   * @param {Object|String} options
   * @returns {JQuery}
   * @constructor
   */
  $.fn[PLUGIN_NAME] = function(path, options) {
    var instance;

    this.each(function() {
      instance = $.data(this, PLUGIN_NAME);

      // Destroy the plugin instance if exists
      instance && instance.destroy();

      // Create the plugin instance
      instance = new Vidbg(this, path, options);
      instance.index = $[PLUGIN_NAME].lookup.push(instance) - 1;
      $.data(this, PLUGIN_NAME, instance);
    });

    return this;
  };

  $(document).ready(function() {
    var $window = $(window);

    // Window resize event listener
    $window.on('resize.' + PLUGIN_NAME, function() {
      for (var len = $[PLUGIN_NAME].lookup.length, i = 0, instance; i < len; i++) {
        instance = $[PLUGIN_NAME].lookup[i];

        if (instance && instance.settings.resizing) {
          instance.resize();
        }
      }
    });

    $window.on('unload.' + PLUGIN_NAME, function() {
      return false;
    });

    // HTML initialization
    // Add 'data-vidbg-bg' attribute with a path to the video
    // Pass options throw the 'data-vidbg-options' attribute
    $(document).find('[data-' + PLUGIN_NAME + '-bg]').each(function(i, element) {
      var $element = $(element);
      var options = $element.data(PLUGIN_NAME + '-options');
      var path = $element.data(PLUGIN_NAME + '-bg');

      $element[PLUGIN_NAME](path, options);
    });

  });

});
