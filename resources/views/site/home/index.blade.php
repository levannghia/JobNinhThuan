@extends('site.layout')
@section('content')
    <!-- Category Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Explore By Category</h1>
            <div class="row g-4">
                @foreach (Helper::categoryShowIndex() as $key => $item)
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.{{$key+1}}s">
                    <a class="cat-item rounded p-4" href="">
                        <img class="mb-4" style="max-width: 80px; filter: invert(61%) sepia(82%) saturate(5002%) hue-rotate(132deg) brightness(96%) contrast(102%);" src="/upload/{{ $item->photo }}" alt="{{$item->name}}">
                        <h6 class="mb-3">{{$item->name}}</h6>
                        <p class="mb-0">{{$item->recruitments_count}} Vacancy</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Category End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="row g-0 about-bg rounded overflow-hidden">
                        <div class="col-6 text-start">
                            <img class="img-fluid w-100" src="img/about-1.jpg">
                        </div>
                        <div class="col-6 text-start">
                            <img class="img-fluid" src="img/about-2.jpg" style="width: 85%; margin-top: 15%;">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid" src="img/about-3.jpg" style="width: 85%;">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid w-100" src="img/about-4.jpg">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-4">We Help To Get The Best Job And Find A Talent</h1>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Tempor erat elitr rebum at clita</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Aliqu diam amet diam et eos</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Clita duo justo magna dolore erat amet</p>
                    <a class="btn btn-primary py-3 px-5 mt-3" href="">Read More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Jobs Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Job Listing</h1>
            <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill"
                            href="#tab-1">
                            <h6 class="mt-n1 mb-0">Nổi bật</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 pb-3" data-bs-toggle="pill" href="#tab-2">
                            <h6 class="mt-n1 mb-0">Tuyển gấp</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center text-start mx-3 me-0 pb-3" data-bs-toggle="pill"
                            href="#tab-3">
                            <h6 class="mt-n1 mb-0">Việc làm</h6>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        @foreach ($hot_recruitment as $item)
                            <div class="job-item p-4 mb-4">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid border rounded"
                                            src="/upload/{{ $item->Employers->photo }}" alt=""
                                            style="width: 80px; height: 80px;">
                                        <div class="text-start ps-4">
                                            <a href="{{route('recruitment.job.detail',['slug' => $item->slug, 'id' => $item->id])}}" title="{{ $item->vi_tri }}"><h5 class="mb-3">{{ $item->vi_tri }}</h5></a>
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
                                                data-add-wishlist="{{ $item->id }}" data-vitri="{{ $item->vi_tri }}"
                                                href="" id="btn_wishlist_{{ $item->id }}">
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
                        <a class="btn btn-primary py-3 px-5" href="">Browse More Jobs</a>
                    </div>
                    <div id="tab-2" class="tab-pane fade show p-0">
                        @foreach ($urgent_recruitment as $item)
                            <div class="job-item p-4 mb-4">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid border rounded"
                                            src="/upload/{{ $item->Employers->photo }}" alt=""
                                            style="width: 80px; height: 80px;">
                                        <div class="text-start ps-4">
                                            <a href="{{route('recruitment.job.detail',['slug' => $item->slug, 'id' => $item->id])}}" title="{{ $item->vi_tri }}"><h5 class="mb-3">{{ $item->vi_tri }}</h5></a>
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
                                                data-add-wishlist="{{ $item->id }}" data-vitri="{{ $item->vi_tri }}"
                                                href="" id="btn_wishlist_{{ $item->id }}">
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
                        <a class="btn btn-primary py-3 px-5" href="">Browse More Jobs</a>
                    </div>
                    <div id="tab-3" class="tab-pane fade show p-0">
                        @foreach ($recruitment as $item)
                            <div class="job-item p-4 mb-4">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid border rounded"
                                            src="/upload/{{ $item->Employers->photo }}" alt=""
                                            style="width: 80px; height: 80px;">
                                        <div class="text-start ps-4">
                                            <a href="{{route('recruitment.job.detail',['slug' => $item->slug, 'id' => $item->id])}}" title="{{ $item->vi_tri }}"><h5 class="mb-3">{{ $item->vi_tri }}</h5></a>
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
                                                data-add-wishlist="{{ $item->id }}" data-vitri="{{ $item->vi_tri }}"
                                                href="" id="btn_wishlist_{{ $item->id }}">
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
                        <a class="btn btn-primary py-3 px-5" href="">Browse More Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Jobs End -->


    <!-- Testimonial Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <h1 class="text-center mb-5">Our Clients Say!!!</h1>
            <div class="owl-carousel testimonial-carousel">
                <div class="testimonial-item bg-light rounded p-4">
                    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                    <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-1.jpg"
                            style="width: 50px; height: 50px;">
                        <div class="ps-3">
                            <h5 class="mb-1">Client Name</h5>
                            <small>Profession</small>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-4">
                    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                    <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-2.jpg"
                            style="width: 50px; height: 50px;">
                        <div class="ps-3">
                            <h5 class="mb-1">Client Name</h5>
                            <small>Profession</small>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-4">
                    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                    <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-3.jpg"
                            style="width: 50px; height: 50px;">
                        <div class="ps-3">
                            <h5 class="mb-1">Client Name</h5>
                            <small>Profession</small>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-4">
                    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                    <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-4.jpg"
                            style="width: 50px; height: 50px;">
                        <div class="ps-3">
                            <h5 class="mb-1">Client Name</h5>
                            <small>Profession</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
@endsection
@push('script')
    <script>
        $(document).on("click", "[data-add-wishlist]", function(event) {
            event.preventDefault();

            const id = $(this).attr('data-add-wishlist');
            const vi_tri = $(this).attr('data-vitri');
            const employer_name = $(this).attr('data-employer-name');
            const url = "";

            const item = {
                'id': id,
                'vi_tri': vi_tri,
                'employer': employer_name,
                'user_id': "{{ auth()->guard('web')->check()? auth()->guard('web')->user()->id: '' }}",
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
    </script>
@endpush
