$(function () {
  ('use strict');
  var modernVerticalWizard = document.querySelector('.create-app-wizard'),
    createAppModal = document.getElementById('createAppModal'),
    assetsPath = '../../../app-assets/',
    creditCard = $('.create-app-card-mask'),
    expiryDateMask = $('.create-app-expiry-date-mask'),
    cvvMask = $('.create-app-cvv-code-mask');

  if ($('body').attr('data-framework') === 'laravel') {
    assetsPath = $('body').attr('data-asset-path');
  }

  // --- create app  ----- //
  if (typeof modernVerticalWizard !== undefined && modernVerticalWizard !== null) {
    var modernVerticalStepper = new Stepper(modernVerticalWizard, {
      linear: false
    });

    $(modernVerticalWizard)
      .find('.btn-next')
      .on('click', function () {
        modernVerticalStepper.next();
      });
    $(modernVerticalWizard)
      .find('.btn-prev')
      .on('click', function () {
        modernVerticalStepper.previous();
      });

    $(modernVerticalWizard)
      .find('.btn-submit')
      .on('click', function () {
        alert('Submitted..!!');
      });

    // reset wizard on modal hide
    createAppModal.addEventListener('hide.bs.modal', function (event) {
      modernVerticalStepper.to(1);
    });
  }

  // Credit Card
  if (creditCard.length) {
    creditCard.each(function () {
      new Cleave($(this), {
        creditCard: true,
        onCreditCardTypeChanged: function (type) {
          if (type != '' && type != 'unknown') {
            document.querySelector('.credit-app-card-type').innerHTML =
              '<img src="' + assetsPath + 'images/icons/payments/' + type + '-cc.png" height="24"/>';
          } else {
            document.querySelector('.credit-app-card-type').innerHTML = '';
          }
        }
      });
    });
  }

  // Expiry Date Mask
  if (expiryDateMask.length) {
    expiryDateMask.each(function () {
      new Cleave($(this), {
        date: true,
        delimiter: '/',
        datePattern: ['m', 'y']
      });
    });
  }

  // CVV
  if (cvvMask.length) {
    cvvMask.each(function () {
      new Cleave($(this), {
        numeral: true,
        numeralPositiveOnly: true
      });
    });
  }

  // --- / create app ----- //
});
