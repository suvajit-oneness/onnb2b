@extends('layouts.app')

@section('page', 'Product detail')

@section('content')
<style>
    .color__holder {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #000;
    }
    .color__holder.active {
        border: 4px solid #000;
    }
</style>

@php
    if(!empty(request()->input('store'))) {
        $store = \App\Models\Store::findOrFail(request()->input('store'));
    }
@endphp

<section class="product_details">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        @if (!empty(request()->input('store')))
                            <li class="breadcrumb-item"><a href="{{ route('front.store.index') }}">Store</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.store.detail', $store->slug) }}">{{$store->store_name}}</a></li>
                        @endif
                        {{-- <li class="breadcrumb-item"><a href="{{ route('front.category.detail', $data->category->slug) }}">{{$data->category->name}}</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">{{$data->name}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6">
                <img src="{{ asset($data->image) }}" alt="" class="w-100">
            </div>
            <div class="col-md-6">
                <div class="productDetails_info">
                    <div class="badgeId"><span># {{$data->style_no}}</span></div>
                    <h2>{{$data->name}}</h2>
                    <p class="mb-4">{!! $data->short_desc !!}</p>

                    {{-- {{dd($primaryColorSizes[0])}} --}}

                    <div class="w-100">
                        <form action="{{ route('front.cart.add.bulk') }}" method="POST">@csrf
                            <h5>Select colors</h5>
                            <ul class="pl-0 pb-3" style="list-style: none;display: flex">
                            @foreach ($primaryColorSizes[0]['colors'] as $colorsKey => $colorsValue)
                                <li class="mr-3"><a href="javascript: void(0)">
                                    <div class="color__holder {{ $colorsKey == 0 ? 'active' : '' }}"
                                    @if ($colorsValue['id'] == 61)
                                    style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%); "
                                    @else
                                    style="background-color: {{$colorsValue['code']}}"
                                    @endif
                                    data-toggle="tooltip" title="{{$colorsValue['name']}}" onclick="sizeCheck({{$data->id}}, {{$colorsValue['id']}}, '{{$colorsValue['name']}}')"></div>
                                </a></li>
                            @endforeach
                            </ul>

                            <h5 class="mb-3">Select size for <span id="colorName">{{$primaryColorSizes[0]['colors'][0]['name']}}</span> </h5>
                            <div class="row sizeBoxWrap" id="sizeLoad">
                            @foreach ($primaryColorSizes[0]['primarySizes'] as $sizesKey => $sizesValue)
                                <div class="col-xl-6 col-lg-8pu col-6">
                                    <div class="sizeBox">
                                        <div class="productSize">
                                            <span>
                                                {{$sizesValue['name']}}
                                            </span>
                                        </div>
                                        <div class="productPrice">
                                            Rs. {{$sizesValue['offer_price']}}
                                        </div>
                                        <div class="prductQuantity">
                                            <input min="0" type="number" name="qty[]" id="" value="0">
                                        </div>
                                        <input type="hidden" name="size[]" value="{{ $sizesValue['name'] }}">
                                        <input type="hidden" name="price[]" value="{{ $sizesValue['offer_price'] }}">
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-lg-8pu col-6">
                                @if (Auth::check())
                                    @if (!empty(request()->input('store')))
                                        <input type="hidden" name="store_id" value="{{request()->input('store')}}">
                                        {{-- <input type="hidden" name="order_type" value="{{request()->input('type')}}"> --}}
                                    @endif
                                    @if (!empty(request()->input('type')))
                                        {{-- <input type="hidden" name="store_id" value="{{request()->input('store')}}"> --}}
                                        <input type="hidden" name="order_type" value="{{request()->input('type')}}">
                                    @endif
                                    <input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">
                                    <input type="hidden" name="product_id" value="{{$data->id}}">
                                    <input type="hidden" name="product_name" value="{{$data->name}}">
                                    <input type="hidden" name="product_style_no" value="{{$data->style_no}}">
                                    <input type="hidden" name="product_slug" value="{{$data->slug}}">
                                    <input type="hidden" name="color" value="{{$primaryColorSizes[0]['colors'][0]['id']}}">

                                    <button type="submit" class="btn btn-block addtocartBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> Add to cart</button>
                                @else
                                    <a href="{{ route('login') }}" class="btn addtocartBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                    Add to cart</a>
                                @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    // $('.product__color li').eq(0).addClass('active');

    function sizeCheck(productId, colorId, colorName) {
        $.ajax({
            url : '{{route("front.product.size")}}',
            method : 'POST',
            data : {'_token' : '{{csrf_token()}}', productId : productId, colorId : colorId},
            beforeSend: function() {
                $loadingSwal = Swal.fire({
                    title: 'Please wait...',
                    text: 'We are fetching your details!',
                    showConfirmButton: false,
                    // allowOutsideClick: false
                    // timer: 1500
                })
            },
            success : function(result) {
                if (result.error === false) {
                    $loadingSwal.close();

                    $('#colorName').text(colorName);
                    $('input[name="color"]').val(colorId);
                    var content = '';

                    $.each(result.data, (key, val) => {
                        content += `
                        <div class="col-xl-6 col-lg-8pu col-6">
                            <div class="sizeBox">
                                <div class="productSize">
                                    <span>
                                        ${val.sizeName}
                                    </span>
                                </div>
                                <div class="productPrice">
                                    Rs. ${val.offerPrice}
                                </div>
                                <div class="prductQuantity">
                                    <input min="0" type="number" name="qty[]" id="" value="0">
                                </div>
                                <input type="hidden" name="size[]" value="${val.sizeName}">
                                <input type="hidden" name="price[]" value="${val.offerPrice}">
                            </div>
                        </div>
                        `;
                    })
                    $('#sizeLoad').html(content);
                } else {
                    $loadingSwal.close();

                    Swal.fire({
                        title: 'OOPS',
                        text: 'No images found!',
                        timer: 1000
                    })
                }
            },
            error: function(xhr, status, error) {
                $('#colorSelectAlert').text('Something Went wrong. Try again');
            }
        });
    }

    // variation selection check
    $('#addToCart__btn').on('click', function(e) {
        if ($(this).hasClass('missingVariationSelection')) {
            e.preventDefault();
            alert('Select color & size first');
        }
    });

    // get variation id & load into product_variation_id
    $(document).on('click', '#sizeContainer li', function(){
        $('#addToCart__btn').removeClass('missingVariationSelection');
        var variationId = $(this).attr('data-id');
        $('input[name="product_variation_id"]').val(variationId);
        // console.log(variationId);
    });

    /* $(document).on('click', '.missingVariationSelection', function(){
        alert('here');
    }); */
</script>
@endsection
