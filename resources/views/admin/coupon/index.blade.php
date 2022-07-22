@extends('admin.layouts.app')

@section('page', 'Coupon')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">    
                <div class="card-body">

                    {{-- <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="#">All <span class="count">({{$data->count()}})</span></a></li>
                                    <li><a href="#">Active <span class="count">(7)</span></a></li>
                                    <li><a href="#">Inactive <span class="count">(3)</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form>
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="search" name="" class="form-control" placeholder="Search here..">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Search Product</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="filter">
                        <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <form>
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                <select class="form-control">
                                    <option>Bulk Action</option>
                                    <option>Delect</option>
                                </select>
                                </div>
                                <div class="col-auto">
                                <button type="submit" class="btn btn-outline-danger btn-sm">Apply</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div class="col-auto">
                            <p>{{$data->count()}} Items</p>
                        </div>
                        </div>
                    </div> --}}

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="check-column">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault"></label>
                                </div>
                                </th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Validity</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                            <tr>
                                <td class="check-column">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault"></label>
                                    </div>
                                </td>
                                <td>
                                {{$item->name}}
                                <div class="row__action">
                                    <a href="{{ route('admin.coupon.view', $item->id) }}">Edit</a>
                                    <a href="{{ route('admin.coupon.view', $item->id) }}">View</a>
                                    <a href="{{ route('admin.coupon.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                    <a href="{{ route('admin.coupon.delete', $item->id) }}" class="text-danger">Delete</a>
                                </div>
                                </td>
                                <td>{{$item->coupon_code}}</td>
                                <td>{{ ($item->type == 1) ? 'Flat' : 'Offer' }}</td>
                                <td>{{$item->amount}}</td>
                                <td>{{date('d M Y', strtotime($item->start_date))}} - {{date('d M Y', strtotime($item->end_date))}}</td>
                                <td>Published<br/>{{date('d M Y', strtotime($item->created_at))}}</td>
                                <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.coupon.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Coupon code <span class="text-danger">*</span> </label>
                            <input type="text" name="coupon_code" placeholder="" class="form-control" value="{{old('coupon_code')}}">
                            @error('coupon_code') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Type <span class="text-danger">*</span> </label>
                            <input type="text" name="type" placeholder="" class="form-control" value="{{old('type')}}">
                            @error('type') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Amount <span class="text-danger">*</span> </label>
                            <input type="number" name="amount" placeholder="" class="form-control" value="{{old('amount')}}">
                            @error('amount') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Max time of use <span class="text-danger">*</span> </label>
                            <input type="number" name="max_time_of_use" placeholder="" class="form-control" value="{{old('max_time_of_use')}}">
                            @error('max_time_of_use') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Max time one can use <span class="text-danger">*</span> </label>
                            <input type="number" name="max_time_one_can_use" placeholder="" class="form-control" value="{{old('max_time_one_can_use')}}">
                            @error('max_time_one_can_use') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Start date <span class="text-danger">*</span> </label>
                            <input type="date" name="start_date" placeholder="" class="form-control" value="{{old('start_date')}}">
                            @error('start_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">End date <span class="text-danger">*</span> </label>
                            <input type="date" name="end_date" placeholder="" class="form-control" value="{{old('end_date')}}">
                            @error('end_date') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Add New</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection