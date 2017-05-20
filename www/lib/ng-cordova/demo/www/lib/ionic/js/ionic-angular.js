/*!
 * Copyright 2014 Drifty Co.
 * http://drifty.com/
 *
 * Ionic, v1.0.0-beta.14
 * A powerful HTML5 mobile app framework.
 * http://ionicframework.com/
 *
 * By @maxlynch, @benjsperry, @adamdbradley <3
 *
 * Licensed under the MIT license. Please see LICENSE for more information.
 *
 */

(function() {
/*
 * deprecated.js
 * https://github.com/wearefractal/deprecated/
 * Copyright (c) 2014 Fractal <contact@wearefractal.com>
 * License MIT
 */
//Interval object
var deprecated = {
  method: function(msg, log, fn) {
    var called = false;
    return function deprecatedMethod() {
      if (!called) {
        called = true;
        log(msg);
      }
      return fn.apply(this, arguments);
    };
  },

  field: function(msg, log, parent, field, val) {
    var called = false;
    var getter = function() {
      if (!called) {
        called = true;
        log(msg);
      }
      return val;
    };
    var setter = function(v) {
      if (!called) {
        called = true;
        log(msg);
      }
      val = v;
      return v;
    };
    Object.defineProperty(parent, field, {
      get: getter,
      set: setter,
      enumerable: true
    });
    return;
  }
};

var IonicModule = angular.module('ionic', ['ngAnimate', 'ngSanitize', 'ui.router']),
  extend = angular.extend,
  forEach = angular.forEach,
  isDefined = angular.isDefined,
  isNumber = angular.isNumber,
  isString = angular.isString,
  jqLite = angular.element;


/**
 * @ngdoc service
 * @name $ionicActionSheet
 * @module ionic
 * @description
 * The Action Sheet is a slide-up pane that lets the user choose from a set of options.
 * Dangerous options are highlighted in red and made obvious.
 *
 * There are easy ways to cancel out of the action sheet, such as tapping the backdrop or even
 * hitting escape on the keyboard for desktop testing.
 *
 * ![Action Sheet](http://ionicframework.com.s3.amazonaws.com/docs/controllers/actionSheet.gif)
 *
 * @usage
 * To trigger an Action Sheet in your code, use the $ionicActionSheet service in your angular controllers:
 *
 * ```js
 * angular.module('mySuperApp', ['ionic'])
 * .controller(function($scope, $ionicActionSheet, $timeout) {
 *
 *  // Triggered on a button click, or some other target
 *  $scope.show = function() {
 *
 *    // Show the action sheet
 *    var hideSheet = $ionicActionSheet.show({
 *      buttons: [
 *        { text: '<b>Share</b> This' },
 *        { text: 'Move' }
 *      ],
 *      destructiveText: 'Delete',
 *      titleText: 'Modify your album',
 *      cancelText: 'Cancel',
 *      cancel: function() {
          // add cancel code..
        },
 *      buttonClicked: function(index) {
 *        return true;
 *      }
 *    });
 *
 *    // For example's sake, hide the sheet after two seconds
 *    $timeout(function() {
 *      hideSheet();
 *    }, 2000);
 *
 *  };
 * });
 * ```
 *
 */
IonicModule
.factory('$ionicActionSheet', [
  '$rootScope',
  '$compile',
  '$animate',
  '$timeout',
  '$ionicTemplateLoader',
  '$ionicPlatform',
  '$ionicBody',
function($rootScope, $compile, $animate, $timeout, $ionicTemplateLoader, $ionicPlatform, $ionicBody) {

  return {
    show: actionSheet
  };

  /**
   * @ngdoc method
   * @name $ionicActionSheet#show
   * @description
   * Load and return a new action sheet.
   *
   * A new isolated scope will be created for the
   * action sheet and the new element will be appended into the body.
   *
   * @param {object} options The options for this ActionSheet. Properties:
   *
   *  - `[Object]` `buttons` Which buttons to show.  Each button is an object with a `text` field.
   *  - `{string}` `titleText` The title to show on the action sheet.
   *  - `{string=}` `cancelText` the text for a 'cancel' button on the action sheet.
   *  - `{string=}` `destructiveText` The text for a 'danger' on the action sheet.
   *  - `{function=}` `cancel` Called if the cancel button is pressed, the backdrop is tapped or
   *     the hardware back button is pressed.
   *  - `{function=}` `buttonClicked` Called when one of the non-destructive buttons is clicked,
   *     with the index of the button that was clicked and the button object. Return true to close
   *     the action sheet, or false to keep it opened.
   *  - `{function=}` `destructiveButtonClicked` Called when the destructive button is clicked.
   *     Return true to close the action sheet, or false to keep it opened.
   *  -  `{boolean=}` `cancelOnStateChange` Whether to cancel the actionSheet when navigating
   *     to a new state.  Default true.
   *  - `{string}` `cssClass` The custom CSS class name.
   *
   * @returns {function} `hideSheet` A function which, when called, hides & cancels the action sheet.
   */
  function actionSheet(opts) {
    var scope = $rootScope.$new(true);

    angular.extend(scope, {
      cancel: angular.noop,
      destructiveButtonClicked: angular.noop,
      buttonClicked: angular.noop,
      $deregisterBackButton: angular.noop,
      buttons: [],
      cancelOnStateChange: true
    }, opts || {});


    // Compile the template
    var element = scope.element = $compile('<ion-action-sheet ng-class="cssClass" buttons="buttons"></ion-action-sheet>')(scope);

    // Grab the sheet element for animation
    var sheetEl = jqLite(element[0].querySelector('.action-sheet-wrapper'));

    var stateChangeListenDone = scope.cancelOnStateChange ?
      $rootScope.$on('$stateChangeSuccess', function() { scope.cancel(); }) :
      angular.noop;

    // removes the actionSheet from the screen
    scope.removeSheet = function(done) {
      if (scope.removed) return;

      scope.removed = true;
      sheetEl.removeClass('action-sheet-up');
      $timeout(function() {
        // wait to remove this due to a 300ms delay native
        // click which would trigging whatever was underneath this
        $ionicBody.removeClass('action-sheet-open');
      }, 400);
      scope.$deregisterBackButton();
      stateChangeListenDone();

      $animate.removeClass(element, 'active').then(function() {
        scope.$destroy();
        element.remove();
        // scope.cancel.$scope is defined near the bottom
        scope.cancel.$scope = sheetEl = null;
        (done || angular.noop)();
      });
    };

    scope.showSheet = function(done) {
      if (scope.removed) return;

      $ionicBody.append(element)
                .addClass('action-sheet-open');

      $animate.addClass(element, 'active').then(function() {
        if (scope.removed) return;
        (done || angular.noop)();
      });
      $timeout(function() {
        if (scope.removed) return;
        sheetEl.addClass('action-sheet-up');
      }, 20, false);
    };

    // registerBackButtonAction returns a callback to deregister the action
    scope.$deregisterBackButton = $ionicPlatform.registerBackButtonAction(
      function() {
        $timeout(scope.cancel);
      },
      PLATFORM_BACK_BUTTON_PRIORITY_ACTION_SHEET
    );

    // called when the user presses the cancel button
    scope.cancel = function() {
      // after the animation is out, call the cancel callback
      scope.removeSheet(opts.cancel);
    };

    scope.buttonClicked = function(index) {
      // Check if the button click event returned true, which means
      // we can close the action sheet
      if (opts.buttonClicked(index, opts.buttons[index]) === true) {
        scope.removeSheet();
      }
    };

    scope.destructiveButtonClicked = function() {
      // Check if the destructive button click event returned true, which means
      // we can close the action sheet
      if (opts.destructiveButtonClicked() === true) {
        scope.removeSheet();
      }
    };

    scope.showSheet();

    // Expose the scope on $ionicActionSheet's return value for the sake
    // of testing it.
    scope.cancel.$scope = scope;

    return scope.cancel;
  }
}]);


jqLite.prototype.addClass = function(cssClasses) {
  var x, y, cssClass, el, splitClasses, existingClasses;
  if (cssClasses && cssClasses != 'ng-scope' && cssClasses != 'ng-isolate-scope') {
    for (x = 0; x < this.length; x++) {
      el = this[x];
      if (el.setAttribute) {

        if (cssClasses.indexOf(' ') < 0 && el.classList.add) {
          el.classList.add(cssClasses);
        } else {
          existingClasses = (' ' + (el.getAttribute('class') || '') + ' ')
            .replace(/[\n\t]/g, " ");
          splitClasses = cssClasses.split(' ');

          for (y = 0; y < splitClasses.length; y++) {
            cssClass = splitClasses[y].trim();
            if (existingClasses.indexOf(' ' + cssClass + ' ') === -1) {
              existingClasses += cssClass + ' ';
            }
          }
          el.setAttribute('class', existingClasses.trim());
        }
      }
    }
  }
  return this;
};

jqLite.prototype.removeClass = function(cssClasses) {
  var x, y, splitClasses, cssClass, el;
  if (cssClasses) {
    for (x = 0; x < this.length; x++) {
      el = this[x];
      if (el.getAttribute) {
        if (cssClasses.indexOf(' ') < 0 && el.classList.remove) {
          el.classList.remove(cssClasses);
        } else {
          splitClasses = cssClasses.split(' ');

          for (y = 0; y < splitClasses.length; y++) {
            cssClass = splitClasses[y];
            el.setAttribute('class', (
                (" " + (el.getAttribute('class') || '') + " ")
                .replace(/[\n\t]/g, " ")
                .replace(" " + cssClass.trim() + " ", " ")).trim()
            );
          }
        }
      }
    }
  }
  return this;
};


/**
 * @private
 */
IonicModule
.factory('$$ionicAttachDrag', [function() {

  return attachDrag;

  function attachDrag(scope, element, options) {
    var opts = extend({}, {
      getDistance: function() { return opts.element.prop('offsetWidth'); },
      onDragStart: angular.noop,
      onDrag: angular.noop,
      onDragEnd: angular.noop
    }, options);

    var dragStartGesture = ionic.onGesture('dragstart', handleDragStart, element[0]);
    var dragGesture = ionic.onGesture('drag', handleDrag, element[0]);
    var dragEndGesture = ionic.onGesture('dragend', handleDragEnd, element[0]);

    scope.$on('$destroy', function() {
      ionic.offGesture(dragStartGesture, 'dragstart', handleDragStart);
      ionic.offGesture(dragGesture, 'drag', handleDrag);
      ionic.offGesture(dragEndGesture, 'dragend', handleDragEnd);
    });

    var isDragging = false;
    element.on('touchmove pointermove mousemove', function(ev) {
      if (isDragging) ev.preventDefault();
    });
    element.on('touchend mouseup mouseleave', function(ev) {
      isDragging = false;
    });

    var dragState;
    function handleDragStart(ev) {
      if (dragState) return;
      if (opts.onDragStart() !== false) {
        dragState = {
          startX: ev.gesture.center.pageX,
          startY: ev.gesture.center.pageY,
          distance: opts.getDistance()
        };
      }
    }
    function handleDrag(ev) {
      if (!dragState) return;
      var deltaX = dragState.startX - ev.gesture.center.pageX;
      var deltaY = dragState.startY - ev.gesture.center.pageY;
      var isVertical = ev.gesture.direction === 'up' || ev.gesture.direction === 'down';

      if (isVertical && Math.abs(deltaY) > Math.abs(deltaX) * 2) {
        handleDragEnd(ev);
        return;
      }
      if (Math.abs(deltaX) > Math.abs(deltaY) * 2) {
        isDragging = true;
      }

      var percent = getDragPercent(ev.gesture.center.pageX);
      opts.onDrag(percent);
    }
    function handleDragEnd(ev) {
      if (!dragState) return;
      var percent = getDragPercent(ev.gesture.center.pageX);
      options.onDragEnd(percent, ev.gesture.velocityX);

      dragState = null;
    }

    function getDragPercent(x) {
      var delta = dragState.startX - x;
      var percent = delta / dragState.distance;
      return percent;
    }
  }

}]);

/**
 * @ngdoc service
 * @name $ionicBackdrop
 * @module ionic
 * @description
 * Shows and hides a backdrop over the UI.  Appears behind popups, loading,
 * and other overlays.
 *
 * Often, multiple UI components require a backdrop, but only one backdrop is
 * ever needed in the DOM at a time.
 *
 * Therefore, each component that requires the backdrop to be shown calls
 * `$ionicBackdrop.retain()` when it wants the backdrop, then `$ionicBackdrop.release()`
 * when it is done with the backdrop.
 *
 * For each time `retain` is called, the backdrop will be shown until `release` is called.
 *
 * For example, if `retain` is called three times, the backdrop will be shown until `release`
 * is called three times.
 *
 * @usage
 *
 * ```js
 * function MyController($scope, $ionicBackdrop, $timeout) {
 *   //Show a backdrop for one second
 *   $scope.action = function() {
 *     $ionicBackdrop.retain();
 *     $timeout(function() {
 *       $ionicBackdrop.release();
 *     }, 1000);
 *   };
 * }
 * ```
 */
IonicModule
.factory('$ionicBackdrop', [
  '$document', '$timeout',
function($document, $timeout) {

  var el = jqLite('<div class="backdrop">');
  var backdropHolds = 0;

  $document[0].body.appendChild(el[0]);

  return {
    /**
     * @ngdoc method
     * @name $ionicBackdrop#retain
     * @description Retains the backdrop.
     */
    retain: retain,
    /**
     * @ngdoc method
     * @name $ionicBackdrop#release
     * @description
     * Releases the backdrop.
     */
    release: release,

    getElement: getElement,

    // exposed for testing
    _element: el
  };

  function retain() {
    if ((++backdropHolds) === 1) {
      el.addClass('visible');
      ionic.requestAnimationFrame(function() {
        backdropHolds && el.addClass('active');
      });
    }
  }
  function release() {
    if ((--backdropHolds) === 0) {
      el.removeClass('active');
      $timeout(function() {
        !backdropHolds && el.removeClass('visible');
      }, 400, false);
    }
  }

  function getElement() {
    return el;
  }

}]);

/**
 * @private
 */
IonicModule
.factory('$ionicBind', ['$parse', '$interpolate', function($parse, $interpolate) {
  var LOCAL_REGEXP = /^\s*([@=&])(\??)\s*(\w*)\s*$/;
  return function(scope, attrs, bindDefinition) {
    forEach(bindDefinition || {}, function (definition, scopeName) {
      //Adapted from angular.js $compile
      var match = definition.match(LOCAL_REGEXP) || [],
        attrName = match[3] || scopeName,
        mode = match[1], // @, =, or &
        parentGet,
        unwatch;

      switch(mode) {
        case '@':
          if (!attrs[attrName]) {
            return;
          }
          attrs.$observe(attrName, function(value) {
            scope[scopeName] = value;
          });
          // we trigger an interpolation to ensure
          // the value is there for use immediately
          if (attrs[attrName]) {
            scope[scopeName] = $interpolate(attrs[attrName])(scope);
          }
          break;

        case '=':
          if (!attrs[attrName]) {
            return;
          }
          unwatch = scope.$watch(attrs[attrName], function(value) {
            scope[scopeName] = value;
          });
          //Destroy parent scope watcher when this scope is destroyed
          scope.$on('$destroy', unwatch);
          break;

        case '&':
          /* jshint -W044 */
          if (attrs[attrName] && attrs[attrName].match(RegExp(scopeName + '\(.*?\)'))) {
            throw new Error('& expression binding "' + scopeName + '" looks like it will recursively call "' +
                          attrs[attrName] + '" and cause a stack overflow! Please choose a different scopeName.');
          }
          parentGet = $parse(attrs[attrName]);
          scope[scopeName] = function(locals) {
            return parentGet(scope, locals);
          };
          break;
      }
    });
  };
}]);

/**
 * @ngdoc service
 * @name $ionicBody
 * @module ionic
 * @description An angular utility service to easily and efficiently
 * add and remove CSS classes from the document's body element.
 */
IonicModule
.factory('$ionicBody', ['$document', function($document) {
  return {
    /**
     * @ngdoc method
     * @name $ionicBody#add
     * @description Add a class to the document's body element.
     * @param {string} class Each argument will be added to the body element.
     * @returns {$ionicBody} The $ionicBody service so methods can be chained.
     */
    addClass: function() {
      for (var x = 0; x < arguments.length; x++) {
        $document[0].body.classList.add(arguments[x]);
      }
      return this;
    },
    /**
     * @ngdoc method
     * @name $ionicBody#removeClass
     * @description Remove a class from the document's body element.
     * @param {string} class Each argument will be removed from the body element.
     * @returns {$ionicBody} The $ionicBody service so methods can be chained.
     */
    removeClass: function() {
      for (var x = 0; x < arguments.length; x++) {
        $document[0].body.classList.remove(arguments[x]);
      }
      return this;
    },
    /**
     * @ngdoc method
     * @name $ionicBody#enableClass
     * @description Similar to the `add` method, except the first parameter accepts a boolean
     * value determining if the class should be added or removed. Rather than writing user code,
     * such as "if true then add the class, else then remove the class", this method can be
     * given a true or false value which reduces redundant code.
     * @param {boolean} shouldEnableClass A true/false value if the class should be added or removed.
     * @param {string} class Each remaining argument would be added or removed depending on
     * the first argument.
     * @returns {$ionicBody} The $ionicBody service so methods can be chained.
     */
    enableClass: function(shouldEnableClass) {
      var args = Array.prototype.slice.call(arguments).slice(1);
      if (shouldEnableClass) {
        this.addClass.apply(this, args);
      } else {
        this.removeClass.apply(this, args);
      }
      return this;
    },
    /**
     * @ngdoc method
     * @name $ionicBody#append
     * @description Append a child to the document's body.
     * @param {element} element The element to be appended to the body. The passed in element
     * can be either a jqLite element, or a DOM element.
     * @returns {$ionicBody} The $ionicBody service so methods can be chained.
     */
    append: function(ele) {
      $document[0].body.appendChild(ele.length ? ele[0] : ele);
      return this;
    },
    /**
     * @ngdoc method
     * @name $ionicBody#get
     * @description Get the document's body element.
     * @returns {element} Returns the document's body element.
     */
    get: function() {
      return $document[0].body;
    }
  };
}]);

IonicModule
.factory('$ionicClickBlock', [
  '$document',
  '$ionicBody',
  '$timeout',
function($document, $ionicBody, $timeout) {
  var CSS_HIDE = 'click-block-hide';
  var cbEle, fallbackTimer, pendingShow;

  function addClickBlock() {
    if (pendingShow) {
      if (cbEle) {
        cbEle.classList.remove(CSS_HIDE);
      } else {
        cbEle = $document[0].createElement('div');
        cbEle.className = 'click-block';
        $ionicBody.append(cbEle);
      }
      pendingShow = false;
    }
  }

  function removeClickBlock() {
    cbEle && cbEle.classList.add(CSS_HIDE);
  }

  return {
    show: function(autoExpire) {
      pendingShow = true;
      $timeout.cancel(fallbackTimer);
      fallbackTimer = $timeout(this.hide, autoExpire || 310);
      ionic.requestAnimationFrame(addClickBlock);
    },
    hide: function() {
      pendingShow = false;
      $timeout.cancel(fallbackTimer);
      ionic.requestAnimationFrame(removeClickBlock);
    }
  };
}]);

IonicModule
.factory('$collectionDataSource', [
  '$cacheFactory',
  '$parse',
  '$rootScope',
function($cacheFactory, $parse, $rootScope) {
  function hideWithTransform(element) {
    element.css(ionic.CSS.TRANSFORM, 'translate3d(-2000px,-2000px,0)');
  }

  function CollectionRepeatDataSource(options) {
    var self = this;
    this.scope = options.scope;
    this.transcludeFn = options.transcludeFn;
    this.transcludeParent = options.transcludeParent;
    this.element = options.element;

    this.keyExpr = options.keyExpr;
    this.listExpr = options.listExpr;
    this.trackByExpr = options.trackByExpr;

    this.heightGetter = options.heightGetter;
    this.widthGetter = options.widthGetter;

    this.dimensions = [];
    this.data = [];

    this.attachedItems = {};
    this.BACKUP_ITEMS_LENGTH = 20;
    this.backupItemsArray = [];
  }
  CollectionRepeatDataSource.prototype = {
    setup: function() {
      if (this.isSetup) return;
      this.isSetup = true;
      for (var i = 0; i < this.BACKUP_ITEMS_LENGTH; i++) {
        this.detachItem(this.createItem());
      }
    },
    destroy: function() {
      this.dimensions.length = 0;
      this.data = null;
      this.backupItemsArray.length = 0;
      this.attachedItems = {};
    },
    calculateDataDimensions: function() {
      var locals = {};
      this.dimensions = this.data.map(function(value, index) {
        locals[this.keyExpr] = value;
        locals.$index = index;
        return {
          width: this.widthGetter(this.scope, locals),
          height: this.heightGetter(this.scope, locals)
        };
      }, this);
      this.dimensions = this.beforeSiblings.concat(this.dimensions).concat(this.afterSiblings);
      this.dataStartIndex = this.beforeSiblings.length;
    },
    createItem: function() {
      var item = {};

      item.scope = this.scope.$new();
      this.transcludeFn(item.scope, function(clone) {
        clone.css('position', 'absolute');
        item.element = clone;
      });
      this.transcludeParent.append(item.element);

      return item;
    },
    getItem: function(index) {
      var item;
      if ( (item = this.attachedItems[index]) ) {
        //do nothing, the item is good
      } else if ( (item = this.backupItemsArray.pop()) ) {
        ionic.Utils.reconnectScope(item.scope);
      } else {
        item = this.createItem();
      }
      return item;
    },
    attachItemAtIndex: function(index) {
      if (index < this.dataStartIndex) {
        return this.beforeSiblings[index];
      }
      // Subtract so we start at the beginning of this.data, after
      // this.beforeSiblings.
      index -= this.dataStartIndex;

      if (index > this.data.length - 1) {
        return this.afterSiblings[index - this.dataStartIndex];
      }

      var item = this.getItem(index);
      var value = this.data[index];

      if (item.index !== index || item.scope[this.keyExpr] !== value) {
        item.index = item.scope.$index = index;
        item.scope[this.keyExpr] = value;
        item.scope.$first = (index === 0);
        item.scope.$last = (index === (this.getLength() - 1));
        item.scope.$middle = !(item.scope.$first || item.scope.$last);
        item.scope.$odd = !(item.scope.$even = (index&1) === 0);

        //We changed the scope, so digest if needed
        if (!$rootScope.$$phase) {
          item.scope.$digest();
        }
      }
      this.attachedItems[index] = item;

      return item;
    },
    destroyItem: function(item) {
      item.element.remove();
      item.scope.$destroy();
      item.scope = null;
      item.element = null;
    },
    detachItem: function(item) {
      delete this.attachedItems[item.index];

      //If it's an outside item, only hide it. These items aren't part of collection
      //repeat's list, only sit outside
      if (item.isOutside) {
        hideWithTransform(item.element);
      // If we are at the limit of backup items, just get rid of the this element
      } else if (this.backupItemsArray.length >= this.BACKUP_ITEMS_LENGTH) {
        this.destroyItem(item);
      // Otherwise, add it to our backup items
      } else {
        this.backupItemsArray.push(item);
        hideWithTransform(item.element);
        //Don't .$destroy(), just stop watchers and events firing
        ionic.Utils.disconnectScope(item.scope);
      }

    },
    getLength: function() {
      return this.dimensions && this.dimensions.length || 0;
    },
    setData: function(value, beforeSiblings, afterSiblings) {
      this.data = value || [];
      this.beforeSiblings = beforeSiblings || [];
      this.afterSiblings = afterSiblings || [];
      this.calculateDataDimensions();

      this.afterSiblings.forEach(function(item) {
        item.element.css({position: 'absolute', top: '0', left: '0' });
        hideWithTransform(item.element);
      });
    },
  };

  return CollectionRepeatDataSource;
}]);


IonicModule
.factory('$collectionRepeatManager', [
  '$rootScope',
  '$timeout',
function($rootScope, $timeout) {
  /**
   * Vocabulary: "primary" and "secondary" size/direction/position mean
   * "y" and "x" for vertical scrolling, or "x" and "y" for horizontal scrolling.
   */
  function CollectionRepeatManager(options) {
    var self = this;
    this.dataSource = options.dataSource;
    this.element = options.element;
    this.scrollView = options.scrollView;

    this.isVertical = !!this.scrollView.options.scrollingY;
    this.renderedItems = {};
    this.dimensions = [];
    this.setCurrentIndex(0);

    //Override scrollview's render callback
    this.scrollView.__$callback = this.scrollView.__callback;
    this.scrollView.__callback = angular.bind(this, this.renderScroll);

    function getViewportSize() { return self.viewportSize; }
    //Set getters and setters to match whether this scrollview is vertical or not
    if (this.isVertical) {
      this.scrollView.options.getContentHeight = getViewportSize;

      this.scrollValue = function() {
        return this.scrollView.__scrollTop;
      };
      this.scrollMaxValue = function() {
        return this.scrollView.__maxScrollTop;
      };
      this.scrollSize = function() {
        return this.scrollView.__clientHeight;
      };
      this.secondaryScrollSize = function() {
        return this.scrollView.__clientWidth;
      };
      this.transformString = function(y, x) {
        return 'translate3d('+x+'px,'+y+'px,0)';
      };
      this.primaryDimension = function(dim) {
        return dim.height;
      };
      this.secondaryDimension = function(dim) {
        return dim.width;
      };
    } else {
      this.scrollView.options.getContentWidth = getViewportSize;

      this.scrollValue = function() {
        return this.scrollView.__scrollLeft;
      };
      this.scrollMaxValue = function() {
        return this.scrollView.__maxScrollLeft;
      };
      this.scrollSize = function() {
        return this.scrollView.__clientWidth;
      };
      this.secondaryScrollSize = function() {
        return this.scrollView.__clientHeight;
      };
      this.transformString = function(x, y) {
        return 'translate3d('+x+'px,'+y+'px,0)';
      };
      this.primaryDimension = function(dim) {
        return dim.width;
      };
      this.secondaryDimension = function(dim) {
        return dim.height;
      };
    }
  }

  CollectionRepeatManager.prototype = {
    destroy: function() {
      this.renderedItems = {};
      this.render = angular.noop;
      this.calculateDimensions = angular.noop;
      this.dimensions = [];
    },

    /*
     * Pre-calculate the position of all items in the data list.
     * Do this using the provided width and height (primarySize and secondarySize)
     * provided by the dataSource.
     */
    calculateDimensions: function() {
      /*
       * For the sake of explanations below, we're going to pretend we are scrolling
       * vertically: Items are laid out with primarySize being height,
       * secondarySize being width.
       */
      var primaryPos = 0;
      var secondaryPos = 0;
      var secondaryScrollSize = this.secondaryScrollSize();
      var previousItem;

      this.dataSource.beforeSiblings && this.dataSource.beforeSiblings.forEach(calculateSize, this);
      var beforeSize = primaryPos + (previousItem ? previousItem.primarySize : 0);

      primaryPos = secondaryPos = 0;
      previousItem = null;

      var dimensions = this.dataSource.dimensions.map(calculateSize, this);
      var totalSize = primaryPos + (previousItem ? previousItem.primarySize : 0);

      return {
        beforeSize: beforeSize,
        totalSize: totalSize,
        dimensions: dimensions
      };

      function calculateSize(dim) {

        //Each dimension is an object {width: Number, height: Number} provided by
        //the dataSource
        var rect = {
          //Get the height out of the dimension object
          primarySize: this.primaryDimension(dim),
          //Max out the item's width to the width of the scrollview
          secondarySize: Math.min(this.secondaryDimension(dim), secondaryScrollSize)
        };

        //If this isn't the first item
        if (previousItem) {
          //Move the item's x position over by the width of the previous item
          secondaryPos += previousItem.secondarySize;
          //If the y position is the same as the previous item and
          //the x position is bigger than the scroller's width
          if (previousItem.primaryPos === primaryPos &&
              secondaryPos + rect.secondarySize > secondaryScrollSize) {
            //Then go to the next row, with x position 0
            secondaryPos = 0;
            primaryPos += previousItem.primarySize;
          }
        }

        rect.primaryPos = primaryPos;
        rect.secondaryPos = secondaryPos;

        previousItem = rect;
        return rect;
      }
    },
    resize: function() {
      var result = this.calculateDimensions();
      this.dimensions = result.dimensions;
      this.viewportSize = result.totalSize;
      this.beforeSize = result.beforeSize;
      this.setCurrentIndex(0);
      this.render(true);
      this.dataSource.setup();
    },
    /*
     * setCurrentIndex sets the index in the list that matches the scroller's position.
     * Also save the position in the scroller for next and previous items (if they exist)
     */
    setCurrentIndex: function(index, height) {
      var currentPos = (this.dimensions[index] || {}).primaryPos || 0;
      this.currentIndex = index;

      this.hasPrevIndex = index > 0;
      if (this.hasPrevIndex) {
        this.previousPos = Math.max(
          currentPos - this.dimensions[index - 1].primarySize,
          this.dimensions[index - 1].primaryPos
        );
      }
      this.hasNextIndex = index + 1 < this.dataSource.getLength();
      if (this.hasNextIndex) {
        this.nextPos = Math.min(
          currentPos + this.dimensions[index + 1].primarySize,
          this.dimensions[index + 1].primaryPos
        );
      }
    },
    /**
     * override the scroller's render callback to check if we need to
     * re-render our collection
     */
    renderScroll: ionic.animationFrameThrottle(function(transformLeft, transformTop, zoom, wasResize) {
      if (this.isVertical) {
        this.renderIfNeeded(transformTop);
      } else {
        this.renderIfNeeded(transformLeft);
      }
      return this.scrollView.__$callback(transformLeft, transformTop, zoom, wasResize);
    }),

    renderIfNeeded: function(scrollPos) {
      if ((this.hasNextIndex && scrollPos >= this.nextPos) ||
          (this.hasPrevIndex && scrollPos < this.previousPos)) {
           // Math.abs(transformPos - this.lastRenderScrollValue) > 100) {
        this.render();
      }
    },
    /*
     * getIndexForScrollValue: Given the most recent data index and a new scrollValue,
     * find the data index that matches that scrollValue.
     *
     * Strategy (if we are scrolling down): keep going forward in the dimensions list,
     * starting at the given index, until an item with height matching the new scrollValue
     * is found.
     *
     * This is a while loop. In the worst case it will have to go through the whole list
     * (eg to scroll from top to bottom).  The most common case is to scroll
     * down 1-3 items at a time.
     *
     * While this is not as efficient as it could be, optimizing it gives no noticeable
     * benefit.  We would have to use a new memory-intensive data structure for dimensions
     * to fully optimize it.
     */
    getIndexForScrollValue: function(i, scrollValue) {
      var rect;
      //Scrolling up
      if (scrollValue <= this.dimensions[i].primaryPos) {
        while ( (rect = this.dimensions[i - 1]) && rect.primaryPos > scrollValue) {
          i--;
        }
      //Scrolling down
      } else {
        while ( (rect = this.dimensions[i + 1]) && rect.primaryPos < scrollValue) {
          i++;
        }
      }
      return i;
    },
    /*
     * render: Figure out the scroll position, the index matching it, and then tell
     * the data source to render the correct items into the DOM.
     */
    render: function(shouldRedrawAll) {
      var self = this;
      var i;
      var isOutOfBounds = ( this.currentIndex >= this.dataSource.getLength() );
      // We want to remove all the items and redraw everything if we're out of bounds
      // or a flag is passed in.
      if (isOutOfBounds || shouldRedrawAll) {
        for (i in this.renderedItems) {
          this.removeItem(i);
        }
        // Just don't render anything if we're out of bounds
        if (isOutOfBounds) return;
      }

      var rect;
      var scrollValue = this.scrollValue();
      // Scroll size = how many pixels are visible in the scroller at one time
      var scrollSize = this.scrollSize();
      // We take the current scroll value and add it to the scrollSize to get
      // what scrollValue the current visible scroll area ends at.
      var scrollSizeEnd = scrollSize + scrollValue;
      // Get the new start index for scrolling, based on the current scrollValue and
      // the most recent known index
      var startIndex = this.getIndexForScrollValue(this.currentIndex, scrollValue);

      // If we aren't on the first item, add one row of items before so that when the user is
      // scrolling up he sees the previous item
      var renderStartIndex = Math.max(startIndex - 1, 0);
      // Keep adding items to the 'extra row above' until we get to a new row.
      // This is for the case where there are multiple items on one row above
      // the current item; we want to keep adding items above until
      // a new row is reached.
      while (renderStartIndex > 0 &&
         (rect = this.dimensions[renderStartIndex]) &&
         rect.primaryPos === this.dimensions[startIndex - 1].primaryPos) {
        renderStartIndex--;
      }

      // Keep rendering items, adding them until we are past the end of the visible scroll area
      i = renderStartIndex;
      while ((rect = this.dimensions[i]) && (rect.primaryPos - rect.primarySize < scrollSizeEnd)) {
        doRender(i, rect);
        i++;
      }

      // Render two extra items at the end as a buffer
      if (self.dimensions[i]) {
        doRender(i, self.dimensions[i]);
        i++;
      }
      if (self.dimensions[i]) {
        doRender(i, self.dimensions[i]);
      }
      var renderEndIndex = i;

      // Remove any items that were rendered and aren't visible anymore
      for (var renderIndex in this.renderedItems) {
        if (renderIndex < renderStartIndex || renderIndex > renderEndIndex) {
          this.removeItem(renderIndex);
        }
      }

      this.setCurrentIndex(startIndex);

      function doRender(dataIndex, rect) {
        if (dataIndex < self.dataSource.dataStartIndex) {
          // do nothing
        } else {
          self.renderItem(dataIndex, rect.primaryPos - self.beforeSize, rect.secondaryPos);
        }
      }
    },
    renderItem: function(dataIndex, primaryPos, secondaryPos) {
      // Attach an item, and set its transform position to the required value
      var item = this.dataSource.attachItemAtIndex(dataIndex);
      //console.log(dataIndex, item);
      if (item && item.element) {
        if (item.primaryPos !== primaryPos || item.secondaryPos !== secondaryPos) {
          item.element.css(ionic.CSS.TRANSFORM, this.transformString(
            primaryPos, secondaryPos
          ));
          item.primaryPos = primaryPos;
          item.secondaryPos = secondaryPos;
        }
        // Save the item in rendered items
        this.renderedItems[dataIndex] = item;
      } else {
        // If an item at this index doesn't exist anymore, be sure to delete
        // it from rendered items
        delete this.renderedItems[dataIndex];
      }
    },
    removeItem: function(dataIndex) {
      // Detach a given item
      var item = this.renderedItems[dataIndex];
      if (item) {
        item.primaryPos = item.secondaryPos = null;
        this.dataSource.detachItem(item);
        delete this.renderedItems[dataIndex];
      }
    }
  };

  return CollectionRepeatManager;
}]);


/**
 * @ngdoc service
 * @name $ionicGesture
 * @module ionic
 * @description An angular service exposing ionic
 * {@link ionic.utility:ionic.EventController}'s gestures.
 */
IonicModule
.factory('$ionicGesture', [function() {
  return {
    /**
     * @ngdoc method
     * @name $ionicGesture#on
     * @description Add an event listener for a gesture on an element. See {@link ionic.utility:ionic.EventController#onGesture}.
     * @param {string} eventType The gesture event to listen for.
     * @param {function(e)} callback The function to call when the gesture
     * happens.
     * @param {element} $element The angular element to listen for the event on.
     * @param {object} options object.
     * @returns {ionic.Gesture} The gesture object (use this to remove the gesture later on).
     */
    on: function(eventType, cb, $element, options) {
      return window.ionic.onGesture(eventType, cb, $element[0], options);
    },
    /**
     * @ngdoc method
     * @name $ionicGesture#off
     * @description Remove an event listener for a gesture on an element. See {@link ionic.utility:ionic.EventController#offGesture}.
     * @param {ionic.Gesture} gesture The gesture that should be removed.
     * @param {string} eventType The gesture event to remove the listener for.
     * @param {function(e)} callback The listener to remove.
     */
    off: function(gesture, eventType, cb) {
      return window.ionic.offGesture(gesture, eventType, cb);
    }
  };
}]);

/**
 * @ngdoc service
 * @name $ionicHistory
 * @module ionic
 * @description
 * $ionicHistory keeps track of views as the user navigates through an app. Similar to the way a
 * browser behaves, an Ionic app is able to keep track of the previous view, the current view, and
 * the forward view (if there is one).  However, a typical web browser only keeps track of one
 * history stack in a linear fashion.
 *
 * Unlike a traditional browser environment, apps and webapps have parallel independent histories,
 * such as with tabs. Should a user navigate few pages deep on one tab, and then switch to a new
 * tab and back, the back button relates not to the previous tab, but to the previous pages
 * visited within _that_ tab.
 *
 * `$ionicHistory` facilitates this parallel history architecture.
 */

IonicModule
.factory('$ionicHistory', [
  '$rootScope',
  '$state',
  '$location',
  '$window',
  '$timeout',
  '$ionicViewSwitcher',
  '$ionicNavViewDelegate',
function($rootScope, $state, $location, $window, $timeout, $ionicViewSwitcher, $ionicNavViewDelegate) {

  // history actions while navigating views
  var ACTION_INITIAL_VIEW = 'initialView';
  var ACTION_NEW_VIEW = 'newView';
  var ACTION_MOVE_BACK = 'moveBack';
  var ACTION_MOVE_FORWARD = 'moveForward';

  // direction of navigation
  var DIRECTION_BACK = 'back';
  var DIRECTION_FORWARD = 'forward';
  var DIRECTION_ENTER = 'enter';
  var DIRECTION_EXIT = 'exit';
  var DIRECTION_SWAP = 'swap';
  var DIRECTION_NONE = 'none';

  var stateChangeCounter = 0;
  var lastStateId, nextViewOptions, nextViewExpireTimer, forcedNav;

  var viewHistory = {
    histories: { root: { historyId: 'root', parentHistoryId: null, stack: [], cursor: -1 } },
    views: {},
    backView: null,
    forwardView: null,
    currentView: null
  };

  var View = function() {};
  View.prototype.initialize = function(data) {
    if (data) {
      for (var name in data) this[name] = data[name];
      return this;
    }
    return null;
  };
  View.prototype.go = function() {

    if (this.stateName) {
      return $state.go(this.stateName, this.stateParams);
    }

    if (this.url && this.url !== $location.url()) {

      if (viewHistory.backView === this) {
        return $window.history.go(-1);
      } else if (viewHistory.forwardView === this) {
        return $window.history.go(1);
      }

      $location.url(this.url);
      return;
    }

    return null;
  };
  View.prototype.destroy = function() {
    if (this.scope) {
      this.scope.$destroy && this.scope.$destroy();
      this.scope = null;
    }
  };


  function getViewById(viewId) {
    return (viewId ? viewHistory.views[ viewId ] : null);
  }

  function getBackView(view) {
    return (view ? getViewById(view.backViewId) : null);
  }

  function getForwardView(view) {
    return (view ? getViewById(view.forwardViewId) : null);
  }

  function getHistoryById(historyId) {
    return (historyId ? viewHistory.histories[ historyId ] : null);
  }

  function getHistory(scope) {
    var histObj = getParentHistoryObj(scope);

    if (!viewHistory.histories[ histObj.historyId ]) {
      // this history object exists in parent scope, but doesn't
      // exist in the history data yet
      viewHistory.histories[ histObj.historyId ] = {
        historyId: histObj.historyId,
        parentHistoryId: getParentHistoryObj(histObj.scope.$parent).historyId,
        stack: [],
        cursor: -1
      };
    }
    return getHistoryById(histObj.historyId);
  }

  function getParentHistoryObj(scope) {
    var parentScope = scope;
    while (parentScope) {
      if (parentScope.hasOwnProperty('$historyId')) {
        // this parent scope has a historyId
        return { historyId: parentScope.$historyId, scope: parentScope };
      }
      // nothing found keep climbing up
      parentScope = parentScope.$parent;
    }
    // no history for the parent, use the root
    return { historyId: 'root', scope: $rootScope };
  }

  function setNavViews(viewId) {
    viewHistory.currentView = getViewById(viewId);
    viewHistory.backView = getBackView(viewHistory.currentView);
    viewHistory.forwardView = getForwardView(viewHistory.currentView);
  }

  function getCurrentStateId() {
    var id;
    if ($state && $state.current && $state.current.name) {
      id = $state.current.name;
      if ($state.params) {
        for (var key in $state.params) {
          if ($state.params.hasOwnProperty(key) && $state.params[key]) {
            id += "_" + key + "=" + $state.params[key];
          }
        }
      }
      return id;
    }
    // if something goes wrong make sure its got a unique stateId
    return ionic.Utils.nextUid();
  }

  function getCurrentStateParams() {
    var rtn;
    if ($state && $state.params) {
      for (var key in $state.params) {
        if ($state.params.hasOwnProperty(key)) {
          rtn = rtn || {};
          rtn[key] = $state.params[key];
        }
      }
    }
    return rtn;
  }


  return {

    register: function(parentScope, viewLocals) {

      var currentStateId = getCurrentStateId(),
          hist = getHistory(parentScope),
          currentView = viewHistory.currentView,
          backView = viewHistory.backView,
          forwardView = viewHistory.forwardView,
          viewId = null,
          action = null,
          direction = DIRECTION_NONE,
          historyId = hist.historyId,
          url = $location.url(),
          tmp, x, ele;

      if (lastStateId !== currentStateId) {
        lastStateId = currentStateId;
        stateChangeCounter++;
      }

      if (forcedNav) {
        // we've previously set exactly what to do
        viewId = forcedNav.viewId;
        action = forcedNav.action;
        direction = forcedNav.direction;
        forcedNav = null;

      } else if (backView && backView.stateId === currentStateId) {
        // they went back one, set the old current view as a forward view
        viewId = backView.viewId;
        historyId = backView.historyId;
        action = ACTION_MOVE_BACK;
        if (backView.historyId === currentView.historyId) {
          // went back in the same history
          direction = DIRECTION_BACK;

        } else if (currentView) {
          direction = DIRECTION_EXIT;

          tmp = getHistoryById(backView.historyId);
          if (tmp && tmp.parentHistoryId === currentView.historyId) {
            direction = DIRECTION_ENTER;

          } else {
            tmp = getHistoryById(currentView.historyId);
            if (tmp && tmp.parentHistoryId === hist.parentHistoryId) {
              direction = DIRECTION_SWAP;
            }
          }
        }

      } else if (forwardView && forwardView.stateId === currentStateId) {
        // they went to the forward one, set the forward view to no longer a forward view
        viewId = forwardView.viewId;
        historyId = forwardView.historyId;
        action = ACTION_MOVE_FORWARD;
        if (forwardView.historyId === currentView.historyId) {
          direction = DIRECTION_FORWARD;

        } else if (currentView) {
          direction = DIRECTION_EXIT;

          if (currentView.historyId === hist.parentHistoryId) {
            direction = DIRECTION_ENTER;

          } else {
            tmp = getHistoryById(currentView.historyId);
            if (tmp && tmp.parentHistoryId === hist.parentHistoryId) {
              direction = DIRECTION_SWAP;
            }
          }
        }

        tmp = getParentHistoryObj(parentScope);
        if (forwardView.historyId && tmp.scope) {
          // if a history has already been created by the forward view then make sure it stays the same
          tmp.scope.$historyId = forwardView.historyId;
          historyId = forwardView.historyId;
        }

      } else if (currentView && currentView.historyId !== historyId &&
                hist.cursor > -1 && hist.stack.length > 0 && hist.cursor < hist.stack.length &&
                hist.stack[hist.cursor].stateId === currentStateId) {
        // they just changed to a different history and the history already has views in it
        var switchToView = hist.stack[hist.cursor];
        viewId = switchToView.viewId;
        historyId = switchToView.historyId;
        action = ACTION_MOVE_BACK;
        direction = DIRECTION_SWAP;

        tmp = getHistoryById(currentView.historyId);
        if (tmp && tmp.parentHistoryId === historyId) {
          direction = DIRECTION_EXIT;

        } else {
          tmp = getHistoryById(historyId);
          if (tmp && tmp.parentHistoryId === currentView.historyId) {
            direction = DIRECTION_ENTER;
          }
        }

        // if switching to a different history, and the history of the view we're switching
        // to has an existing back view from a different history than itself, then
        // it's back view would be better represented using the current view as its back view
        tmp = getViewById(switchToView.backViewId);
        if (tmp && switchToView.historyId !== tmp.historyId) {
          hist.stack[hist.cursor].backViewId = currentView.viewId;
        }

      } else {

        // create an element from the viewLocals template
        ele = $ionicViewSwitcher.createViewEle(viewLocals);
        if (this.isAbstractEle(ele, viewLocals)) {
          void 0;
          return {
            action: 'abstractView',
            direction: DIRECTION_NONE,
            ele: ele
          };
        }

        // set a new unique viewId
        viewId = ionic.Utils.nextUid();

        if (currentView) {
          // set the forward view if there is a current view (ie: if its not the first view)
          currentView.forwardViewId = viewId;

          action = ACTION_NEW_VIEW;

          // check if there is a new forward view within the same history
          if (forwardView && currentView.stateId !== forwardView.stateId &&
             currentView.historyId === forwardView.historyId) {
            // they navigated to a new view but the stack already has a forward view
            // since its a new view remove any forwards that existed
            tmp = getHistoryById(forwardView.historyId);
            if (tmp) {
              // the forward has a history
              for (x = tmp.stack.length - 1; x >= forwardView.index; x--) {
                // starting from the end destroy all forwards in this history from this point
                tmp.stack[x].destroy();
                tmp.stack.splice(x);
              }
              historyId = forwardView.historyId;
            }
          }

          // its only moving forward if its in the same history
          if (hist.historyId === currentView.historyId) {
            direction = DIRECTION_FORWARD;

          } else if (currentView.historyId !== hist.historyId) {
            direction = DIRECTION_ENTER;

            tmp = getHistoryById(currentView.historyId);
            if (tmp && tmp.parentHistoryId === hist.parentHistoryId) {
              direction = DIRECTION_SWAP;

            } else {
              tmp = getHistoryById(tmp.parentHistoryId);
              if (tmp && tmp.historyId === hist.historyId) {
                direction = DIRECTION_EXIT;
              }
            }
          }

        } else {
          // there's no current view, so this must be the initial view
          action = ACTION_INITIAL_VIEW;
        }

        if (stateChangeCounter < 2) {
          // views that were spun up on the first load should not animate
          direction = DIRECTION_NONE;
        }

        // add the new view
        viewHistory.views[viewId] = this.createView({
          viewId: viewId,
          index: hist.stack.length,
          historyId: hist.historyId,
          backViewId: (currentView && currentView.viewId ? currentView.viewId : null),
          forwardViewId: null,
          stateId: currentStateId,
          stateName: this.currentStateName(),
          stateParams: getCurrentStateParams(),
          url: url
        });

        // add the new view to this history's stack
        hist.stack.push(viewHistory.views[viewId]);
      }

      $timeout.cancel(nextViewExpireTimer);
      if (nextViewOptions) {
        if (nextViewOptions.disableAnimate) direction = DIRECTION_NONE;
        if (nextViewOptions.disableBack) viewHistory.views[viewId].backViewId = null;
        if (nextViewOptions.historyRoot) {
          for (x = 0; x < hist.stack.length; x++) {
            if (hist.stack[x].viewId === viewId) {
              hist.stack[x].index = 0;
              hist.stack[x].backViewId = hist.stack[x].forwardViewId = null;
            } else {
              delete viewHistory.views[hist.stack[x].viewId];
            }
          }
          hist.stack = [viewHistory.views[viewId]];
        }
        nextViewOptions = null;
      }

      setNavViews(viewId);

      if (viewHistory.backView && historyId == viewHistory.backView.historyId && currentStateId == viewHistory.backView.stateId && url == viewHistory.backView.url) {
        for (x = 0; x < hist.stack.length; x++) {
          if (hist.stack[x].viewId == viewId) {
            action = 'dupNav';
            direction = DIRECTION_NONE;
            hist.stack[x - 1].forwardViewId = viewHistory.forwardView = null;
            viewHistory.currentView.index = viewHistory.backView.index;
            viewHistory.currentView.backViewId = viewHistory.backView.backViewId;
            viewHistory.backView = getBackView(viewHistory.backView);
            hist.stack.splice(x, 1);
            break;
          }
        }
      }

      void 0;

      hist.cursor = viewHistory.currentView.index;

      return {
        viewId: viewId,
        action: action,
        direction: direction,
        historyId: historyId,
        enableBack: !!(viewHistory.backView && viewHistory.backView.historyId === viewHistory.currentView.historyId),
        isHistoryRoot: (viewHistory.currentView.index === 0),
        ele: ele
      };
    },

    registerHistory: function(scope) {
      scope.$historyId = ionic.Utils.nextUid();
    },

    createView: function(data) {
      var newView = new View();
      return newView.initialize(data);
    },

    getViewById: getViewById,

    /**
     * @ngdoc method
     * @name $ionicHistory#viewHistory
     * @description The app's view history data, such as all the views and histories, along
     * with how they are ordered and linked together within the navigation stack.
     * @returns {object} Returns an object containing the apps view history data.
     */
    viewHistory: function() {
      return viewHistory;
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#currentView
     * @description The app's current view.
     * @returns {object} Returns the current view.
     */
    currentView: function(view) {
      if (arguments.length) {
        viewHistory.currentView = view;
      }
      return viewHistory.currentView;
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#currentHistoryId
     * @description The ID of the history stack which is the parent container of the current view.
     * @returns {string} Returns the current history ID.
     */
    currentHistoryId: function() {
      return viewHistory.currentView ? viewHistory.currentView.historyId : null;
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#currentTitle
     * @description Gets and sets the current view's title.
     * @param {string=} val The title to update the current view with.
     * @returns {string} Returns the current view's title.
     */
    currentTitle: function(val) {
      if (viewHistory.currentView) {
        if (arguments.length) {
          viewHistory.currentView.title = val;
        }
        return viewHistory.currentView.title;
      }
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#backView
     * @description Returns the view that was before the current view in the history stack.
     * If the user navigated from View A to View B, then View A would be the back view, and
     * View B would be the current view.
     * @returns {object} Returns the back view.
     */
    backView: function(view) {
      if (arguments.length) {
        viewHistory.backView = view;
      }
      return viewHistory.backView;
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#backTitle
     * @description Gets the back view's title.
     * @returns {string} Returns the back view's title.
     */
    backTitle: function() {
      if (viewHistory.backView) {
        return viewHistory.backView.title;
      }
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#forwardView
     * @description Returns the view that was in front of the current view in the history stack.
     * A forward view would exist if the user navigated from View A to View B, then
     * navigated back to View A. At this point then View B would be the forward view, and View
     * A would be the current view.
     * @returns {object} Returns the forward view.
     */
    forwardView: function(view) {
      if (arguments.length) {
        viewHistory.forwardView = view;
      }
      return viewHistory.forwardView;
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#currentStateName
     * @description Returns the current state name.
     * @returns {string}
     */
    currentStateName: function() {
      return ($state && $state.current ? $state.current.name : null);
    },

    isCurrentStateNavView: function(navView) {
      return !!($state && $state.current && $state.current.views && $state.current.views[navView]);
    },

    goToHistoryRoot: function(historyId) {
      if (historyId) {
        var hist = getHistoryById(historyId);
        if (hist && hist.stack.length) {
          if (viewHistory.currentView && viewHistory.currentView.viewId === hist.stack[0].viewId) {
            return;
          }
          forcedNav = {
            viewId: hist.stack[0].viewId,
            action: ACTION_MOVE_BACK,
            direction: DIRECTION_BACK
          };
          hist.stack[0].go();
        }
      }
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#goBack
     * @description Navigates the app to the back view, if a back view exists.
     */
    goBack: function() {
      viewHistory.backView && viewHistory.backView.go();
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#clearHistory
     * @description Clears out the app's entire history, except for the current view.
     */
    clearHistory: function() {
      var
      histories = viewHistory.histories,
      currentView = viewHistory.currentView;

      if (histories) {
        for (var historyId in histories) {

          if (histories[historyId].stack) {
            histories[historyId].stack = [];
            histories[historyId].cursor = -1;
          }

          if (currentView && currentView.historyId === historyId) {
            currentView.backViewId = currentView.forwardViewId = null;
            histories[historyId].stack.push(currentView);
          } else if (histories[historyId].destroy) {
            histories[historyId].destroy();
          }

        }
      }

      for (var viewId in viewHistory.views) {
        if (viewId !== currentView.viewId) {
          delete viewHistory.views[viewId];
        }
      }

      if (currentView) {
        setNavViews(currentView.viewId);
      }
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#clearCache
     * @description Removes all cached views within every {@link ionic.directive:ionNavView}.
     * This both removes the view element from the DOM, and destroy it's scope.
     */
    clearCache: function() {
      $ionicNavViewDelegate._instances.forEach(function(instance) {
        instance.clearCache();
      });
    },

    /**
     * @ngdoc method
     * @name $ionicHistory#nextViewOptions
     * @description Sets options for the next view. This method can be useful to override
     * certain view/transition defaults right before a view transition happens. For example,
     * the {@link ionic.directive:menuClose} directive uses this method internally to ensure
     * an animated view transition does not happen when a side menu is open, and also sets
     * the next view as the root of its history stack. After the transition these options
     * are set back to null.
     *
     * Available options:
     *
     * * `disableAnimate`: Do not animate the next transition.
     * * `disableBack`: The next view should forget its back view, and set it to null.
     * * `historyRoot`: The next view should become the root view in its history stack.
     *
     * ```js
     * $ionicHistory.nextViewOptions({
     *   disableAnimate: true,
     *   disableBack: true
     * });
     * ```
     */
    nextViewOptions: function(opts) {
      if (arguments.length) {
        $timeout.cancel(nextViewExpireTimer);
        if (opts === null) {
          nextViewOptions = opts;
        } else {
          nextViewOptions = nextViewOptions || {};
          extend(nextViewOptions, opts);
          if (nextViewOptions.expire) {
            nextViewExpireTimer = $timeout(function(){
              nextViewOptions = null;
            }, nextViewOptions.expire);
          }
        }
      }
      return nextViewOptions;
    },

    isAbstractEle: function(ele, viewLocals) {
      if (viewLocals && viewLocals.$$state && viewLocals.$$state.self.abstract) {
        return true;
      }
      return !!(ele && (isAbstractTag(ele) || isAbstractTag(ele.children())));
    },

    isActiveScope: function(scope) {
      if (!scope) return false;

      var climbScope = scope;
      var currentHistoryId = this.currentHistoryId();
      var foundHistoryId;

      while (climbScope) {
        if (climbScope.$$disconnected) {
          return false;
        }

        if (!foundHistoryId && climbScope.hasOwnProperty('$historyId')) {
          foundHistoryId = true;
        }

        if (currentHistoryId) {
          if (climbScope.hasOwnProperty('$historyId') && currentHistoryId == climbScope.$historyId) {
            return true;
          }
          if (climbScope.hasOwnProperty('$activeHistoryId')) {
            if (currentHistoryId == climbScope.$activeHistoryId) {
              if (climbScope.hasOwnProperty('$historyId')) {
                return true;
              }
              if (!foundHistoryId) {
                return true;
              }
            }
          }
        }

        if (foundHistoryId && climbScope.hasOwnProperty('$activeHistoryId')) {
          foundHistoryId = false;
        }

        climbScope = climbScope.$parent;
      }

      return currentHistoryId ? currentHistoryId == 'root' : true;
    }

  };

  function isAbstractTag(ele) {
    return ele && ele.length && /ion-side-menus|ion-tabs/i.test(ele[0].tagName);
  }

}])

.run([
  '$rootScope',
  '$state',
  '$location',
  '$document',
  '$ionicPlatform',
  '$ionicHistory',
function($rootScope, $state, $location, $document, $ionicPlatform, $ionicHistory) {

  // always reset the keyboard state when change stage
  $rootScope.$on('$ionicView.beforeEnter', function() {
    ionic.keyboard && ionic.keyboard.hide && ionic.keyboard.hide();
  });

  $rootScope.$on('$ionicHistory.change', function(e, data) {
    if (!data) return;

    var viewHistory = $ionicHistory.viewHistory();

    var hist = (data.historyId ? viewHistory.histories[ data.historyId ] : null);
    if (hist && hist.cursor > -1 && hist.cursor < hist.stack.length) {
      // the history they're going to already exists
      // go to it's last view in its stack
      var view = hist.stack[ hist.cursor ];
      return view.go(data);
    }

    // this history does not have a URL, but it does have a uiSref
    // figure out its URL from the uiSref
    if (!data.url && data.uiSref) {
      data.url = $state.href(data.uiSref);
    }

    if (data.url) {
      // don't let it start with a #, messes with $location.url()
      if (data.url.indexOf('#') === 0) {
        data.url = data.url.replace('#', '');
      }
      if (data.url !== $location.url()) {
        // we've got a good URL, ready GO!
        $location.url(data.url);
      }
    }
  });

  $rootScope.$ionicGoBack = function() {
    $ionicHistory.goBack();
  };

  // Set the document title when a new view is shown
  $rootScope.$on('$ionicView.afterEnter', function(ev, data) {
    if (data && data.title) {
      $document[0].title = data.title;
    }
  });

  // Triggered when devices with a hardware back button (Android) is clicked by the user
  // This is a Cordova/Phonegap platform specifc method
  function onHardwareBackButton(e) {
    var backView = $ionicHistory.backView();
    if (backView) {
      // there is a back view, go to it
      backView.go();
    } else {
      // there is no back view, so close the app instead
      ionic.Platform.exitApp();
    }
    e.preventDefault();
    return false;
  }
  $ionicPlatform.registerBackButtonAction(
    onHardwareBackButton,
    PLATFORM_BACK_BUTTON_PRIORITY_VIEW
  );

}]);

/**
 * @ngdoc provider
 * @name $ionicConfigProvider
 * @module ionic
 * @description
 * Ionic automatically takes platform configurations into account to adjust things like what
 * transition style to use and whether tab icons should show on the top or bottom. For example,
 * iOS will move forward by transitioning the entering view from right to center and the leaving
 * view from center to left. However, Android will transition with the entering view going from
 * bottom to center, covering the previous view, which remains stationary. It should be noted
 * that when a platform is not iOS or Android, then it'll default to iOS. So if you are
 * developing on a desktop browser, it's going to take on iOS default configs.
 *
 * These configs can be changed using the `$ionicConfigProvider` during the configuration phase
 * of your app. Additionally, `$ionicConfig` can also set and get config values during the run
 * phase and within the app itself.
 *
 * By default, all base config variables are set to `'platform'`, which means it'll take on the
 * default config of the platform on which it's running. Config variables can be set at this
 * level so all platforms follow the same setting, rather than its platform config.
 * The following code would set the same config variable for all platforms:
 *
 * ```js
 * $ionicConfigProvider.views.maxCache(10);
 * ```
 *
 * Additionally, each platform can have it's own config within the `$ionicConfigProvider.platform`
 * property. The config below would only apply to Android devices.
 *
 * ```js
 * $ionicConfigProvider.platform.android.views.maxCache(5);
 * ```
 *
 * @usage
 * ```js
 * var myApp = angular.module('reallyCoolApp', ['ionic']);
 *
 * myApp.config(function($ionicConfigProvider) {
 *   $ionicConfigProvider.views.maxCache(5);
 *
 *   // note that you can also chain configs
 *   $ionicConfigProvider.backButton.text('Go Back').icon('ion-chevron-left');
 * });
 * ```
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#views.transition
 * @description Animation style when transitioning between views. Default `platform`.
 *
 * @param {string} transition Which style of view transitioning to use.
 *
 * * `platform`: Dynamically choose the correct transition style depending on the platform
 * the app is running from. If the platform is not `ios` or `android` then it will default
 * to `ios`.
 * * `ios`: iOS style transition.
 * * `android`: Android style transition.
 * * `none`: Do not preform animated transitions.
 *
 * @returns {string} value
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#views.maxCache
 * @description  Maximum number of view elements to cache in the DOM. When the max number is
 * exceeded, the view with the longest time period since it was accessed is removed. Views that
 * stay in the DOM cache the view's scope, current state, and scroll position. The scope is
 * disconnected from the `$watch` cycle when it is cached and reconnected when it enters again.
 * When the maximum cache is `0`, the leaving view's element will be removed from the DOM after
 * each view transition, and the next time the same view is shown, it will have to re-compile,
 * attach to the DOM, and link the element again. This disables caching, in effect.
 * @param {number} maxNumber Maximum number of views to retain. Default `10`.
 * @returns {number} How many views Ionic will hold onto until the a view is removed.
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#views.forwardCache
 * @description  By default, when navigating, views that were recently visited are cached, and
 * the same instance data and DOM elements are referenced when navigating back. However, when
 * navigating back in the history, the "forward" views are removed from the cache. If you
 * navigate forward to the same view again, it'll create a new DOM element and controller
 * instance. Basically, any forward views are reset each time. Set this config to `true` to have
 * forward views cached and not reset on each load.
 * @param {boolean} value
 * @returns {boolean}
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#backButton.icon
 * @description Back button icon.
 * @param {string} value
 * @returns {string}
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#backButton.text
 * @description Back button text.
 * @param {string} value Defaults to `Back`.
 * @returns {string}
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#backButton.previousTitleText
 * @description If the previous title text should become the back button text. This
 * is the default for iOS.
 * @param {boolean} value
 * @returns {boolean}
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#tabs.style
 * @description Tab style. Android defaults to `striped` and iOS defaults to `standard`.
 * @param {string} value Available values include `striped` and `standard`.
 * @returns {string}
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#tabs.position
 * @description Tab position. Android defaults to `top` and iOS defaults to `bottom`.
 * @param {string} value Available values include `top` and `bottom`.
 * @returns {string}
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#templates.maxPrefetch
 * @description Sets the maximum number of templates to prefetch from the templateUrls defined in
 * $stateProvider.state. If set to `0`, the user will have to wait
 * for a template to be fetched the first time when navigating to a new page. Default `30`.
 * @param {integer} value Max number of template to prefetch from the templateUrls defined in
 * `$stateProvider.state()`.
 * @returns {integer}
 */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#navBar.alignTitle
 * @description Which side of the navBar to align the title. Default `center`.
 *
 * @param {string} value side of the navBar to align the title.
 *
 * * `platform`: Dynamically choose the correct title style depending on the platform
 * the app is running from. If the platform is `ios`, it will default to `center`.
 * If the platform is `android`, it will default to `left`. If the platform is not
 * `ios` or `android`, it will default to `center`.
 *
 * * `left`: Left align the title in the navBar
 * * `center`: Center align the title in the navBar
 * * `right`: Right align the title in the navBar.
 *
 * @returns {string} value
 */

/**
  * @ngdoc method
  * @name $ionicConfigProvider#navBar.positionPrimaryButtons
  * @description Which side of the navBar to align the primary navBar buttons. Default `left`.
  *
  * @param {string} value side of the navBar to align the primary navBar buttons.
  *
  * * `platform`: Dynamically choose the correct title style depending on the platform
  * the app is running from. If the platform is `ios`, it will default to `left`.
  * If the platform is `android`, it will default to `right`. If the platform is not
  * `ios` or `android`, it will default to `left`.
  *
  * * `left`: Left align the primary navBar buttons in the navBar
  * * `right`: Right align the primary navBar buttons in the navBar.
  *
  * @returns {string} value
  */

/**
 * @ngdoc method
 * @name $ionicConfigProvider#navBar.positionSecondaryButtons
 * @description Which side of the navBar to align the secondary navBar buttons. Default `right`.
 *
 * @param {string} value side of the navBar to align the secondary navBar buttons.
 *
 * * `platform`: Dynamically choose the correct title style depending on the platform
 * the app is running from. If the platform is `ios`, it will default to `right`.
 * If the platform is `android`, it will default to `right`. If the platform is not
 * `ios` or `android`, it will default to `right`.
 *
 * * `left`: Left align the secondary navBar buttons in the navBar
 * * `right`: Right align the secondary navBar buttons in the navBar.
 *
 * @returns {string} value
 */

IonicModule
.provider('$ionicConfig', function() {

  var provider = this;
  provider.platform = {};
  var PLATFORM = 'platform';

  var configProperties = {
    views: {
      maxCache: PLATFORM,
      forwardCache: PLATFORM,
      transition: PLATFORM
    },
    navBar: {
      alignTitle: PLATFORM,
      positionPrimaryButtons: PLATFORM,
      positionSecondaryButtons: PLATFORM,
      transition: PLATFORM
    },
    backButton: {
      icon: PLATFORM,
      text: PLATFORM,
      previousTitleText: PLATFORM
    },
    form: {
      checkbox: PLATFORM
    },
    tabs: {
      style: PLATFORM,
      position: PLATFORM
    },
    templates: {
      maxPrefetch: PLATFORM
    },
    platform: {}
  };
  createConfig(configProperties, provider, '');



  // Default
  // -------------------------
  setPlatformConfig('default', {

    views: {
      maxCache: 10,
      forwardCache: false,
      transition: 'ios'
    },

    navBar: {
      alignTitle: 'center',
      positionPrimaryButtons: 'left',
      positionSecondaryButtons: 'right',
      transition: 'view'
    },

    backButton: {
      icon: 'ion-ios7-arrow-back',
      text: 'Back',
      previousTitleText: true
    },

    form: {
      checkbox: 'circle'
    },

    tabs: {
      style: 'standard',
      position: 'bottom'
    },

    templates: {
      maxPrefetch: 30
    }

  });



  // iOS (it is the default already)
  // -------------------------
  setPlatformConfig('ios', {});



  // Android
  // -------------------------
  setPlatformConfig('android', {

    views: {
      transition: 'android'
    },

    navBar: {
      alignTitle: 'left',
      positionPrimaryButtons: 'right',
      positionSecondaryButtons: 'right'
    },

    backButton: {
      icon: 'ion-arrow-left-c',
      text: false,
      previousTitleText: false
    },

    form: {
      checkbox: 'square'
    },

    tabs: {
      style: 'striped',
      position: 'top'
    }

  });


  provider.transitions = {
    views: {},
    navBar: {}
  };


  // iOS Transitions
  // -----------------------
  provider.transitions.views.ios = function(enteringEle, leavingEle, direction, shouldAnimate) {
    shouldAnimate = shouldAnimate && (direction == 'forward' || direction == 'back');

    function setStyles(ele, opacity, x) {
      var css = {};
      css[ionic.CSS.TRANSITION_DURATION] = shouldAnimate ? '' : 0;
      css.opacity = opacity;
      css[ionic.CSS.TRANSFORM] = 'translate3d(' + x + '%,0,0)';
      ionic.DomUtil.cachedStyles(ele, css);
    }

    return {
      run: function(step) {
        if (direction == 'forward') {
          setStyles(enteringEle, 1, (1 - step) * 99); // starting at 98% prevents a flicker
          setStyles(leavingEle, (1 - 0.1 * step), step * -33);

        } else if (direction == 'back') {
          setStyles(enteringEle, (1 - 0.1 * (1 - step)), (1 - step) * -33);
          setStyles(leavingEle, 1, step * 100);

        } else {
          // swap, enter, exit
          setStyles(enteringEle, 1, 0);
          setStyles(leavingEle, 0, 0);
        }
      },
      shouldAnimate: shouldAnimate
    };
  };

  provider.transitions.navBar.ios = function(enteringHeaderBar, leavingHeaderBar, direction, shouldAnimate) {
    shouldAnimate = shouldAnimate && (direction == 'forward' || direction == 'back');

    function setStyles(ctrl, opacity, titleX, backTextX) {
      var css = {};
      css[ionic.CSS.TRANSITION_DURATION] = shouldAnimate ? '' : 0;
      css.opacity = opacity === 1 ? '' : opacity;

      ctrl.setCss('buttons-left', css);
      ctrl.setCss('buttons-right', css);
      ctrl.setCss('back-button', css);

      css[ionic.CSS.TRANSFORM] = 'translate3d(' + backTextX + 'px,0,0)';
      ctrl.setCss('back-text', css);

      css[ionic.CSS.TRANSFORM] = 'translate3d(' + titleX + 'px,0,0)';
      ctrl.setCss('title', css);
    }

    function enter(ctrlA, ctrlB, step) {
      if (!ctrlA) return;
      var titleX = (ctrlA.titleTextX() + ctrlA.titleWidth()) * (1 - step);
      var backTextX = (ctrlB && (ctrlB.titleTextX() - ctrlA.backButtonTextLeft()) * (1 - step)) || 0;
      setStyles(ctrlA, step, titleX, backTextX);
    }

    function leave(ctrlA, ctrlB, step) {
      if (!ctrlA) return;
      var titleX = (-(ctrlA.titleTextX() - ctrlB.backButtonTextLeft()) - (ctrlA.titleLeftRight())) * step;
      setStyles(ctrlA, 1 - step, titleX, 0);
    }

    return {
      run: function(step) {
        var enteringHeaderCtrl = enteringHeaderBar.controller();
        var leavingHeaderCtrl = leavingHeaderBar && leavingHeaderBar.controller();
        if (direction == 'back') {
          leave(enteringHeaderCtrl, leavingHeaderCtrl, 1 - step);
          enter(leavingHeaderCtrl, enteringHeaderCtrl, 1 - step);
        } else {
          enter(enteringHeaderCtrl, leavingHeaderCtrl, step);
          leave(leavingHeaderCtrl, enteringHeaderCtrl, step);
        }
      },
      shouldAnimate: shouldAnimate
    };
  };


  // Android Transitions
  // -----------------------

  provider.transitions.views.android = function(enteringEle, leavingEle, direction, shouldAnimate) {
    shouldAnimate = shouldAnimate && (direction == 'forward' || direction == 'back');

    function setStyles(ele, x) {
      var css = {};
      css[ionic.CSS.TRANSITION_DURATION] = shouldAnimate ? '' : 0;
      css[ionic.CSS.TRANSFORM] = 'translate3d(' + x + '%,0,0)';
      ionic.DomUtil.cachedStyles(ele, css);
    }

    return {
      run: function(step) {
        if (direction == 'forward') {
          setStyles(enteringEle, (1 - step) * 99); // starting at 98% prevents a flicker
          setStyles(leavingEle, step * -100);

        } else if (direction == 'back') {
          setStyles(enteringEle, (1 - step) * -100);
          setStyles(leavingEle, step * 100);

        } else {
          // swap, enter, exit
          setStyles(enteringEle, 0);
          setStyles(leavingEle, 0);
        }
      },
      shouldAnimate: shouldAnimate
    };
  };

  provider.transitions.navBar.android = function(enteringHeaderBar, leavingHeaderBar, direction, shouldAnimate) {
    shouldAnimate = shouldAnimate && (direction == 'forward' || direction == 'back');

    function setStyles(ctrl, opacity) {
      if (!ctrl) return;
      var css = {};
      css.opacity = opacity === 1 ? '' : opacity;

      ctrl.setCss('buttons-left', css);
      ctrl.setCss('buttons-right', css);
      ctrl.setCss('back-button', css);
      ctrl.setCss('back-text', css);
      ctrl.setCss('title', css);
    }

    return {
      run: function(step) {
        setStyles(enteringHeaderBar.controller(), step);
        setStyles(leavingHeaderBar && leavingHeaderBar.controller(), 1 - step);
      },
      shouldAnimate: true
    };
  };


  // No Transition
  // -----------------------

  provider.transitions.views.none = function(enteringEle, leavingEle) {
    return {
      run: function(step) {
        provider.transitions.views.android(enteringEle, leavingEle, false, false).run(step);
      }
    };
  };

  provider.transitions.navBar.none = function(enteringHeaderBar, leavingHeaderBar) {
    return {
      run: function(step) {
        provider.transitions.navBar.ios(enteringHeaderBar, leavingHeaderBar, false, false).run(step);
        provider.transitions.navBar.android(enteringHeaderBar, leavingHeaderBar, false, false).run(step);
      }
    };
  };


  // private: used to set platform configs
  function setPlatformConfig(platformName, platformConfigs) {
    configProperties.platform[platformName] = platformConfigs;
    provider.platform[platformName] = {};

    addConfig(configProperties, configProperties.platform[platformName]);

    createConfig(configProperties.platform[platformName], provider.platform[platformName], '');
  }


  // private: used to recursively add new platform configs
  function addConfig(configObj, platformObj) {
    for (var n in configObj) {
      if (n != PLATFORM && configObj.hasOwnProperty(n)) {
        if (angular.isObject(configObj[n])) {
          if (!isDefined(platformObj[n])) {
            platformObj[n] = {};
          }
          addConfig(configObj[n], platformObj[n]);

        } else if (!isDefined(platformObj[n])) {
          platformObj[n] = null;
        }
      }
    }
  }


  // private: create methods for each config to get/set
  function createConfig(configObj, providerObj, platformPath) {
    forEach(configObj, function(value, namespace) {

      if (angular.isObject(configObj[namespace])) {
        // recursively drill down the config object so we can create a method for each one
        providerObj[namespace] = {};
        createConfig(configObj[namespace], providerObj[namespace], platformPath + '.' + namespace);

      } else {
        // create a method for the provider/config methods that will be exposed
        providerObj[namespace] = function(newValue) {
          if (arguments.length) {
            configObj[namespace] = newValue;
            return providerObj;
          }
          if (configObj[namespace] == PLATFORM) {
            // if the config is set to 'platform', then get this config's platform value
            var platformConfig = stringObj(configProperties.platform, ionic.Platform.platform() + platformPath + '.' + namespace);
            if (platformConfig || platformConfig === false) {
              return platformConfig;
            }
            // didnt find a specific platform config, now try the default
            return stringObj(configProperties.platform, 'default' + platformPath + '.' + namespace);
          }
          return configObj[namespace];
        };
      }

    });
  }

  function stringObj(obj, str) {
    str = str.split(".");
    for (var i = 0; i < str.length; i++) {
      if (obj && isDefined(obj[str[i]])) {
        obj = obj[str[i]];
      } else {
        return null;
      }
    }
    return obj;
  }

  provider.setPlatformConfig = setPlatformConfig;


  // private: Service definition for internal Ionic use
  /**
   * @ngdoc service
   * @name $ionicConfig
   * @module ionic
   * @private
   */
  provider.$get = function() {
    return provider;
  };
});


var LOADING_TPL =
  '<div class="loading-container">' +
    '<div class="loading">' +
    '</div>' +
  '</div>';

var LOADING_HIDE_DEPRECATED = '$ionicLoading instance.hide() has been deprecated. Use $ionicLoading.hide().';
var LOADING_SHOW_DEPRECATED = '$ionicLoading instance.show() has been deprecated. Use $ionicLoading.show().';
var LOADING_SET_DEPRECATED = '$ionicLoading instance.setContent() has been deprecated. Use $ionicLoading.show({ template: \'my content\' }).';

/**
 * @ngdoc service
 * @name $ionicLoading
 * @module ionic
 * @description
 * An overlay that can be used to indicate activity while blocking user
 * interaction.
 *
 * @usage
 * ```js
 