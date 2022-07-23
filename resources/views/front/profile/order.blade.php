{{-- @extends('front.profile.layouts.app') --}}
@extends('layouts.app')

{{-- @section('profile-content') --}}
@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        @if (Auth::guard('web')->user()->user_type != 4 && Auth::guard('web')->user()->user_type != 5)
            <h3>Order History for {{$orderValue->ordered_by_username}}</h3>
        @else
            {{-- {{dd($data[0]->stores->store_name)}} --}}
            @if (request()->input('name'))
                <h3>Order History for {{request()->input('name')}}</h3>
            @else
                <h3>Order History</h3>
            @endif
        @endif

        @if (request()->input('store'))
            <p class="text-muted">{{request()->input('store_name')}}</p>
        @endif

        <table class="table table-sm table-hover">
            <tbody>
                @forelse($data as $orderKey => $orderValue)
                    <tr style="background: #e1e1e1">
                        <th colspan="100%">
                            <div class="d-flex justify-content-between">
                                Order id : #{{$orderValue->order_no}}<br>
                                <a href="javascript: void(0)" data-toggle="collapse" data-target="#collapse__{{$orderKey}}" style="font-weight: 500;font-size: 12px;">View details</a>
                            </div>

                            <div class="collapse" id="collapse__{{$orderKey}}">
                                @if (Auth::guard('web')->user()->user_type == 4)
                                    Store : {{$orderValue->stores->store_name}}<br>
                                @endif
                                Time : {{date('D, j M Y H:i a', strtotime($orderValue->created_at))}}<br>

                                @php
                                    if (Auth::guard('web')->user()->user_type == 5) {
                                        $orderProducts = \App\Models\OrderProductDistributor::where('order_id', $orderValue->id)->get();
                                    } else {
                                        $orderProducts = \App\Models\OrderProduct::where('order_id', $orderValue->id)->get();
                                    }
                                    $totalPcs = $totalPcsBefore = $totalAmountBefore = 0;

                                    foreach($orderProducts as $productKey => $productValue) {
                                        $totalPcsBefore += $productValue->qty;
                                        $totalAmountBefore += $productValue->offer_price;
                                    }
                                @endphp

                                Qty : {{$totalPcsBefore}}<br>
                                @if (Auth::guard('web')->user()->user_type == 5)
                                    Amount : &#8377; {{number_format($totalAmountBefore)}}
                                @endif
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>Style no</th>
                        <th>Product</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Qty</th>
                    </tr>
                    @foreach($orderProducts as $productKey => $productValue)
                        @php
                            if($productValue->productVariationDetails) {
                                $colorDisplay = $productValue->productVariationDetails->colorDetails->name;
                                $sizeDisplay = $productValue->productVariationDetails->sizeDetails->name;
                            }else {
                                $colorDisplay = $productValue->colorDetails->name;
                                $sizeDisplay = $productValue->size;
                            }
                            $totalPcs += $productValue->qty;
                        @endphp

                        <tr>
                            <td>{{$productValue->product_style_no}}</td>
                            <td>{{$productValue->product_name}}</td>
                            <td>{{$colorDisplay}}</td>
                            <td>{{$sizeDisplay}}</td>
                            <td>{{$productValue->qty}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="100%"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-muted"><p>No orders found</p></td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- @forelse($data as $orderKey => $orderValue)
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
        @endforelse --}}
    </div>
</div>
@endsection
