@if (Session::has('type'))
    <div class="alert alert-{{Session::get('type')}} alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        <strong>{{config('app.name')}}!</strong> {{Session::get('flash_message')}}
    </div>
@endif
