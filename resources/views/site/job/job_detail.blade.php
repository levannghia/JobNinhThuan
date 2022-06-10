@extends('site.layout')
@section('content')
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row gy-5 gx-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-5">
                    <img class="flex-shrink-0 img-fluid border rounded" src="/upload/images/employer/thumb/{{ $recruitment->Employers->photo }}" alt="{{ $recruitment->Employers->company_name }}" style="width: 80px; height: 80px;">
                    <div class="text-start ps-4">
                        <h3 class="mb-3">{{$recruitment->vi_tri}}</h3>
                        <span id="add_wishlist" class="text-truncate me-3"><i id="icon_wishlist" class="{{ isset($check_wishlist) && $check_wishlist->wishlist == 1 ? 'fas' : 'far' }} fa-heart text-primary me-2"></i>Lưu việc làm</span>
                        <span class="text-truncate me-3"><button type="button" class="btn btn-outline-primary">Nộp hồ sơ</button></span>
                        <span class="text-truncate me-3"><button type="button" onclick="window.print();" class="btn btn-outline-primary">In việc làm</button></span>
                    </div>
                </div>

                <div class="infor-recruitment mb-5">
                    <div class="row">
                        
                            @foreach ($category_list as $category)
                               @foreach ($category->informations as $info)
                               @if ($recruitment->informations->contains('id', $info->id))
                               <div class="col-md-6 mb-3">
                               <h5>{{$category->name}}</h5>
                                   <span class="text-truncate me-3"><i class="fa fa-angle-right text-primary me-2"></i>{{ $info->name }}</span>
                                </div>
                               @endif
                               @endforeach
                            @endforeach
                            <div class="col-md-6 mb-3">
                                <h5>Địa điểm làm việc</h5>
                            <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>
                                @foreach ($recruitment->provinces as $province)
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
                    <h4 class="mb-3">Mô tả công việc</h4>
                    <p>{{$recruitment->description}}</p>
                    <h4 class="mb-3">Yêu cầu</h4>
                    <p>{{$recruitment->yeu_cau}}</p>
                    <h4 class="mb-3">Quyền lợi</h4>
                    <p>{{$recruitment->quyen_loi}}</p>
                    <h4 class="mb-3">Hồ sơ ứng tuyển gồm</h4>
                    <p>{{$recruitment->ho_so_gom}}</p>
                    @if (isset($employer))
                    <h4 class="mb-3">Thông tin liên hệ</h4>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-angle-right text-primary me-2"></i>Người liên hệ: {{$employer->name}}</li>
                        <li><i class="fa fa-angle-right text-primary me-2"></i>Địa chỉ: {{$employer->address}}</li>
                        <li><i class="fa fa-angle-right text-primary me-2"></i>Số điện thoại: <a href="tel:{{$employer->phone}}">{{$employer->phone}}</a></li>
                        <li><i class="fa fa-angle-right text-primary me-2"></i>Email: <a href="mailto:{{$employer->email}}">{{$employer->email}}</a></li>
                    </ul>
                    @endif
                </div>

                <div class="">
                    <h4 class="mb-4">Apply For The Job</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control" placeholder="Your Name">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="email" class="form-control" placeholder="Your Email">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control" placeholder="Portfolio Website">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="file" class="form-control bg-white">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" rows="5" placeholder="Coverletter"></textarea>
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
                    
                    <p><i class="fa fa-angle-right text-primary me-2"></i>View: {{$recruitment->view}}</p>
                    <p><i class="fa fa-angle-right text-primary me-2"></i>Published On: {{Helper::formatDate($recruitment->created_at)}}</p>
                    <p><i class="fa fa-angle-right text-primary me-2"></i>Số lượng tuyển: {{$recruitment->so_luong}} </p>
                    <p class="m-0"><i class="fa fa-angle-right text-primary me-2"></i>Date Line: {{$recruitment->han_nop}}</p>
                </div>
                <div class="bg-light rounded p-5 wow slideInUp" data-wow-delay="0.1s">
                    <h4 class="mb-4">Company Detail</h4>
                    <p class="m-0">{{$recruitment->description}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        $(document).ready(function(){
            $(document).on("click","#add_wishlist",function(event) {

                const id = "{{$recruitment->id}}";
                const vi_tri = "{{$recruitment->vi_tri}}";
                const employer_name = "{{ $recruitment->Employers->company_name }}";
                const url = "{{route('recruitment.job.detail',['slug' => $recruitment->slug, 'id' => $recruitment->id])}}";

                const item = {
                    'id': id,
                    'vi_tri': vi_tri,
                    'employer': employer_name,
                    'user_id': "{{ auth()->guard('web')->check() ? auth()->guard('web')->user()->id : '' }}",
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