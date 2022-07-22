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
                    <h4>No of Customer <i class="fi fi-br-user"></i></h4>
                    <h2><a href="{{ route('admin.user.index') }}"> {{$data->users}}</a></h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-info">
                <div class="card-body">
                    <h4>Collection <i class="fi fi-br-chart-histogram"></i></h4>
                    <h2><a href="{{ route('admin.collection.index') }}"> {{$data->collection}}</a></h2>
                </div>
            </div>
        </div>



        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-secondary">
                <div class="card-body">
                    <h4>No of Product <i class="fi fi-br-cube"></i></h4>
                    <h2><a href="{{ route('admin.product.index') }}"> {{$data->products->count()}}</a></h2>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>Distributor <i class="fi fi-br-user"></i></h4>
                    <h2><a href="{{ route('admin.distributor.index') }}"> {{$distributor}}</a></h2>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>Retailer <i class="fi fi-br-user"></i></h4>
                    <h2><a href="{{ route('admin.store.index') }}"> {{$data->retailer}}</a></h2>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>State <i class="fi fi-br-chart-histogram"></i></h4>
                    <h2>{{$data->state}}</h2>
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
        {{-- <div class="col-sm-6 col-lg-3">
            <div class="card home__card bg-gradient-success">
                <div class="card-body">
                    <h4>Order <i class="fi fi-br-chart-histogram"></i></h4>
                    <h2>{{$data->orders}}</h2>
                </div>
            </div>
        </div> --}}
    </div>
</section>
{{-- <section>
    <div class="row">
        <div class="col-xl-6">
            <h5>Products List</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center"><i class="fi fi-br-picture"></i></th>
                        <th>Name</th>
                        <th>Style</th>
                        <th>Category</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->products as $productKey => $product)
                        @php if($productKey == 5) break;  @endphp
                        <tr>
                            <td class="text-center column-thumb">
                                <img src="{{asset($product->image)}}">
                            </td>
                            <td>
                                <p style="height: 42px;overflow: hidden;text-overflow: ellipsis;margin-bottom: 0;">{{$product->name}}</p>
                                <div class="row__action">
                                    <a href="{{ route('admin.product.edit', $product->id) }}">Edit</a>
                                    <a href="{{ route('admin.product.view', $product->id) }}">View</a>
                                </div>
                            </td>
                            <td>{{$product->style_no}}</td>
                            <td>{{$product->category->name}}</td>
                            <td>Rs. {{$product->offer_price}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-xl-6">
            <h5>Recent orders</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->orders as $order)
                        @php
                            switch($order->status) {
                                case 1:$status = 'New';break;
                                case 2:$status = 'Confirmed';break;
                                case 3:$status = 'Shipped';break;
                                case 4:$status = 'Delivered';break;
                                case 5:$status = 'Cancelled';break;
                            }
                        @endphp
                        <tr>
                            <td><a href="{{ route('admin.order.view', $order->id) }}">#{{$order->order_no}}</a></td>
                            <td>{{date('j M Y g:i A', strtotime($order->created_at))}}</td>
                            <td>Rs {{$order->final_amount}}</td>
                            <td><span class="badge bg-info">{{ $status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section> --}}


<section>
    <div class="row">
        <div class="col-xl-6">
            <h5>Store List</h5>
            <table class="table">
                <thead>
                    <tr>

                        <th>Store Name</th>
                        <th>Firm Name</th>
                        <th>Contact</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($store as $productKey => $product)
                        <tr>
                            <td>{{$product->store_name}}</td>
                            <td>{{$product->bussiness_name}}</td>
                            <td>{{$product->email}}<br>{{$product->contact}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-xl-6">
            <h5>User Activity </h5>
            <table class="table">
                <thead>
                    <tr>

                        <th>User Name</th>
                        <th>Login Time</th>
                        <th>Location</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($activity as $productKey => $item)


                        <tr>
                            <td>{{$item->users ? $item->users->fname : ''}} {{ $item->users ? $item->users->lname : ''}}</td>

                            <td>{{$item->date.' '.$item->time}}</td>
                            <td>{{$item->location}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @php
            $stateReportNameArray = $stateReportValueArray = [];
        @endphp
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="stateReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100" id="distributorCard" style="max-height: 360px;overflow:hidden">
                    <div class="card-body">
                        <h5 class="card-title">States report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($stateWiseReport as $distributorKey => $item)

                                    @php
                                        $stateReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $stateReportValueArray[] = ($item->value == null) ? 0 : $item->value;

                                    @endphp
                                    <tr>

                                        <td>

                                            <a href="{{ route('admin.sales.report.index', ['state' => ($item->name == null) ? 'NA' : $item->name]) }}"> {{ ($item->name == null) ? 'NA' : $item->name }}</a>
                                        </td>
                                        <td>Rs {{number_format($item->value)}}</td>
                                    </tr>
                                    @if ($distributorKey == 4)
                                    <tr>
                                        <td colspan="100%" class="text-end">
                                            <a href="javascript: void(0)" id="distributorShowMore">Show more</a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('admin.sales.report.index') }}" class="btn btn-sm btn-danger float-right">View complete report</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            @php
                $regionWiseReportAreaArray = $regionWiseReportValueArray = [];
            @endphp
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Region report</h5>

                        <form action="" method="get" class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Select state</label>
                                    @php
                                        // $loggedInUserType = \Auth::guard('web')->user()->user_type;
                                        // $loggedInUserState = \Auth::guard('web')->user()->state;

                                        // (count($vp_states) != 0) ? $vp_states = $vp_states : $vp_states = $loggedInUserState;
                                    @endphp
                                    @if (count($vp_states) != 0)
                                        <select name="state" class="form-control form-control-sm">
                                            @foreach ($vp_states as $state)
                                                <option value="{{$state->state}}"
                                                @if (request()->input('state'))
                                                    @if ($state->state == request()->input('state'))
                                                        {{'selected'}}
                                                    @endif
                                                {{-- @else
                                                    @if ($state->state == $loggedInUserState)
                                                        {{'selected'}}
                                                    @endif --}}
                                                @endif
                                                >{{$state->state}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{-- <select name="state" class="form-control form-control-sm">
                                            <option value="{{$loggedInUserState}}">{{$loggedInUserState}}</option>
                                        </select> --}}
                                    @endif
                                </div>
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
                                    <a href="{{route('admin.home')}}" class="btn btn-sm btn-light border" data-toggle="tooltip" data-placement="top" title="Remove filter">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    </a>
                                </div>
                            </div>
                        </form>

                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Region</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($regionWiseReport as $item)
                                    @php
                                        $regionWiseReportAreaArray[] = $item->area;
                                        $regionWiseReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>{{$item->area}}</td>
                                        <td>Rs {{number_format($item->value)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('admin.sales.report.index') }}" class="btn btn-sm btn-danger float-right">View complete report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="regionReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @php
            $RSMwiseReportNameArray = $RSMwiseReportValueArray = [];
        @endphp


        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="rsmReportDiv" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Regional sales manager report</h5>
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($RSMwiseReport as $item)
                                    @php
                                        $RSMwiseReportNameArray[] = ($item->name == null) ? 'NA' : $item->name;
                                        $RSMwiseReportValueArray[] = ($item->value == null) ? 0 : $item->value;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{-- {{ ($item->name == null) ? 'NA' : $item->name }} --}}
                                            <a href="{{ route('front.sales.report.detail', ['rsm' => ($item->name == null) ? 'NA' : $item->name, 'state' => $item->state]) }}">{{ ($item->name == null) ? 'NA' : $item->name }}</a>
                                        </td>
                                        <td>Rs {{number_format($item->value)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('admin.sales.report.index') }}" class="btn btn-sm btn-danger float-right">View complete report</a>
                    </div>
                </div>
            </div>
        </div>

    </div>


    </div>
