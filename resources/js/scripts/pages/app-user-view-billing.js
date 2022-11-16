$(function () {
  ;('use strict')

  // On edit address click, update text of add address modal
  var addressEdit = $('.edit-address'),
    addressTitle = $('.address-title'),
    addressSubTitle = $('.address-subtitle')

  addressEdit.on('click', function () {
    addressTitle.text('Edit Address') // reset text
    addressSubTitle.text('Edit your current address')
  })
})
