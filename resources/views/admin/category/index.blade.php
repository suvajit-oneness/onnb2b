@extends('admin.layouts.app')
@section('page', 'Category')
@section('content')
<section>
    <div class="row">
        <div class="col-xl-8 order-2 order-xl-1">
            <div class="card">
                <div class="card-body">
                    <div class="search__filter">
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <ul>
                                    <li class="active"><a href="{{ route('admin.category.index') }}">All <span class="count">({{$data->total()}})</span></a></li>
                                    @php
                                    $activeCount = $inactiveCount = 0;
                                    foreach ($data as $catKey => $catVal) {
                                    if ($catVal->status == 1) $activeCount++;
                                    else $inactiveCount++;
                                    }
                                    @endphp
                                    <li><a href="{{ route('admin.category.index', ['status' => 'active'])}}">Active <span class="count">({{$activeCount}})</span></a></li>
                                    <li><a href="{{ route('admin.category.index', ['status' => 'inactive'])}}">Inactive <span class="count">({{$inactiveCount}})</span></a></li>
                                </ul>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('admin.category.index') }}" method="GET">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <input type="search" name="term" class="form-control" placeholder="Search here.." id="term" value="{{app('request')->input('term')}}" autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Search Category</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="col-auto">
                                <a href="{{ route('admin.category.index',['export_all'=>'true']) }}"  class="btn btn-outline-danger btn-sm float-right"><i class="fa fa-cloud-download"></i> CSV Export</a></div>
                        </div>
                    </div>
                        <table class="table" id="example5">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Icon</th>
                                    <th class="text-center"><i class="fi fi-br-picture"></i> Sketch</th>
                                    <th>Name</th>
                                    <th>Products</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)

                                @php
                                if (!empty($_GET['status'])) {
                                if ($_GET['status'] == 'active') {
                                if ($item->status == 0) continue;
                                } else {
                                if ($item->status == 1) continue;
                                }
                                }
                                @endphp
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td class="text-center column-thumb">
                                        <img src="{{ asset($item->icon_path) }}">
                                    </td>
                                    <td class="text-center column-thumb">
                                        <img src="{{ asset($item->sketch_icon) }}">
                                    </td>
                                    <td>
                                        <h3 class="text-dark">{{$item->name}}</h3>
                                        <p>{{$item->parentCatDetails ? $item->parentCatDetails->name : ''}}</p>
                                        <div class="row__action">
                                            <a href="{{ route('admin.category.view', $item->id) }}">Edit</a>
                                            <a href="{{ route('admin.category.view', $item->id) }}">View</a>
                                            <a href="{{ route('admin.category.status', $item->id) }}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</a>
                                            <a href="{{ route('admin.category.delete', $item->id) }}" class="text-danger">Delete</a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.category.view', $item->id) }}">{{$item->ProductDetails->count()}} products</a>
                                    </td>
                                    <td>Published<br />{{date('d M Y', strtotime($item->created_at))}}</td>
                                    <td><span class="badge bg-{{($item->status == 1) ? 'success' : 'danger'}}">{{($item->status == 1) ? 'Active' : 'Inactive'}}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="100%" class="small text-muted">No data found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $data->appends($_GET)->links() }}
                        </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 order-1 order-xl-2">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="page__subtitle">Add New Category</h4>
                        <div class="row">
                            <div class="col-12 col-md-6 col-xl-12">
                                <div class="form-group mb-3">
                                    <label class="label-control">Name <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" placeholder="" class="form-control" value="{{old('name')}}">
                                    @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label-control">Parent <span class="text-danger">*</span> </label>
                                    <input type="text" name="parent" placeholder="" class="form-control" value="{{old('parent')}}">
                                    @error('parent') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label-control">Description </label>
                                    <textarea name="description" class="form-control">{{old('description')}}</textarea>
                                    @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-12">
                                <div class="row">
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Icon <span class="text-danger">*</span></div>
                                        <div class="card-body p-0">
                                            <div class="w-100 product__thumb">
                                                <label for="icon"><img id="iconOutput" src="{{ asset('admin/images/placeholder-image.jpg') }}" /></label>
                                            </div>
                                            <input type="file" name="icon_path" id="icon" accept="image/*" onchange="loadIcon(event)" class="d-none">
                                            <script>
                                                let loadIcon = function(event) {
                                                    let iconOutput = document.getElementById('iconOutput');
                                                    iconOutput.src = URL.createObjectURL(event.target.files[0]);
                                                    iconOutput.onload = function() {
                                                        URL.revokeObjectURL(iconOutput.src) // free memory
                                                    }
                                                };
                                            </script>
                                        </div>
                                        @error('icon_path') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Sketch icon <span class="text-danger">*</span></div>
                                        <div class="card-body p-0">
                                            <div class="w-100 product__thumb">
                                                <label for="sketch_icon"><img id="sketchOutput" src="{{ asset('admin/images/placeholder-image.jpg') }}" /></label>
                                            </div>
                                            <input type="file" name="sketch_icon" id="sketch_icon" accept="image/*" onchange="loadSketch(event)" class="d-none">
                                            <script>
                                                let loadSketch = function(event) {
                                                    let sketchOutput = document.getElementById('sketchOutput');
                                                    sketchOutput.src = URL.createObjectURL(event.target.files[0]);
                                                    sketchOutput.onload = function() {
                                                        URL.revokeObjectURL(sketchOutput.src) // free memory
                                                    }
                                                };
                                            </script>
                                        </div>
                                        @error('sketch_icon') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Thumbnail <span class="text-danger">*</span></div>
                                        <div class="card-body p-0">
                                            <div class="w-100 product__thumb">
                                                <label for="thumbnail"><img id="output" src="{{ asset('admin/images/placeholder-image.jpg') }}" /></label>
                                            </div>
                                            <input type="file" name="image_path" id="thumbnail" accept="image/*" onchange="loadFile(event)" class="d-none">
                                            <script>
                                                let loadFile = function(event) {
                                                    let output = document.getElementById('output');
                                                    output.src = URL.createObjectURL(event.target.files[0]);
                                                    output.onload = function() {
                                                        URL.revokeObjectURL(output.src) // free memory
                                                    }
                                                };
                                            </script>
                                        </div>
                                        @error('image_path') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-6 card">
                                        <div class="card-header p-0 mb-3">Banner <span class="text-danger">*</span></div>
                                        <div class="card-body p-0">
                                            <div class="w-100 product__thumb">
                                                <label for="banner"><img id="bannerOutput" src="{{ asset('admin/images/placeholder-image.jpg') }}" /></label>
                                            </div>
                                            <input type="file" name="banner_image" id="banner" accept="image/*" onchange="loadBanner(event)" class="d-none">
                                            <script>
                                                let loadBanner = function(event) {
                                                    let output = document.getElementById('bannerOutput');
                                                    output.src = URL.createObjectURL(event.target.files[0]);
                                                    output.onload = function() {
                                                        URL.revokeObjectURL(output.src) // free memory
                                                    }
                                                };
                                            </script>
                                        </div>
                                        @error('banner_image') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-danger">Add New Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
    function htmlToCSV() {
        var data = [];
        var rows = document.querySelectorAll("#example5 tbody tr");
        @php
            if (!request()->input('page')) {
                $page = '1';
            } else {
                $page = request()->input('page');
            }
        @endphp

        var page = "{{ $page }}";

        data.push("SRNO,Icon,Sketch,Name,Product,Date,Status");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td");

            for (var j = 0; j < cols.length ; j++) {

                var text = cols[j].innerText.split(' ');
                var new_text = text.join('-');
                if (j == 3||j==5)
                    var comtext = new_text.replace(/\n/g, "-");
                else
                    var comtext = new_text.replace(/\n/g, ";");
                row.push(comtext);

            }
            data.push(row.join(","));

        }

        downloadCSVFile(data.join("\n"), 'Category.csv');
    }

    function downloadCSVFile(csv, filename) {
        var csv_file, download_link;

        csv_file = new Blob([csv], {
            type: "text/csv"
        });

        download_link = document.createElement("a");

        download_link.download = filename;

        download_link.href = window.URL.createObjectURL(csv_file);

        download_link.style.display = "none";

        document.body.appendChild(download_link);

        download_link.click();
    }


</script>
 @if (request()->input('export_all') == true)
                <script>
                    htmlToCSV();
                    window.location.href = "{{ route('admin.category.index') }}";
                </script>
            @endif
@endsection
