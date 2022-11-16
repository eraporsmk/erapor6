$(function () {
  ;('use strict')

  // variables
  var form = $('.validate-form'),
    assetPath = '../../../app-assets/',
    dtInvoiceTable = $('.invoice-list-table'),
    invoicePreview = 'app-invoice-preview.html',
    invoiceEdit = 'app-invoice-edit.html',
    accountNumberMask = $('.account-number-mask'),
    accountZipCode = $('.account-zip-code'),
    select2 = $('.select2'),
    cancelSubscription = document.querySelector('.cancel-subscription')

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path')
    invoicePreview = assetPath + 'app/invoice/preview'
    invoiceEdit = assetPath + 'app/invoice/edit'
  }

  // jQuery Validation for all forms
  // --------------------------------------------------------------------
  if (form.length) {
    form.each(function () {
      var $this = $(this)

      $this.validate({
        rules: {
          addCard: {
            required: true
          },
          companyName: {
            required: true
          },
          billingEmail: {
            required: true
          }
        }
      })
      $this.on('submit', function (e) {
        e.preventDefault()
      })
    })
  }

  // cancel subscription button
  if (cancelSubscription) {
    cancelSubscription.onclick = function () {
      Swal.fire({
        text: 'Are you sure you would like to cancel your subscription?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ms-1'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
          Swal.fire({
            icon: 'success',
            title: 'Unsubscribed!',
            text: 'Your subscription cancelled successfully.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          })
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire({
            title: 'Cancelled',
            text: 'Unsubscription Cancelled!!',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          })
        }
      })
    }
  }

  //phone
  if (accountNumberMask.length) {
    accountNumberMask.each(function () {
      new Cleave($(this), {
        phone: true,
        phoneRegionCode: 'US'
      })
    })
  }

  //zip code
  if (accountZipCode.length) {
    accountZipCode.each(function () {
      new Cleave($(this), {
        delimiter: '',
        numeral: true
      })
    })
  }

  // For all Select2
  if (select2.length) {
    select2.each(function () {
      var $this = $(this)
      $this.wrap('<div class="position-relative"></div>')
      $this.select2({
        dropdownParent: $this.parent()
      })
    })
  }

  // datatable
  if (dtInvoiceTable.length) {
    var dtInvoice = dtInvoiceTable.DataTable({
      ajax: assetPath + 'data/invoice-list.json', // JSON file to add data
      autoWidth: false,
      pageLength: 6,
      columns: [
        // columns according to JSON
        { data: 'responsive_id' },
        { data: 'invoice_id' },
        { data: 'invoice_status' },
        { data: 'issued_date' },
        { data: 'due_date' },
        { data: 'total' },
        { data: 'balance' },
        { data: 'invoice_status' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          responsivePriority: 2,
          targets: 0
        },
        {
          // Invoice ID
          targets: 1,
          width: '46px',
          render: function (data, type, full, meta) {
            var $invoiceId = full['invoice_id']
            // Creates full output for row
            var $rowOutput = '<a class="fw-bold" href="' + invoicePreview + '"> #' + $invoiceId + '</a>'
            return $rowOutput
          }
        },
        {
          // Invoice status
          targets: 2,
          width: '42px',
          render: function (data, type, full, meta) {
            var $invoiceStatus = full['invoice_status'],
              $dueDate = full['due_date'],
              $balance = full['balance'],
              roleObj = {
                Sent: { class: 'bg-light-secondary', icon: 'send' },
                Paid: { class: 'bg-light-success', icon: 'check-circle' },
                Draft: { class: 'bg-light-primary', icon: 'save' },
                Downloaded: { class: 'bg-light-info', icon: 'arrow-down-circle' },
                'Past Due': { class: 'bg-light-danger', icon: 'info' },
                'Partial Payment': { class: 'bg-light-warning', icon: 'pie-chart' }
              }
            return (
              "<span data-bs-toggle='tooltip' data-bs-html='true' title='<span>" +
              $invoiceStatus +
              '<br> <span class="fw-bold">Balance:</span> ' +
              $balance +
              '<br> <span class="fw-bold">Due Date:</span> ' +
              $dueDate +
              "</span>'>" +
              '<div class="avatar avatar-status ' +
              roleObj[$invoiceStatus].class +
              '">' +
              '<span class="avatar-content">' +
              feather.icons[roleObj[$invoiceStatus].icon].toSvg({ class: 'avatar-icon' }) +
              '</span>' +
              '</div>' +
              '</span>'
            )
          }
        },
        {
          // Total Invoice Amount
          targets: 3,
          width: '73px',
          render: function (data, type, full, meta) {
            var $total = full['total']
            return '$' + $total
          }
        },
        {
          // Issue date
          targets: 4,
          width: '130px',
          render: function (data, type, full, meta) {
            var $issuedDate = new Date(full['issued_date'])
            // Creates full output for row
            var $rowOutput = moment($issuedDate).format('DD MMM YYYY')
            $issuedDate
            return $rowOutput
          }
        },
        {
          // Due Date
          targets: 5,
          width: '130px',
          render: function (data, type, full, meta) {
            var $dueDate = new Date(full['due_date'])
            // Creates full output for row
            var $rowOutput = moment($dueDate).format('DD MMM YYYY')
            $dueDate
            return $rowOutput
          }
        },
        {
          // Client Balance/Status
          targets: 6,
          width: '98px',
          render: function (data, type, full, meta) {
            var $balance = full['balance']
            if ($balance === 0) {
              var $badge_class = 'badge-light-success'
              return '<span class="badge rounded-pill ' + $badge_class + '" text-capitalized> Paid </span>'
            } else {
              return '<span class="d-none">' + $balance + '</span>' + $balance
            }
          }
        },
        {
          targets: 7,
          visible: false
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          width: '80px',
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center col-actions">' +
              '<a class="me-1" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Mail">' +
              feather.icons['send'].toSvg({ class: 'font-medium-2 text-body' }) +
              '</a>' +
              '<a class="me-25" href="' +
              invoicePreview +
              '" data-bs-toggle="tooltip" data-bs-placement="top" title="Preview Invoice">' +
              feather.icons['eye'].toSvg({ class: 'font-medium-2 text-body' }) +
              '</a>' +
              '<div class="dropdown">' +
              '<a class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
              feather.icons['more-vertical'].toSvg({ class: 'font-medium-2 text-body' }) +
              '</a>' +
              '<div class="dropdown-menu dropdown-menu-end">' +
              '<a href="#" class="dropdown-item">' +
              feather.icons['download'].toSvg({ class: 'font-small-4 me-50' }) +
              'Download</a>' +
              '<a href="' +
              invoiceEdit +
              '" class="dropdown-item">' +
              feather.icons['edit'].toSvg({ class: 'font-small-4 me-50' }) +
              'Edit</a>' +
              '<a href="#" class="dropdown-item">' +
              feather.icons['trash'].toSvg({ class: 'font-small-4 me-50' }) +
              'Delete</a>' +
              '<a href="#" class="dropdown-item">' +
              feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) +
              'Duplicate</a>' +
              '</div>' +
              '</div>' +
              '</div>'
            )
          }
        }
      ],
      order: [[1, 'desc']],
      dom: '<"card-header pt-1 pb-25"<"head-label"><"dt-action-buttons text-end"B>>t',
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle',
          text: feather.icons['external-link'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
          buttons: [
            {
              extend: 'print',
              text: feather.icons['printer'].toSvg({ class: 'font-small-4 me-50' }) + 'Print',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'csv',
              text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + 'Csv',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'excel',
              text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'pdf',
              text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + 'Pdf',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'copy',
              text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + 'Copy',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            }
          ],
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary')
            $(node).parent().removeClass('btn-group')
            setTimeout(function () {
              $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex')
            }, 50)
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data()
              return 'Details of ' + data['client_name']
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.columnIndex !== 2 // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIdx +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : ''
            }).join('')
            return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false
          }
        }
      },
      initComplete: function () {
        $(document).find('[data-bs-toggle="tooltip"]').tooltip()
      },
      drawCallback: function () {
        $(document).find('[data-bs-toggle="tooltip"]').tooltip()
      }
    })
    $('div.head-label').html('<h4 class="card-title">Billing History</h4>')
  }
})
