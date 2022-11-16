$(function () {
  const select2 = $('.select2'),
    editUserForm = $('#editUserForm'),
    modalEditUserPhone = $('.phone-number-mask');

  // Select2 Country
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent()
      });
    });
  }

  // Phone Number Input Mask
  if (modalEditUserPhone.length) {
    modalEditUserPhone.each(function () {
      new Cleave($(this), {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }

  // Edit user form validation
  if (editUserForm.length) {
    editUserForm.validate({
      rules: {
        modalEditUserFirstName: {
          required: true
        },
        modalEditUserLastName: {
          required: true
        },
        modalEditUserName: {
          required: true,
          minlength: 6,
          maxlength: 30
        }
      },
      messages: {
        modalEditUserName: {
          required: 'Please enter your username',
          minlength: 'The name must be more than 6 and less than 30 characters long',
          maxlength: 'The name must be more than 6 and less than 30 characters long'
        }
      }
    });
  }
});
