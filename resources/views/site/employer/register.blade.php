@extends('site.layout')
@section('content')
<style>
    form .error{
    color: red;
}
</style>
    <!-- Section: Design Block -->
    <section class="">
        <!-- Jumbotron -->
        <div class="px-4 py-5 px-md-5 text-center text-lg-start">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Nhà tuyển dụng đăng ký</h1>
            <div class="container wow fadeInUp" data-wow-delay="0.1s">
                <form class="row g-3 needs-validation" id="form_register" method="POST" action="{{route('employer.post.register')}}">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="name" placeholder="Họ và tên" class="form-control" required>

                    </div>
                    <div class="col-md-4">
                        <input type="text" name="phone" placeholder="Số điện thoại" class="form-control" required>
                    </div>
                    <div class="col-md-4">              
                        <input type="email" name="email" placeholder="Email đăng nhập" class="form-control"
                                aria-describedby="inputGroupPrepend" required>
                    </div>
                    <div class="col-md-6">
                        <input type="password" name="password" placeholder="Mật khẩu" id="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <input type="password" name="re_password" placeholder="Nhập lại mật khẩu" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="company_name" placeholder="Tên công ty" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" name="province_id" required>
                            <option selected disabled value="">Tỉnh / Thành Phố</option>
                            @foreach ($province as $item)
                                <option value="{{$item->matp}}">{{$item->name}}</option> 
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="quy_mo" placeholder="Quy mô công ty: ví dụ trên 200 người" class="form-control" required>
                    </div>
                   
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="phone_co_dinh" placeholder="Điện thoại cố định" required>
                    </div>

                    <div class="col-md-4">
                        <input type="text" placeholder="Tên người liên hệ" name="phone_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="phone_lien_he" placeholder="Điện thoại liên hệ" required>
                    </div>
                    <div class="col-md-4">
                        <input type="email" class="form-control" name="email_lien_he" placeholder="Email liên hệ" required>
                    </div>
                    {{-- <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                            <label class="form-check-label" for="invalidCheck">
                                Agree to terms and conditions
                            </label>
                        </div>
                    </div> --}}
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">REGISTER</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(document).ready(function() {

            $("#form_register").validate({
                onfocusout: false,
                onkeyup: false,
                onclick: false,
                rules: {
                    "name": {
                        required: true,
                        minlength: 5
                    },

                    "email": {
                        required: true,
                        email: true
                    },
                    
                    "phone": {
                        number: true,
                        digits: true
                    },
                    "phone_name": {
                        required: true,
                    },
                    "company_name": {
                        required: true,
                        minlength: 8
                    },

                    "email_lien_he": {
                        email: true,
                        required: true,
                        minlength: 5
                    },

                    "province_id": {
                        required: true,
                    },

                    "quy_mo":{
                        required: true,
                    },

                    "phone_co_dinh": {
                        required: true,
                        digits: true
                    },

                    "phone_lien_he": {
                        required: true,
                        digits:true
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

                    "password": {
                        required: "Bắt buộc nhập password",
                        minlength: "Hãy nhập ít nhất 8 ký tự"
                    },

                    "email": {
                        required: "Bắt buộc nhập email",
                        email: "Trường này phải là email"
                    },
                    "re_password": {
                        equalTo: "Password không giống nhau",
                        minlength: "Hãy nhập ít nhất 8 ký tự"
                    }
                }
            });
        });
    </script>
@endpush
