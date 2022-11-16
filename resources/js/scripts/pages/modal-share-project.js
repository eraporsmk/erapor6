$(function () {
  ('use strict');

  var assetsPath = '../../../app-assets/',
    addMemberSelect = $('#addMemberSelect');

  if ($('body').attr('data-framework') === 'laravel') {
    assetsPath = $('body').attr('data-asset-path');
  }

  // --- Share project ----- //

  // add new member select
  if (addMemberSelect.length) {
    function renderGuestAvatar(option) {
      if (!option.id) {
        return option.text;
      }

      var $avatar =
        "<div class='d-flex flex-wrap align-items-center'>" +
        "<div class='avatar avatar-sm my-0 me-50'>" +
        "<span class='avatar-content'>" +
        "<img src='" +
        assetsPath +
        'images/avatars/' +
        $(option.element).data('avatar') +
        "' alt='avatar' />" +
        '</span>' +
        '</div>' +
        option.text +
        '</div>';

      return $avatar;
    }

    addMemberSelect.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Add project members by name or email...',
      dropdownParent: addMemberSelect.parent(),
      templateResult: renderGuestAvatar,
      templateSelection: renderGuestAvatar,
      escapeMarkup: function (es) {
        return es;
      }
    });
  }

  // --- / Share project ----- //
});
