/*=========================================================================================
	File Name: page-api-key.js
	Description: API Key.
	----------------------------------------------------------------------------------------
	Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
	Author: PIXINVENT
	Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';

  var select2 = $('.select2'),
    createApiForm = $('#createApiForm');

  // select 2
  select2.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
      // the following code is used to disable x-scrollbar when click in select input and
      // take 100% width in responsive also
      dropdownAutoWidth: true,
      width: '100%',
      dropdownParent: $this.parent()
    });
  });

  // validation
  if (createApiForm.length) {
    createApiForm.validate({
      rules: {
        apiKeyName: {
          required: true
        }
      }
    });
  }
});
