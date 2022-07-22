@extends('admin.layouts.app')

@section('page', 'Transaction')

@section('content')
<section>
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
                <button type="submit" class="btn btn-outline-danger btn-sm">Search</button>
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
                    <option>Select Category</option>
                    <option>T-shirt</option>
                    <option>Jacket</option>
                    <option>Vests</option>
                    <option>Brief</option>
                    <option>Track Pants</option>
                    <option>Joggers</option>
                    <option>Socks</option>
                    <option>Sweatshirt</option>
                    <option>Thermal</option>
                    <option>Trunks</option>
                    <option>Boxer</option>
                </select>
                </div>
                <div class="col-auto">
                <select class="form-control">
                    <option>Select Range</option>
                    <option>Grandde</option>
                    <option>Stretchz</option>
                    <option>Sport</option>
                    <option>Comfortz</option>
                    <option>Acttive</option>
                    <option>Platina</option>
                    <option>Relaxz</option>
                    <option>Footkins</option>
                    <option>Thermal</option>
                    <option>Winter</option>
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
            <th>User</th>
            <th>Transaction</th>
            <th>Order</th>
            <th>Amount</th>
            <th>Datetime</th>
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
                <td>
                    <p class="small text-dark mb-1">{{$item->userDetails->fname.' '.$item->userDetails->lname}}</p>
                    <p class="small text-muted mb-0">{{$item->userDetails->email.' | '.$item->userDetails->mobile}}</p>
                    <div class="row__action">
                        <a href="{{ route('admin.transaction.view', $item->id) }}">View</a>
                    </div>
                </td>
                <td>
                    {{$item->transaction}}
                </td>
                <td>
                    <a href="{{ route('admin.order.view', $item->orderDetails->id) }}" class="badge bg-primary text-decoration-none">View Order</a>
                </td>
                <td>
                    <p class="small text-muted mb-1">Rs {{$item->amount}}</p>
                </td>
                <td>
                    <p class="small">{{date('j M Y g:i A', strtotime($item->created_at))}}</p>
                </td>
                <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
            </tr>
            @empty
            <tr><td colspan="100%" class="small text-muted">No data found</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection