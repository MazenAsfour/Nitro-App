<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title id="title" title="@yield('title', 'home')">@yield('title', 'Laravel')</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- plugins:css -->
    <link rel="stylesheet" href="/dashboard/vendors/feather/feather.css">
    <link rel="stylesheet" href="/dashboard/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/dashboard/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/dashboard/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/dashboard/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="/dashboard/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="/dashboard/css/vertical-layout-light/style.css">

    <meta id="token" name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous">
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>


    @stack('css')
</head>

<body>
    <div class="container-scroller">
        @includeFirst(['dashboard/layouts/header'])

        <div class="container-fluid page-body-wrapper">
            @includeFirst(['dashboard/layouts/leftBar'])

            @yield('content')
        </div>
    </div>
    <!-- plugins:js -->
    <script src="/dashboard/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="/dashboard/vendors/chart.js/Chart.min.js"></script>
    <script src="/dashboard/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="/dashboard/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="/dashboard/js/dataTables.select.min.js"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="/dashboard/js/off-canvas.js"></script>
    <script src="/dashboard/js/hoverable-collapse.js"></script>
    <script src="/dashboard/js/template.js"></script>
    <script src="/dashboard/js/settings.js"></script>
    <script src="/dashboard/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="/dashboard/js/dashboard.js"></script>
    <script src="/dashboard/js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->
    @stack('js')
</body>

</html>
