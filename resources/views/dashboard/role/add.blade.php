@extends('dashboard.layout')
@section('content')
    @include('dashboard.inc.breadcrumb', ['title1' => 'Vai trò', 'title2' => 'Thêm'])
    <div class="col-lg-6">
        <div class="card">
            <div class="card-title">
                <h4>Cập nhập vai trò</h4>

            </div>
            <div class="card-body">
                <div class="horizontal-form">
                    <form class="form-horizontal" method="POST" action="{{ route('dashboard.vai-tro.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-2 control-label">name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ old('name') }}" placeholder="name"
                                    name="name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Display name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ old('name') }}"
                                    placeholder="display name" name="display_name">
                            </div>
                        </div>

                       
                                <table class="table table_role">
                                    <thead>
                                        <tr>
                                            <th scope="col"><input type="checkbox" name="" class="checkbox_all" id=""></th>
                                            <th scope="col">Tên module</th>
                                            @foreach (config('permission.module_childrent') as $module_childrent)
                                                <th scope="col">{{ $module_childrent }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permission as $item)
                                            <tr>
                                                <th scope="row"><input type="checkbox" name="" class="checkbox_table" id=""></th>
                                                <td>{{ $item->name }}</td>
                                        
                                                    @foreach ($item->permissionChildrent as $value)
                                                        <td><input type="checkbox" value="{{$value->id}}" class="checkbox_childrent" name="permission_id[]"></td>
                                                    @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                       

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
