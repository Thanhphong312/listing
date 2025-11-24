<head>
    <title>@yield('page-title') - {{Setting('app_name')}}</title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="template language" name="keywords">
    <meta content="Tamerlan Soziev" name="author">
    <meta content="Admin- Supover" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link rel="shortcut icon" href="{{ url('assets/img/logo.jpg') }}" />
    <link href="{{ asset('https://fonts.googleapis.com/css?family=Lato:300,400,700')}}" rel="stylesheet"
        type="text/css">
    <!-- <link href="{{ asset('bower_components/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <!-- <link href="{{ asset('components/dropzone/dist/dropzone.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('components/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('components/perfect-scrollbar/css/perfect-scrollbar.min.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('components/slick-carousel/slick/slick.css')}}" rel="stylesheet"> -->
    <!-- <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/style.css')}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/owl.carousel.css')}}">
    <link href="{{ asset('css/main.css?version=4.5.0')}}" rel="stylesheet">
    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/vendor.css')) }}"> -->
    <!-- Include FontAwesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    {{--
    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/app.css')) }}"> --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css"
        integrity="sha512-mR/b5Y7FRsKqrYZou7uysnOdCIJib/7r5QeJMFvLNHNhtye3xJp1TdJVPLtetkukFn227nKpXD9OjUc09lx97Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Move these links to the head section of your layout file (layouts.app) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        strong {
            font-weight: bold !important;
        }
    </style>
    <style>
        #timezonenow {
            font-size: 16px;
            /* Kích thước nhỏ hơn */
            font-weight: normal;
            color: #3498db;
            /* Màu xanh nổi bật */
            text-align: center;
            margin: 0 auto;
            /* Căn giữa */
            padding: 5px;
            border: 1px solid #3498db;
            /* Viền mỏng hơn */
            border-radius: 5px;
            background-color: #f9f9f9;
            /* Màu nền rất nhẹ */
            width: 200px;
            /* Chiều rộng nhỏ hơn */
        }

        #timezonenowfaddflashdeal {
            font-size: 16px;
            /* Kích thước nhỏ hơn */
            font-weight: normal;
            color: #3498db;
            /* Màu xanh nổi bật */
            text-align: center;
            margin: 0 auto;
            /* Căn giữa */
            padding: 5px;
            border: 1px solid #3498db;
            /* Viền mỏng hơn */
            border-radius: 5px;
            background-color: #f9f9f9;
            /* Màu nền rất nhẹ */
            width: 200px;
            /* Chiều rộng nhỏ hơn */
        }
    </style>
    @yield('styles')

    @hook('app:styles')
</head>

