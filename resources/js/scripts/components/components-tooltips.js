/*=========================================================================================
    File Name: tooltip.js
    Description: Tooltips are an updated version, which don’t rely on images, use CSS3 for animations, and data-attributes for local title storage.
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: Pixinvent
    Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/
(function (window, document, $) {
  'use strict';

  /* Manual Trigger*/
  var tooltipTriggerList = document.getElementById('manual-tooltip');

  var manualTooltip = new bootstrap.Tooltip(tooltipTriggerList);
  tooltipTriggerList.addEventListener('click', function () {
    manualTooltip.show();
  });

  tooltipTriggerList.addEventListener('mouseleave', function () {
    manualTooltip.hide();
  });

  /*******************/
  // Tooltip methods //
  /*******************/

  // Show method
  var showMethod = document.getElementById('show-method');

  var showTooltipMethod = new bootstrap.Tooltip(showMethod);
  showMethod.addEventListener('click', function () {
    showTooltipMethod.show();
  });

  // Hide method
  var hideMethod = document.getElementById('hide-method');

  var hideTooltipMethod = new bootstrap.Tooltip(hideMethod);
  hideMethod.addEventListener('mouseenter', function () {
    hideTooltipMethod.show();
  });

  hideMethod.addEventListener('click', function () {
    hideTooltipMethod.hide();
  });

  // Toggle method
  var toggleMethod = document.getElementById('toggle-method');

  var toggleTooltipMethod = new bootstrap.Tooltip(toggleMethod);
  toggleMethod.addEventListener('click', function () {
    toggleTooltipMethod.toggle();
  });

  /******************/
  // Tooltip events //
  /******************/

  // onShow event
  var showTooltipTrigger = document.getElementById('show-tooltip');

  var showTooltip = new bootstrap.Tooltip(showTooltipTrigger, {
    title: 'Tooltip Show Event',
    trigger: 'click',
    placement: 'right'
  });

  showTooltipTrigger.addEventListener('show.bs.tooltip', function () {
    alert('Show event fired.');
  });

  // onShown event
  var shownTooltipTrigger = document.getElementById('shown-tooltip');

  var shownTooltip = new bootstrap.Tooltip(shownTooltipTrigger, {
    title: 'Tooltip Shown Event',
    trigger: 'click',
    placement: 'top'
  });

  shownTooltipTrigger.addEventListener('shown.bs.tooltip', function () {
    alert('Shown event fired.');
  });

  // onHide event
  var hideTooltipTrigger = document.getElementById('hide-tooltip');

  var hideTooltip = new bootstrap.Tooltip(hideTooltipTrigger, {
    title: 'Tooltip Hide Event',
    trigger: 'click',
    placement: 'bottom'
  });

  hideTooltipTrigger.addEventListener('hide.bs.tooltip', function () {
    alert('Hide event fired.');
  });

  // onHidden event
  var hiddenTooltipTrigger = document.getElementById('hidden-tooltip');

  var hiddenTooltip = new bootstrap.Tooltip(hiddenTooltipTrigger, {
    title: 'Tooltip Hidden Event',
    trigger: 'click',
    placement: 'left'
  });

  hiddenTooltipTrigger.addEventListener('hidden.bs.tooltip', function () {
    alert('Hidden event fired.');
  });

  // onInserted event
  var insertedTooltipTrigger = document.getElementById('inserted-tooltip');

  var insertedTooltip = new bootstrap.Tooltip(insertedTooltipTrigger, {
    title: 'Tooltip inserted Event',
    trigger: 'click',
    placement: 'left'
  });

  insertedTooltipTrigger.addEventListener('inserted.bs.tooltip', function () {
    alert('inserted event fired.');
  });
})(window, document, jQuery);
