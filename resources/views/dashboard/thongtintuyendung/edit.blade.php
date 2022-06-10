@extends('dashboard.layout')
@section('content')
@include('dashboard.inc.breadcrumb',['title1' => 'Thông tin tuyển dụng','title2' => 'Cập nhật'])
<div class="col-lg-6">
    <div class="card">
        <div class="card-title">
            <h4>Horizontal Form</h4>
            
        </div>
        <div class="card-body">
            <div class="horizontal-form">
                <form class="form-horizontal" method="POST" action="{{route('dashboard.thong-tin-tuyen-dung.update',$thong_tin_TD->id)}}">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tiêu đề</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{old('name',$thong_tin_TD->name)}}" placeholder="Tiêu đề" name="name">
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control" name="type">
                            <option value="">Chọn loại thông tin</option>  
                            @foreach (config('thongtintuyendung.thongtintuyendung') as $thongtin)
                                <option value="{{$thongtin['type']}}" {{$thongtin['type'] == $thong_tin_TD->type ? 'selected' : ''}}>{{$thongtin['name']}}</option>                               
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Hiển thị</label>
                        <div class="col-sm-10">
                            <input type="checkbox" class="form-control" {{$thong_tin_TD->status == 1 ? 'checked' : ''}} name="status">
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