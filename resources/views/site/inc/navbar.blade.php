<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
        <h1 class="m-0 text-primary">Job NT</h1>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="/" class="nav-item nav-link active">Home</a>
            <a href="{{route('hoso.index')}}" class="nav-item nav-link">Hồ Sơ</a>
            {{-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Jobs</a>
                <div class="dropdown-menu rounded-0 m-0">
                    <a href="job-list.html" class="dropdown-item">Job List</a>
                    <a href="job-detail.html" class="dropdown-item">Job Detail</a>
                </div>
            </div> --}}
            <a href="{{route('recruitment.index')}}" class="nav-item nav-link">Tin Tuyển Dụng</a>
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->type == 1)
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{auth()->guard('web')->user()->name}}</a>
                <div class="dropdown-menu rounded-0 m-0">   
                    <a href="{{route('seeker.profile.index.profile.user')}}" class="dropdown-item">Thông tin tài khoản</a>                
                    <a href="{{route('seeker.profile.create.profile')}}" class="dropdown-item">Tạo hồ sơ</a>
                    <a href="{{route('seeker.profile.index.profile')}}" class="dropdown-item">Quản lý hồ sơ</a>
                    <a href="{{route('seeker.profile.apply')}}" class="dropdown-item">Việc làm đã ứng tuyển</a>
                    <a href="{{route('seeker.profile.wishlist')}}" class="dropdown-item">Việc làm đã lưu</a>
                    <a href="{{route('seeker.logout')}}" class="dropdown-item">logout</a>
                </div>
            </div>
           
            @elseif (auth()->guard('web')->check() && auth()->guard('web')->user()->type == 2)
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{Helper::getNameEmployer()}}</a>
                <div class="dropdown-menu rounded-0 m-0">                   
                    <a href="{{route('employer.job.create')}}" class="dropdown-item">Tạo tin tuyển dụng</a>
                    <a href="{{route('employer.job.profile')}}" class="dropdown-item">Thông tin tài khoản</a>
                    <a href="{{route('employer.job.index')}}" class="dropdown-item">Quản lý tin tuyển dụng</a>
                    <a href="{{route('employer.job.apply')}}" class="dropdown-item">Hồ sơ ứng tuyển</a>
                    <a href="{{route('employer.job.folow')}}" class="dropdown-item">Hồ sơ đã lưu</a>
                    <a href="{{route('employer.logout')}}" class="dropdown-item">logout</a>
                </div>
            </div>
            @else
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Login</a>
                <div class="dropdown-menu rounded-0 m-0">
                    <a href="{{route('employer.login')}}" class="dropdown-item">Nhà tuyển dụng</a>
                    <a href="{{route('seeker.login')}}" class="dropdown-item">Người tìm việc</a>
                </div>
            </div>
            @endif
            
        </div>
        <a href="/service" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">Service<i class="fa fa-arrow-right ms-3"></i></a>
    </div>
</nav>