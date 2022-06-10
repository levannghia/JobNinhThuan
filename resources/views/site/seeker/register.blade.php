@extends('site.layout')
@section('content')
    <!-- Section: Design Block -->
    <section class="">
        <!-- Jumbotron -->
        <div class="px-4 py-5 px-md-5 text-center text-lg-start">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Người tìm việc đăng ký</h1>
            <div class="container wow fadeInUp" data-wow-delay="0.1s">
                <div class="row gx-lg-5 align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h1 class="my-5 display-3 fw-bold ls-tight">
                            The best offer <br />
                            <span class="text-primary">for your business</span>
                        </h1>
                        <p style="color: hsl(217, 10%, 50.8%)">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Eveniet, itaque accusantium odio, soluta, corrupti aliquam
                            quibusdam tempora at cupiditate quis eum maiores libero
                            veritatis? Dicta facilis sint aliquid ipsum atque?
                        </p>
                    </div>

                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <div class="card">
                            <div class="card-body py-5 px-md-5">
                                <form id="form_register" class="error-form">
                                    <!-- 2 column grid layout with text inputs for the first and last names -->
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-outline">
                                                <input type="text" id="form3Example1" class="form-control"
                                                    name="firstName" placeholder="First name" />
                                                <label id="form3Example1-error" class="error error-first"
                                                    for="form3Example1"></label>
                                                {{-- <label class="form-label" for="form3Example1">First name</label> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-outline">
                                                <input type="text" id="form3Example2" class="form-control" name="lastName"
                                                    placeholder="Last name" />
                                                <label id="form3Example1-error" class="error error-last"
                                                    for="form3Example1"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email input -->
                                    <div class="form-outline mb-4">
                                        <input type="email" id="form3Example3" class="form-control" name="email"
                                            placeholder="Email address" />
                                        <label id="form3Example1-error" class="error error-email"
                                            for="form3Example1"></label>
                                    </div>

                                    <!-- Password input -->
                                    <div class="form-outline mb-4">
                                        <input type="password" id="form3Example4" class="form-control" name="password"
                                            placeholder="Password" />
                                        <label id="form3Example1-error" class="error error-password"
                                            for="form3Example1"></label>
                                    </div>

                                    <!-- Checkbox -->
                                    <div class="form-check d-flex justify-content-center mb-4">
                                        <input class="form-check-input me-2" type="checkbox" value="" id="form2Example33"
                                            checked />
                                        <label class="form-check-label" for="form2Example33">
                                            Subscribe to our newsletter
                                        </label>
                                    </div>

                                    <!-- Submit button -->
                                    <button type="submit" class="btn btn-primary btn-block mb-4 btn-size" id="btn_submit">
                                        Sign up
                                    </button>

                                    <!-- Register buttons -->
                                    <div class="text-center">
                                        <p>or sign up with:</p>
                                        <button type="button" class="btn btn-link btn-floating mx-1">
                                            <i class="fab fa-facebook-f"></i>
                                        </button>

                                        <button type="button" class="btn btn-link btn-floating mx-1">
                                            <i class="fab fa-google"></i>
                                        </button>

                                        <button type="button" class="btn btn-link btn-floating mx-1">
                                            <i class="fab fa-twitter"></i>
                                        </button>

                                        <button type="button" class="btn btn-link btn-floating mx-1">
                                            <i class="fab fa-github"></i>
                                        </button>
                                    </div>
                                    <p class="mt-3 pb-lg-2" style="color: #393f81;">Bạn đã có tài khoản? <a
                                            href="{{ route('seeker.login') }}" style="color: #393f81;">Login here</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jumbotron -->
    </section>
    <!-- Section: Design Block -->
@endsection
@push('script')
    <script>
        const btn_submit = document.getElementById("btn_submit");
        $(document).ready(function() {
        });
        btn_submit.addEventListener("click", event => {
            event.preventDefault();
            var data_form = $("#form_register").serialize();
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('seeker.post.register') }}",
                type: "post",
                data: "_token=" + _token + "&" + data_form,
                beforeSend: function() {
                    $(".error-first").hide();
                    $(".error-last").hide();
                    $(".error-email").hide();
                    $(".error-password").hide();
                },
                success: function(data) {

                    if (data.status == 0) {
                        data.error.firstName != undefined ? $(".error-first").html(
                            data.error.firstName).show() : "";
                        data.error.lastName != undefined ? $(".error-last").html(data.error
                            .lastName).show() : "";
                        data.error.email != undefined ? $(".error-email").html(data.error
                            .email).show() : "";
                        data.error.password != undefined ? $(".error-password").html(data
                            .error.password).show() : "";
                    } else {
                        swal("Đăng ký thành công", "Vui lòng xác nhận email!",
                            "success").then((value) => {
                            if (value) {
                                location.reload();
                            }
                            if (value == null) {
                                location.reload();
                            }
                        });
                    }
                }
            });
        });
    </script>
@endpush
