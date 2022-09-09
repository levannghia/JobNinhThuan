@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Hồ Sơ</h1>

            <div class="row">
                <div class="col-md-3">
                    <form id="form_hosoxinviec">
                        @csrf
                        @foreach ($category_search as $key => $item)
                            <div class="category-search">
                                <h5>{{ $item->title }} <span
                                        class="badge bg-primary">{{ count($item->informations) }}</span></h5>
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
                <div class="col-md-9">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Vị trí ứng tuyển</th>
                                <th scope="col">Khu vực</th>
                                @foreach ($category_noibat as $item)
                                    <th scope="col">{{ $item->title }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="load-data-hoso">
                            @foreach ($hoso_list as $key => $hoso)
                                @php
                                    if (auth()->check() && auth()->user()->type == 2) {
                                        $id_user = auth()->user()->id;
                                        $check_flow = DB::table('user_recruitment')
                                            ->where('hoso_id', $hoso->id)
                                            ->where('user_id', $id_user)
                                            ->first();
                                    }
                                @endphp
                                <tr>
                                    <th scope="row"><i data-add-flow="{{ $hoso->id }}"
                                            data-vitri="{{ $hoso->vi_tri }}" id="star_{{ $hoso->id }}"
                                            class="{{ isset($check_flow) && $check_flow->flow_user == 1 ? 'fas' : 'far' }} fa-star"></i>
                                    </th>
                                    <td>
                                        <a class="name-hoso" href="{{route('hoso.detail',['slug'=>$hoso->slug,'id'=>$hoso->id])}}">{{ $hoso->vi_tri }}</a>
                                        <div class="info-more">
                                            <span>{{$hoso->users->name}} - Update: {{ Helper::formatDate($hoso->updated_at) }}</span>
                                            <span>- View: {{ $hoso->view }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach ($hoso->provinces as $province)
                                            {{ $province->name }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    @foreach ($category_noibat as $category)
                                        @foreach ($category->informations as $info)
                                            @if ($hoso->informations->contains('id', $info->id))
                                                <td>{{ $info->name }}</td>
                                            @endif
                                        @endforeach
                                    @endforeach

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                var _token = $('meta[name="csrf-token"]').attr('content');
                var data_form = $("#form_hosoxinviec").serialize();
                $.ajax({
                    url: "{{ route('hoso.search.information') }}",
                    type: "GET",
                    data: "_token=" + _token + "&" + data_form,
                    beforeSend: function() {},
                    success: function(data) {
                        if (data.length > 0) {
                            $('.load-data-hoso').html(data);
                            // $('.load-more').show();
                        } else {
                            $('.load-data-hoso').html(
                                `<td colspan="{{ count($category_noibat) + 3 }}" style="text-align:center;"><div class="alert alert-warning" role="alert">Không tìm thấy kết quả!</div></td>`
                                );
                            // $('.load-more').hide();
                        }
                    }
                })
            });


            $(document).on("click", "[data-add-flow]", function(event) {
                event.preventDefault();

                const id = $(this).attr('data-add-flow');
                const vi_tri = $(this).attr('data-vitri');
                const url = "";

                const item = {
                    'id': id,
                    'vi_tri': vi_tri,
                    'user_id': "{{ auth()->guard('web')->check()? auth()->guard('web')->user()->id: '' }}",
                    'url': url
                }

                if ($(this).hasClass('far')) {
                    if (localStorage.getItem('flow_user') == null) {
                        localStorage.setItem('flow_user', '[]');
                    }

                    let old_data = JSON.parse(localStorage.getItem('flow_user'));
                    $.ajax({
                        url: "{{ route('hoso.flow.user') }}",
                        type: "GET",
                        data: {
                            id: id,
                            flow_user: 1,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                $('#star_' + id).addClass('fas').removeClass('far');
                                if (old_data.length <= 50) {
                                    old_data.push(item);
                                } else {
                                    Errornotification('Đã đạt giới hạn lưu');
                                }
                                localStorage.setItem('flow_user', JSON.stringify(old_data));
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
                } else if ($(this).hasClass('fas')) {
                    $.ajax({
                        url: "{{ route('hoso.flow.user') }}",
                        type: "GET",
                        data: {
                            id: id,
                            flow_user: 0,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                $('#star_' + id).addClass('far').removeClass('fas');
                                let data1 = JSON.parse(localStorage.getItem('flow_user'));
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
