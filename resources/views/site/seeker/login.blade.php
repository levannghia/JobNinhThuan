@extends('site.layout')
@section('content')
    <section class="">
        <div class="container h-100">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Người tìm việc đăng nhập</h1>
            <div class="row d-flex align-items-center justify-content-center h-100 fadeInUp wow" data-wow-delay="0.1s">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                        class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <form method="POST" id="form_login" class="error-form" action="{{route('seeker.post.login')}}">
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

                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                        </div>

                        <a class="btn btn-primary btn-lg btn-block btn-size mb-2" style="background-color: #3b5998"
                            href="{{route('seeker.login.facebook')}}" role="button">
                            <i class="fab fa-facebook-f me-2"></i>Continue with Facebook
                        </a>
                        <a class="btn btn-primary btn-lg btn-block btn-size" style="background-color: #1787dc" href="{{route('seeker.login.google')}}"
                            role="button">
                            <i class="fab fa-google me-2"></i>Continue with Google</a>
                        <p class="mt-3 pb-lg-2" style="color: #393f81;">Don't have an account? <a
                                href="{{ route('seeker.register') }}" style="color: #393f81;">Register here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
@include('site.inc.toast_noti')
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
