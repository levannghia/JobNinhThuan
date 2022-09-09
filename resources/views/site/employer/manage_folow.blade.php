@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Hồ Sơ Đã Lưu</h1>

            <table class="table table-hover" id="profileTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vị trí ứng tuyển</th>
                        <th scope="col">Tên ứng viên</th>
                        <th scope="col">Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_folow as $item)
                        @foreach ($item->hoSoFolow as $key => $folow)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td><a href="{{ route('hoso.detail', ['slug' => $folow->slug, 'id' => $folow->id]) }}"
                                        title="Xem hồ sơ {{ $folow->vi_tri }}">{{ $folow->vi_tri }}</a>
                                </td>
                                <td>{{$folow->users->name }}</td>
                                <td>
                                    <a href="" title="Xóa tin hồ sơ {{ $folow->vi_tri }}" data-name="{{ $folow->vi_tri }}" data-delete-id="{{$folow->id }}"><i
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
                    text: "Xóa hồ sơ: " + name,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('hoso.flow.user') }}",
                            type: "GET",
                            data: {
                                id: id,
                                flow_user: 0,
                            },
                            success: function(data) {
                                if (data.status == 1) {
                                    let data1 = JSON.parse(localStorage.getItem('flow_user'));
                                    let matches = $.grep(data1, function(data) {
                                        return data.id == id;
                                    });

                                    if (matches.length) {
                                        var index = data1.indexOf(matches[0])
                                        var new_arr = data1.splice(index, 1);
                                        localStorage.setItem('flow_user', JSON.stringify(
                                            data1));
                                    }
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
    </script>
@endpush