</section>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>

    <script>

        // State report
        var labelValues0 = [];
        var dataValues0 = [];
        labelValues0 = <?php echo json_encode($stateReportNameArray); ?>;
        dataValues0 = <?php echo json_encode($stateReportValueArray); ?>;

        // console.log(labelValues0);

        const ctx0 = document.getElementById('stateReportDiv').getContext('2d');
        const stateReportDiv = new Chart(ctx0, {
            type: 'bar',
            data: {
                labels: labelValues0,
                datasets: [{
                    label: 'State report',
                    data: dataValues0,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

  // Region report
        var labelValues1 = [];
        var dataValues1 = [];
        labelValues1 = <?php echo json_encode($regionWiseReportAreaArray); ?>;
        dataValues1 = <?php echo json_encode($regionWiseReportValueArray); ?>;

        const ctx = document.getElementById('regionReportDiv').getContext('2d');
        const regionReportDiv = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelValues1,
                datasets: [{
                    label: ' state report',
                    data: dataValues1,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // RSM report
        var labelValues2 = [];
        var dataValues2 = [];
        labelValues2 = <?php echo json_encode($RSMwiseReportNameArray); ?>;
        dataValues2 = <?php echo json_encode($RSMwiseReportValueArray); ?>;

        const ctx2 = document.getElementById('rsmReportDiv').getContext('2d');
        const rsmReportDiv = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labelValues2,
                datasets: [{
                    label: 'Regional sales manager report',
                    data: dataValues2,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        $('#distributorShowMore').on('click', function() {
            $(this).parent().parent().hide();
            $('#distributorCard').css('maxHeight', '100%');
        });
    </script>
@endsection
