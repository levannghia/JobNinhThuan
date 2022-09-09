@extends('site.layout')
@section('content')
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Service</h1>

            <div class="wrapper-service" style="position: relative;">
                <div class="row">
                    @foreach ($package as $item)
                        <div class="col-md-4 col-sm-6">
                            <div class="card">
                                <div class="card-container">
                                    <div class="header">
                                        <div class="header-container">
                                            <div class="header-part">
                                                <span class="span-bold">{{ Helper::formatCurrency($item->price) }}</span>
                                                <span class="span-light">/{{ Helper::getEXPValue($item->expiry) }}</span>
                                            </div>
                                            <div>
                                                <span class="span-green">{{ $item->title }}</span>
                                            </div>
                                            <div>
                                                <p class="p-light">
                                                    {{ $item->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="lists">
                                        <ul>
                                            @foreach ($item->services as $service)
                                                <li>
                                                    <div>
                                                        <figure>
                                                            <img src="https://i.postimg.cc/qMnC7h8G/tick-1.png"
                                                                alt="{{ $service->title }}" />
                                                        </figure>
                                                    </div>
                                                    <span class="span-list">{{ $service->title }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="footer">
                                        <div class="button">
                                            <a class="btn btn-add-cart" data-id="{{$item->id}}" href="">Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="loadings"><img src="{{asset('site/img/loading.gif')}}" alt="loading"></div>
            </div>
        </div>
    </div>
@endsection
