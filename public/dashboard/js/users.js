$(document).ready(function () {
  /*
   *
   * Perpare datatable for table users when exist
   * */
  if ($("#table-users").length) {
    prepareTableUsers();
  }

  /*
   *
   * Perpare datatable for table trashed users when exist
   * */
  if ($("#table-trashed-users").length) {
    prepareTableTrashedUsers();
  }

  /*
   *
   * Handle submit form by ajax when create user
   * */
  if ($("#create-user").length) {
    $("#create-user").submit(function (e) {
      e.preventDefault();
      var selector = "#create-user";
      handleSpinner(selector, true);

      var formData = new FormData(this);

      $.ajax({
        type: "POST",
        url: $(selector).attr("action"),
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
          handleSpinner(selector, false);
          if (res.success) {
            handleAlerts(selector, true, res.message);
            setTimeout(() => {
              window.location.href = "/admin";
            }, 3000);
          } else {
            handleAlerts(selector, false, res.message);
          }
        },
        error: function (xhr, textStatus, errorThrown) {
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            collectValidationErrors(xhr.responseJSON.errors, selector);
            handleSpinner(selector, false);
          }
        },
      });
    });
  }

  /*
   *
   * Handle submit form by ajax when update user
   * */
  if ($("#update-user").length) {
    $("#update-user").submit(function (e) {
      e.preventDefault();
      var selector = "#update-user";
      handleSpinner(selector, true);
      var formData = new FormData(this);
      $.ajax({
        type: "POST",
        url: $(selector).attr("action"),
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
          handleSpinner(selector, false);
          if (res.success) {
            handleAlerts(selector, true, res.message);
            setTimeout(() => {
              window.location.href = "/admin";
            }, 3000);
          } else {
            handleAlerts(selector, false, res.message);
          }
        },
        error: function (xhr, textStatus, errorThrown) {
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            var selector = "#update-user";
            collectValidationErrors(xhr.responseJSON.errors, selector);
            handleSpinner(selector, false);
          }
        },
      });
    });
  }

  /*
   *
   * Checbox for handle password show and hide fields on update user
   * */
  $("#update-password").change(function () {
    if ($("#update-password:checked").length) {
      $(".password-area").each(function () {
        $(this).show();
      });
    } else {
      $(".password-area").each(function () {
        $(this).hide();
      });
    }
  });

  /*
   *
   * Handle submit form by ajax when restore user
   * */
  if ($("#restore-user").length) {
    $("#restore-user").submit(function (e) {
      e.preventDefault();
      var selector = "#restore-user";
      handleSpinner(selector, true);

      var formData = new FormData(this);
      var id = $("#restore-user .data-id").val();
      $.ajax({
        type: "POST",
        url: "/admin/users/" + id + "/restore",
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
          handleSpinner(selector, false);
          if (res.success) {
            handleAlerts(selector, true, res.message);
            destory("#table-trashed-users");
            prepareTableTrashedUsers();
            setTimeout(() => {
              $("#modal-restore").modal("hide");
              $(selector + " .alert-success").addClass("d-none");
            }, 3000);
          } else {
            alert(res.message);
            handleAlerts(selector, true, res.message);
          }
        },
        error: function (data) {},
      });
    });
  }

  /*
   *
   * Handle submit form by ajax when trash user
   * */
  if ($("#trash-user").length) {
    $("#trash-user").submit(function (e) {
      e.preventDefault();
      var selector = "#trash-user";
      handleSpinner(selector, true);
      var formData = new FormData(this);

      $.ajax({
        type: "POST",
        url: "/admin/user-trash/",
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
          handleSpinner(selector, false);
          if (res.success) {
            handleAlerts(selector, true, res.message);
            destory("#table-users");
            prepareTableUsers();
            setTimeout(() => {
              $("#modal-delete").modal("hide");
              $(selector + " .alert-success").hide();
            }, 3000);
          } else {
            handleAlerts(selector, false, res.message);
          }
        },
        error: function (data) {},
      });
    });
  }

  /*
   *
   * Handle submit form by ajax when delete user permently on trashed users page
   * */
  if ($("#delete-user-permently").length) {
    $("#delete-user-permently").submit(function (e) {
      e.preventDefault();
      $("#delete-user-permently .spinner-border").removeClass("d-none");

      var formData = new FormData(this);
      var id = $("#delete-user-permently .data-id").val();

      $.ajax({
        type: "POST",
        url: "/admin/users/" + id + "/delete",
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
          $("#delete-user-permently .spinner-border").addClass("d-none");

          if (res.success) {
            $("#delete-user-permently .alert-success").show();
            destory("#table-trashed-users");
            prepareTableTrashedUsers();
          } else {
            alert(res.message);
          }
          setTimeout(() => {
            $("#delete-user-permently").modal("hide");
            $("#delete-user-permently .alert-success").hide();
          }, 3000);
        },
        error: function (data) {},
      });
    });
  }

  /*
   *
   * Read image file when change to preview when readUrl is found
   * */
  var selectorPreview = document.getElementById("readUrl");
  if (selectorPreview) {
    selectorPreview.addEventListener("change", function () {
      if (this.files[0]) {
        var picture = new FileReader();
        picture.readAsDataURL(this.files[0]);
        picture.addEventListener("load", function (event) {
          document
            .getElementById("uploadedImageProfile")
            .setAttribute("src", event.target.result);
        });
      }
    });
  }
});

/*
 *
 * Destroy datatable
 * */
function destory(selector) {
  jQuery(selector).DataTable().destroy();
}

/*
 *
 * Hide modal
 * */
function hideModal(id) {
  $("#" + id).modal("hide");
}

/*
 *
 * show Modal destroy user
 * */
function lanuchModalDeleteUser(id) {
  $("#modal-delete").modal("show");
  $("#modal-delete .data-id").val(id);
}

