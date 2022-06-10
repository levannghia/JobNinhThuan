@extends('dashboard.layout')
@section('content')
    @include('dashboard.inc.breadcrumb', ['title1' => 'User hệ thống', 'title2' => 'Cập nhật'])
    <div class="col-lg-6">
        <div class="card">
            <div class="card-title">
                <h4>Quản lý user hệ thống</h4>

            </div>
            <div class="card-body">
                <div class="horizontal-form">
                    <form class="form-horizontal" method="POST" action="{{ route('dashboard.user.update', $user->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ old('name', $user->name) }}"
                                    placeholder="Name" name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" value="{{ old('emale', $user->email) }}"
                                    placeholder="Email" name="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Vai trò</label>
                            <select class="form-control select2-ne" name="role_id[]" multiple>
                                @foreach ($role_list as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $roleOfUser->contains('id',$item->id)}}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="0" {{ Helper::getSelectedValue(0, $user->status) }}>Khóa</option>
                                <option value="1" {{ Helper::getSelectedValue(0, $user->status) }}>Kích hoạt</option>
                            </select>
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
