@extends('dashboard.layout')
@section('content')
    @include('dashboard.inc.breadcrumb', ['title1' => 'Quyền', 'title2' => 'Thêm'])
    <div class="col-lg-6">
        <div class="card">
            <div class="card-title">
                <h4>Thêm quyền</h4>

            </div>
            <div class="card-body">
                <div class="horizontal-form">
                    <form class="form-horizontal" method="POST" action="{{ route('dashboard.permission.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-2 control-label">name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ old('name') }}" placeholder="name"
                                    name="name">
                            </div>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ old('display_name') }}" placeholder="Display name"
                                    name="display_name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Vai trò</label>
                            <select class="form-control" name="parent_id">
                                <option value="0">Chọn permission cha</option>
                                {!! $htmlOption !!}
                            </select>
                        </div>

                        <div class="card">
                            <input type="checkbox" name="" class="checkbox_wapper" id="">
                            <div class="card-body">
                                @foreach (config('permission.module_childrent') as $item)
                                <div class="form-group">
                                    <label class="col-sm-6">{{$item}}</label>
                                    <input type="checkbox" class="checkbox_childrent" value="{{$item}}" name="module_childrent[]">
                                </div>
                                @endforeach
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
