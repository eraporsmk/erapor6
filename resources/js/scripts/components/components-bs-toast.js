(function (window, document, jQuery) {
  'use strict';

  // Basic Toast
  var basicToast = document.querySelector('.basic-toast');
  var basicToastBtn = document.querySelector('.toast-basic-toggler');
  var showBasicToast = new bootstrap.Toast(basicToast);

  basicToastBtn.addEventListener('click', function () {
    showBasicToast.show();
  });

  // Required to hide translucent toast
  var toastElList = [].slice.call(document.querySelectorAll('.toast'));

  var toastList = toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl);
  });

  // Auto Hide Toast
  var autoHideToast = document.querySelector('.toast-autohide');
  var autoHideToastBtn = document.querySelector('.toast-autohide-toggler');

  var showAutoHideToast = new bootstrap.Toast(autoHideToast, {
    autohide: false
  });

  autoHideToastBtn.addEventListener('click', function () {
    showAutoHideToast.show();
  });

  // Stacked Toast
  var stackedToast = document.querySelector('.toast-stacked');
  var stackedToastBtn = document.querySelector('.toast-stacked-toggler');
  var showStackedToast = new bootstrap.Toast(stackedToast);

  stackedToastBtn.addEventListener('click', function () {
    showStackedToast.show();
  });
})(window, document, jQuery);
