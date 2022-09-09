@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Đăng tin tuyển dụng</h1>
            <div class="g-4 with-hoso">
                <form class="needs-validation" method="POST" action="{{ route('employer.job.update', $recruitment->id) }}"
                    novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header viethoa">
                            Thông tin chung
                        </div>
                        <div class="card-body">
                            {{-- <h5 class="card-title">Special title treatment</h5> --}}
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label">Vị trí tuyển dụng</label>
                                    <input type="text" class="form-control" name="vitri" id=""
                                        value="{{ old('vitri', $recruitment->vi_tri) }}" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label">Số lượng tuyển</label>
                                    <input type="number" class="form-control" name="soluong"
                                        value="{{ old('soluong', $recruitment->so_luong) }}" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label">Hạn nộp hồ sơ</label>
                                    {{-- <div id="picker_hannop"></div> --}}
                                    <input type="text" class="form-control" id="datetimepicker1" name="hannop"
                                        value="{{ old('hannop', $recruitment->han_nop) }}" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Hoa hồng (nếu có)</label>
                                    <input type="number" class="form-control" placeholder="Từ (%)" name="hoahong_from"
                                        value="{{ old('hoa_hong_from', $recruitment->hoa_hong_from) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">-></label>
                                    <input type="number" class="form-control" placeholder="Đến (%)" name="hoahong_to"
                                        value="{{ old('hoa_hong_to', $recruitment->hoa_hong_to) }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="validationCustom02" class="form-label">Địa điểm làm việc</label>
                                    <select class="form-select province" name="province_matp[]" id=""
                                        required multiple>
                                        <option disabled value="">Choose...</option>
                                        @foreach ($province_list as $province)
                                            <option value="{{ $province->matp }}"
                                                {{ $recruitment->provinces->contains('matp', $province->matp) ? 'selected' : '' }}>
                                                {{ $province->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                @foreach ($category_list as $key => $category)
                                    <div class="col-md-4">
                                        <label for="" class="form-label">{{ $category->title }}</label>
                                        <select class="form-select" id="" required
                                            name="information_id[]">
                                            <option selected disabled value="">Choose...</option>
                                            @foreach ($category->informations as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $recruitment->informations->contains('id', $item->id) ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Trường này là bắt buộc
                                        </div>
                                    </div>
                                @endforeach
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
                                <div class="col-md-12">
                                    <label class="form-label" for="">Mô tả công việc</label>
                                    <textarea class="form-control" id="" name="description" rows="3" required>{{ $recruitment->description }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Quyền lợi</label>
                                    <textarea class="form-control" id="" name="quyenloi" rows="3" required>{{ $recruitment->quyen_loi }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Yêu cầu công việc</label>
                                    <textarea class="form-control" id="" name="yeucau" rows="3" required>{{ $recruitment->yeu_cau }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Hồ sơ ứng tuyển gồm</label>
                                    <textarea class="form-control" id="" name="hosogom" rows="3" required>{{ $recruitment->ho_so_gom }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Hình thức nộp</label>
                                    <textarea class="form-control" id="" name="hinhthuc" rows="3" required>{{ $recruitment->hinh_thuc }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header viethoa">
                            Nội dung tuyển dụng
                        </div>
                        <div class="card-body">
                            <div class="row g-3 pt-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="">Tên người liên hệ</label>
                                    <input type="text" class="form-control" name="phone_name"
                                        value="{{ $employer->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Địa chỉ liên hệ</label>
                                    <input type="text" class="form-control" name="address"
                                        value="{{ $employer->address }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone_lien_he"
                                        value="{{ $employer->phone }}" required>
                                </div>
                                <div class="col-md-6">
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
    <script>
        $(document).ready(function() {
            $('.province').select2();
            // $('#picker_hannop').dateTimePicker({title: 'Hạn nộp hồ sơ'});
            $('#datetimepicker1').datetimepicker({
                sideBySide: true,
                format: 'DD/MM/YYYY HH:mm A',
                icons: {
                    time: "far fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },

            });
        });
    </script>
@endpush
