@extends('admin.layouts.app')

@section('page', 'Address')

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
                                <th>User</th>
                                <th>Address</th>
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
                                    {{$item->user->fname.' '.$item->user->lname}}
                                </td>
                                <td>
                                {{$item->address}}
                                <div class="row__action">
                                    <a href="{{ route('admin.address.view', $item->id) }}">Edit</a>
                                    <a href="{{ route('admin.address.view', $item->id) }}">View</a>
                                    <a href="{{ route('admin.address.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                    <a href="{{ route('admin.address.delete', $item->id) }}" class="text-danger">Delete</a>
                                </div>
                                </td>
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
                    <form method="POST" action="{{ route('admin.address.store') }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Add New</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">User <span class="text-danger">*</span> </label>
                            <select class="form-control" name="user_id">
                                <option hidden selected>Select user...</option>
                                @foreach ($users as $index => $item)
                                    <option value="{{$item->id}}" {{ (old('user_id')) ?? (old('user_id') == $item->id) ? 'selected' : ''  }}>{{ $item->fname.' '.$item->lname }}</option>
                                @endforeach
                            </select>
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Address </label>
                            <textarea name="address" class="form-control">{{old('address')}}</textarea>
                            @error('address') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Landmark <span class="text-danger">*</span> </label>
                            <input type="text" name="landmark" placeholder="" class="form-control" value="{{old('landmark')}}">
                            @error('landmark') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">lat <span class="text-danger">*</span> </label>
                            <input type="text" name="lat" placeholder="" class="form-control" value="{{old('lat')}}">
                            @error('lat') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">lng <span class="text-danger">*</span> </label>
                            <input type="text" name="lng" placeholder="" class="form-control" value="{{old('lng')}}">
                            @error('lng') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">state <span class="text-danger">*</span> </label>
                            <input type="text" name="state" placeholder="" class="form-control" value="{{old('state')}}">
                            @error('state') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">city <span class="text-danger">*</span> </label>
                            <input type="text" name="city" placeholder="" class="form-control" value="{{old('city')}}">
                            @error('city') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">pin <span class="text-danger">*</span> </label>
                            <input type="number" name="pin" placeholder="" class="form-control" value="{{old('pin')}}">
                            @error('pin') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">type <span class="text-danger">*</span> </label>
                            <input type="text" name="type" placeholder="" class="form-control" value="{{old('type')}}">
                            @error('type') <p class="small text-danger">{{ $message }}</p> @enderror
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