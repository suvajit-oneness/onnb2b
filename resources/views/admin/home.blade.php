@extends('admin.layouts.app')

@section('page', 'Home')

@section('content')
<section>
    {{-- @if (request()->input('state'))
    <p class="text-muted">{{request()->input('state')}}</p>
   @endif --}}
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-danger">
                <div class="card-body">
                    <h4>VP <i class="fi fi-br-user"></i></h4>
                    <h2><a href="{{ route('admin.user.index') }}"> {{$data->vp->count()}}</a></h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-info">
                <div class="card-body">
                    <h4>RSM <i class="fi fi-br-chart-histogram"></i></h4>
                    <h2><a href="{{ route('admin.collection.index') }}"> {{$data->rsm->count()}}</a></h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-secondary">
                <div class="card-body">
                    <h4>ASM <i class="fi fi-br-cube"></i></h4>
                    <h2><a href="{{ route('admin.product.index') }}"> {{$data->asm->count()}}</a></h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-secondary">
                <div class="card-body">
                    <h4>ASE <i class="fi fi-br-cube"></i></h4>
                    <h2><a href="{{ route('admin.product.index') }}"> {{$data->ase->count()}}</a></h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>Distributor <i class="fi fi-br-user"></i></h4>
                    <h2><a href="{{ route('admin.distributor.index') }}"> {{$data->distributor->count()}}</a></h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>Store <i class="fi fi-br-chart-histogram"></i></h4>
                    <h2><a href="{{ route('admin.store.index') }}">{{$data->store}}</a></h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>Today's Primary Order Value <i class="fi fi-br-chart-histogram"></i></h4>
                    {{-- <h2>&#8377; {{number_format($data->primary)}}</h2> --}}
                    <h2>&#8377; {{number_format($data->primary[0]->final_amount)}}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>Today Secondary Order Quantity <i class="fi fi-br-chart-histogram"></i></h4>
                    <h2>{{number_format($data->secondary[0]->qty)}}</h2>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script> --}}
@endsection
