@extends('dashboard.layouts.app')
@section('title')
    Users
@endsection
@push('css')
    <style>

    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Create User</h2>
                    <form type="POST" id="create-user" action="/admin/user-create" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 ">
                                <img id="uploadedImageProfile" src="/images/user-defualt.png" alt="Uploaded Image"
                                    src="" accept="image/png, image/jpeg">
                                <input type='file' name="photo" class="form-control mt-2" id="readUrl"
                                    accept="image/*">
                            </div>

                            <div class="col-md-4 mt-3">
                                <label>First Name</label>
                                <input type="text" placeholder="First Name" class="form-control" name="firstname"
                                    value="" required autocomplete="firstname" autofocus>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Middle Name</label>
                                <input type="text" placeholder="Middle Name" class="form-control" name="middlename"
                                    value="" autocomplete="middlename" autofocus>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Last Name</label>
                                <input id="lastname" type="text" placeholder="Last Name" class="form-control"
                                    name="lastname" value="" required autocomplete="lastname" autofocus>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Suffix Name(optional)</label>
                                <input id="suffixname" type="text" placeholder="Suffix Name" class="form-control"
                                    name="suffixname" value="">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Type(optional)</label>
                                <input id="type" type="text" placeholder="Type" class="form-control" name="type"
                                    value="">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="">Gender And Marital Status</label>
                                <select name="gender" class="form-control" id="" name="gender">
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Ms">Ms</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Username</label>
                                <input id="username" type="text" placeholder="Username" class="form-control"
                                    name="username" value="" required>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Email</label>
                                <input id="email" type="email" placeholder="Email" class="form-control" name="email"
                                    value="" required autocomplete="email">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Password</label>
                                <input id="password" type="password" placeholder="Password" class="form-control "
                                    name="password" required autocomplete="new-password">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Confirm Password</label>
                                <input id="password-confirm" type="password" placeholder="Confirm Password"
                                    class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            <input type="hidden" name="action" value="create">
                            <div class="alert-area">
                                <div id="errors-area" class="d-none mt-2 alert alert-danger"></div>
                                <div id="success-area" class="d-none mt-2 alert alert-success">
                                    Created User Succussfully
                                </div>
                            </div>
                            <div class="spinner-border d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="d-inline">
                                <input type="submit" class="btn pr-3 pl-3 d-inline btn-primary mt-3" value="Create">
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    @push('js')
        <script></script>
    @endpush
@endsection
