$(function () {
  ('use strict');

  var assetsPath = '../../../app-assets/',
    creditCard = $('.add-credit-card-mask'),
    addNewCardValidation = $('#addNewCardValidation'),
    expiryDateMask = $('.add-expiry-date-mask'),
    cvvMask = $('.add-cvv-code-mask');

  if ($('body').attr('data-framework') === 'laravel') {
    assetsPath = $('body').attr('data-asset-path');
  }

  // --- add new credit card ----- //

  // Credit Card
  if (creditCard.length) {
    creditCard.each(function () {
      new Cleave($(this), {
        creditCard: true,
        onCreditCardTypeChanged: function (type) {
          if (type != '' && type != 'unknown') {
            document.querySelector('.add-card-type').innerHTML =
              '<img src="' + assetsPath + 'images/icons/payments/' + type + '-cc.png" height="24"/>';
          } else {
            document.querySelector('.add-card-type').innerHTML = '';
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

  // card number validation
  if (addNewCardValidation.length) {
    addNewCardValidation.validate({
      rules: {
        modalAddCard: {
          required: true
        }
      }
    });
  }

  // --- / add new credit card ----- //
});
