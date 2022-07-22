@extends('admin.layouts.app')

@section('page', 'Collection detail')

@section('content')
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ $data->name }}</h3>
                            <p class="small">{{ $data->description }}</p>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted">Icon</p>
                            <img src="{{ asset($data->icon_path) }}" alt="" style="height: 50px">
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted">Sketch</p>
                            <img src="{{ asset($data->sketch_icon) }}" alt="" style="height: 10px">
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted">Thumbnail</p>
                            <img src="{{ asset($data->image_path) }}" alt="" style="height: 50px">
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted">Banner</p>
                            <img src="{{ asset($data->banner_image) }}" alt="" style="height: 50px">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-muted">Products</h3>
                            <p>{{$data->ProductDetails->count()}} products total</p>

							@php
							$categories = \DB::select('SELECT p.cat_id, c.name, count(p.id) AS products FROM `products` p INNER JOIN categories c ON c.id = p.cat_id WHERE p.collection_id = '.$data->id.' GROUP BY p.cat_id ORDER BY c.position ASC;');

							echo '<p>Categories Under '.$data->name.' - ';
							foreach($categories as $category) {
								echo $category->name.'('.$category->products.'), ';
							}
							echo '</p>';
							@endphp

                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Package</th>
                                    <th>Color+Size</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data->ProductDetails as $index => $item)
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
                                        {{-- <td class="text-center column-thumb">
                                            <img src="{{asset('img/product-box.png')}}" />
                                        </td> --}}
										<td>{{$item->id}}</td>
                                        <td>
											<p class="mb-1 text-dark">{{$item->style_no}}</p>
                                            <p class="small text-muted">{{$item->name}}</p>
                                            <div class="row__action">
                                                <a href="{{ route('admin.product.edit', $item->id) }}">Edit</a>
                                                <a href="{{ route('admin.product.view', $item->id) }}">View</a>
                                            </div>
                                        </td>
                                        <td>{{$item->category ? $item->category->name : ''}}</td>
										
										<td>{{$item->master_pack}}</td>
										<td>
											@php
											$colors = \DB::select('SELECT color, color_name FROM `product_color_sizes` WHERE product_id = '.$item->id.' GROUP BY color;');
											foreach($colors as $color) {
												echo '<p class="small text-dark d-flex">'.$color->color_name.'(#'.$color->color.')';
												$sizes = \DB::select('SELECT size, size_name, offer_price, size_details FROM `product_color_sizes` WHERE product_id = '.$item->id.' AND color = '.$color->color.' GROUP BY size;');
												echo '<span class="ms-auto">No of sizes - ';
												echo count($sizes);
											echo '</span></p>';
											echo '<table class="table no-shadow">';
											echo '<tr><th class="px-0">Size</th><th class="px-0">Price</th></tr>';
													
												foreach($sizes as $size) {
											echo '<tr><td class="p-0"><p class="small text-dark">'.$size->size_name.'(#'.$size->size.') - '.$size->size_details.'</p></td>';
											echo '<td class="p-0"><p class="small text-dark">Rs'.$size->offer_price.'</p></td></tr>';
												}
											echo '</table>';
											}
											@endphp
										</td>
											
                                        <td>
                                            Rs. {{$item->offer_price}}
                                        </td>
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
        </div>

        {{-- <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.collection.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                        <h4 class="page__subtitle">Edit Collection</h4>
                        <div class="form-group mb-3">
                            <label class="label-control">Name <span class="text-danger">*</span> </label>
                            <input type="text" name="name" placeholder="" class="form-control" value="{{ $data->name }}">
                            @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label-control">Description </label>
                            <textarea name="description" class="form-control">{{$data->description}}</textarea>
                            @error('description') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 card">
                                <div class="card-header p-0 mb-3">Icon <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="icon"><img id="iconOutput" src="{{ asset($data->icon_path) }}" /></label>
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
                                <div class="card-header p-0 mb-3">Sketch Icon <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="sketch_icon"><img id="outputSketch" src="{{ asset($data->sketch_icon) }}" /></label>
                                    </div>
                                    <input type="file" name="sketch_icon" id="sketch_icon" accept="image/*" onchange="loadSketch(event)" class="d-none">
                                    <script>
                                        var loadSketch = function(event) {
                                            var outputSketch = document.getElementById('outputSketch');
                                            outputSketch.src = URL.createObjectURL(event.target.files[0]);
                                            outputSketch.onload = function() {
                                                URL.revokeObjectURL(outputSketch.src) // free memory
                                            }
                                        };
                                    </script>
                                </div>
                                @error('sketch_icon') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 card">
                                <div class="card-header p-0 mb-3">Image <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="thumbnail"><img id="output" src="{{ asset($data->image_path) }}" /></label>
                                    </div>
                                    <input type="file" name="image_path" id="thumbnail" accept="image/*" onchange="loadFile(event)" class="d-none">
                                    <script>
                                        var loadFile = function(event) {
                                            var output = document.getElementById('output');
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
                                <div class="card-header p-0 mb-3">Banner Image <span class="text-danger">*</span></div>
                                <div class="card-body p-0">
                                    <div class="w-100 product__thumb">
                                        <label for="banner"><img id="bannerOutput" src="{{ asset($data->banner_image) }}" /></label>
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
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-danger">Update Collection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>
</section>
@endsection
