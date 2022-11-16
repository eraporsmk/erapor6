$(function () {
  ('use strict');

  var addNewAddressForm = $('#addNewAddressForm'),
    modalAddressCountry = $('#modalAddressCountry');

  // --- add new address ----- //

  // Select2 initialization
  if (modalAddressCountry.length) {
    modalAddressCountry.wrap('<div class="position-relative"></div>').select2({
      dropdownParent: modalAddressCountry.parent()
    });
  }

  // add new address validation
  if (addNewAddressForm.length) {
    addNewAddressForm.validate({
      rules: {
        modalAddressFirstName: {
          required: true
        },
        modalAddressLastName: {
          required: true
        }
      }
    });
  }
  // --- / add new address ----- //
});