/*
 *
 * Prepare table users function
 * */
function prepareTableUsers() {
  $("#table-users").DataTable({
    serverSide: true,
    ajax: "/admin/user-all",
    columns: [
      {
        data: "id",
        name: "id",
        render: function (data, type, row) {
          return "#" + row.id;
        },
        orderable: false,
      },
      {
        data: "image_path",
        name: "image_path",
        render: function (data, type, row) {
          return (
            '<img id="' +
            row.id +
            'img" src="' +
            row.photo +
            '"class="img-avatar" alt="#" />'
          );
        },
      },
      {
        data: "username",
        name: "username",
        render: function (data, type, row) {
          return '<p id="username_' + row.id + '">' + row.username + "</p>";
        },
      },
      {
        data: "email",
        name: "email",
        render: function (data, type, row) {
          return '<p id="email_' + row.id + '">' + row.email + "</p>";
        },
      },
      {
        data: "prefixname",
        name: "prefixname",
        render: function (data, type, row) {
          return '<p id="prefixname_' + row.id + '">' + row.prefixname + "</p>";
        },
      },
      {
        data: "created_at",
        name: "created_at",
        render: function (data, type, row) {
          return (
            '<p id="created_at_' +
            row.id +
            '">' +
            formatReadableDate(row.created_at) +
            "</p>"
          );
        },
      },

      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return (
            ' <td> <a href="/admin/user-update/' +
            row.id +
            '"><i class="fa fa-edit  pointer"  aria-hidden="true"></i></a> <i class=" pl-3 fa fa-times pointer" onclick="lanuchModalDeleteUser(' +
            row.id +
            ')" aria-hidden="true"></i></td>'
          );
        },
      },
    ],
  });
}

/*
 *
 * Function to format date
 * */
function formatReadableDate(dateString) {
  var date = new Date(dateString);

  var optionsDate = {
    year: "numeric",
    month: "short",
    day: "numeric",
  };
  var optionsTime = {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    // timeZoneName: 'short'
  };

  var formattedDate = date.toLocaleDateString("en-US", optionsDate);
  var formattedTime = date.toLocaleTimeString("en-US", optionsTime);

  return formattedDate + " " + formattedTime;
}

/*
 *
 * Show modal delete peremntly
 * */
function lanuchModalDeletePermently(id) {
  $("#modal-delete-permently").modal("show");
  $("#modal-delete-permently .data-id").val(id);
}

/*
 *
 * Show modal restore users
 * */
function lanuchModalRestore(id) {
  $("#modal-restore").modal("show");
  $("#modal-restore .data-id").val(id);
}

/*
 *
 * Perpare trashed users table
 * */
function prepareTableTrashedUsers() {
  $("#table-trashed-users").DataTable({
    serverSide: true,
    ajax: "/admin/users/trashed",
    columns: [
      {
        data: "id",
        name: "id",
        render: function (data, type, row) {
          return "#" + row.id;
        },
        orderable: false,
      },
      {
        data: "image_path",
        name: "image_path",
        render: function (data, type, row) {
          return (
            '<img id="' +
            row.id +
            'img" src="' +
            row.photo +
            '"class="img-avatar" alt="#" />'
          );
        },
      },
      {
        data: "username",
        name: "username",
        render: function (data, type, row) {
          return '<p id="username_' + row.id + '">' + row.username + "</p>";
        },
      },
      {
        data: "email",
        name: "email",
        render: function (data, type, row) {
          return '<p id="email_' + row.id + '">' + row.email + "</p>";
        },
      },
      {
        data: "prefixname",
        name: "prefixname",
        render: function (data, type, row) {
          return '<p id="prefixname_' + row.id + '">' + row.prefixname + "</p>";
        },
      },
      {
        data: "created_at",
        name: "created_at",
        render: function (data, type, row) {
          return (
            '<p id="created_at_' +
            row.id +
            '">' +
            formatReadableDate(row.created_at) +
            "</p>"
          );
        },
      },

      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return (
            ' <td> <i class="fa fa-undo  pointer" onclick="lanuchModalRestore(' +
            row.id +
            ')" aria-hidden="true"></i><i class=" pl-3 fa fa-times pointer" onclick="lanuchModalDeletePermently(' +
            row.id +
            ')" aria-hidden="true"></i></td>'
          );
        },
      },
    ],
  });
}

/*
 *
 * Collect Validation error from xhr.errors as list
 * */
function collectValidationErrors(errors, selector) {
  var errorMessage = "<ul>";

  $.each(errors, function (field, messages) {
    // Loop through each error message for the field
    $.each(messages, function (index, message) {
      errorMessage += "<li>" + message + "</li>";
    });
  });

  errorMessage += "</ul>";
  handleAlerts(selector, false, errorMessage);
}

/*
 *
 * Show / Hide spinner (loader)
 * */
function handleSpinner(selector, show = false) {
  var SelectorSpinner = $(selector + " .spinner-border");
  if (show) {
    SelectorSpinner.removeClass("d-none");
  } else {
    SelectorSpinner.addClass("d-none");
  }
}

/*
 *
 * Show /Hide alerts and pass messages to it
 * */
function handleAlerts(selector, success = false, message = null) {
  var SelectorSuccess = $(selector + " .alert-success");
  var SelectorFailed = $(selector + " .alert-danger");

  if (success) {
    if (SelectorSuccess.length) {
      SelectorSuccess.removeClass("d-none");
      SelectorFailed.addClass("d-none");
      if (message) SelectorSuccess.html(message);
    }
  } else {
    if (SelectorFailed.length) {
      SelectorFailed.removeClass("d-none");
      SelectorSuccess.addClass("d-none");
      if (message) SelectorFailed.html(message);
    }
  }
}
