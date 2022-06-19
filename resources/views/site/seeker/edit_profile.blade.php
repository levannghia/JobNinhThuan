@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Hoàn thiện hồ sơ</h1>
            <div class="g-4 with-hoso">
                <form class="needs-validation" method="POST" action="{{ route('seeker.profile.update.profile', $hoSoXinViec->id) }}" novalidate enctype="multipart/form-data">
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
                                    <input type="text" class="form-control" name="vitri" id="validationCustom01" value="{{old('vitri', $hoSoXinViec->vi_tri)}}"
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
                                        <option value="{{ $province->matp }}" {{$hoSoXinViec->provinces->contains('matp',$province->matp) ? 'selected' : ''}}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <div class="valid-feedback">
                                        Looks good!
                                    </div> --}}
                                    <div class="invalid-feedback">
                                        Trường này là bắt buộc
                                    </div>
                                </div>
                                @foreach ($category_list as $key => $category)
                                    <div class="col-md-4">
                                        <label for="validationCustom04"
                                            class="form-label">{{ $category->name }}</label>
                                        <select class="form-select" id="validationCustom04" required
                                            name="information_id[]">
                                            <option selected disabled value="">Choose...</option>
                                            @foreach ($category->informations as $item)
                                                <option value="{{ $item->id }}" {{$hoSoXinViec->informations->contains('id',$item->id) ? 'selected' : ''}}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Trường này là bắt buộc
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-md-12">
                                    <label class="form-label" for="">Mục tiêu nghề nghiệp</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{$hoSoXinViec->description}}</textarea>
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
                                @if (!empty($ngoai_ngu))
                                @foreach ($kinh_nghiem as $key => $item)
                                <div class="row g-3 pt-3">
                                    <p class="card-title btn text-center delete-style btn-delete-kn" data-dem-kn="{{$key}}">DELETE</p>
                                    <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Tên công ty / Tổ chức</label>
                                    <input type="text" name="data[kinh_nghiem][{{$key}}][ten_cong_ty]" value="{{$item->ten_cong_ty}}" class="form-control" id="inputEmail4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Chức danh</label>
                                    <input type="text" name="data[kinh_nghiem][{{$key}}][chuc_danh]" value="{{$item->chuc_danh}}" class="form-control" id="inputPassword4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Thời gian làm việc</label>
                                    <input type="text" name="data[kinh_nghiem][{{$key}}][thoi_gian_lam]" value="{{$item->thoi_gian_lam}}" class="form-control" id="inputAddress">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress2" class="form-label">Mức lương</label>
                                    <input type="number" name="data[kinh_nghiem][{{$key}}][muc_luong]" value="{{$item->muc_luong}}" class="form-control" id="inputAddress2">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputCity" class="form-label">Mô tả công việc</label>
                                    <textarea class="form-control" name="data[kinh_nghiem][{{$key}}][description]" id="exampleFormControlTextarea1" rows="3">{{$item->description}}</textarea>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputState" class="form-label">Thành tích đạt được</label>
                                    <textarea class="form-control" name="data[kinh_nghiem][{{$key}}][thanh_tich]" id="exampleFormControlTextarea1" rows="3">{{$item->thanh_tich}}</textarea>
                                  </div>
                                </div>
                                @endforeach
                                @endif
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
                                @if (!empty($bang_cap))
                                @foreach ($bang_cap as $key => $item)
                                <div class="row g-3 pt-3">
                                    <p class="card-title btn text-center delete-style btn-delete-bc" data-dem-bc="{{$key}}">DELETE</p>
                                    <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Tên bằng cấp / chứng chỉ</label>
                                    <input type="text" name="data[bang_cap][{{$key}}][name]" value="{{$item->name}}" class="form-control" id="inputEmail4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Trường / Đơn vị cấp </label>
                                    <input type="text" name="data[bang_cap][{{$key}}][don_vi]" value="{{$item->don_vi}}" class="form-control" id="inputPassword4">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Thời gian</label>
                                    <input type="text" name="data[bang_cap][{{$key}}][thoi_gian]" value="{{$item->thoi_gian}}" class="form-control" id="inputAddress">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputAddress2" class="form-label">Chuyên ngành</label>
                                    <input type="text" name="data[bang_cap][{{$key}}][chuyen_nganh]" value="{{$item->chuyen_nganh}}" class="form-control" id="inputAddress2">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputCity" class="form-label">Loại tốt nghiệp</label>
                                    <input type="text" name="data[bang_cap][{{$key}}][loai]" class="form-control" value="{{$item->loai_tot_nghiep}}" id="inputAddress2">
                                  </div>
                                  
                                    <div class="col-md-6">
                                        <label for="formFile" class="form-label">Tải bằng cấp: &nbsp;</label><span class="kich-thuoc">(width: <?= $thumbsize->width ?>, height:  <?= $thumbsize->height ?>)</span>
                                        <input class="form-control" type="file" accept="image/*" name="data[bang_cap][{{$key}}][photo]" />

                                        @if (isset($item->photo))
                                            <input type="hidden" value="{{$item->photo}}" name="data[bang_cap][{{$key}}][photo_temp]">
                                            <img style="padding-top: 10px;" src="/upload/images/hosoxinviec/thumb/{{$item->photo}}" class="img-fluid" alt="{{$item->name}}">
                                        @endif
                                        
                                    </div>
                                </div>
                                @endforeach
                            @endif
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
                                @if (!empty($ngoai_ngu))
                                @foreach ($ngoai_ngu as $stt => $item )
                                <div class="ngoai-ngu-child">
                                    <p class="card-title btn text-center delete-style btn-delete-nn" data-dem-nn="{{$stt}}">DELETE</p>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">Ngoại ngữ</span>
                                        <input type="text" class="form-control" value="{{$item->ten_ngoai_ngu}}" name="data[ngoai_ngu][{{$stt}}][ten_ngoai_ngu]" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="check-trinh-do">
                                        @foreach (config('thongtintuyendung.trinhdo') as $key => $value)       
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="data[ngoai_ngu][{{$stt}}][trinh_do]" id="inlineRadio1"
                                                value="{{$value['value']}}" {{ $item->trinh_do == $value['value'] ? 'checked' : ''}}>
                                            <label class="form-check-label" for="inlineRadio1">{{$value['name']}}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn-them-nn btn-style btn btn-outline-success viethoa">Thêm ngoại ngữ</button>
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
                                                        
                                                       @foreach (Helper::getTinHoc() as $stt => $item)
                                                           
                                                       <div class="tin-hoc-box">
                                                        <label for="exampleFormControlInput1" style="margin-right: 30px;" class="form-label">{{$item['name']}}</label>
                                                        <div class="check-trinh-do">
                                                        @foreach (config('thongtintuyendung.trinhdo') as $key => $value)       
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="tin_hoc[trinh_do][{{$item['value']}}]" id="inlineRadio1"
                                                                value="{{$value['value']}}" {{ isset($tin_hoc->trinh_do) && $tin_hoc->trinh_do[$stt] == $value['value'] ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="inlineRadio1">{{$value['name']}}</label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    </div>
                                                        @endforeach
                                                    
                                                       <div class="input-group mb-3 mt-3">
                                                        <span class="input-group-text" id="basic-addon1">Phần mềm khác</span>
                                                        <input type="text" value="{{$tin_hoc->phan_mem_khac}}" class="form-control" name="tin_hoc[phan_mem_khac]" aria-label="Username" aria-describedby="basic-addon1">
                                                    </div>
                                                    </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                            <label class="form-check-label" for="invalidCheck">
                                Agree to terms and conditions
                            </label>
                            <div class="invalid-feedback">
                                You must agree before submitting.
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        
        const arr_kn = $("[data-dem-kn]").toArray();
        const arr_nn = $("[data-dem-nn]").toArray();
        const arr_bc = $("[data-dem-bc]").toArray();
        var dem3 = arr_bc.length - 1;
        var dem2 = arr_nn.length - 1;
        var dem = arr_kn.length - 1;

        $('.btn-them-bc').click(function() {
            // console.log("Ok");
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
    </script>
@endpush
