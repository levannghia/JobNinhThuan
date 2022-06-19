@extends('site.layout')
@section('content')
    @php
    if (
        auth()
            ->guard('web')
            ->check() &&
        auth()
            ->guard('web')
            ->user()->type == 1
    ) {
        $check_apply = DB::table('user_recruitment')
            ->where('recruitment_id', $hoSo->id)
            ->where('user_id', auth()->user()->id)
            ->first();
    }
    @endphp

    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gy-5 gx-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-5">
                        @if (@getimagesize($hoSo->users->photo))
                        <img class="flex-shrink-0 img-fluid border rounded"
                            src="{{ $hoSo->users->photo }}"
                            alt="{{ $hoSo->users->name }}" style="width: 80px; height: 80px;">
                        @elseif ($hoSo->users->photo == null)
                            ok
                        @else
                        <img class="flex-shrink-0 img-fluid border rounded"
                        src="/upload/images/seeker/thumb/{{ $hoSo->users->photo }}"
                        alt="{{ $hoSo->users->name }}" style="width: 80px; height: 80px;">
                        @endif
                        <div class="text-start ps-4">
                            <h3 class="mb-3">{{ $hoSo->vi_tri }}</h3>
                            <span id="add_wishlist" class="text-truncate me-3"><i id="icon_wishlist"
                                    class="{{ isset($check_wishlist) && $check_wishlist->wishlist == 1 ? 'fas' : 'far' }} fa-heart text-primary me-2"></i>Lưu
                                việc làm</span>
                            <span class="text-truncate me-3 show_btn_apply">
                                @if (isset($check_apply) && $check_apply->hoso_id > 0)
                                    <button type="button" class="btn btn-primary">Đã ứng tuyển</button>
                                @else
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#applyModal">Nộp hồ sơ</button>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="infor-recruitment mb-5">
                        <div class="row">

                            @foreach ($category_list as $category)
                                @foreach ($category->informations as $info)
                                    @if ($hoSo->informations->contains('id', $info->id))
                                        <div class="col-md-6 mb-3">
                                            <h5>{{ $category->name }}</h5>
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

                    {{-- <div class="mb-5">
                        <h4 class="mb-3">Mô tả công việc</h4>
                        <p>{{ $hoSo->description }}</p>
                        <h4 class="mb-3">Yêu cầu</h4>
                        <p>{{ $hoSo->yeu_cau }}</p>
                        <h4 class="mb-3">Quyền lợi</h4>
                        <p>{{ $hoSo->quyen_loi }}</p>
                        <h4 class="mb-3">Hồ sơ ứng tuyển gồm</h4>
                        <p>{{ $hoSo->ho_so_gom }}</p>
                        <h4 class="mb-3">Thông tin liên hệ</h4>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Người liên hệ:
                                {{ $hoSo->Employers->name }}
                            </li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Địa chỉ:
                                {{ $hoSo->Employers->address }}
                            </li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Số điện thoại: <a
                                    href="tel:{{ $hoSo->Employers->phone }}">{{ $hoSo->Employers->phone }}</a>
                            </li>
                            <li><i class="fa fa-angle-right text-primary me-2"></i>Email: <a
                                    href="mailto:{{ $hoSo->Employers->email }}">{{ $hoSo->Employers->email }}</a>
                            </li>
                        </ul>
                    </div> --}}

                    <div class="">
                        <h4 class="mb-4">Apply For The Job</h4>
                        <form method="POST" action="{{ route('recruitment.apply.for.email', $hoSo->id) }}"
                            class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        placeholder="Your Name" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                        placeholder="Your Email" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="form-control" name="portfolio"
                                        value="{{ old('portfolio') }}" placeholder="Portfolio Website">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="file" name="file" class="form-control bg-white" required>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" rows="5" name="content" placeholder="Coverletter">{{ old('content') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Apply Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light rounded p-5 mb-4 wow slideInUp" data-wow-delay="0.1s">
                        <h4 class="mb-4">Job Summery</h4>
                        {{-- <p><i class="fa fa-angle-right text-primary me-2"></i></p> --}}

                        <p><i class="fa fa-angle-right text-primary me-2"></i>View: {{ $hoSo->view }}</p>
                        <p><i class="fa fa-angle-right text-primary me-2"></i>Published On:
                            {{ Helper::formatDate($hoSo->created_at) }}</p>
                        <p><i class="fa fa-angle-right text-primary me-2"></i>Số lượng tuyển:
                            {{ $hoSo->so_luong }}
                        </p>
                        <p class="m-0"><i class="fa fa-angle-right text-primary me-2"></i>Date Line:
                            {{ $hoSo->han_nop }}</p>
                    </div>
                    <div class="bg-light rounded p-5 wow slideInUp" data-wow-delay="0.1s">
                        <h4 class="mb-4">Company Detail</h4>
                        <p class="m-0">{{ $hoSo->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nộp Hồ Sơ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="form_apply">
                        @csrf
                        <div class="modal-body">
                            @if (auth()->check() && auth()->user()->type == 1)
                                @foreach ($hoso_list as $key => $item)
                                    <div class="form-check">
                                        <input class="form-check-input" value="{{ $item->id }}"
                                            {{ $key == 0 ? 'checked' : '' }} type="radio" name="hoso_id"
                                            id="hoso_{{ $item->id }}">
                                        <label class="form-check-label" for="hoso_{{ $item->id }}">
                                            {{ $item->vi_tri }}
                                        </label>
                                    </div>
                                @endforeach
                            @elseif (auth()->check() && auth()->user()->type == 2)
                                <p>Chức năng này chỉ dành cho người tìm việc</p>
                            @else
                                <p>Vui lòng <a href="{{ route('seeker.login') }}" class="tooltip-test"
                                        title="Đăng nhập">đăng
                                        nhập</a> để tiếp tục.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            @if (auth()->check() && auth()->user()->type == 1)
                                <button type="button" id="btn_apply" class="btn btn-primary">Gửi</button>
                            @endif
                        </div>
                    </form>
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
        $(document).ready(function() {

            $('#btn_apply').click(function() {
                var form_data = $('#form_apply').serialize();

                $.ajax({
                    url: "{{ route('recruitment.apply', $hoSo->id) }}",
                    type: "POST",
                    data: form_data,
                    success: function(data) {
                        if (data.status == 1) {
                            Successnotification(data.msg);
                            $('#applyModal').modal('hide')
                            $('.show_btn_apply').html(
                                '<button type="button" class="btn btn-primary">Đã ứng tuyển</button>'
                                );
                        } else {
                            Errornotification(data.msg);
                        }
                    }
                })
            })

            $(document).on("click", "#add_wishlist", function(event) {

                const id = "{{ $hoSo->id }}";
                const vi_tri = "{{ $hoSo->vi_tri }}";
                const url =
                    "{{ route('recruitment.job.detail', ['slug' => $hoSo->slug, 'id' => $hoSo->id]) }}";
                const item = {
                    'id': id,
                    'vi_tri': vi_tri,
                    'employer': employer_name,
                    'user_id': "{{ auth()->guard('web')->check()? auth()->guard('web')->user()->id: '' }}",
                    'url': url
                }

                if ($('#icon_wishlist').hasClass('far')) {
                    if (localStorage.getItem('wishlist_recruitment') == null) {
                        localStorage.setItem('wishlist_recruitment', '[]');
                    }

                    let old_data = JSON.parse(localStorage.getItem('wishlist_recruitment'));
                    $.ajax({
                        url: "{{ route('recruitment.wishlist') }}",
                        type: "GET",
                        data: {
                            id: id,
                            wishlist: 1,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                $('#icon_wishlist').addClass('fas').removeClass('far');
                                if (old_data.length <= 50) {
                                    old_data.push(item);

                                } else {
                                    alert('Đã đạt giới hạn lưu')
                                }

                                localStorage.setItem('wishlist_recruitment', JSON.stringify(
                                    old_data));
                            } else if (data.status == 0) {
                                Errornotification(data.msg);
                            } else {
                                if (confirm(data.msg) == true) {
                                    location.href = "{{ route('seeker.login') }}";
                                } else {
                                    return false;
                                }

                            }
                        }
                    });
                } else if ($('#icon_wishlist').hasClass('fas')) {
                    $.ajax({
                        url: "{{ route('recruitment.wishlist') }}",
                        type: "GET",
                        data: {
                            id: id,
                            wishlist: 0,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                $('#icon_wishlist').addClass('far').removeClass('fas');
                                let data1 = JSON.parse(localStorage.getItem(
                                    'wishlist_recruitment'));
                                let matches = $.grep(data1, function(data) {
                                    return data.id == id;
                                });

                                if (matches.length) {
                                    var index = data1.indexOf(matches[
                                        0]) //tim vi tri phan tu can xoa
                                    var new_arr = data1.splice(index,
                                        1); //xoa phan tu vua tim dk tai vi tri do
                                    localStorage.setItem('wishlist_recruitment', JSON.stringify(
                                        data1));
                                }
                            } else if (data.status == 0) {
                                Errornotification(data.msg);
                            } else {
                                if (confirm(data.msg) == true) {
                                    location.href = "{{ route('seeker.login') }}";
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
