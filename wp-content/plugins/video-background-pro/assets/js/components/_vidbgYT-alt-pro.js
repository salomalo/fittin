/**
 * Video Background Pro (vidbgYT component)
 * Copyright 2016 Blake Wilson and Push Labs
 */
(function($){
    $.vidbgYT = function(el, options){

        window.InstanceCollection = window.InstanceCollection || [];

        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Add a reverse reference to the DOM object
        base.$el.data("vidbgYT", base);

        function randomId() {
          var S4 = function() {
             return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
          };
          return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
        }

        function isResSmall() {
          if( $(window).width() < 1000 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            return true;
          } else {
            return false;
          }
        }

        function getYouTubeIdParameter(name, url) {
          if (!url) url = window.location.href;
          name = name.replace(/[\[\]]/g, "\\$&");
          var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
              results = regex.exec(url);
          if (!results) return null;
          if (!results[2]) return '';
          return decodeURIComponent(results[2].replace(/\+/g, " "));
        }


        var $thePlayerId = 'yt-' + randomId();

        base.init = function(){
          base.options = $.extend({},$.vidbgYT.defaultOptions, options);
          var $wrapper;
          var $loadingEl;
          var $iframeHolder;
          var $ytOverlay;
          var $overlayTexture;

          $wrapper = base.$wrapper = $('<div class="vidbg-container">').css({
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
            'background-position': '50% 50%',
          });

          // If a parent element has a static position, make it relative
          if (base.$el.css('position') === 'static') {
            base.$el.css('position', 'relative');
          }
          base.$el.css('z-index', '1');

          $(base.$el).prepend($wrapper);

          /**
           * If container is body make position fixed
           */
           if ( base.$el.is( 'body' ) ) {
             $wrapper.css({
               position: 'fixed'
             });
           }

          $loadingEl = base.$loadingEl = $('<div class="vidbg-loader">').css({
            position: 'absolute',
            top: 0,
            left: 0,
            bottom: 0,
            right: 0,
            background: base.options.loaderBgColor,
          });

          $loadingEl.prepend('<div class="sk-fading-circle"><div class="sk-circle1 sk-circle"></div><div class="sk-circle2 sk-circle"></div><div class="sk-circle3 sk-circle"></div><div class="sk-circle4 sk-circle"></div><div class="sk-circle5 sk-circle"></div><div class="sk-circle6 sk-circle"></div><div class="sk-circle7 sk-circle"></div><div class="sk-circle8 sk-circle"></div><div class="sk-circle9 sk-circle"></div><div class="sk-circle10 sk-circle"></div><div class="sk-circle11 sk-circle"></div><div class="sk-circle12 sk-circle"></div></div>');

          $wrapper.prepend($loadingEl);


          $iframeHolder = base.$iframeHolder = $('<div class="vidbgYT-video" id="' + $thePlayerId + '">').css({
            position : 'absolute',
            top : 0,
            left : 0,
            bottom : 0,
            right : 0,
            'z-index' : '-1',
            'opacity' : 0,
            'max-width' : 'none',
            '-webkit-transition' : 'opacity 0.5s ease',
            '-moz-transition' : 'opacity 0.5s ease',
            'transition' : 'opacity 0.5s ease',
          });

          $wrapper.prepend($iframeHolder);


          $ytOverlay = base.$ytOverlay = $('<div class="vidbg-overlay">').css({
            position: 'absolute',
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            opacity: 0,
            '-webkit-transition' : 'opacity 0.5s ease',
            '-moz-transition' : 'opacity 0.5s ease',
            'transition' : 'opacity 0.5s ease',
          });

          if(base.options.overlay === true) {
            $ytOverlay.css({
              background: 'rgba(' + base.hexToRgb(base.options.overlayColor).r + ', ' + base.hexToRgb(base.options.overlayColor).g + ', ' + base.hexToRgb(base.options.overlayColor).b + ', ' + base.options.overlayAlpha + ')',
            });
          }

          $wrapper.append($ytOverlay);

          $overlayTexture = base.$overlayTexture = $('.vidbg-overlay');

          if( base.options.overlayTexture !== '' && base.options.overlay === true ) {
            base.$el.find($overlayTexture).css({
              'background' : 'url(' + base.options.overlayTexture + ')',
            });
          }

          if( isResSmall() === true ) {
            $wrapper.css({
              'background-image' : 'url(' + base.options.poster + ')',
            });
            $loadingEl.hide();
            $iframeHolder.hide();
            $ytOverlay.hide();
          }

          base.loadAPI();
        };

        this.onYouTubePlayerAPIReady = function(){
          base.$thePlayerId = new YT.Player( $thePlayerId, {events: {'onReady': base.onPlayerReady, 'onStateChange': base.onPlayerStateChange}, playerVars: base.playerDefaults});

          $(window).on('load resize', function(){
            base.vidRescale();
          });
        };

        base.loadAPI = function() {
          var $playerDefaults;
          var tag = document.createElement('script');
          tag.src = 'https://www.youtube.com/iframe_api';

          var firstScriptTag = document.getElementsByTagName('script')[0];
          firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

          $playerDefaults = base.playerDefaults = {
            autoplay: 0,
            autohide: 1,
            modestbranding: 0,
            rel: 0,
            loop: 1,
            showinfo: 0,
            controls: 0,
            disablekb: 0,
            enablejsapi: 0,
            iv_load_policy: 3,
          };

          window.InstanceCollection.push(this);
        };

        base.onPlayerReady = function(){
          var $wrapper = base.$wrapper;
          var $frontendButtonsEl;
          var $frontendButtonPlay;
          var $frontendButtonPlayAnchor;
          var $frontendButtonMute;
          var $frontendButtonMuteAnchor;

          base.$thePlayerId.loadVideoById({'videoId': getYouTubeIdParameter('v', base.options.videoID ),
                                           'suggestedQuality': 'hd720'});

          if(base.options.mute === true || isResSmall() === true ){
            base.$thePlayerId.mute();
          }

          if( base.options.isFrontendPlay === true || base.options.isFrontendMute === true ) {
            base.$el.append('<ul class="vidbg-frontend-buttons"></ul>');
          }

          $frontendButtonsEl = base.$frontendButtonsEl = base.$el.find('.vidbg-frontend-buttons');
          if( base.options.isFrontendPlay === true ) {
            $frontendButtonsEl.append('<li class="vidbg-frontend-button vidbg-play-button"><a class="vidbg-toggle-pause"></a></li>');
            $frontendButtonPlayAnchor = base.$frontendButtonPlayAnchor = base.$el.find('.vidbg-play-button > a');
            $frontendButtonPlay = base.$frontendButtonPlay = base.$el.find('.vidbg-play-button').click(function() {
              if (myPlayerState == 1) {
                base.$thePlayerId.pauseVideo();
                $frontendButtonPlayAnchor.removeClass('vidbg-toggle-pause');
                $frontendButtonPlayAnchor.addClass('vidbg-toggle-play');
              } else {
                base.$thePlayerId.playVideo();
                $frontendButtonPlayAnchor.removeClass('vidbg-toggle-play');
                $frontendButtonPlayAnchor.addClass('vidbg-toggle-pause');
              }
            });
          }

          if( base.options.isFrontendMute === true ) {
            if( base.options.mute === true ) {
              var currentVideoAudioStatus = 'mute';
            } else {
              var currentVideoAudioStatus = 'unmute';
            }
            $frontendButtonsEl.append('<li class="vidbg-frontend-button vidbg-mute-button"><a class="vidbg-toggle-' + currentVideoAudioStatus + '"></a></li>');

            $frontendButtonMute = base.$frontendButtonMute = base.$el.find('.vidbg-mute-button').click(function() {
              $frontendButtonMuteAnchor = base.$frontendButtonMuteAnchor = base.$el.find('.vidbg-mute-button > a');
              if( base.$thePlayerId.isMuted() === true ){
                base.$thePlayerId.unMute();
                $frontendButtonMuteAnchor.removeClass('vidbg-toggle-mute');
                $frontendButtonMuteAnchor.addClass('vidbg-toggle-unmute');
              } else {
                base.$thePlayerId.mute();
                $frontendButtonMuteAnchor.removeClass('vidbg-toggle-unmute');
                $frontendButtonMuteAnchor.addClass('vidbg-toggle-mute');
              }
            });
          }

          /**
           * Frontend buttons position
           */
          if ($.inArray(base.options.frontendPosition, ['top-right', 'bottom-right', 'bottom-left', 'top-left']) >= 0) {
            $frontendButtonsEl.addClass( base.options.frontendPosition );
          }

          if( isResSmall() === true ) {
            $frontendButtonsEl.hide();
          }
        };

        base.onPlayerStateChange = function(e) {
          var $wrapper = base.$wrapper;
          if (e.data === 1){
            base.$el.find('.vidbg-loader').fadeOut(450, function() {
              $(this).remove();
            });
            $('#' + $thePlayerId).addClass('active');
            $('#' + $thePlayerId).css({
              'opacity' : 1,
            });
            $wrapper.find('.vidbg-overlay').css({
              'opacity' : 1,
            });
            base.$el.find('.vidbg-frontend-buttons').css({
              'opacity' : 1,
            });
          } else if (e.data === 0){
            if(base.options.repeat === true) {
              base.$thePlayerId.seekTo(0);
            } else {
              base.$thePlayerId.seekTo(0);
              base.$thePlayerId.pauseVideo();
              $frontendButtonPlayAnchor = base.$frontendButtonPlayAnchor = base.$el.find('.vidbg-play-button > a').removeClass('vidbg-toggle-pause');
              $frontendButtonPlayAnchor.addClass('vidbg-toggle-play');
            }
          }
          myPlayerState = e.data;
        };

        base.hexToRgb = function(hex) {
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
        };

        base.vidRescale = function(){
          var container = base.$el;

          var width = container.outerWidth(),
             pWidth, // player width, to be defined
             height = container.outerHeight(),
             pHeight, // player height, tbd
             $theEl = $('#' + $thePlayerId),
             ratio = 16 / 9;

           // when screen aspect ratio differs from video, video must center and underlay one dimension
           if (width / ratio < height) {
             pWidth = Math.ceil(height * ratio); // get new player width
             $theEl.outerWidth(pWidth).outerHeight(height).css({
               left: (width - pWidth) / 2,
               top: 0
             }); // player width is greater, offset left; reset top
           } else { // new video width < window width (gap to right)
             pHeight = Math.ceil(width / ratio); // get new player height
             $theEl.outerWidth(width).outerHeight(pHeight).css({
               left: 0,
               top: (height - pHeight) / 2
             }); // player height is greater, offset top; reset left
           }

           $YTPlayerPlayer = null;
           container = null;

        };


        // Run initializer
        base.init();

    };

    $.vidbgYT.defaultOptions = {
        videoID: '#',
        poster: '#',
        mute: true,
        repeat: true,
        overlay: false,
        overlayColor: '#000',
        overlayAlpha: '0.3',
        overlayTexture: '',
        isFrontendPlay: false,
        isFrontendMute: false,
        frontendPosition: 'bottom-right',
        loaderBgColor: '#232323',
        loaderColor: '#ffffff',
    };

    $.fn.vidbgYT = function(options){
        return this.each(function(){
            (new $.vidbgYT(this, options));
        });
    };

})(jQuery);

jQuery(function ($) {
  window.onYouTubePlayerAPIReady = function(){
    for (var i = 0; i < window.InstanceCollection.length; i++) {
        window.InstanceCollection[i].onYouTubePlayerAPIReady();
    }
  };
});
