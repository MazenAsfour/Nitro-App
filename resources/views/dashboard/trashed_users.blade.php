@extends('dashboard.layouts.app')
@section('title')
    Users
@endsection
@push('custom-style')
    <style>

    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title" style="display: inline-block">Trashed Users</h2>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mt-3">
                                <table id="table-trashed-users" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Avatar</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Prefix Name</th>
                                            <th>Created at</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="modal fade modal-delete-permently" id="modal-delete-permently" tabindex="-1" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog d-flex justify-content-center">
            <div class="modal-content w-100">
                <form id="delete-user-permently">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Do You Want To Delete This User Permently?</h5>
                        <button type="button" class="btn-close" onclick="hideModal('modal-delete-permently')"
                            data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        Make sure that this user will not back to site again!
                    </div>
                    <input type="hidden" class="id" name="id">
                    <div class="alert alert-success ds-none" style="padding:8px 12px;font-size:14px;margin:0 10px 10px "
                        role="alert">
                        Deleted Seccuessfully
                    </div>
                    <div class="modal-footer text-right">
                        <button class="btn btn-light" onclick="hideModal('modal-delete-permently')">Cancel</button>
                        <button class="btn btn-primary" type="submit">Confirm And Delete</button>
                        <div class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade restore-user" id="modal-restore" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog d-flex justify-content-center">
            <div class="modal-content w-100">
                <form id="restore-user">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Do You Want To Restore This User?</h5>
                        <button type="button" class="btn-close" onclick="hideModal('restore-user')"
                            data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        Make sure that this user will return to users table!
                    </div>
                    <input type="hidden" class="id" name="id">
                    <div class="alert alert-success ds-none" style="padding:8px 12px;font-size:14px;margin:0 10px 10px "
                        role="alert">
                        Restored Seccuessfully
                    </div>
                    <div class="modal-footer text-right">
                        <button class="btn btn-light" onclick="hideModal('restore-user')">Cancel</button>
                        <button class="btn btn-primary" type="submit">Confirm And Restore</button>
                        <div class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function lanuchModalDeletePermently(id) {
            $("#modal-delete-permently").modal("show");
            $("#modal-delete-permently .id").val(id);
        }

        function lanuchModalRestore(id) {
            $("#modal-restore").modal("show");
            $("#modal-restore .id").val(id);
        }

        function prepareTableTrashedUsers() {
            $("#table-trashed-users").DataTable({
                serverSide: true,
                ajax: "/admin/users-trashed",
                columns: [{
                        data: "id",
                        name: "id",
                        render: function(data, type, row) {
                            return "#" + row.id;
                        },
                    },
                    {
                        data: "image_path",
                        name: "image_path",
                        render: function(data, type, row) {
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
                        render: function(data, type, row) {
                            return '<p id="username_' + row.id + '">' + row.username + "</p>";
                        },
                    },
                    {
                        data: "email",
                        name: "email",
                        render: function(data, type, row) {
                            return '<p id="email_' + row.id + '">' + row.email + "</p>";
                        },
                    },
                    {
                        data: "prefixname",
                        name: "prefixname",
                        render: function(data, type, row) {
                            return '<p id="prefixname_' + row.id + '">' + row.prefixname + "</p>";
                        },
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                        render: function(data, type, row) {
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
                        render: function(data, type, row) {
                            console.log(row);
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
        if ($("#restore-user").length) {
            $("#restore-user").submit(function(e) {
                e.preventDefault();
                $("#restore-user .spinner-border").removeClass("d-none");

                var formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: "/admin/restore-user/",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $("#restore-user .spinner-border").addClass("d-none");

                        if (res.success) {
                            $("#restore-user .alert-success").show();
                            destory("#table-trashed-users");
                            prepareTableTrashedUsers();
                        } else {
                            alert(res.message);
                        }
                        setTimeout(() => {
                            $("#restore-user").modal("hide");
                            $("#restore-user .alert-success").hide();
                        }, 3000);
                    },
                    error: function(data) {},
                });
            });
        }
    </script>
@endsection
