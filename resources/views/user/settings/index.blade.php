@extends('admin.layouts.app')

@section('page', 'Settings')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
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
                                <th>Page</th>
                                <th>Content</th>
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
                                    {{strtoupper($item->page_heading)}}
                                </td>
                                <td>
                                {{$item->content}}
                                <div class="row__action">
                                    <a href="{{ route('admin.settings.view', $item->id) }}">Edit</a>
                                    <a href="{{ route('admin.settings.view', $item->id) }}">View</a>
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
    </div>
</section>
@endsection