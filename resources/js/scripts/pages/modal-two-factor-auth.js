$(function () {
  ('use strict');

  // --- two factor auth ----- //
  var phoneNumberMask = $('.phone-number-mask'),
    twoFactorAuthModal = new bootstrap.Modal(document.getElementById('twoFactorAuthModal')),
    authAppsModal = new bootstrap.Modal(document.getElementById('twoFactorAuthAppsModal')),
    authSmsModal = new bootstrap.Modal(document.getElementById('twoFactorAuthSmsModal'));

  // toggle modals
  document.getElementById('nextStepAuth').onclick = function () {
    var currentSelectMethod = document.querySelector('input[name=twoFactorAuthRadio]:checked').value;

    if (currentSelectMethod === 'apps-auth') {
      twoFactorAuthModal.hide();
      authAppsModal.show();
    } else {
      twoFactorAuthModal.hide();
      authSmsModal.show();
    }
  };

  // phone number mask
  if (phoneNumberMask.length) {
    phoneNumberMask.each(function () {
      new Cleave($(this), {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }

  // --- / two factor auth ----- //
});
