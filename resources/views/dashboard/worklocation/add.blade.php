@extends('dashboard.layout')
@section('content')
@include('dashboard.inc.breadcrumb',['title1' => 'Địa điểm làm việc','title2' => 'Thêm'])
<div class="col-lg-6">
    <div class="card">
        <div class="card-title">
            <h4>Địa điểm làm việc</h4>
            
        </div>
        <div class="card-body">
            <div class="horizontal-form">
                <form class="form-horizontal" method="POST" action="{{route('dashboard.dia-diem-lam-viec.store')}}">
                    @csrf
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tiêu đề</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{old('name')}}" placeholder="Tiêu đề" name="name">
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Hiển thị</label>
                        <div class="col-sm-10">
                            <input type="checkbox" class="form-control" checked name="status">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection