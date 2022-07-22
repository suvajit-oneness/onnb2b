@extends('layouts.app')

@section('page', 'Offer')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        <h3>Schemes</h3>

        <section class="store_listing">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-current-tab" data-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-current" aria-selected="true">Current</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-past-tab" data-toggle="pill" href="#pills-past" role="tab" aria-controls="pills-past" aria-selected="false">Past</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab">
                            <div class="row">
                            @forelse($data as $offerKey => $offerValue)
                                @if($offerValue->is_current == 1)
                                <div class="col-4 col-sm-3 col-lg-2">
                                    <a href="{{asset($offerValue->pdf)}}" class="product__cat__single" download>
                                        <figure>
                                            <img src="{{asset($offerValue->image)}}" alt="">
                                        </figure>
                                        <h5>{{$offerValue->title}}</h5>
                                    </a>
                                </div>
                                @endif
                                @empty
                            @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-past" role="tabpanel" aria-labelledby="pills-past-tab">
                            <div class="row">
                                @forelse($data as $offerKey => $offerValue)
                                    @if($offerValue->is_current == 0)
                                    <div class="col-4 col-sm-3 col-lg-2">
                                        <a href="{{asset($offerValue->pdf)}}" class="product__cat__single" download>
                                            <figure>
                                                <img src="{{asset($offerValue->image)}}" alt="">
                                            </figure>
                                            <h5>{{$offerValue->title}}</h5>
                                        </a>
                                    </div>
                                    @endif
                                    @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
