@extends('layouts.app')

@section('page', 'Store')

@section('content')
@php
    if (request()->is('store')) {
        $type = 'store-visit';
    } else {
        $type = 'order-on-call';
    }
@endphp

<section class="store_listing">
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <div class="dropdown">
                    <input type="search" class="form-control dropdown-toggle" name="store" value="" placeholder="Search store name" data-toggle="dropdown">
                    <div class="respDrop"></div>
                </div>
            </div>
        </div>
        <div class="row">
        @forelse($data as $categoryProductKey => $categoryProductValue)
        @if ($categoryProductValue->user_id != auth()->guard('web')->user()->id)
            @continue
        @endif
            <div class="col-lg-4 col-12 jQueryEqualHeight">
                <div class="store_card card">
                    <div class="store_card_body">
                        <a href="{{ route('front.store.detail', [$categoryProductValue->id, 'type' => $type]) }}" class="product__single" data-events data-cat="tshirt">
                            <figcaption>
                                <h5>{{$categoryProductValue->store_name}}</h5>
                                <ul>
                                    <li><span class="storeId">#{{$categoryProductValue->store_OCC_number}}</span></li>
                                    <li>{{$categoryProductValue->bussiness_name}}</li>
                                </ul>
                                <div class="storLoction">
                                    <div class="storLoction_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#34acc0" stroke="#fff" stroke-width="0" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" stroke-width="2" cy="10" r="4"></circle></svg>
                                    </div>
                                    <div class="storLoction_text">
                                        <h6 class="mb-1">{{$categoryProductValue->area}}</h6>
                                        <p class="text-muted mb-0">{{$categoryProductValue->state}}</p>
                                        @if (request()->is('order-on-call'))
                                            <a href="tel:{{ $categoryProductValue->contact }}">
												<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
												{{$categoryProductValue->contact}}
											</a>
                                        @endif
                                    </div>
                                </div>
                            </figcaption>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            here
        @endforelse
        </div>

        {{$data->links()}}
    </div>
</section>
@endsection

@section('script')
    <script>
        // store name search
        $('input[name="store"]').on('keyup', function() {
            var $this = 'input[name="store"]'

            if ($($this).val().length > 0) {
                $.ajax({
                    url: '{{route("front.store.search")}}',
                    method: 'post',
                    data: {
                        '_token': '{{csrf_token()}}',
                        name: $($this).val(),
                        type: '{{$type}}',
                    },
                    success: function(result) {
                        var content = '';
                        if (result.error === false) {
                            content += `<div class="dropdown-menu show w-100 postcode-dropdown" aria-labelledby="dropdownMenuButton">`;

                            $.each(result.data, (key, value) => {
                                content += `<a class="dropdown-item" href="${value.route}">${value.store_name} (${value.bussiness_name})</a>`;
                            })
                            content += `</div>`;
                        } else {
                            content += `<div class="dropdown-menu show w-100 postcode-dropdown" aria-labelledby="dropdownMenuButton"><li class="dropdown-item">${result.message}</li></div>`;
                        }
                        $('.respDrop').html(content);
                    }
                });
            } else {
                $('.respDrop').text('');
            }
        });
    </script>
@endsection