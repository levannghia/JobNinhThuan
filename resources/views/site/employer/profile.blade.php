@extends('site.layout')
@section('content')

    <?php
    $thumbsize = json_decode($setting['THUMB_SIZE_LOGO_EMPLOYER']);
    ?>
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Quản lý tài khoản</h1>
            <div class="g-4 with-hoso">
                <form class="needs-validation" method="POST" action="{{ route('employer.job.update.profile', $user->id) }}"
                    novalidate enctype="multipart/form-data">
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
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Số điện thoại</label>
                                    <input type="number" class="form-control" name="phone"
                                        value="{{ old('phone', $user->phone) }}" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
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
                            Thông tin công ty
                        </div>
                        <div class="card-body">
                            <div class="row g-3 pt-3">
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Tên công ty</label>
                                    <input type="text" class="form-control" name="company_name"
                                        value="{{ old('company_name', $employer->company_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Quy mô công ty</label>
                                    <input type="text" class="form-control" name="quy_mo"
                                        value="{{ old('quy_mo', $employer->quy_mo) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Điện thoại cố định</label>
                                    <input type="number" class="form-control" name="company_phone"
                                        value="{{ old('company_phone', $employer->company_phone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Mã số thuế</label>
                                    <input type="number" class="form-control" name="maso_thue"
                                        value="{{ old('maso_thue', $employer->maso_thue) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Địa chỉ liên hệ</label>
                                    <input type="text" class="form-control" name="address"
                                        value="{{ $employer->address }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom02" class="form-label">Tỉnh / Thành Phố</label>
                                    <select class="form-select province" name="province_matp" id="validationCustom04"
                                        required>
                                        <option disabled value="">Choose...</option>
                                        @foreach ($province_list as $province)
                                            <option value="{{ $province->matp }}" {{$province->matp == $employer->province_matp ? 'selected' : ''}}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Giới thiệu công ty</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ $employer->description }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="formFile" class="form-label">Logo công ty: &nbsp;</label><span
                                        class="kich-thuoc">(width: <?= $thumbsize->width ?>, height: <?= $thumbsize->height ?>)</span>
                                    <input class="form-control" type="file" accept="image/*" name="photo" />

                                    @if (!empty($employer->photo))
                                        <img style="padding-top: 10px;" src="/upload/images/employer/thumb/{{$employer->photo}}" class="img-fluid" alt="{{$employer->name}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header viethoa">
                            Thông tin liên hệ
                        </div>
                        <div class="card-body">
                            <div class="row g-3 pt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="">Tên người liên hệ</label>
                                    <input type="text" class="form-control" name="phone_name"
                                        value="{{ $employer->name }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone_lien_he"
                                        value="{{ $employer->phone }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="">Email liên hệ</label>
                                    <input type="text" class="form-control" name="email_lien_he"
                                        value="{{ $employer->email }}" required>
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
@include('site.inc.toast_noti')
@endpush
