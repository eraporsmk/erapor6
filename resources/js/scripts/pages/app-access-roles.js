$(function () {
  ;('use strict')
  var dtUserTable = $('.user-list-table'),
    assetPath = '../../../app-assets/',
    userView = 'app-user-view-account.html',
    statusObj = {
      1: { title: 'Pending', class: 'badge-light-warning' },
      2: { title: 'Active', class: 'badge-light-success' },
      3: { title: 'Inactive', class: 'badge-light-secondary' }
    }

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path')
    userView = assetPath + 'app/user/view/account'
  }

  // Users List datatable
  if (dtUserTable.length) {
    dtUserTable.DataTable({
      ajax: assetPath + 'data/user-list.json', // JSON file to add data
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'full_name' },
        { data: 'role' },
        { data: 'current_plan' },
        { data: 'billing' },
        { data: 'status' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return ''
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            return (
              '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="" id="checkbox' +
              data +
              '" /><label class="form-check-label" for="checkbox' +
              data +
              '"></label></div>'
            )
          },
          checkboxes: {
            selectAllRender:
              '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>'
          }
        },
        {
          // User full name and username
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['full_name'],
              $email = full['email'],
              $image = full['avatar']
            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' + assetPath + 'images/avatars/' + $image + '" alt="Avatar" height="32" width="32">'
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6) + 1
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary']
              var $state = states[stateNum],
                $name = full['full_name'],
                $initials = $name.match(/\b\w/g) || []
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase()
              $output = '<span class="avatar-content">' + $initials + '</span>'
            }
            var colorClass = $image === '' ? ' bg-light-' + $state + ' ' : ''
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-left align-items-center">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar ' +
              colorClass +
              ' me-1">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              userView +
              '" class="user_name text-body text-truncate"><span class="fw-bolder">' +
              $name +
              '</span></a>' +
              '<small class="emp_post text-muted">' +
              $email +
              '</small>' +
              '</div>' +
              '</div>'
            return $row_output
          }
        },
        {
          // User Role
          targets: 3,
          render: function (data, type, full, meta) {
            var $role = full['role']
            var roleBadgeObj = {
              Subscriber: feather.icons['user'].toSvg({ class: 'font-medium-3 text-primary me-50' }),
              Author: feather.icons['settings'].toSvg({ class: 'font-medium-3 text-warning me-50' }),
              Maintainer: feather.icons['database'].toSvg({ class: 'font-medium-3 text-success me-50' }),
              Editor: feather.icons['edit-2'].toSvg({ class: 'font-medium-3 text-info me-50' }),
              Admin: feather.icons['slack'].toSvg({ class: 'font-medium-3 text-danger me-50' })
            }
            return "<span class='text-truncate align-middle'>" + roleBadgeObj[$role] + $role + '</span>'
          }
        },
        {
          targets: 5,
          render: function (data, type, full, meta) {
            var $billing = full['billing']

            return '<span class="text-nowrap">' + $billing + '</span>'
          }
        },
        {
          // User Status
          targets: 6,
          render: function (data, type, full, meta) {
            var $status = full['status']

            return (
              '<span class="badge rounded-pill ' +
              statusObj[$status].class +
              '" text-capitalized>' +
              statusObj[$status].title +
              '</span>'
            )
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<a href="' +
              userView +
              '" class="btn btn-sm btn-icon">' +
              feather.icons['eye'].toSvg({ class: 'font-medium-3 text-body' }) +
              '</a>'
            )
          }
        }
      ],
      order: [[2, 'desc']],
      dom:
        '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-50 mb-1"' +
        '<"col-sm-12 col-md-4 col-lg-6" l>' +
        '<"col-sm-12 col-md-8 col-lg-6 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-md-end justify-content-center flex-sm-nowrap flex-wrap"<"me-1"f><"user_role mt-50 width-200">>>' +
        '>t' +
        '<"d-flex justify-content-between mx-2 row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: 'Search',
        searchPlaceholder: 'Search..'
      },
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data()
              return 'Details of ' + data['full_name']
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
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
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      },
      initComplete: function () {
        // Adding role filter once table initialized
        this.api()
          .columns(4)
          .every(function () {
            var column = this
            var select = $(
              '<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Role </option></select>'
            )
              .appendTo('.user_role')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val())
                column.search(val ? '^' + val + '$' : '', true, false).draw()
              })

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>')
              })
          })
      }
    })
  }

  // On edit role click, update text
  var roleEdit = $('.role-edit-modal'),
    roleAdd = $('.add-new-role'),
    roleTitle = $('.role-title')

  roleAdd.on('click', function () {
    roleTitle.text('Add New Role') // reset text
  })
  roleEdit.on('click', function () {
    roleTitle.text('Edit Role')
  })
})
