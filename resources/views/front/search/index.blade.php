@extends('layouts.app')

@section('page', app('request')->input('query'))

@section('content')
{{-- <section class="listing-header">
    <div class="container">
        <div class="row flex-sm-row-reverse align-items-center">
            <div class="col-sm-6 d-none d-sm-block">
                <img src="" class="img-fluid">
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{app('request')->input('query')}}</li>
                    </ol>
                </nav>
                <h1>{{ app('request')->input('query') }}</h1>
            </div>
        </div>
    </div>
</section> --}}

@if (count($data) > 0)
<section class="listing-block mt-5">
    <div class="container">
        <div class="listing-block__meta">
            <p>Displaying search result for <b>{{app('request')->input('query')}}</b></p>
            {{-- <div class="filter">
                <div class="filter__toggle">
                    Filter
                </div>
                <div class="filter__data"></div>
            </div>
            <div class="sorting">
                Sort By:
                <select>
                    <option>New Arrivals</option>
                    <option>Best Sellers</option>
                    <option>Price: Low To High</option>
                    <option>Price: High To Low</option>
                </select>
            </div> --}}
        </div>

        <div class="product__wrapper">
            {{-- <div class="product__filter">
                <div class="product__filter__bar">
                    <div class="filter__close">
                        <i class="fal fa-times"></i>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-12 mb-3 mb-sm-0">
                            <h4>Categories</h4>
                            <ul class="product__filter__bar-list">
                                @foreach($categories as $categoryKey => $categoryValue)
                                    <li><label><input type="checkbox" pro-filter="{{$categoryValue->name}}"><i></i> {{$categoryValue->name}}</label></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6 col-sm-12 mb-3 mb-sm-0">
                            <h4>Range</h4>
                            <ul class="product__filter__bar-list">
                                <li><label><input type="checkbox" pro-filter="Innerwear"><i></i> Innerwear</label></li>
                                <li><label><input type="checkbox" pro-filter="Outerwear"><i></i> Outerwear</label></li>
                                <li><label><input type="checkbox" pro-filter="Footkins"><i></i> Footkins</label></li>
                                <li><label><input type="checkbox" pro-filter="Thermals"><i></i> Thermals</label></li>
                                <li><label><input type="checkbox" pro-filter="Socks"><i></i> Socks</label></li>
                                <li><label><input type="checkbox" pro-filter="Winter Wear"><i></i> Winter Wear</label></li>
                                <li><label><input type="checkbox" pro-filter="Relaxz"><i></i> Relaxz</label></li>
                            </ul>
                        </div>
                        <div class="col-6 col-sm-12 mb-3 mb-sm-0">
                            <h4>Sizes</h4>
                            <ul class="product__filter__bar-list">
                                @foreach($sizes as $sizeKey => $sizeValue)
                                    <li><label><input type="checkbox" pro-filter="{{$sizeValue->name}}"><i></i> {{$sizeValue->name}}</label></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6 col-sm-12 mb-3 mb-sm-0">
                            <h4>Price</h4>
                            <ul class="product__filter__bar-list">
                                <li><label><input type="checkbox" pro-filter="&#8377;339 - &#8377;425"><i></i> &#8377;339 - &#8377;425</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;410 - &#8377;450"><i></i> &#8377;410 - &#8377;450</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;499 - &#8377;525"><i></i> &#8377;499 - &#8377;525</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;575 - &#8377;599"><i></i> &#8377;575 - &#8377;599</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;599 - &#8377;625"><i></i> &#8377;599 - &#8377;625</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;590 - &#8377;615"><i></i> &#8377;590 - &#8377;615</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;450 - &#8377;475"><i></i> &#8377;450 - &#8377;475</label></li>
                                <li><label><input type="checkbox" pro-filter="&#8377;430 - &#8377;450"><i></i> &#8377;430 - &#8377;450</label></li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <h4>Color</h4>
                            <ul class="product__filter__bar-list column-2">
                                @foreach($colors as $colorKey => $colorValue)
                                    <li><label><input type="checkbox" pro-filter="{{$colorValue->name}}"><i></i> {{ucwords($colorValue->name)}}</label></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="product__holder">
                <div class="row">
                    @foreach($data as $collectionProductKey => $collectionProductValue)
                    <a href="{{ route('front.product.detail', $collectionProductValue->slug) }}" class="product__single" data-events data-cat="tshirt">
                        <figure>
                            <img src="{{asset($collectionProductValue->image)}}" />
                            <h6>{{$collectionProductValue->style_no}}</h6>
                        </figure>
                        <figcaption>
                            <h4>{{$collectionProductValue->name}}</h4>
                            <h5>
                            &#8377;{{$collectionProductValue->offer_price}} 
                            {{-- - &#8377;{{$collectionProductValue->price}} --}}
                            </h5>
                        </figcaption>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section> 
@else
<section class="cart-header mb-3 mb-sm-5"></section>
<section class="cart-wrapper">
    <div class="container">
        <div class="complele-box">
            <figure>
                <img src="{{asset('img/close.svg')}}" height="100">
            </figure>
            <figcaption>
                <h2>OOPS ! <b>{{app('request')->input('query')}}</b> returns no result</h2>
                <p>Search new query.</p>
                <a href="{{route('front.home')}}">Back to Home</a>
            </figcaption>
        </div>
    </div>
</section>
@endif
@endsection