<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Focus Admin: Widget</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">

    <!-- Styles -->
    <link href="{{ asset('assets/css/lib/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body class="bg-primary">
    @include('dashboard.inc.noitfication')
    <div class="unix-login">
        <div class="container-fluid">
            @include('dashboard.inc.error')
            <div class="row justify-content-center">
                
                <div class="col-lg-6">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="/"><span>Focus</span></a>
                        </div>
                        <div class="login-form">
                            <h4>Register to Administration</h4>
                            <form id="form_register" method="POST" action="{{ route('dashboard.post.register') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="title">Full name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Full Name">
                                </div>
                                <div class="form-group">
                                    <label class="title">Email address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label class="title">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label class="title">Password confirmation</label>
                                    <input type="password" name="re_password" class="form-control"
                                        placeholder="Password Confirmation">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Agree the terms and policy
                                    </label>
                                </div>
                                <button type="submit" id="btn_register"
                                    class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
                                {{-- <div class="social-login-content">
                                    <div class="social-button">
                                        <button type="button" class="btn btn-primary bg-facebook btn-flat btn-addon m-b-10"><i class="ti-facebook"></i>Register with facebook</button>
                                        <button type="button" class="btn btn-primary bg-twitter btn-flat btn-addon m-t-10"><i class="ti-twitter"></i>Register with twitter</button>
                                    </div>
                                </div> --}}
                                <div class="register-link m-t-15 text-center">
                                    <p>Already have account ? <a href="{{ route('dashboard.login') }}"> Sign in</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery.validate.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.validator.addMethod("validatePassword", function(value, element) {
                return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/i.test(value);
            }, "Hãy nhập password từ 8 đến 16 ký tự bao gồm chữ hoa, chữ thường và ít nhất một chữ số");

            $("#form_register").validate({
                onfocusout: false,
                onkeyup: false,
                onclick: false,
                rules: {
                    "name": {
                        required: true,
                        maxlength: 40
                    },
                    "email": {
                        required: true,
                        email: true
                    },
                   
                    "password": {
                        required: true,
                        minlength: 8
                    },
                    "re_password": {
                        equalTo: "#password",
                        minlength: 8
                    }
                },
                messages: {
                    "name": {
                        required: "Bắt buộc nhập username",
                        maxlength: "Hãy nhập tối đa 40 ký tự"
                    },


                    "password": {
                        required: "Bắt buộc nhập password",
                        minlength: "Hãy nhập ít nhất 8 ký tự"
                    },
                    "re_password": {
                        equalTo: "Hai password phải giống nhau",
                        minlength: "Hãy nhập ít nhất 8 ký tự"
                    }
                }
            });
        });
    </script>
</body>

</html>
