/*=========================================================================================
    File Name: app-user-view-account.js
    Description: User View Account page
    --------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict'

  // Variable declaration for table
  var dt_project_table = $('.datatable-project'),
    dtInvoiceTable = $('.invoice-table'),
    invoicePreview = 'app-invoice-preview.html',
    assetPath = '../../../app-assets/'

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path')
    invoicePreview = assetPath + 'app/invoice/preview'
  }

  // Project datatable
  // --------------------------------------------------------------------

  if (dt_project_table.length) {
    var dt_project = dt_project_table.DataTable({
      ajax: assetPath + 'data/projects-list.json', // JSON file to add data
      ordering: false,
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'project_name' },
        { data: 'total_task' },
        { data: 'progress' },
        { data: 'hours' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return ''
          }
        },
        {
          // User full name and email
          targets: 1,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['project_name'],
              $framework = full['framework'],
              $image = full['project_image']
            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' +
                assetPath +
                'images/icons/brands/' +
                $image +
                '" alt="Project Image" width="32" class="rounded-circle">'
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6) + 1
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary']
              var $state = states[stateNum],
                $name = full['full_name'],
                $initials = $name.match(/\b\w/g) || []
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase()
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>'
            }
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-left align-items-center">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-1">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<span class="text-truncate fw-bolder">' +
              $name +
              '</span>' +
              '<small class="text-muted">' +
              $framework +
              '</small>' +
              '</div>' +
              '</div>'
            return $row_output
          }
        },
        {
          // Label
          targets: -2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $progress = full['progress'] + '%',
              $color
            switch (true) {
              case full['progress'] < 25:
                $color = 'bg-danger'
                break
              case full['progress'] < 50:
                $color = 'bg-warning'
                break
              case full['progress'] < 75:
                $color = 'bg-info'
                break
              case full['progress'] <= 100:
                $color = 'bg-success'
                break
            }
            return (
              '<div class="d-flex flex-column"><small class="mb-1">' +
              $progress +
              '</small>' +
              '<div class="progress w-100 me-3" style="height: 6px;">' +
              '<div class="progress-bar ' +
              $color +
              '" style="width: ' +
              $progress +
              '" aria-valuenow="' +
              $progress +
              '" aria-valuemin="0" aria-valuemax="100"></div>' +
              '</div>' +
              '</div>'
            )
          }
        }
      ],
      order: [[1, 'desc']],
      dom: 't',
      displayLength: 7,
      language: {
        sLengthMenu: 'Show _MENU_',
        // search: '',
        searchPlaceholder: 'Search Project'
      },
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data()
              return 'Details of ' + data['framework']
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
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

            return data ? $('<table class="table"/><tbody />').append(data) : false
          }
        }
      }
    })
  }

  // Invoice datatable
  // --------------------------------------------------------------------
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
        { data: 'total' },
        { data: 'issued_date' },
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
            var $rowOutput = '<a class="fw-bolder" href="' + invoicePreview + '"> #' + $invoiceId + '</a>'
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
              '<a class="me-1" href="' +
              invoicePreview +
              '" data-bs-toggle="tooltip" data-bs-placement="top" title="Preview Invoice">' +
              feather.icons['eye'].toSvg({ class: 'font-medium-2 text-body' }) +
              '</a>' +
              '<a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" title="Download">' +
              feather.icons['download'].toSvg({ class: 'font-medium-2 text-body' }) +
              '</a>'
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
              exportOptions: { columns: [1, 3, 4] }
            },
            {
              extend: 'csv',
              text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + 'Csv',
              className: 'dropdown-item',
              exportOptions: { columns: [1, 3, 4] }
            },
            {
              extend: 'excel',
              text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
              className: 'dropdown-item',
              exportOptions: { columns: [1, 3, 4] }
            },
            {
              extend: 'pdf',
              text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + 'Pdf',
              className: 'dropdown-item',
              exportOptions: { columns: [1, 3, 4] }
            },
            {
              extend: 'copy',
              text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + 'Copy',
              className: 'dropdown-item',
              exportOptions: { columns: [1, 3, 4] }
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
    $('div.head-label').html('<h4 class="card-title">Invoices</h4>')
  }

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm')
    $('.dataTables_length .form-select').removeClass('form-select-sm')
  }, 300)

  // To initialize tooltip with body container
  $('body').tooltip({
    selector: '[data-bs-toggle="tooltip"]',
    container: 'body'
  })
})
