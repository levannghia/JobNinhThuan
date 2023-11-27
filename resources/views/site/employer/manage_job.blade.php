@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Hồ Sơ Tuyển Dụng</h1>

            <table class="table table-hover" id="profileTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vị trí tuyển dụng</th>
                        <th scope="col">View</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Đẩy tin</th>
                        <th scope="col">Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recruitment_list as $key => $item)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td><a href="{{ route('employer.job.edit', ['slug' => $item->slug, 'id' => $item->id]) }}"
                                    title="Chỉnh sửa hồ sơ {{ $item->vi_tri }}">{{ $item->vi_tri }}</a></td>
                            <td>{{ $item->view }}</td>
                            <td>{{ Helper::formatDate($item->created_at) }}</td>
                            <td><span id="badge_{{ $item->id }}"
                                    class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-dark' }}"
                                    data-status="{{ $item->id }}">Hiển thị</span></td>
                            <td>
                                @can('push_news')
                                    <a href="{{route('service.push.news')}}" class="btn btn-outline-success btn-sm">Đẩy tin 2/2</a>
                                @endcan
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Select
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                                href="{{ route('employer.job.edit', ['slug' => $item->slug, 'id' => $item->id]) }}">Chỉnh sửa</a></li>
                                        <li><a class="dropdown-item" href="#" data-name="{{$item->vi_tri}}" data-delete-id="{{ $item->id }}">Xóa</a>
                                        </li>
                                    </ul>
                                </div>
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
                    text: "Tin tuyển dụng: " + name,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "GET",
                            headers: {
                                'X-CSRF-TOKEN': _token
                            },
                            url: "{{ route('employer.job.delete') }}",
                            contentType: false,
                            dataType: "json",
                            data: {
                                id: id,
                                status: 2
                            },
                            success: function(data) {
                                if (data.status == 1) {
                                    window.location.reload();
                                } else {
                                    let text = data.msg;
                                    Errornotification(text);
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
                    url: "{{ route('employer.job.update.status') }}",
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
                    url: "{{ route('employer.job.update.status') }}",
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
