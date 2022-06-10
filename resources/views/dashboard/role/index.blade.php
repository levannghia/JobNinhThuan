@extends('dashboard.layout')
@section('content')
@include('dashboard.inc.breadcrumb',['title1'=>'Vai trò', 'title2' =>''])
<section id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-title">
                    <h4>Quản lý vai trò </h4>
                    <a href="{{route('dashboard.vai-tro.create')}}" class="btn btn-primary m-b-10 m-l-5">Thêm</a>
                </div>
                
                <div class="bootstrap-data-table-panel">
                    <div class="table-responsive">
                        <table id="row-select" class="display table table-borderd table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="" id=""></th>
                                    <th>name</th>
                                    <th>Display name</th>
                                    <th>Start date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($role as $item)
                                <tr>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->display_name}}</td>
                                    <td>{{Helper::formatDate($item->created_at)}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                              Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                              <a class="dropdown-item" href="{{route('dashboard.vai-tro.update',$item->id)}}">Edit</a>
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
                                    <th>Name</th>
                                    <th>status</th>
                                    <th>date</th>
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