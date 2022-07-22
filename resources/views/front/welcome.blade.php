@extends('layouts.app')

@section('page', 'Home')

@section('content')
@if (\Auth::guard('web')->check())
    <script>window.location = "{{route('front.dashboard.index')}}";</script>
@endif
<section class="storeList_panel">
    <div class="dashboard_box">
        <ul>
            {{-- <li class="nav-item {{ (request()->is('catalouge*')) ? 'active' : '' }}">
                <a href="{{ route('front.catalouge.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Catalouge</a>
            </li> --}}
            {{-- <li class="nav-item {{ (request()->is('order-on-call*')) ? 'active' : '' }}"><a href="{{ route('front.store.order.call.index') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                Order on call</a></li> --}}
            <li class="dashboardItem {{ (request()->is('order-on-call*')) ? 'active' : '' }}"><a href="{{ route('front.store.order.call.index') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></span>
                Order on call</a>
            </li>
            <li class="dashboardItem {{ (request()->is('store*')) ? 'active' : '' }}"><a href="{{ route('front.store.index') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg></span>
                Store visit</a>
            </li>
            <li class="dashboardItem {{ (request()->is('store/add*')) ? 'active' : '' }}"><a href="{{ route('front.store.add') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></span>
                Add Store</a>
            </li>
            <li class="dashboardItem {{ (request()->is('directory*')) ? 'active' : '' }}"><a href="{{ route('front.directory.index') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg></span>
                Directory</a>
            </li>
            <li class="dashboardItem {{ (request()->is('offer*')) ? 'active' : '' }}"><a href="{{ route('front.offer.index') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg></span>
                Offer</a>
            </li>
            <li class="dashboardItem {{ (request()->is('catalouge/download*')) ? 'active' : '' }}"><a href="{{ route('front.catalouge.download.index') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></span>
                Catalouge</a>
            </li>
        </ul>
        <ul>
            <li class="dashboardItemText addACC">
                <h5 class="acountText">Account</h5>
            </li>

            <li class="dashboardItem {{ (request()->is('user/profile*')) ? 'active' : '' }}">
                <a href="{{ route('front.user.profile') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                Profile</a>
            </li>

            <li class="dashboardItem {{ (request()->is('user/order*')) ? 'active' : '' }}">
                <a href="{{ route('front.user.order') }}">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></span>
                Order</a>
            </li>

            <li class="dashboardItem">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></span>
                Logout</a>
            </li>
        </ul>
    </div>


    {{-- <div class="storeCatgoryListWrap">
        <div class="container">
            <ul class="storeCatgoryList">
                @foreach ($category as $categoryKey => $categoryVal)
                @php
                    if($categoryVal->ProductDetails->count() == 0) {continue;}
                @endphp
                    <li><a href="#tab{{$categoryKey+1}}">{{$categoryVal->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        @foreach ($category as $categoryKeyDet => $category)
            <div class="storeProduct_list" id="tab{{$categoryKeyDet + 1}}">
                <h3>{{ $category->name }} <span>{{ $category->ProductDetails->count() }} products</span></h3>

                <div class="row mb-3">
                @forelse($category->ProductDetails as $categoryProductKey => $categoryProductValue)
                    @php if($categoryProductValue->status == 0) {continue;} @endphp
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="storeProduct_card">
                            <div class="storeProduct_card_body">
                                <a href="{{ route('front.product.detail', $categoryProductValue->slug) }}" class="product__single" data-events data-cat="tshirt">
                                    <figure class="storeProduct_card_img">
                                        <img src="{{asset($category->sketch_icon)}}" class="" />
                                    </figure>
                                    <figcaption>
                                        <span class="collectionTag">{{ $categoryProductValue->collection->name }}</span>
                                        <h4>{{$categoryProductValue->name}}</h4>
                                        <h5>Style # {{$categoryProductValue->style_no}}</h5>
                                        <h6 class="mb-0">
                                        <span class="mr_price">
                                        @if (count($categoryProductValue->colorSize) > 0)
                                            @php
                                                $varArray = [];
                                                foreach($categoryProductValue->colorSize as $productVariationKey => $productVariationValue) {
                                                    $varArray[] = $productVariationValue->offer_price;
                                                }
                                                $bigger = $varArray[0];
                                                for ($i = 1; $i < count($varArray); $i++) {
                                                    if ($bigger < $varArray[$i]) {
                                                        $bigger = $varArray[$i];
                                                    }
                                                }

                                                $smaller = $varArray[0];
                                                for ($i = 1; $i < count($varArray); $i++) {
                                                    if ($smaller > $varArray[$i]) {
                                                        $smaller = $varArray[$i];
                                                    }
                                                }

                                                $displayPrice = $smaller.' - '.$bigger;

                                                if ($smaller == $bigger) $displayPrice = $smaller;
                                            @endphp
                                            Rs: {{$displayPrice}}
                                        @else
                                            Rs: {{$categoryProductValue->offer_price}}
                                        @endif
                                        </span>
                                        </h6>
                                    </figcaption>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty

                @endforelse
                </div>
            </div>
        @endforeach
    </div> --}}
</section>
@endsection
