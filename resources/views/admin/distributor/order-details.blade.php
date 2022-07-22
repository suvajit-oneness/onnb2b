@extends('admin.layouts.app')

@section('page', 'Order detail')

@section('content')
{{--  <section>
    <div class="row">
        <div class="col-sm-5">
            <div class="card shadow-sm">
                <div class="card-header">Ordered Products ({{count($data->orderProducts)}})</div>
                <div class="card-body">
                    <div class="row product__thumb">
                        @foreach($data->orderProducts as $productKey => $productValue)

                            <div class="col-md-6">
                                <img src="{{asset('img/product-box.png')}}" />
                                <p class="small single-line mb-0">{{$productValue->product_name}}</p>
                                <hr class="my-1">
                                <p class="small text-dark mb-0"> <span class="text-muted">Qty : </span> {{$productValue->qty}}</p>
                                <p class="small text-dark mb-0"> <span class="text-muted">Price : </span> Rs {{$productValue->price}}</p>
                                <p class="small text-dark mb-0"> <span class="text-muted">Offer price : </span> Rs {{$productValue->offer_price}}</p>
                                <p class="small text-dark mb-0"> <span class="text-muted">Color : </span> {{ucwords($productValue->colorDetails->name)}}</p>
                                <p class="small text-dark mb-0"> <span class="text-muted">Size : </span> {{strtoupper($productValue->size)}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <a href="{{ route('admin.order.status', [$data->id, 1]) }}" type="button" class="btn btn-outline-primary btn-sm {{($data->status == 1) ? 'active' : ''}}">New</a>
                            <a href="{{ route('admin.order.status', [$data->id, 2]) }}" type="button" class="btn btn-outline-primary btn-sm {{($data->status == 2) ? 'active' : ''}}">Confirm</a>
                            <a href="{{ route('admin.order.status', [$data->id, 3]) }}" type="button" class="btn btn-outline-primary btn-sm {{($data->status == 3) ? 'active' : ''}}">Shipped</a>
                            <a href="{{ route('admin.order.status', [$data->id, 4]) }}" type="button" class="btn btn-outline-success btn-sm {{($data->status == 4) ? 'active' : ''}}">Delivered</a>
                            <a href="{{ route('admin.order.status', [$data->id, 5]) }}" type="button" class="btn btn-outline-danger btn-sm {{($data->status == 5) ? 'active' : ''}}">Cancelled</a>
                        </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <p class="small">Order Time : {{date('j M Y g:i A', strtotime($data->created_at))}}</p>
                        <p class="small">Order Type : {{$data->order_type}}</p>
                        <p class="small">Order Latitude : {{$data->order_lat}}</p>
                        <p class="small">Order Longitude : {{  $data->order_lng}}</p>
                        <h2>{{$data->fname.' '.$data->lname}}</h2>
                        <p class="small text-dark mb-0"> <span class="text-muted">Email : </span> {{$data->email}}</p>
                        <p class="small text-dark mb-0"> <span class="text-muted">Mobile : </span> {{$data->mobile}}</p>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="small text-dark mb-2"> <span class="text-muted">Billing address : </span></p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Street address : </span> {{$data->billing_address}}</p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Landmark : </span> {{$data->billing_landmark}}</p>
                            <p class="small text-dark mb-0"> {{$data->billing_pin.', '.$data->billing_city.', '.$data->billing_state.', '.$data->billing_country}}</p>
                        </div>

                        <div class="col-md-6 border-start">
                            <p class="small text-dark mb-2"> <span class="text-muted">Shipping address : </span></p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Street address : </span> {{$data->shipping_address}}</p>
                            <p class="small text-dark mb-0"> <span class="text-muted">Landmark : </span> {{$data->shipping_landmark}}</p>
                            <p class="small text-dark mb-0"> {{$data->shipping_pin.', '.$data->shipping_city.', '.$data->shipping_state.', '.$data->shipping_country}}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-4 text-end">
                            <p class="small text-muted mb-2">Pricing</p>
                            <table class="w-100">
                                <tr>
                                    <td><p class="small text-muted mb-0">Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">Rs {{$data->amount}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="small text-muted mb-0">Tax Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">+ Rs {{$data->tax_amount}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="small text-muted mb-0">Discount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">- Rs {{$data->discount_amount}}</p></td>
                                </tr>
                                <tr class="border-top">
                                    <td><p class="small text-muted mb-0">Final Amount : </p></td>
                                    <td><p class="small text-dark mb-0 text-end">Rs {{$data->final_amount}}</p></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>--}}

<section>
    @if (request()->input('store'))
    <p class="text-muted">{{request()->input('store_name')}}</p>
    @endif
    <div class="search__filter">
        <div class="row align-items-center justify-content-between">
        <div class="col">
        </div>
        </div>
    </div>

    <div class="filter">
        <div class="row align-items-center justify-content-between">
        <div class="col">
        </div>
        <div class="col-auto">

        </div>
        </div>
    </div>

    <table class="table">
        <thead>
        <tr>

            <th>Order No</th>
            <th>Distributor Name</th>
			 <th>Distributor Contact Details</th>
            <th>Order time</th>
            
            <th>Amount</th>
            <th>Tax</th>
            <th>Discount</th>
            <th>Final Amount</th>


        </tr>
        </thead>
        <tbody>


            <tr>

                <td>
                    <p class="small text-dark mb-1">{{$data->order_no}}</p>


                </td>
                <td>
                    <p class="small text-dark mb-1">{{$data->distributor_name}}</p>
                   
                </td>
                 <td>
                    
                    <p class="small text-muted mb-0">{{$data->users ? $data->users->mobile : ''}}</p>
                </td>
                <td>
                    <p class="small">{{date('j M Y g:i A', strtotime($data->created_at))}}</p>
                </td>
                
                  
                <td>
                    <p class="small text-muted mb-1">Rs {{$data->amount}}</p>
                </td>

                <td>
                    <p class="small text-muted mb-1">Rs {{$data->tax_amount}}</p>
                </td>
                <td>
                    <p class="small text-muted mb-1">Rs {{$data->discount_amount}}</p>
                </td>
                <td>
                    <p class="small text-muted mb-1">Rs {{$data->final_amount}}</p>
                </td>


            </tr>

        </tbody>
    </table>
<div class="card-header">Ordered Products ({{count($data->orderProducts)}})</div>
<table class="table">
        <thead>
        <tr>
<th> SL No</th>
            <th>Product Name</th>
            <th>Style No</th>
            <th>Quantity</th>
            <th>Price</th>
           
            <th>Color Name</th>
            <th>Size</th>
</tr>
        </thead>
        <tbody>
			@foreach($data->orderProducts as $productKey => $productValue)
      <tr>
		  <td>{{$productKey+1}}</td>
	<td>
                    <p class="small text-dark mb-1">{{$productValue->product_name}}</p></td>
                   <td> <p class="small text-dark mb-1">{{$productValue->product_style_no}}</p>
                    </td>
					  <td> <p class="small text-muted mb-0">{{$productValue->qty}}</p></td>

<td><p>{{$productValue->offer_price}}</p></td>
<td> <p class="small text-muted mb-0">{{ucwords($productValue->colorDetails ? $productValue->colorDetails->name : '')}}</p></td>
<td> <p class="small text-muted mb-0">{{ucwords($productValue->size)}}</p></td>
</tr>
@endforeach
		</tbody>	
	</table>


</section>
@endsection

@section('script')
    <script>
    </script>
@endsection
