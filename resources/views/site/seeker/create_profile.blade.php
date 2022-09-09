@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Tạo hồ sơ ứng tuyển</h1>
            <div class="g-4 with-hoso">
                <form class="needs-validation" method="POST" action="{{ route('seeker.profile.store.profile') }}" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header viethoa">
                            Thông tin ứng tuyển
                        </div>
                        <div class="card-body">
                            {{-- <h5 class="card-title">Special title treatment</h5> --}}
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Vị trí mong muốn</label>
                                    <input type="text" class="form-control" name="vitri" id="validationCustom01" value=""
                                        required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="validationCustom02" class="form-label">Địa điểm mong muốn</label>
                                    <select class="form-select province" name="province_matp[]" id="validationCustom04" required multiple>
                                        <option disabled value="">Choose...</option>
                                        @foreach ($province_list as $province)
                                        <option value="{{ $province->matp }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                @foreach ($category_list as $key => $category)
                                    <div class="col-md-4">
                                        <label for="validationCustom04"
                                            class="form-label">{{ $category->title }}</label>
                                        <select class="form-select" id="validationCustom04" required
                                            name="information_id[]">
                                            <option selected disabled value="">Choose...</option>
                                            @foreach ($category->informations as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Trường này là bắt buộc
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-md-12">
                                    <label class="form-label" for="">Mục tiêu nghề nghiệp</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header viethoa">
                            Kinh nghiệm làm việc
                        </div>
                        <div class="card-body">
                            <div class="kinh-nghiem-lv">


                            </div>
                            <button type="button" class="btn-them-kn btn btn-style btn-outline-success viethoa">Thêm kinh nghiêm</button>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header viethoa">
                            Trình độ / Bằng cấp
                        </div>
                        <div class="card-body">
                            <?php
                                $thumbsize = json_decode($setting["THUMB_SIZE_BANG_CAP"]);
                            ?>
                            <div class="bang-cap">
                                

                            </div>
                            <button type="button" class="btn-them-bc btn btn-style btn-outline-success viethoa">Thêm bằng cấp</button>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header viethoa">
                            Ngoại ngữ
                        </div>
                        <div class="card-body">
                            <div class="ngoai-ngu">
                                
                            </div>
                            <button type="button" class="btn-them-nn btn-style btn btn-outline-success viethoa">Thêm ngoại
                                ngữ</button>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header viethoa">
                            Trình độ tin học
                        </div>
                        <div class="card-body">
                            <div class="tin-hoc">
                                <div class="ngoai-ngu-child">
                                                        
                                                       @foreach (Helper::getTinHoc() as $item)
                                                           
                                                       <div class="tin-hoc-box">
                                                        <label for="exampleFormControlInput1" style="margin-right: 30px;" class="form-label">{{$item['name']}}</label>
                                                        <div class="check-trinh-do">
                                                        @foreach (config('thongtintuyendung.trinhdo') as $key => $value)       
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="tin_hoc[trinh_do][{{$item['value']}}]" id="inlineRadio1"
                                                                value="{{$value['value']}}" required>
                                                            <label class="form-check-label" for="inlineRadio1">{{$value['name']}}</label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    </div>
                                                        @endforeach
                                                    
                                                       <div class="input-group mb-3 mt-3">
                                                        <span class="input-group-text" id="basic-addon1">Phần mềm khác</span>
                                                        <input type="text" class="form-control" name="tin_hoc[phan_mem_khac]" aria-label="Username" aria-describedby="basic-addon1">
                                                    </div>
                                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
@include('site.inc.toast_noti')
    <script>
        var dem = -1;
        var dem2 = -1;
        var dem3 = -1;


        $('.btn-them-bc').click(function() {
            dem3 = dem3 + 1;
            $('.bang-cap').append(`<div class="row g-3 pt-3">
                                    <p class="card-title btn text-center delete-style btn-delete-bc">DELETE</p>
                                    <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Tên bằng cấp / chứng chỉ</label>
                                    <input type="text" name="data[bang_cap][${dem3}][name]" class="form-control" id="inputEmail4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Trường / Đơn vị cấp </label>
                                    <input type="text" name="data[bang_cap][${dem3}][don_vi]" class="form-control" id="inputPassword4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Thời gian</label>
                                    <input type="text" name="data[bang_cap][${dem3}][thoi_gian]" class="form-control" id="inputAddress">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress2" class="form-label">Chuyên ngành</label>
                                    <input type="text" name="data[bang_cap][${dem3}][chuyen_nganh]" class="form-control" id="inputAddress2">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputCity" class="form-label">Loại tốt nghiệp</label>
                                    <input type="text" name="data[bang_cap][${dem3}][loai]" class="form-control" id="inputAddress2">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="formFile" class="form-label">Tải bằng cấp: &nbsp;</label><span class="kich-thuoc">(width: <?= $thumbsize->width ?>, height:  <?= $thumbsize->height ?>)</span>
                                    <input class="form-control" type="file" accept="image/*" name="data[bang_cap][${dem3}][photo]" />
                                  </div>
                                </div>`);
            return false;
        });

        $('.bang-cap').on('click', '.btn-delete-bc', function(e) {
            e.preventDefault();
            $(this).parent().remove();
        });

        $('.btn-them-nn').click(function() {
            dem2 = dem2 + 1;
            $('.ngoai-ngu').append(`<div class="ngoai-ngu-child">
                <p class="card-title btn text-center delete-style btn-delete-nn">DELETE</p>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">Ngoại ngữ</span>
                                        <input type="text" class="form-control" name="data[ngoai_ngu][${dem2}][ten_ngoai_ngu]" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                   <div class="check-trinh-do">
                                    @foreach (config('thongtintuyendung.trinhdo') as $key => $value)       
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="data[ngoai_ngu][${dem2}][trinh_do]" id="inlineRadio1"
                                            value="{{$value['value']}}">
                                        <label class="form-check-label" for="inlineRadio1">{{$value['name']}}</label>
                                    </div>
                                    @endforeach
                                   </div>
                                </div>`);
            return false;
        });
        $('.ngoai-ngu').on('click', '.btn-delete-nn', function(e) {
            e.preventDefault();
            $(this).parent().remove();
        });

        $('.btn-them-kn').click(function() {
            dem = dem + 1;
            $('.kinh-nghiem-lv').append(`<div class="row g-3 pt-3"><p class="card-title btn text-center delete-style btn-delete-kn">DELETE</p><div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Tên công ty / Tổ chức</label>
                                    <input type="text" name="data[kinh_nghiem][${dem}][ten_cong_ty]" class="form-control" id="inputEmail4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Chức danh</label>
                                    <input type="text" name="data[kinh_nghiem][${dem}][chuc_danh]" class="form-control" id="inputPassword4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Thời gian làm việc</label>
                                    <input type="text" name="data[kinh_nghiem][${dem}][thoi_gian_lam]" class="form-control" id="inputAddress">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress2" class="form-label">Mức lương</label>
                                    <input type="number" name="data[kinh_nghiem][${dem}][muc_luong]" class="form-control" id="inputAddress2">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputCity" class="form-label">Mô tả công việc</label>
                                    <textarea class="form-control" name="data[kinh_nghiem][${dem}][description]" id="exampleFormControlTextarea1" rows="3"></textarea>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputState" class="form-label">Thành tích đạt được</label>
                                    <textarea class="form-control" name="data[kinh_nghiem][${dem}][thanh_tich]" id="exampleFormControlTextarea1" rows="3"></textarea>
                                  </div></div>`);
            return false;
        });

        $('.kinh-nghiem-lv').on('click', '.btn-delete-kn', function(e) {
            e.preventDefault();
            $(this).parent().remove();
        });

        // Example starter JavaScript for disabling form submissions if there are invalid fields

        $(document).ready(function() {
            $('.province').select2();
        });

//         function readURL(input) {
//   if (input.files && input.files[0]) {

//     var reader = new FileReader();

//     reader.onload = function(e) {
//       $('.image-upload-wrap').hide();

//       $('.file-upload-image').attr('src', e.target.result);
//       $('.file-upload-content').show();

//       $('.image-title').html(input.files[0].name);
//     };

//     reader.readAsDataURL(input.files[0]);

//   } else {
//     removeUpload();
//   }
// }

// function removeUpload() {
//   $('.file-upload-input').replaceWith($('.file-upload-input').clone());
//   $('.file-upload-content').hide();
//   $('.image-upload-wrap').show();
// }
// $('.image-upload-wrap').bind('dragover', function () {
//     $('.image-upload-wrap').addClass('image-dropping');
//   });
//   $('.image-upload-wrap').bind('dragleave', function () {
//     $('.image-upload-wrap').removeClass('image-dropping');
// });

    </script>
@endpush
