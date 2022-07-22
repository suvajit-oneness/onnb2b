@extends('layouts.app')

@section('page', 'Cart')

@section('content')

<div class="col-sm-12">
    <div class="profile-card">
        <h3>Shopping Cart</h3>
        @if(count($data) > 0)
            <section class="store_listing">
                <div class="">
                    {{-- <div class="cartHeading">
                        <h4>Shopping Cart</h4>
                    </div> --}}

                    @php
                        $subTotal = $grandTotal = $couponCodeDiscount = $shippingCharges = $taxPercent = 0;
                    @endphp

                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#SR</th>
                                <th>Style no.</th>
                                <th>Name</th>
                                @if (auth()->guard('web')->user()->user_type != 4)
                                    <th>Price</th>
                                @endif
                                <th>Size</th>
                                <th>Color</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $cartKey => $cartValue)
                            <tr>
                                <td>{{$cartKey + 1}}</td>
                                <td>{{$cartValue->product_style_no}}</td>
                                <td>{{$cartValue->product_name}}</td>
                                @if (auth()->guard('web')->user()->user_type != 4)
                                    <td>Rs {{ number_format($cartValue->offer_price) }}</td>
                                @endif
                                <td>{{$cartValue->size}}</td>
                                <td>
									@if(isset($cartValue->colorDetails))
                                    <span
                                        @if ($cartValue->colorDetails->id == 61)
                                        style="background: -webkit-linear-gradient(left, rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); "
                                        @else
                                        style="background-color: {{$cartValue->colorDetails->code}};"
                                        @endif
                                        ></span> {{$cartValue->colorDetails->name}}
									@else
									{{$cartValue->color}}
									@endif
                                </td>
                                <td>{{$cartValue->qty}} {{($cartValue->qty == 1) ? 'pc' : 'pcs'}}</td>
                                <td>
                                    @if (auth()->guard('web')->user()->user_type == 5)
                                        <a href="{{route('front.cart.distributor.delete', $cartValue->id)}}">
                                    @else
                                        <a href="{{route('front.cart.delete', $cartValue->id)}}">
                                    @endif
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        Delete
                                    </a>
                                </td>
                            </tr>

                            @php
                                // subtotal calculation
                                $subTotal += (int) $cartValue->offer_price * $cartValue->qty;

                                // coupon code calculation
                                if (!empty($data[0]->coupon_code_id)) {
                                    $couponCodeDiscount = (int) $data[0]->couponDetails->amount;
                                }

                                // grand total calculation
                                $grandTotalWithoutCoupon = $subTotal;
                                $grandTotal = ($subTotal + $shippingCharges) - $couponCodeDiscount;
                            @endphp

                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="100%"><h4 class="my-4">Cart Total</h4></td>
                            </tr>
                            @if (auth()->guard('web')->user()->user_type != 4)
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">Subtotal</td>
                                    <td colspan="2">&#8377; {{number_format($subTotal)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">Shipping Charges</td>
                                    <td colspan="2">&#8377; {{$shippingCharges}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">Total</td>
                                    <td colspan="2">&#8377; {{number_format($grandTotal)}}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">Total Pcs</td>
                                <td colspan="2">{{$cartCount}}</td>
                            </tr>
                            {{-- <tr>
                                <td colspan="3"></td>
                                <td colspan="2">Comment</td>
                                <td colspan="2">
                                    <textarea class="form-control" name="" id="" rows="5"></textarea>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>

                    {{-- <div class="cartTotalTable row justify-content-end">
                        <div class="col-lg-6 col-md-8 col-12">
                            <h4>Cart Total</h4>
                            <table class="table table-sm">
                                @if (auth()->guard('web')->user()->user_type != 4)
                                <tr>
                                    <td>Subtotal</td>
                                    <td>&#8377; {{number_format($subTotal)}}</td>
                                </tr>
                                <tr>
                                    <td>Shipping Charges</td>
                                    <td>&#8377; {{$shippingCharges}}</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>&#8377; {{number_format($grandTotal)}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Total Pcs</td>
                                    <td>{{$cartCount}}</td>
                                </tr>
                            </table>
                        </div>
                    </div> --}}
                    <div class="row justify-content-end placeOrderBox">
                        <div class="col-lg-6 col-md-8 col-12">
                            @if (auth()->guard('web')->user()->user_type == 5)
                                <form action="{{route('front.checkout.store.distributor')}}" method="POST">@csrf
                            @else
                                <form action="{{route('front.checkout.store')}}" method="POST">@csrf
                            @endif
                                <textarea name="comment" class="form-control" cols="30" rows="8" placeholder="Comment"></textarea>
                                <input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">
                                <input type="hidden" name="email" value="{{Auth::guard('web')->user()->email}}">
                                <input type="hidden" name="store_id" value="{{$data[0]->store_id}}">
                                <input type="hidden" name="order_type" value="store_type">
                                <input type="hidden" name="order_lat" value="22.22">
                                <input type="hidden" name="order_lng" value="44.44">
                                <div class="text-right">
                                    <button type="submit" class="btn placeOrderBtn">Place Order</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class="cart-header mb-3 mb-sm-5"></section>
            <section class="cart-wrapper">
                <div class="container text-center">
                    <div class="complele-box">
                        <figure>
                            <img src="{{asset('img/empty-cart.png')}}" height="100">
                        </figure>
                        <figcaption>
                            <h2>Your cart is empty</h2>
                            <a href="{{route('front.home')}}">Back to Dashboard</a>
                        </figcaption>
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>

@endsection

@section('script')
    <script>
        // cart page coupon
        $('#applyCouponBtn').on('click', (e) => {
            e.preventDefault()
            let couponCode = $('input[name="couponText"]').val();
            if (couponCode.length > 0) {
                $.ajax({
                    url: '{{ route('front.cart.coupon.check') }}',
                    method: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        code: couponCode
                    },
                    beforeSend: function() {
                        $('#applyCouponBtn').text('Checking');
                        // $('#applyCouponBtn').text('Checking').attr('disabled', true);
                    },
                    success: function(result) {
                        // console.log(result);

                        if (result.type == 'success') {
                            $('#applyCouponBtn').text('APPLIED').css('background', '#c1080a').attr('disabled', true);

                            $('input[name="couponText"]').attr('disabled', true);
                            let beforeCouponValue = parseInt($('#displayGrandTotal').text());
                            let couponDiscount = parseInt(result.amount);
                            let discountedGrandTotal = beforeCouponValue - couponDiscount;
                            $('#displayGrandTotal').text(discountedGrandTotal);

                            /* $('input[name="coupon_code_id"]').val(result.id);
                            let grandTotal = $('input[name="grandTotal"]').val();
                            let discountedGrandTotal = parseInt(grandTotal) - parseInt(result.amount);
                            $('input[name="grandTotal"]').val(discountedGrandTotal);
                            $('#displayGrandTotal').text(discountedGrandTotal); */

                            let couponContent = `
                            <div class="cart-total">
                                <div class="cart-total-label">
                                    COUPON APPLIED - <strong>${couponCode}</strong><br/>
                                    <a href="javascript:void(0)" onclick="removeAppliedCoupon()"><small>(Remove this coupon)</small></a>
                                </div>
                                <div class="cart-total-value">- ${result.amount}</div>
                            </div>
                            `;

                            $('#appliedCouponHolder').html(couponContent);
                            toastFire(result.type, result.message);
                        } else {
                            toastFire(result.type, result.message);
                            $('#applyCouponBtn').text('Apply');
                        }
                    }
                });
            }
        });
    </script>
@endsection
