@extends('front.profile.layouts.app')

@section('profile-content')
<div class="col-sm-7">
    <div class="profile-card">
        <h3>Order History</h3>
        @if (request()->input('store'))
            <p class="text-muted">{{request()->input('store_name')}}</p>
        @endif

        @forelse($data as $orderKey => $orderValue)

        @php
        $orderSTatus = "Unknown";
        switch($orderValue->status) {
            case 1: $orderSTatus = "New";break;
            case 2: $orderSTatus = "Confirmed";break;
            case 3: $orderSTatus = "Shipped";break;
            case 4: $orderSTatus = "Delivered";break;
            case 5: $orderSTatus = "Cancelled";break;
            default: $orderSTatus = "Unknown";break;
        }
        @endphp

        <div class="order-card">
            <div class="order-card-header">
                <figure>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                </figure>
                <figcaption>
                    <h5 class="{{ ($orderSTatus == "Cancelled" ? 'text-danger' : 'text-success') }}">Status : {{$orderSTatus}}</h5>
                    @if (Auth::guard('web')->user()->user_type != 4 && Auth::guard('web')->user()->user_type != 5)
                        <p class="small">Order by {{$orderValue->ordered_by_username}}</p>
                    @else
                        <p class="small">Order by self</p>
                    @endif
                    <p>Ordered On {{date('D, j M Y', strtotime($orderValue->created_at))}}</p>
                </figcaption>
                {{-- @if (Auth::guard('web')->user()->user_type == 5)
                    <a href="{{ route('front.user.invoice', $orderValue->id) }}" class="text-primary" style="font-weight: bold">Invoice</a>
                @else
                    <a href="{{ route('front.user.distributor.invoice', $orderValue->id) }}" class="text-primary" style="font-weight: bold">Invoice</a>
                @endif --}}
            </div>
            <div class="order-card-body">
                @php
                    if (Auth::guard('web')->user()->user_type == 5) {
                        $orderProducts = \App\Models\OrderProductDistributor::where('order_id', $orderValue->id)->get();
                    } else {
                        $orderProducts = \App\Models\OrderProduct::where('order_id', $orderValue->id)->get();
                    }
                    $totalPcs = 0;
                @endphp

                @foreach($orderProducts as $productKey => $productValue)
                @php
                    $variation = '';
                    if($productValue->productVariationDetails) {
                        $variation = 'Color: <span>'.ucwords($productValue->productVariationDetails->colorDetails->name).'</span> | Size: <span>'.$productValue->productVariationDetails->sizeDetails->name.'</span>';
                    }

                    if($variation == '') {
                        $variation = 'Color: <span>'.ucwords($productValue->colorDetails->name).'</span> | Size: <span>'.$productValue->size.'</span> | ';
                    }
                    $totalPcs += $productValue->qty;
                @endphp
                <div class="order-product-card">
                    <figure>
                        <img src="{{asset('img/product-box.png')}}" />
                    </figure>
                    <figcaption>
                        <h6>Style # OF {{$productValue->product_style_no}}</h6>
                        <h4>{{$productValue->product_name}}</h4>
                        <h5>
                        @if (auth()->guard('web')->user()->user_type != 4)
                            Price: <span>&#8377;{{$productValue->offer_price}}</span> |
                        @endif
                        {!!$variation!!}
                        @if (auth()->guard('web')->user()->user_type != 4)
                        | 
                        @endif
                        Qty: <span>{{$productValue->qty}}</span></h5>
                    </figcaption>
                </div>
                @endforeach
            </div>
            <div class="order-card-footer">
                <div class="row">
                    <div class="col-sm-6">
                        Order # {{$orderValue->order_no}}
                    </div>
                    <div class="col-sm-6 text-sm-right">
                        @if (auth()->guard('web')->user()->user_type != 4)
                            Total Order Price: &#8377; {{number_format($orderValue->final_amount)}}
                        @else
                            Total Order Pcs: {{$totalPcs}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p>No orders found</p>
        @endforelse
    </div>
</div>
@endsection
