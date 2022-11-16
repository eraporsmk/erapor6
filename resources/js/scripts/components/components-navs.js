/*=========================================================================================
    File Name: navs.js
    Description: Navigation available in Bootstrap share general markup and styles, from
                the base .nav class to the active and disabled states. Swap modifier
                classes to switch between each style.
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
(function (window, document, $) {
  'use strict';

  // add height to navigation in left tabs according to content area height
  var heightLeft = $('.nav-left + .tab-content').height();

  $('ul.nav-left').height(heightLeft);

  // add height to navigation in right tabs according to content area height
  var heightRight = $('.nav-right + .tab-content').height();

  $('ul.nav-right').height(heightRight);
})(window, document, jQuery);
