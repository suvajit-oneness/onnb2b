@extends('admin.layouts.app')

@section('page', 'Sales Report')

@section('content')
<div class="col-sm-12">
    <div class="profile-card">
        {{-- <h3>Sales Report</h3> --}}

        <section class="store_listing">
            <div class="row">
                {{-- for all users except distributor --}}

                    <div class="col-12">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                <h5 class="card-title"></h5>
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {{-- {{ dd($loggedInUser) }} --}}
                                                <label for="">Select Vp</label>

                                                <select name="vp" class="form-control">
                                                 <option value="">Select</option>
                                                    @foreach ($loggedInUser as $vp)

                                                        <option value="{{$vp->vp}}" {{ ($vp->vp == request()->input('vp')) ? 'selected' : '' }}>{{$vp->vp}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                 <label for="">Select state</label>
                                                {{-- {{ dd($vp_states) }} --}}
                                                <select name="state" class="form-control">
                                                    <option value="">Select</option>
                                                    @foreach ($vp_states as $state)

                                                        <option value="{{$state->state}}" {{ ($state->state == request()->input('state')) ? 'selected' : '' }}>{{$state->state}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Date from</label>
                                                <input type="date" name="from" class="form-control" value="{{ (request()->input('from')) ? request()->input('from') : date('Y-m-01') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Date to</label>
                                                <input type="date" name="to" class="form-control" value="{{ (request()->input('to')) ? request()->input('to') : date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="" style="visibility: hidden;">save</label>
                                                <br>
                                                <button type="submit" class="btn btn-danger">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            {{-- </div>
                        </div> --}}
                    </div>
                </section>
                   <br/> <br>
                <section class="store_listing">
                   <div class="row">
                    <div class="col-12">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Region</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($regionWisereport as $item)
                                            <tr>
                                                <td>{{$item->area}}</td>
                                                <td>Rs {{number_format($item->value)}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%">No data found for this selection</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            {{-- </div>
                        </div> --}}
                    </div>

                    <div class="col-12">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                <h5 class="card-title">Team wise report</h5>

                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Regional sales manager</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($RSMwiseReport as $item)
                                            <tr>
                                                <td><a href="{{ route('admin.sales.report.detail', ['rsm' => ($item->name == null) ? 'NA' : $item->name, 'state' => request()->input('state')]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a></td>
                                                <td>Rs {{number_format($item->value)}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%">No data found for this selection</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            {{-- </div>
                        </div> --}}
                    </div>
                    {{-- @if (\Auth::guard('web')->user()->user_type == 1 || \Auth::guard('web')->user()->user_type == 2) --}}
                    <div class="col-12">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Area sales manager</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($ASMwiseReport as $item)
                                            <tr>
                                                <td><a href="{{ route('admin.sales.report.detail', ['asm' => ($item->name == null) ? 'NA' : $item->name, 'state' => request()->input('state')]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a></td>
                                                <td>Rs {{number_format($item->value)}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%">No data found for this selection</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            {{-- </div>
                        </div> --}}
                    </div>
                    {{-- @endif --}}
                    {{-- @if (\Auth::guard('web')->user()->user_type == 1 || \Auth::guard('web')->user()->user_type == 2) --}}
                    <div class="col-12">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Area sales executive</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($ASEwiseReport as $item)
                                        {{-- {{dd($ASEwiseReport)}} --}}

                                            @if ( $item->name)

                                                <tr>
                                                    <td><a href="{{ route('admin.sales.report.detail', ['ase' => ($item->name == null) ? 'NA' : $item->name, 'state' => request()->input('state')]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a></td>
                                                    <td>Rs {{number_format($item->value)}}</td>
                                                </tr>

                                        @else
                                            <tr>
                                                <td><a href="{{ route('admin.sales.report.detail', ['ase' => ($item->name == null) ? 'NA' : $item->name, 'state' => request()->input('state')]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a></td>
                                                <td>Rs {{number_format($item->value)}}</td>
                                            </tr>
                                        @endif
                                        @empty
                                            <tr>
                                                <td colspan="100%">No data found for this selection</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            {{-- </div>
                        </div> --}}
                    </div>
                    {{-- @endif --}}

                {{-- for distributor only --}}

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order placed to company by Distributor</h5>

                            {{-- <form action="" method="get" class="row">
                                <div class="col-12">
                                    <p class="small">Select date range</p>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Date from</label>
                                        <input type="date" name="from" class="form-control form-control-sm" value="{{ (request()->input('from')) ? request()->input('from') : date('Y-m-01') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Date to</label>
                                        <input type="date" name="to" class="form-control form-control-sm" value="{{ (request()->input('to')) ? request()->input('to') : date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" style="visibility: hidden;">save</label>
                                        <br>
                                        <button type="submit" class="btn btn-sm btn-danger">Apply</button>
                                        <a href="{{route('front.dashboard.index')}}" class="btn btn-sm btn-light border" data-toggle="tooltip" data-placement="top" title="Remove filter">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </form> --}}

                            <table class="table table-sm table-hover">
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            {{-- <td><a href="{{ route('admin.distributor.order.index') }}">Order details</a></td> --}}
                                            <td><a href="{{ route('admin.distributor.order.index',  [ 'distributor' => ($item->name == null) ? 'NA' : $item->name]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a></td>
                                            <td>Rs {{number_format($item->value)}}</td>
                                         </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">No data found for this selection</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection
