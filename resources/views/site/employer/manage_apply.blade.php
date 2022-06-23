@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Hồ Sơ Ứng Tuyển</h1>

            <table class="table table-hover" id="profileTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vị trí ứng tuyển</th>
                        <th scope="col">Họ & tên</th>
                        <th scope="col">Ngày ứng tuyển</th>
                        <th scope="col">Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_apply as $item)
                        @foreach ($item->recruitmentApply as $key => $wishlist)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td><a href="{{ route('recruitment.job.detail', ['slug' => $wishlist->slug, 'id' => $wishlist->id]) }}"
                                        title="Chỉnh sửa hồ sơ {{ $wishlist->vi_tri }}">{{ $wishlist->vi_tri }}</a>
                                </td>
                                <td>{{ $wishlist->Employers->company_name }}</td>
                                <td>{{ $wishlist->han_nop }}</td>
                                <td>
                                    <a href="" title="Xóa tin tuyển dụng {{ $wishlist->vi_tri }}" data-name="{{ $wishlist->vi_tri }}" data-delete-id="{{ $wishlist->id }}"><i
                                            class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
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
