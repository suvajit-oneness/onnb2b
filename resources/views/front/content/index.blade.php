@extends('layouts.app')

@section('page', $page)

@section('content')

<style type="text/css">
	.cms_context h1, .cms_context h2, .cms_context h3, .cms_context h4, .cms_context h5, .cms_context h6 {
		margin-bottom: 25px;
	}
	.cms_context p {
		margin-bottom: 30px;
	}
	.cms_context ul {
		list-style-type: disc;
		padding-left: 30px;
		margin-bottom: 30px;
	}
	.cms_context ul li {
		margin-bottom: 10px;
	}
</style>

<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>{{$page}}</h4>
            </div>
        </div>
    </div>
</section>

<section class="cart-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <ul class="account-list mt-0">
                    <li>
                        <span><strong>Quick Links</strong></span>
                        <ul>
                            <li><a href="{{route('front.faq.index')}}">FAQ</a></li>
                            <li><a href="{{route('front.user.order')}}">My Shopping</a></li>
                            <li><a href="{{route('front.content.shipping')}}">Shipping & Delivery</a></li>
                            <li><a href="{{route('front.content.payment')}}">Payment, Voucher & Promotions</a></li>
                            <li><a href="{{route('front.content.return')}}">Returns Policy</a></li>
                            <li><a href="{{route('front.content.refund')}}">Refund & Cancellation Policy</a></li>
                            <li><a href="{{route('front.content.service')}}">Service & Contact</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-sm-8">
                <div class="cms_context">
                    {!! $data->content !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection