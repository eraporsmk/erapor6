$((function(){var e=$("#addPermissionForm");e.length&&e.validate({rules:{modalPermissionName:{required:!0}}}),$(".modal").on("hidden.bs.modal",(function(){$(this).find("form")[0].reset()}))}));
