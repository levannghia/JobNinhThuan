@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Job Listing</h1>
            <div class="row">
                <div class="col-md-3">
                    <form method="get" id="form_recruitment">
                        @csrf
                        @foreach ($category_search as $key => $item)
                            <div class="category-search">
                                <h5>{{ $item->title }} <span class="badge bg-primary">{{count($item->informations)}}</span></h5>
                            </div>
                            <div class="scroll-category">
                                @foreach ($item->informations as $key2 => $information)
                                    <div class="form-check category-item">
                                        <input class="form-check-input" data-information-id="{{ $information->id }}"
                                            name="information[]" type="checkbox" value="{{ $information->id }}"
                                            id="check_info_{{ $information->id }}">
                                        <label class="form-check-label" for="check_info_{{ $information->id }}">
                                            {{ $information->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </form>
                </div>

                <div class="col md-9">
                    <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                        <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                            <li class="nav-item">
                                <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill"
                                    href="#tab-1">
                                    <h6 class="mt-n1 mb-0">Featured</h6>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex align-items-center text-start mx-3 pb-3" data-bs-toggle="pill"
                                    href="#tab-2">
                                    <h6 class="mt-n1 mb-0">Full Time</h6>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex align-items-center text-start mx-3 me-0 pb-3" data-bs-toggle="pill"
                                    href="#tab-3">
                                    <h6 class="mt-n1 mb-0">Part Time</h6>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane fade show p-0 active load-data-recruitment">
                                @foreach ($recruiment_list as $item)
                                    <div class="job-item p-4 mb-4">
                                        <div class="row g-4">
                                            <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                                <img class="flex-shrink-0 img-fluid border rounded"
                                                    src="/upload/images/employer/thumb/{{ $item->Employers->photo }}"
                                                    alt="" style="width: 80px; height: 80px;">
                                                <div class="text-start ps-4">
                                                    <a href="{{route('recruitment.job.detail',['slug' => $item->slug, 'id' => $item->id])}}" title="{{ $item->vi_tri }}">
                                                        <h5 class="mb-3">{{ $item->vi_tri }}</h5>
                                                    </a>
                                                    <span class="text-truncate me-3"><i
                                                            class="fa fa-map-marker-alt text-primary me-2"></i>
                                                        @foreach ($item->provinces as $province)
                                                            {{ $province->name }}
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </span>
                                                    @foreach ($category_noibat as $category)
                                                        @foreach ($category->informations as $info)
                                                            @if ($item->informations->contains('id', $info->id))
                                                                <span class="text-truncate me-3"><i
                                                                        class="fas fa-dot-circle text-primary me-2"></i>{{ $info->name }}</span>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div
                                                class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                                <div class="d-flex mb-3">
                                                    @php
                                                        if (auth()->check() && auth()->user()->type == 1) {
                                                            $id_user = auth()->user()->id;
                                                            $check_wishlist = DB::table('user_recruitment')
                                                                ->where('recruitment_id', $item->id)
                                                                ->where('user_id', $id_user)
                                                                ->first();
                                                        }
                                                    @endphp
                                                    <a class="{{ isset($check_wishlist) && $check_wishlist->wishlist == 1 ? 'fas' : 'far' }} btn btn-light btn-square me-3"
                                                        data-employer-name="{{ $item->Employers->company_name }}"
                                                        data-add-wishlist="{{ $item->id }}"
                                                        data-vitri="{{ $item->vi_tri }}" href=""
                                                        id="btn_wishlist_{{ $item->id }}">
                                                        <i id="icon_wishlist_{{ $item->id }}"
                                                            class="{{ isset($check_wishlist) && $check_wishlist->wishlist == 1 ? 'fas' : 'far' }} fa-heart text-primary"></i></a>
                                                    <a class="btn btn-primary" href="">Apply Now</a>
                                                </div>
                                                <small class="text-truncate"><i
                                                        class="far fa-calendar-alt text-primary me-2"></i>Date Line:
                                                    {{ $item->han_nop }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                            <a class="btn btn-primary load-more py-3 px-5" href="#">Browse More Jobs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            $('[data-information-id]').click(function() {
                var id = $(this).attr('data-information-id');
                // var check = document.getElementById('check_info_' + id).checked;
                var _token = $('meta[name="csrf-token"]').attr('content');
                var data_form = $("#form_recruitment").serialize();
                // if (check == true) {
                    $.ajax({
                        url: "{{ route('recruitment.search.information') }}",
                        type: "GET",
                        data: "_token=" + _token + "&" + data_form,
                        beforeSend: function() {
                        },
                        success: function(data){
                            if(data.length > 0){
                                $('.load-data-recruitment').html(data);
                                $('.load-more').show();
                            }else{
                                $('.load-data-recruitment').html(`<div class="alert alert-warning" role="alert">Không tìm thấy kết quả!</div>`);
                                $('.load-more').hide();
                            }
                        }
                    })
                // }

            })

            $(document).on("click","[data-add-wishlist]",function(event) {
                event.preventDefault();

                const id = $(this).attr('data-add-wishlist');
                const vi_tri = $(this).attr('data-vitri');
                const employer_name = $(this).attr('data-employer-name');
                const url = "";

                const item = {
                    'id': id,
                    'vi_tri': vi_tri,
                    'employer': employer_name,
                    'user_id': "{{ auth()->guard('web')->check() ? auth()->guard('web')->user()->id: '' }}",
                    'url': url
                }

                if ($(this).hasClass('far')) {
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
                                $('#icon_wishlist_' + id).addClass('fas').removeClass('far');
                                $('#btn_wishlist_' + id).addClass('fas').removeClass('far');
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
                } else if ($(this).hasClass('fas')) {
                    $.ajax({
                        url: "{{ route('recruitment.wishlist') }}",
                        type: "GET",
                        data: {
                            id: id,
                            wishlist: 0,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                $('#icon_wishlist_' + id).addClass('far').removeClass('fas');
                                $('#btn_wishlist_' + id).addClass('far').removeClass('fas');
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
