@extends('layouts.app')

@section('page', 'Category')

@section('content')
<style>
select {
    border: none;
    background: transparent;
}
select:focus {
    outline: none;
    box-shadow: none;
}
.color_holder {
    height: 20px;
    width: 20px;
    border-radius: 50%
}
</style>

<section class="listing-header">
    <div class="container">
        <div class="row flex-sm-row-reverse align-items-center">
            {{-- <div class="col-sm-3 d-none d-sm-block">
                <img src="{{ asset($data->banner_image) }}" class="img-fluid">
            </div> --}}
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('front.catalouge.index') }}">Catalouge</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$data->name}}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-6">
                <h1>{{ $data->name }}</h1>
            </div>
        </div>
    </div>
</section>

<section class="listing-block">
    <div class="container">
        @if (count($data->ProductDetails) > 0)
        <div class="listing-block__meta">
            <div class="products mr-3">
                {{--<h6><span id="prod_count">{{ $data->ProductDetails->count() }}</span> <span id="prod_text">{{ ($data->ProductDetails->count() > 1) ? 'products' : 'product' }}</span> found</h6>--}}
            </div>
            <div class="sorting">
                Sort By:
                <select name="orderBy" onclick="productsFetch()">
                    <option value="new_arr">New Arrivals</option>
                    <option value="mst_viw">Most Viewed</option>
                    <option value="prc_low">Price: Low To High</option>
                    <option value="prc_hig">Price: High To Low</option>
                </select>
            </div>
        </div>

        <div class="product__wrapper">
            <div class="product__holder">
                <div class="row">
                    @forelse($data->ProductDetails as $categoryProductKey => $categoryProductValue)
                    @php if($categoryProductValue->status == 0) {continue;} @endphp
                    <div class="col-6 col-md-3">
                        <a href="{{ route('front.product.detail', $categoryProductValue->slug) }}" class="product__single__card" data-events data-cat="tshirt">
                            <figure>
                                <img src="{{asset($categoryProductValue->image)}}" />
                            </figure>
                            <figcaption>
                                <h6>{{$categoryProductValue->style_no}}</h6>
                                <h4>{{$categoryProductValue->name}}</h4>
                                <h5>
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
                                    &#8377;{{$displayPrice}}
                                @else
                                    &#8377;{{$categoryProductValue->offer_price}}
                                @endif
                                </h5>
                            </figcaption>
                        </a>
                    </div>
                    @empty

                    @endforelse
                </div>
            </div>
        </div>
        @else
            <p>Sorry, No products found under {{$data->name}} </p>
        @endif
    </div>
</section>
@endsection

@section('script')
<script>
    function productsFetch() {
        // collection values
        var collectionArr = [];
        $('input[name="collection[]"]:checked').each(function(i){
          collectionArr[i] = $(this).val();
        });

        $.ajax({
            url: '{{route("front.category.filter")}}',
            method: 'POST',
            data: {
                '_token' : '{{ csrf_token() }}',
                'categoryId' : '{{$data->id}}',
                'orderBy' : $('select[name="orderBy"]').val(),
                'collection' : collectionArr,
            },
            beforeSend: function() {
                $loadingSwal = Swal.fire({
                    title: 'Please wait...',
                    text: 'We are adjusting the products as per your need!',
                    showConfirmButton: false,
                    allowOutsideClick: false
                    // timer: 1500
                })
            },
            success: function(result) {
                if (result.status == 200) {
                    var content = prodText = '';
                    $('#prod_count').text(result.data.length);
                    (result.data.length > 1) ? prodText = 'products' : prodText = 'product';
                    $('#prod_text').text(prodText);
                    $.each(result.data, function(key, value) {
                        var url = '{{ route('front.product.detail', ":slug") }}';
                        url = url.replace(':slug', value.slug);

                        content += `
                        <a href="${url}" class="product__single" data-events data-cat="tshirt">
                            <figure>
                                <img src="{{asset('${value.image}')}}" />
                                <h6>${value.styleNo}</h6>
                            </figure>
                            <figcaption>
                                <h4>${value.name}</h4>
                                <h5>&#8377;${value.displayPrice}</h5>
                            </figcaption>
                        </a>
                        `;
                    });

                    $('.product__holder .row').html(content);
                    $loadingSwal.close();
                }
                // console.log(result);
            },
            error: function(result) {
                $loadingSwal.close()
                console.log(result);
                $errorSwal = Swal.fire({
                    // icon: 'error',
                    // title: 'We cound not find anything',
                    text: 'We cound not find anything. Try again with a different filter!',
                    confirmButtonText: 'Okay'
                })
            },
        });
    }
</script>
@endsection
