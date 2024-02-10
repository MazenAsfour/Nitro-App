@extends('dashboard.layouts.app')
@section('title')
    Update User #{{ $user->id }}
@endsection
@push('css')
    <style>
        .password-area {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Update User #{{ $user->id }}</h2>
                    <form type="POST" id="update-user" action="/admin/user-update" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 ">
                                <img id="uploadedImageProfile" src="{{ $user->photo }}" alt="Uploaded Image"
                                    accept="image/png, image/jpeg">
                                <input type='file' name="profile" class="form-control mt-2" id="readUrl">
                            </div>

                            <div class="col-md-4 mt-3">
                                <label>First Name</label>
                                <input type="text" value="{{ $user->firstname }}" placeholder="First Name"
                                    class="form-control" name="firstname" value="" required autocomplete="firstname"
                                    autofocus>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Middle Name</label>
                                <input type="text" placeholder="Middle Name" class="form-control" name="middlename"
                                    value="{{ $user->middlename }}" autocomplete="middlename" autofocus>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Last Name</label>
                                <input id="lastname" type="text" placeholder="Last Name" class="form-control"
                                    name="lastname" value="{{ $user->lastname }}" required autocomplete="lastname"
                                    autofocus>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Suffix Name(optional)</label>
                                <input id="suffixname" type="text" placeholder="Suffix Name" class="form-control"
                                    name="suffixname" value="{{ $user->suffixname }}">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Type(optional)</label>
                                <input id="type" type="text" placeholder="Type" class="form-control" name="type"
                                    value="{{ $user->type }}">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="">Gender And Marital Status</label>
                                <select name="gender" class="form-control" id="" name="gender">
                                    <option value="{{ $user->prefixname }}">{{ ucfirst($user->prefixname) }}</option>

                                    <option class="{{ $user->prefixname == 'mr' ? 'd-none' : '' }}" value="mr">Mr
                                    </option>
                                    <option class="{{ $user->prefixname == 'mrs' ? 'd-none' : '' }}" value="mrs">Mrs
                                    </option>
                                    <option class="{{ $user->gender == 'ms' ? 'd-none' : '' }}" value="ms">Ms</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Username</label>
                                <input id="username" type="text" placeholder="Username" class="form-control"
                                    name="username" value="{{ $user->username }}" required>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Email</label>
                                <input id="email" type="email" placeholder="Email" class="form-control" name="email"
                                    value="{{ $user->email }}" required autocomplete="email">
                            </div>
                            <div class="col-md-6 mt-3 password-area">
                                <label>Password</label>
                                <input id="password" type="password" placeholder="Password" class="form-control "
                                    name="password" autocomplete="new-password">
                            </div>
                            <div class="col-md-6 mt-3 password-area">
                                <label>Confirm Password</label>
                                <input id="password-confirm" type="password" placeholder="Confirm Password"
                                    class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="form-check form-check pl-5 pt-3">
                                <input class="form-check-input" type="checkbox" name="update_password"
                                    id="update-password">
                                <label class="form-check-label  ml-1" for="">
                                    Update Password
                                </label>
                            </div>
                            <div class="alert-area">
                                <div id="errors-area" class="d-none mt-2 alert alert-danger"></div>
                                <div id="success-area" class="d-none mt-2 alert alert-success">
                                    Updated User Succussfully
                                </div>
                            </div>
                            <div class="spinner-border d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="d-inline">
                                <input type="submit" class="btn pr-3 pl-3 d-inline btn-primary mt-3" value="Update">
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    @push('js')
        <script>
            $("#update-password").change(function() {
                if ($("#update-password:checked").length) {
                    $(".password-area").each(function() {
                        $(this).show();
                    })
                } else {
                    $(".password-area").each(function() {
                        $(this).hide();
                    })
                }
            })
            $('#update-user').submit(function(e) {
                e.preventDefault();
                var selector = "#update-user";
                $(selector + " .spinner-border").removeClass("d-none");

                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: $(selector).attr("action"),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $(selector + " .spinner-border").addClass("d-none");
                        if (res.success) {
                            $(selector + " .alert-success ").removeClass("d-none");
                            $(selector + " .alert-danger ").addClass("d-none");
                            $(selector + " .alert-success").html(res.message);

                            setTimeout(() => {
                                window.location.href = "/admin"
                            }, 3000);
                        } else {
                            $(selector + " .alert-success ").addClass("d-none");
                            $(selector + " .alert-danger ").removeClass("d-none");
                            $(selector + " .alert-danger").html(res.message);
                        }

                    },
                    error: function(data) {}
                });
            });
        </script>
    @endpush
@endsection