<body>
    <div id="layout-wrapper">
        @include('partials.header')
        @include('partials.sidebar')
        <!-- Nội dung trang -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                        @yield('breadcrumbs')
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    @yield('content')
                </div>
            </div>
            @include('partials.footer')
        </div>
        <!-- wallet -->
        <div class="modal fade" id="addFund">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add Fund

                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <h6 class="ml-3">Pingpong Email: ngothuydung07032002@gmail.com</h6>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputType">Payment method</label>
                                <select class="form-control" id="inputType">
                                    <option value="payoneer">Payoneer</option>
                                    <option value="lianlian">LianLian</option>
                                    <option value="pingpong">pingpong</option>
                                    <option value="banktransfer">bank transfer</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAmount">Amount (USD)</label>
                                <input type="number" step="0.01" class="form-control" id="inputAmount" value=""
                                    placeholder="the amount in USD">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputNote">Note</label>
                            <input type="text" class="form-control" id="inputNote" value=""
                                placeholder="Transaction ID, ...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" data-orderid="" class="btn btn-primary" id="btnAddFund">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <script src="{{ url(mix('assets/js/vendor.js')) }}"></script>
    <script src="{{ url('assets/js/as/app.js') }}"></script> -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('bower_components/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ asset('bower_components/moment/moment.js')}}"></script>
    <script src="{{ asset('bower_components/chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('bower_components/jquery-bar-rating/dist/jquery.barrating.min.js')}}"></script>
    <script src="{{ asset('bower_components/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap-validator/dist/validator.min.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ asset('bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{ asset('bower_components/dropzone/dist/dropzone.js')}}"></script>
    <script src="{{ asset('bower_components/editable-table/mindmup-editabletable.js')}}"></script>
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{ asset('bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{ asset('bower_components/tether/dist/js/tether.min.js')}}"></script>
    <script src="{{ asset('bower_components/slick-carousel/slick/slick.min.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/util.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/alert.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/button.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/carousel.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/collapse.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/dropdown.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/modal.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/tab.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/tooltip.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap/js/dist/popover.js')}}"></script>
    <script src="{{ asset('js/owl.carousel.js')}}"></script>
    <script src="{{ asset('js/demo_customizer.js?version=4.5.0')}}"></script>
    <script src="{{ asset('js/main.js?version=4.5.0')}}"></script>
    <!-- <script src="{{ asset('js/popper.js')}}"></script> -->
    <!-- <script src="{{ asset('js/bootstrap-multiselect.js')}}"></script> -->

    <!-- JavaScript -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('bower_components/datetimepicker-master/build/jquery.datetimepicker.full.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('scripts')
    @hook('auth:scripts')
    <script>
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        let status = getCookie("onCollapase");
        if (status == "on") {
            document.getElementsByClassName('menu-w')[0].className =
                " menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-mini sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link ";
            document.getElementsByClassName('os-icon')[9].className = "os-icon os-icon-arrow-right4";
            document.getElementsByClassName('arrow')[1].children[1].innerText = 'Expand';
            // alert('yes')
        }
        if (status == "off") {
            document.getElementsByClassName('menu-w')[0].className =
                " menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-compact sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link ";
            document.getElementsByClassName('os-icon')[9].className = "os-icon os-icon-arrow-left4";
            document.getElementsByClassName('arrow')[1].children[1].innerText = 'Collapse';
            // alert('yes')
        }

        function onCollapase() {
            let status = getCookie("onCollapase");
            if (status == "" || status == "off") {
                console.log('on');
                status = "on";
                setCookie("onCollapase", status, 1);
                document.getElementsByClassName('menu-w')[0].className =
                    " menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-mini sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link ";
                document.getElementsByClassName('os-icon')[9].className = "os-icon os-icon-arrow-right4";
                document.getElementsByClassName('arrow')[1].children[1].innerText = 'Expand';
            } else if (status == "on") {
                console.log('off');
                status = "off";
                setCookie("onCollapase", status, 1);
                document.getElementsByClassName('menu-w')[0].className =
                    " menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-compact sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link ";
                document.getElementsByClassName('os-icon')[9].className = "os-icon os-icon-arrow-left4";
                document.getElementsByClassName('arrow')[1].children[1].innerText = 'Collapse';
            }
        }
    </script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>
    <script>
        $(document).ready(function () {
            // Make sure jQuery is fully loaded before initializing Select2
            setTimeout(function () {
                try {
                    $('.select2').select2();
                    console.log('Select2 initialized successfully');
                } catch (e) {
                    console.error('Select2 initialization error:', e);
                }
            }, 100);
        });
        $(document).ready(function () {
            // Kiểm tra xem trạng thái Dark Color đã lưu trong cookie không
            var darkColor = Cookies.get("darkColor") === "true";
            // Cập nhật trạng thái của giao diện dựa trên giá trị trong cookie
            if (darkColor) {
                enableDarkColor();
            }
            // Xử lý sự kiện khi người dùng chuyển đổi Dark Color
            $(".sub-menu.floated-colors-btn").click(function () {
                darkColor = !darkColor; // Đảo ngược trạng thái
                updateDarkColor();
                Cookies.set("darkColor", darkColor.toString(), {
                    expires: 7
                }); // Lưu trạng thái Dark Color vào cookie
            });
            // Hàm kích hoạt Dark Color
            function enableDarkColor() {
                // Thêm lớp CSS hoặc thực hiện các thay đổi khác cho Dark Color
                $("body").addClass("color-scheme-dark");
                $(".menu-position-side").addClass("color-scheme-dark");
                $(".os-toggler-w").addClass("on");
                $(".menu-position-side").removeClass("color-scheme-light");
            }
            // Hàm tắt Dark Color
            function disableDarkColor() {
                // Loại bỏ lớp CSS hoặc thực hiện các thay đổi khác để tắt Dark Color
                $("body").removeClass("color-scheme-dark");
                $(".menu-position-side").removeClass("color-scheme-dark");
                $(".os-toggler-w").removeClass("on");
                $(".menu-position-side").addClass("color-scheme-light");
            }
            // Cập nhật trạng thái của giao diện
            function updateDarkColor() {
                if (darkColor) {
                    enableDarkColor();
                } else {
                    disableDarkColor();
                }
            }
        });
    </script>

    <!-- wallet -->
    <script>
        $(document).ready(function () {
            let token = "{{ csrf_token() }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
        });
        function addFund() {
            $('#addFund').modal('show');
        }
        $(document).on('click', 'body #btnAddFund', function () {
            let order_id = $(this).data('orderid');
            var form_data = new FormData(); // Create a FormData object
            form_data.append('_csrf', "{{csrf_token()}}");
            form_data.append('type', $('#inputType').val());
            form_data.append('amount', $('#inputAmount').val());
            form_data.append('note', $('#inputNote').val());
            ajaxAddFund(form_data);
        });
        function ajaxAddFund(data) {
            let url = "walletpay/addfund";
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    if (response.message == 'ok') {
                        $('#addFund').modal('hide');
                        location.reload();
                    } else {
                        alert('Error!');
                    }
                },
            });
        }
    </script>
</body>

</html>