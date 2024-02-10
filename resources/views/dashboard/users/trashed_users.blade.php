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
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Do You Want To Delete This User Permently?</h5>
                        <button type="button" class="btn-close" onclick="hideModal('modal-delete-permently')"
                            data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        Make sure that this user will not back to site again!
                    </div>
                    <input type="hidden" class="data-id" name="id">
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
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Do You Want To Restore This User?</h5>
                        <button type="button" class="btn-close" onclick="hideModal('restore-user')"
                            data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        Make sure that this user will return to users table!
                    </div>
                    <input type="hidden" class="data-id" name="id">
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
@endsection
