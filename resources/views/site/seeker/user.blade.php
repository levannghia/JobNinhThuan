@extends('site.layout')
@section('content')
    <?php
    $thumbsize = json_decode($setting['THUMB_SIZE_USER']);
    ?>
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Quản lý tài khoản</h1>
            <div class="g-4 with-hoso">
                <form class="needs-validation" method="POST"
                    action="{{ route('seeker.profile.update.profile.user', $user->id) }}" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header viethoa">
                            Thông tin đăng nhập
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" name="name" id="validationCustom01"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Số điện thoại</label>
                                    <input type="number" class="form-control" name="phone"
                                        value="{{ old('phone', $user->phone) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Email đăng nhập</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email', $user->email) }}" required disabled>
                                </div>

                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Mật khẩu củ</label>
                                    <input type="password" class="form-control" name="old_password" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" name="new_password" value="">
                                </div>
                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Nhập lại mật khẩu mới</label>
                                    <input type="password" class="form-control" name="re_password" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header viethoa">
                            Thông tin cá nhân
                        </div>
                        <div class="card-body">
                            @if (@getimagesize($user->photo))
                                <img src="{{ $user->photo }}" alt="{{ $user->name }}" class="rounded-fix mx-auto d-block">
                            @else
                                <img src="/upload/images/seeker/thumb/{{ $user->photo }}" class="rounded-fix mx-auto d-block"
                                    alt="{{ $user->name }}">
                            @endif

                            <div class="row g-3 pt-3">
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address"
                                        value="{{ old('address', $user->address) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom02" class="form-label">Địa điểm mong muốn</label>
                                    <select class="form-select province" name="province_matp" required>
                                        <option value="">Choose...</option>
                                        @foreach ($province_list as $province)
                                            <option value="{{ $province->matp }}"
                                                {{ $user->province_matp == $province->matp ? 'selected' : '' }}>
                                                {{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom02" class="form-label">Giới tính</label>
                                    <select class="form-select province" name="gender" required>
                                        @foreach (Helper::getGender() as $key => $gender)
                                            <option value="{{ $key }}"
                                                {{ isset($user) ? Helper::getSelectedValue($key, $user->gender) : '' }}>
                                                {{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom02" class="form-label">Hôn nhân</label>
                                    @foreach (Helper::getHonNhan() as $key => $honNhan)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hon_nhan"
                                                value="{{ $key }}"
                                                {{ isset($user) ? Helper::getCheckedValue($key, $user->hon_nhan) : '' }}>
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                {{ $honNhan }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Ngày sinh</label>
                                    <input type="text" class="form-control" name="date_of_birth" id="datetimepicker1"
                                        value="{{ old('date_of_birth', $user->date_of_birth) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="file-upload">
                                        <button class="file-upload-btn" type="button"
                                            onclick="$('.file-upload-input').trigger( 'click' )">Add Image</button>

                                        <div class="image-upload-wrap">
                                            <input class="file-upload-input" type='file' name="photo"
                                                onchange="readURL(this);" accept="image/*" />
                                            <div class="drag-text">
                                                <h3>Drag and drop a file or select add Image</h3>
                                            </div>
                                        </div>
                                        <div class="file-upload-content">
                                            <img class="file-upload-image" src="#" alt="your image" />
                                            <div class="image-title-wrap">
                                                <button type="button" onclick="removeUpload()"
                                                    class="remove-image">Remove <span class="image-title">Uploaded
                                                        Image</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary" type="submit">CẬP NHẬT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    @if (Session::has('type') && Session::get('type') == 'success')
        <script>
            var message = "{{ Session::get('flash_message') }}";

            Successnotification(message);
        </script>
    @elseif (Session::has('type') && Session::get('type') == 'danger')
        <script>
            var message = "{{ Session::get('flash_message') }}";
            Errornotification(message);
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#datetimepicker1').datetimepicker({
                // format: 'DD/MM/YYYY hh:mm A',
                format: 'DD/MM/YYYY',
                icons: {
                    time: "far fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },

            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.image-upload-wrap').hide();

                    $('.file-upload-image').attr('src', e.target.result);
                    $('.file-upload-content').show();

                    $('.image-title').html(input.files[0].name);
                };

                reader.readAsDataURL(input.files[0]);

            } else {
                removeUpload();
            }
        }

        function removeUpload() {
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
        }
        $('.image-upload-wrap').bind('dragover', function() {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function() {
            $('.image-upload-wrap').removeClass('image-dropping');
        });
    </script>
@endpush
