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
                    <h2 class="card-title" style="display: inline-block">Users</h2>
                    <a class="btn-primary btn ml-4" href="/admin/user-create" style="margin-left:20px">Add New
                        User</a>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mt-3">
                                <table id="table-users" class="display expandable-table" style="width:100%">
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
    <div class="modal fade modal-delete" id="modal-delete" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog d-flex justify-content-center">
            <div class="modal-content w-100">
                <form id="trash-user">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Do You Want To Trash this user?</h5>
                        <button type="button" class="btn-close" onclick="hideModal('modal-delete')"
                            data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        Make sure that this user will not show here again!
                    </div>
                    <input type="hidden" class="id" name="id">
                    <div class="alert alert-success ds-none" style="padding:8px 12px;font-size:14px;margin:0 10px 10px "
                        role="alert">
                        Trashed Seccuessfully
                    </div>
                    <div class="modal-footer text-right">
                        <button class="btn btn-light" onclick="hideModal('modal-delete')">Cancel</button>
                        <button class="btn btn-primary" type="submit">Confirm And Delete</button>
                        <div class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
