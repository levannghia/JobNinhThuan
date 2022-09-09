@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Hồ Sơ Ứng Tuyển</h1>

            <table class="table table-hover" id="profileTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vị trí tuyển dụng</th>
                        <th scope="col">Hồ sơ ứng tuyển</th>
                        <th scope="col">Hạn nộp</th>
                        <th scope="col">Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoso_apply as $stt => $item)      
                            <tr>
                                <th scope="row">{{ $stt + 1 }}</th>
                                <td><a href="{{ route('recruitment.job.detail', ['slug' => $item->slug, 'id' => $item->id]) }}"
                                        title="Chỉnh sửa hồ sơ {{ $item->vi_tri }}">{{ $item->vi_tri }}</a>
                                </td>
                                <td>
                                   <div class="box-apply">
                                    @foreach ($item->hoSoApply as $key => $hoso)
                                    <div class="hoso-apply">
                                        <a class="name-hoso" href="{{route('hoso.detail',['slug'=>$hoso->slug,'id'=>$hoso->id])}}">{{ $hoso->vi_tri }}: <span>{{Helper::formatDate($hoso->pivot->date_apply)}}</span></a>
                                    </div>
                                    @endforeach
                                   </div>
                                </td>
                                <td>{{ $item->han_nop }}</td>
                                <td>
                                    <a href="" title="Xóa tin tuyển dụng {{ $item->vi_tri }}" data-name="{{ $item->vi_tri }}" data-delete-id="{{ $item->id }}"><i
                                            class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#profileTable').DataTable();
        });
        let _token = $('meta[name="csrf-token"]').attr('content');

        $('[data-delete-id]').click(function(event) {
            event.preventDefault();
            let name = $(this).attr('data-name');
            var id = $(this).attr('data-delete-id');

            swal({
                    title: "Are you sure?",
                    text: "Hủy ứng tuyển vị trí: " + name,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': _token,
                            },
                            url: "{{ route('recruitment.update.apply') }}",
                            type: "POST",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                if (data.status == 1) {
                                    window.location.reload();
                                } else {
                                    Errornotification(data.msg);
                                }

                            }
                        });
                        swal("Poof! Your imaginary file has been deleted!", {
                            icon: "success",
                        });
                    } else {
                        swal("Your imaginary file is safe!");
                    }
                });

        })

        $('[data-status]').click(function() {

            var id = $(this).attr('data-status');
            if ($(this).hasClass('bg-success')) {
                $.ajax({
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': _token
                    },
                    url: "{{ route('seeker.profile.update.status') }}",
                    contentType: false,
                    dataType: "json",
                    data: {
                        id: id,
                        status: 0
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            $('#badge_' + id).addClass('bg-dark').removeClass('bg-success');
                        } else {
                            let text = data.msg;
                            Errornotification(text);
                        }
                    }
                });
            } else if ($(this).hasClass('bg-dark')) {
                $.ajax({
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': _token
                    },
                    url: "{{ route('seeker.profile.update.status') }}",
                    contentType: false,
                    dataType: "json",
                    data: {
                        id: id,
                        status: 1
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status == 1) {
                            $('#badge_' + id).addClass('bg-success').removeClass('bg-dark');
                        } else {
                            let text = data.msg;
                            Errornotification(text);
                        }
                    }
                });
            } else {
                return false;
            }
        })
    </script>
@endpush
