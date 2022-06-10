@extends('site.layout')
@section('content')
    <section class="">
        <div class="container h-100">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Nhà tuyển dụng đăng nhập</h1>
            <div class="row d-flex align-items-center justify-content-center h-100 fadeInUp wow" data-wow-delay="0.1s">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                        class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <form method="POST" id="form_login" class="error-form" action="{{route('employer.post.login')}}">
                        @csrf
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input type="email" id="form1Example13" name="email" placeholder="Email address" class="form-control form-control-lg" />
                      
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" id="form1Example23" placeholder="Password"  name="password" class="form-control form-control-lg" />
                           
                        </div>

                        <div class="d-flex justify-content-around align-items-center mb-4">
                            <!-- Checkbox -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="form1Example3" checked />
                                <label class="form-check-label" for="form1Example3"> Remember me </label>
                            </div>
                            <a href="#!">Forgot password?</a>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-lg btn-block btn-size">Sign in</button>

                        <p class="mt-3 pb-lg-2" style="color: #393f81;">Don't have an account? <a
                                href="{{ route('employer.register') }}" style="color: #393f81;">Register here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    @if (Session::has('success'))
        <script>
            var message = "{{ Session::get('flash_message') }}";

            toastr.success(message, 'Success', {
                timeOut: 10000,
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "tapToDismiss": false

            })
        </script>
    @elseif (Session::has('danger'))
        <script>
            toastr.error('{{ Session::get('flash_message') }}', 'Error', {
                "positionClass": "toast-top-right",
                timeOut: 10000,
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "tapToDismiss": false

            })
        </script>
    @endif


    <script>
         $(document).ready(function() {

            $("#form_login").validate({
                onfocusout: false,
                onkeyup: false,
                onclick: false,
                rules: {
                    "email": {
                        required: true,
                        email: true
                    },
                  
                    "password": {
                        required: true,
                        minlength: 8
                    },
                   
                },
                messages: {
                    
                    "password": {
                        required: "Bắt buộc nhập password",
                        minlength: "Hãy nhập ít nhất 8 ký tự"
                    },

                    "email": {
                        required: "Bắt buộc nhập email",
                        email: "Trường này phải là email"
                    },
                   
                }
            });
        });
    </script>
@endpush
