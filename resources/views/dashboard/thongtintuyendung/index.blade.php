@extends('dashboard.layout')
@section('content')
@include('dashboard.inc.breadcrumb',['title1'=>'Thông tin tuyển dụng', 'title2' =>''])
<section id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-title">
                    <h4>Thông tin tuyển dụng </h4>
                    
                </div>
                <div class="bootstrap-data-table-panel">
                    <div class="table-responsive">
                        <table id="row-select" class="display table table-borderd table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="" id=""></th>
                                    <th>Tiêu đề</th>
                                    <th>Type</th>
                                    <th>Trạng thái</th>
                                    <th>Start date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($thong_tin_TD_list as $item)
                                <tr>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        @foreach (config('thongtintuyendung.thongtintuyendung') as $thongtin)
                                            @if ($thongtin['type'] === $item->type )
                                                {{$thongtin['name']}}
                                            @endif                               
                                        @endforeach
                                    </td>
                                    <td>{{Helper::getStatusValue($item->status)}}</td>
                                    <td>{{Helper::formatDate($item->created_at)}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                              Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                              <a class="dropdown-item" href="{{route('dashboard.thong-tin-tuyen-dung.edit',$item->id)}}">Edit</a>
                                              <a class="dropdown-item" href="#">View</a>
                                              <a class="dropdown-item" href="#">Delete</a>
                                            </div>
                                          </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Tiêu đề</th>
                                    <th>Type</th>
                                    <th>status</th>
                                    <th>Start date</th>
                                    {{-- <th></th> --}}
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /# card -->
        </div>
        <!-- /# column -->
    </div>
    <!-- /# row -->
</section>
@endsection