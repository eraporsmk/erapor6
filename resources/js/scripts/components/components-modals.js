/*=========================================================================================
    File Name: components-modal.js
    Description: Modals are streamlined, but flexible, dialog prompts with the minimum
				required functionality and smart defaults.
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: Pixinvent
    Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/
(function (window, document, $) {
  'use strict';

  /******************/
  // Modal events //
  /******************/

  // onShow event
  var showModalTrigger = document.getElementById('onshow');

  var showModal = new bootstrap.Modal(showModalTrigger, {
    title: 'Modal Show Event',
    trigger: 'click',
    placement: 'right'
  });

  showModalTrigger.addEventListener('show.bs.modal', function () {
    alert('Show event fired.');
  });

  // onShown event
  var shownModalTrigger = document.getElementById('onshown');

  var shownModal = new bootstrap.Modal(shownModalTrigger, {
    title: 'Modal Shown Event',
    trigger: 'click',
    placement: 'right'
  });

  shownModalTrigger.addEventListener('shown.bs.modal', function () {
    alert('Shown event fired.');
  });

  // onHide event
  var hideModalTrigger = document.getElementById('onhide');

  var hideModal = new bootstrap.Modal(hideModalTrigger, {
    title: 'Modal Hide Event',
    trigger: 'click',
    placement: 'right'
  });

  hideModalTrigger.addEventListener('hide.bs.modal', function () {
    alert('Hide event fired.');
  });

  // onHidden event
  var hiddenModalTrigger = document.getElementById('onhidden');

  var hiddenModal = new bootstrap.Modal(hiddenModalTrigger, {
    title: 'Modal Hidden Event',
    trigger: 'click',
    placement: 'right'
  });

  hiddenModalTrigger.addEventListener('hidden.bs.modal', function () {
    alert('Hidden event fired.');
  });
})(window, document, jQuery);
