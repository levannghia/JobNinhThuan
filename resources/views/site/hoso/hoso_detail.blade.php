@extends('site.layout')
@section('content')
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gy-5 gx-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-5">
                        @if (@getimagesize($hoSo->users->photo))
                            <img class="flex-shrink-0 img-fluid border rounded" src="{{ $hoSo->users->photo }}"
                                alt="{{ $hoSo->users->name }}" style="width: 80px; height: 80px;">
                        @elseif ($hoSo->users->photo == null)
                            <img class="flex-shrink-0 img-fluid border rounded" src="/site/img/avatar.png"
                                alt="{{ $hoSo->users->name }}" style="width: 80px; height: 80px;">
                        @else
                            <img class="flex-shrink-0 img-fluid border rounded"
                                src="/upload/{{ $hoSo->users->photo }}"
                                alt="{{ $hoSo->users->name }}" style="width: 80px; height: 80px;">
                        @endif
                        <div class="text-start ps-4">
                            <h3 class="mb-3">{{ $hoSo->vi_tri }}</h3>
                            <span class="text-truncate me-3 viethoa"><i class="fas fa-signature text-primary"></i>
                                {{ $hoSo->users->name }}</span>
                            <span id="add_flow" class="text-truncate me-3"><i id="icon_flow"
                                    class="{{ isset($check_flow) && $check_flow->flow_user == 1 ? 'fas' : 'far' }} fa-star text-primary me-2"></i>Lưu
                                hồ sơ</span>
                        </div>
                    </div>

                    <div class="infor-recruitment mb-5">
                        <div class="row">

                            @foreach ($category_list as $category)
                                @foreach ($category->informations as $info)
                                    @if ($hoSo->informations->contains('id', $info->id))
                                        <div class="col-md-6 mb-3">
                                            <h5>{{ $category->title }}</h5>
                                            <span class="text-truncate me-3"><i
                                                    class="fa fa-angle-right text-primary me-2"></i>{{ $info->name }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            <div class="col-md-6 mb-3">
                                <h5>Địa điểm làm việc</h5>
                                <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    @foreach ($hoSo->provinces as $province)
                                        {{ $province->name }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        @if ($hoSo->kinh_nghiem != '')
                            <h4 class="">Kinh nghiệm</h4>
                            <ul class="list-unstyled">
                                @foreach ($hoSo->kinh_nghiem as $kn)
                                    <li class="pt-3">
                                        <h5>Tên công ty: {{ $kn['ten_cong_ty'] }}</h5>
                                    </li>
                                    <li><i class="fas fa-user-plus text-primary me-2"></i>Chức danh:
                                        {{ $kn['chuc_danh'] }}
                                    </li>
                                    <li><i class="fas fa-calendar-alt text-primary me-2"></i>Thời gian làm việc:
                                        {{ $kn['thoi_gian_lam'] }}
                                    </li>
                                    <li><i class="fas fa-feather text-primary me-2"></i>Mô tả: {{ $kn['description'] }}
                                    </li>
                                    <li><i class="fas fa-trophy text-primary me-2"></i>Thành tích: {{ $kn['thanh_tich'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="row">

                            <div class="col-md-6">
                                @if ($hoSo->tin_hoc != '')
                                    @php
                                        $trinh_do = $hoSo->tin_hoc;
                                    @endphp
                                    <h4 class="mb-3">Tin học</h4>
                                    <ul class="list-unstyled">
                                        @if (isset($trinh_do['trinh_do']) && $trinh_do['trinh_do'] != '')
                                            @foreach (Helper::getTinHoc() as $stt => $tin_hoc)
                                                <li class="mb-2">
                                                    <p>
                                                        <i
                                                            class="fa fa-angle-right text-primary me-2"></i>{{ $tin_hoc['name'] }}:
                                                    </p>
                                                    @foreach (config('thongtintuyendung.trinhdo') as $key => $value)
                                                        @if ($trinh_do['trinh_do'][$stt] == $value['value'])
                                                            <div class="progress" style="margin-top: -10px">
                                                                @switch($trinh_do['trinh_do'][$stt])
                                                                    @case(0)
                                                                        <div class="progress-bar" style="width: 100%;"
                                                                            role="progressbar" aria-valuenow="100"aria-valuemin="0"
                                                                            aria-valuemax="100">{{ $value['name'] }}</div>
                                                                    @break

                                                                    @case(1)
                                                                        <div class="progress-bar" style="width: 75%;"
                                                                            role="progressbar" aria-valuenow="75" aria-valuemin="0"
                                                                            aria-valuemax="100">{{ $value['name'] }}</div>
                                                                    @break

                                                                    @case(2)
                                                                        <div class="progress-bar" style="width: 50%;"
                                                                            role="progressbar" aria-valuenow="50" aria-valuemin="0"
                                                                            aria-valuemax="100">{{ $value['name'] }}</div>
                                                                    @break

                                                                    @case(3)
                                                                        <div class="progress-bar" style="width: 25%;"
                                                                            role="progressbar" aria-valuenow="25" aria-valuemin="0"
                                                                            aria-valuemax="100">{{ $value['name'] }}</div>
                                                                    @break

                                                                    @default
                                                                        <div class="progress-bar" style="width: 0px;"
                                                                            role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                            aria-valuemax="100">{{ $value['name'] }}</div>
                                                                @endswitch
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </li>
                                            @endforeach
                                        @endif
                                        @if ($trinh_do['phan_mem_khac'] != '')
                                            <li>
                                                <p>Phần mềm khác: {{ $trinh_do['phan_mem_khac'] }}</p>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                                @if ($hoSo->ngoai_ngu != '')
                                    <h4 class="mb-3">Ngoại ngữ</h4>
                                    <ul class="list-unstyled">
                                        @foreach ($hoSo->ngoai_ngu as $nn)
                                            <li class="mb-2">
                                                <p>
                                                    <i class="fa fa-angle-right text-primary me-2"></i>{{ $nn['ten_ngoai_ngu'] }}:
                                                </p>
                                                @foreach (config('thongtintuyendung.trinhdo') as $key => $value)
                                                    @if ($nn['trinh_do'] == $value['value'])
                                                        <div class="progress" style="margin-top: -10px">
                                                            @switch($nn['trinh_do'])
                                                                @case(0)
                                                                    <div class="progress-bar" style="width: 100%;"
                                                                        role="progressbar" aria-valuenow="100"aria-valuemin="0"
                                                                        aria-valuemax="100">{{ $value['name'] }}</div>
                                                                @break

                                                                @case(1)
                                                                    <div class="progress-bar" style="width: 75%;" role="progressbar"
                                                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                                        {{ $value['name'] }}</div>
                                                                @break

                                                                @case(2)
                                                                    <div class="progress-bar" style="width: 50%;" role="progressbar"
                                                                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                                        {{ $value['name'] }}</div>
                                                                @break

                                                                @case(3)
                                                                    <div class="progress-bar" style="width: 25%;"
                                                                        role="progressbar" aria-valuenow="25" aria-valuemin="0"
                                                                        aria-valuemax="100">{{ $value['name'] }}</div>
                                                                @break

                                                                @default
                                                                    <div class="progress-bar" style="width: 0px;"
                                                                        role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                        aria-valuemax="100">{{ $value['name'] }}</div>
                                                            @endswitch
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </li>
                                        @endforeach

                                    </ul>
                                @endif
                            </div>


                            @if ($hoSo->bang_cap != '')
                                <div class="col-md-6">

                                    <h4 class="mb-3">Bằng cấp / Chứng Chỉ</h4>
                                    <div class="owl-carousel owl-theme owl-bang-cap">
                                        @foreach ($hoSo->bang_cap as $bc)
                                            <figure class="snip0019">
                                                <img src="/upload/{{ $bc['photo'] }}"
                                                    alt="sample12" />
                                                <figcaption>
                                                    <div>
                                                        <h2> {{ $bc['name'] }}</h2>
                                                    </div>
                                                    <div>
                                                        <p>
                                                            {{-- <i class="fab fa-accusoft"></i> --}}
                                                            <i class="far fa-building text-primary me-2"></i>
                                                            {{ $bc['don_vi'] }} <br>
                                                            <i class="fab fa-accusoft text-primary me-2"></i>
                                                            {{ $bc['chuyen_nganh'] }} <br>
                                                            <i class="fa fa-angle-right text-primary me-2"></i>
                                                            {{ $bc['loai_tot_nghiep'] }} <br>
                                                            <i class="fa fa-angle-right text-primary me-2"></i>
                                                            {{ $bc['thoi_gian'] }}
                                                        </p>
                                                    </div>
                                                    <a href="/upload/{{ $bc['photo'] }}"
                                                        data-fancybox="gallery" data-caption="{{ $bc['name'] }}"></a>
                                                </figcaption>
                                            </figure>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-light rounded p-5 mb-4 wow slideInUp" data-wow-delay="0.1s">
                        <h4 class="mb-4">Thông tin ứng viên</h4>
                        {{-- <p><i class="fa fa-angle-right text-primary me-2"></i></p> --}}

                        <p><i class="fa fa-angle-right text-primary me-2"></i>View: {{ $hoSo->view }}</p>
                        <p><i class="fa fa-angle-right text-primary me-2"></i>Cập nhật:
                            {{ Helper::formatDate($hoSo->updated_at) }}</p>
                        <p><i class="fa fa-angle-right text-primary me-2"></i>Email:
                            {{ $hoSo->users->email }}
                        </p>
                        <p class="m-0"><i class="fa fa-angle-right text-primary me-2"></i>Phone:
                            {{ $hoSo->users->phone }}</p>
                    </div>
                    @if ($hoSo->description != '')
                        <div class="bg-light rounded p-5 wow slideInUp" data-wow-delay="0.1s">
                            <h4 class="mb-4">Mục tiêu nghề nghiệp</h4>
                            <p class="m-0">{{ $hoSo->description }}</p>
                        </div>
                    @endif
                </div>
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
        $('.owl-bang-cap').owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        })
        $(document).ready(function() {

            $(document).on("click", "#add_flow", function(event) {

                const id = "{{ $hoSo->id }}";
                const vi_tri = "{{ $hoSo->vi_tri }}";
                const url =
                    "{{ route('hoso.detail', ['slug' => $hoSo->slug, 'id' => $hoSo->id]) }}";
                const item = {
                    'id': id,
                    'vi_tri': vi_tri,
                    'user_id': "{{ auth()->guard('web')->check()? auth()->guard('web')->user()->id: '' }}",
                    'url': url
                }

                if ($('#icon_flow').hasClass('far')) {
                    if (localStorage.getItem('flow_user') == null) {
                        localStorage.setItem('flow_user', '[]');
                    }

                    let old_data = JSON.parse(localStorage.getItem('flow_user'));
                    $.ajax({
                        url: "{{ route('hoso.flow.user') }}",
                        type: "GET",
                        data: {
                            id: id,
                            wishlist: 1,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                $('#icon_flow').addClass('fas').removeClass('far');
                                if (old_data.length <= 50) {
                                    old_data.push(item);

                                } else {
                                    alert('Đã đạt giới hạn lưu')
                                }

                                localStorage.setItem('flow_user', JSON.stringify(
                                    old_data));
                            } else if (data.status == 0) {
                                Errornotification(data.msg);
                            } else {
                                if (confirm(data.msg) == true) {
                                    location.href = "{{ route('employer.login') }}";
                                } else {
                                    return false;
                                }

                            }
                        }
                    });
                } else if ($('#icon_flow').hasClass('fas')) {
                    $.ajax({
                        url: "{{ route('hoso.flow.user') }}",
                        type: "GET",
                        data: {
                            id: id,
                            wishlist: 0,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                $('#icon_flow').addClass('far').removeClass('fas');
                                let data1 = JSON.parse(localStorage.getItem(
                                    'flow_user'));
                                let matches = $.grep(data1, function(data) {
                                    return data.id == id;
                                });

                                if (matches.length) {
                                    var index = data1.indexOf(matches[
                                        0]) //tim vi tri phan tu can xoa
                                    var new_arr = data1.splice(index,
                                        1); //xoa phan tu vua tim dk tai vi tri do
                                    localStorage.setItem('flow_user', JSON.stringify(
                                        data1));
                                }
                            } else if (data.status == 0) {
                                Errornotification(data.msg);
                            } else {
                                if (confirm(data.msg) == true) {
                                    location.href = "{{ route('employer.login') }}";
                                } else {
                                    return false;
                                }

                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
