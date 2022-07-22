@extends('admin.layouts.app')

@section('page', 'Edit Product')

@section('content')

<style>
    .color_holder {
        display: flex;
        border: 1px dashed #ddd;
        border-radius: 6px;
        padding: 5px;
        background: #f0f0f0;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .color_holder_single {
        margin: 5px;
    }
    .color_box {
        display: flex;
        padding: 6px 10px;
        border-radius: 3px;
        align-items: center;
        margin: 0;
        background: #fff;
    }
    .color_box p {
        margin: 0;
    }
    .color_box span {
        margin-right: 10px;
    }
    .sizeUpload {
        margin-bottom: 10px;
    }
    .size_holder {
        padding: 10px 0;
        border-top: 1px solid #ddd;
    }
    .img_thumb {
        width: 100%;
        padding-bottom: calc((4/3)*100%);
        position: relative;
        border:  1px solid #ccc;
        max-width: 80px;
        min-width: 80px;
    }
    .img_thumb img {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        object-fit: contain;
    }
    .remove_image {
        display: inline-flex;
        width: 30px;
        height: 30px;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        position: absolute;
        top: 0;
        right: 0;
    }
    .remove_image i {
        line-height: 13px;
    }
    .image_upload {
        display: inline-flex;
        padding: 0 20px;
        border:  1px solid #ccc;
        background: #ddd;
        padding: 5px 12px;
        border-radius: 3px;
        vertical-align: top;
        cursor: pointer;
    }
    .status-toggle {
        padding: 6px 10px;
        border-radius: 3px;
        align-items: center;
        background: #fff;
    }
    .status-toggle a {
        text-decoration: none;
        color: #000
    }
</style>

<section>
    <form method="POST" action="{{ route('admin.product.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-9">

                <div class="row mb-3">
                    <div class="col-sm-4">
                        <select class="form-control" name="cat_id">
                            <option hidden selected>Select category...</option>
                            @foreach ($categories as $index => $item)
                                <option value="{{$item->id}}" {{ ($data->cat_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('cat_id') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-sm-4">
                        <select class="form-control" name="sub_cat_id">
                            <option hidden selected>Select sub-category...</option>
                            @foreach ($sub_categories as $index => $item)
                                <option value="{{$item->id}}" {{ ($data->sub_cat_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('sub_cat_id') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-sm-4">
                        <select class="form-control" name="collection_id">
                            <option hidden selected>Select collection...</option>
                            @foreach ($collections as $index => $item)
                                <option value="{{$item->id}}" {{ ($data->collection_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('collection_id') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <input type="text" name="name" placeholder="Add Product Title" class="form-control" value="{{$data->name}}">
                    @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        Product Short Description
                    </div>
                    <div class="card-body">
                        <textarea id="product_short_des" name="short_desc">{{$data->short_desc}}</textarea>
                        @error('short_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <textarea id="product_des" name="desc">{{$data->desc}}</textarea>
                    @error('desc') <p class="small text-danger">{{ $message }}</p> @enderror
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        Product data
                    </div>
                    <div class="card-body pt-0">
                        <div class="admin__content">
                        <aside>
                            <nav>Price</nav>
                        </aside>
                        <content>
                            <div class="row mb-2 align-items-center">
                            <div class="col-3">
                                <label for="inputPassword6" class="col-form-label">Regular Price</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="price" value="{{$data->price}}">
                                @error('price') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-auto">
                                <span id="priceHelpInline" class="form-text">
                                Must be 8-20 characters long.
                                </span>
                            </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                            <div class="col-3">
                                <label for="inputprice6" class="col-form-label">Offer Price</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="offer_price" value="{{$data->offer_price}}">
                                @error('offer_price') <p class="small text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-auto">
                                <span id="passwordHelpInline" class="form-text">
                                Must be 8-20 characters long.
                                </span>
                            </div>
                            </div>
                        </content>
                        </div>
                        <div class="admin__content">
                            <aside>
                                <nav>Meta</nav>
                            </aside>
                            <content>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputPassword6" class="col-form-label">Title</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_title" value="{{$data->meta_title}}">
                                        @error('meta_title') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-auto">
                                        <span id="priceHelpInline" class="form-text">
                                        Must be 8-20 characters long.
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Description</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_desc" value="{{$data->meta_desc}}">
                                        @error('meta_desc') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-auto">
                                        <span id="passwordHelpInline" class="form-text">
                                        Must be 8-20 characters long.
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-3">
                                        <label for="inputprice6" class="col-form-label">Keyword</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="meta_keyword" value="{{$data->meta_keyword}}">
                                        @error('meta_keyword') <p class="small text-danger">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-auto">
                                        <span id="passwordHelpInline" class="form-text">
                                        Must be 8-20 characters long.
                                        </span>
                                    </div>
                                </div>
                            </content>
                        </div>
                        <div class="admin__content">
                            <aside>
                                <nav>Data</nav>
                            </aside>
                            <content>
                                <div class="row mb-2 align-items-center">
                                <div class="col-3">
                                    <label for="inputPassword6" class="col-form-label">Style No</label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="inputprice6" class="form-control" aria-describedby="priceHelpInline" name="style_no" value="{{$data->style_no}}">
                                    @error('style_no') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-auto">
                                    <span id="priceHelpInline" class="form-text">
                                    Must be 8-20 characters long.
                                    </span>
                                </div>
                                </div>
                            </content>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card shadow-sm">
                    {{-- <div class="card-header">
                        Update
                    </div> --}}
                    <div class="card-body text-end">
                        <input type="hidden" name="product_id" value="{{$data->id}}">
                        <button type="submit" class="btn btn-sm btn-danger">Update changes</button>
                    </div>
                </div>
                {{-- <div class="card shadow-sm">
                    <div class="card-header">
                        Product Image
                    </div>
                    <div class="card-body">
                        <div class="w-100 product__thumb">
                            <label for="thumbnail"><img id="output" src="{{ asset($data->image) }}"/></label>
                            @error('image') <p class="small text-danger">{{ $message }}</p> @enderror
                        </div>
                        <input type="file" id="thumbnail" accept="image/*" name="image" onchange="loadFile(event)" class="d-none">
                        <small>Image Size: 870px X 1160px</small>
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
                </div> --}}
                {{-- <div class="card shadow-sm">
                    <div class="card-header">More images</div>
                    <div class="card-body">
                        <input type="file" accept="image/*" name="product_images[]" class="mb-3" multiple>
                        @error('product_images') <p class="small text-danger">{{ $message }}</p> @enderror
                        <div class="w-100 product__thumb">
                        @foreach($images as $index => $singleImage)
                            <label for="thumbnail" class="position-relative">
                                <img id="output" src="{{ asset($singleImage->image) }}" class="img-thumbnail mb-3"/>
                                <a href="{{ route('admin.product.image.delete', $singleImage->id) }}" class="btn btn-sm btn-danger product-img-remove" title="Remove this image">&times;</a>
                            </label>
                        @endforeach
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </form>






    <div class="card shadow-sm">
        <div class="card-header">
            <h3>Product variation</h3>
            <p class="small text-muted m-0">Add color | size | multiple images from here</p>
        </div>
        <div class="card-body pt-0">
            <div class="admin__content">
                <aside>
                    <nav>Available colors</nav>
                    <p class="small text-muted">Drag & drop colors to set position</p>
                    <p class="small text-muted">Toggle color status</p>
                </aside>
                <content>
                    @php
                        $test = \DB::select('SELECT pc.id, pc.position, pc.color, c.name as color_id FROM product_color_sizes pc JOIN colors c ON pc.color = c.id WHERE pc.product_id = 13 GROUP BY pc.color ORDER BY pc.position ASC');

                        // dd($test);
                    @endphp

                    <div class="color_holder row_position here">
                        @foreach ($productColorGroup as $productWiseColorsKey => $productWiseColorsVal)
                        <div class="color_holder_single single-color-holder d-flex" id="{{$productWiseColorsVal->id}}">
                            <div class="color_box shadow-sm" style="{!! ($productWiseColorsVal->status == 0) ? 'background: #c1080a59;' : '' !!}">
                                <span style="display:inline-block;width:15px;height:15px;border-radius:50%;background-color:{{ $productWiseColorsVal->colorDetails->code }}"></span>
                                <p class="small card-title">{{ ($productWiseColorsVal->position != 0) ? $productWiseColorsVal->position.' - ' : '' }} {{ ($productWiseColorsVal->colorDetails) ? $productWiseColorsVal->colorDetails->name : '' }}</p>
                            </div>

                            <div class="status-toggle shadow-sm">
                                <a href="javascript: void(0)" onclick="colorStatusToggle({{$productWiseColorsVal->id}}, {{$data->id}}, {{$productWiseColorsVal->color}})"><i class="fi fi-br-cube"></i></a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="#addColorModal" data-bs-toggle="modal" class="btn btn-sm btn-success">Add color</a>
                </content>
            </div>
            @foreach ($productColorGroup as $productColorKey => $productColorGroupVal)
            <div class="admin__content">
                <content>
                    <div class="row">
                        <div class="col-sm-auto">
                            <label for="inputPassword6" class="col-form-label">{{ $productColorKey + 1 }}</label>
                        </div>
                        <div class="col-sm-1">
                            <label for="inputPassword6" class="col-form-label">Color</label>
                        </div>
                        <div class="col-sm-2">
                            <div class="color_box">
                                <span style="display:inline-block;width:15px;height:15px;border-radius:50%;background-color:{{ $productColorGroupVal->colorDetails->code }}"></span>
								<p >{{ ($productColorGroupVal->colorDetails) ? $productColorGroupVal->colorDetails->name : '' }}</p>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-sm-1">
                                    <label for="inputPassword6" class="col-form-label">Size</label>
                                </div>
                                <div class="col-sm-11">
                                    <form action="{{route('admin.product.variation.size.add')}}" class="sizeUpload row g-3" method="post">
                                        @csrf
                                        <div class="col-sm">
                                            <select name="size" class="form-control">
                                                <option value="" selected>Select...</option>
                                                @php
                                                    $sizes = \App\Models\Size::get();
                                                    foreach ($sizes as $key => $value) {
                                                        echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                                    }
                                                @endphp
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="price" placeholder="Price">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="offer_price" placeholder="Offer Price">
                                        </div>
                                        <input type="hidden" name="product_id" value="{{$id}}">
                                        <input type="hidden" name="color_id" value="{{$productColorGroupVal->color}}">
                                        {{-- <input type="hidden" name="_token" value="{{csrf_token()}}"> --}}
                                        <div class="col-sm-auto">
                                            <button type="submit" class="btn btn-sm btn-success">+ Add size</button>
                                        </div>
                                    </form>

                                    @php
                                        $productVariationColorSizes = \App\Models\ProductColorSize::where('product_id', $id)->where('color', $productColorGroupVal->color)->get();

                                        $prodSizesDIsplay = '';
                                        foreach($productVariationColorSizes as $productSizeKey => $productSizeVal) {
                                            $sizeName = $productSizeVal->sizeDetails ? $productSizeVal->sizeDetails->name : '<span class="text-danger" title="Please delete this & add again">SIZE MISMATCH</span>';

                                            $prodSizesDIsplay .= '<div class="size_holder"><div class="row align-items-center"><div class="col-sm">'.$sizeName.'</div><div class="col-sm-3">Price Rs '.$productSizeVal->price.'</div><div class="col-sm-3">Offer Rs '.$productSizeVal->offer_price.'</div><div class="col-sm-auto"><a href='.route('admin.product.variation.size.delete', $productSizeVal->id).' class="btn btn-sm btn-outline-danger">Delete size</a></div></div></div>';
                                        }
                                        $prodSizesDIsplay .= '';
                                    @endphp
                                    {!!$prodSizesDIsplay!!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-1">
                                    {{-- <label for="inputPassword6" class="col-form-label">Images</label> --}}
                                </div>
                                {{-- <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <form action="{{route('admin.product.variation.image.add')}}" method="post" enctype="multipart/form-data">@csrf
                                                <input type="file" name="image[]" id="prodVar{{$productColorKey}}" class="d-none" multiple>
                                                <label class="image_upload" for="prodVar{{$productColorKey}}">Browse Image</label>

                                                <input type="hidden" name="product_id" value="{{$id}}">
                                                <input type="hidden" name="color_id" value="{{$productColorGroupVal->color}}">
                                                <button type="submit" class="btn btn-sm btn-success">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            {{-- <div class="row mt-3">
                                @php
                                    $productVariationImages = \App\Models\ProductImage::where('product_id', $id)->where('color_id', $productColorGroupVal->color)->get();

                                    $prodImagesDIsplay = '';
                                    foreach($productVariationImages as $productImgKey => $productImgVal) {
                                        $prodImagesDIsplay .= '<div class="col-sm-auto" id="img__holder_'.$productColorKey.'_'.$productImgKey.'"><figure class="img_thumb"><img src='.asset($productImgVal->image).'><a href="javascript: void(0)" class="remove_image" onclick="deleteImage('.$productImgVal->id.', '.$productColorKey.', '.$productImgKey.')"><i class="fi fi-br-trash"></i></a></figure></div>';

                                        // $prodImagesDIsplay .= '<img src='.asset($productImgVal->image).' style="height:30px"> - <a href='.route('admin.product.variation.image.delete', $productImgVal->id).' class="text-danger">Delete image</a><br>';
                                    }
                                @endphp
                                {!!$prodImagesDIsplay!!}
                            </div> --}}
                        </div>
                        <div class="col-sm-auto">
                            <a href="javascript: void(0)" class="btn btn-sm btn-success" onclick="editColorModalOpen({{$productColorGroupVal->color}}, '{{ ($productColorGroupVal->colorDetails) ? $productColorGroupVal->colorDetails->name : '' }}')">Edit Color</a>

                            <a href="{{ route('admin.product.variation.color.delete',['productId' => $id, 'colorId' => $productColorGroupVal->color]) }}" onclick="return confirm('Are you sure ?')" class="btn btn-sm btn-danger">Delete Color</a>
                        </div>
                    </div>


                </content>
            </div>
            @endforeach


             {{-- <div class="row">
                <div class="col-12">
                    <h5>Available colors</h5>

                    <div class="row card-holders row_position2">
                    @foreach ($productColorGroup as $productWiseColorsKey => $productWiseColorsVal)
                    <div class="col-md-1 single-color-holder" id="{{$productWiseColorsVal->id}}">
                        <div class="card text-center">
                            <div class="card-body">
                                <span style="display:inline-block;width:15px;height:15px;border-radius:50%;background-color:{{ $productWiseColorsVal->colorDetails->code }}"></span>
                                <p class="small card-title">{{ ($productWiseColorsVal->colorDetails) ? $productWiseColorsVal->colorDetails->name : '' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>

                    <a href="#addColorModal" data-bs-toggle="modal" class="btn btn-sm btn-success">Add color</a>
                </div>
                <div class="col-12">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>SR</th>
                                <th>Color</th>
                                <th>Image</th>
                                <th>Size</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($productColorGroup as $productColorKey => $productColorGroupVal)
                            <tr>
                                <td>{{ $productColorKey + 1 }}</td>
                                <td>
                                    <span style="display:inline-block;width:15px;height:15px;border-radius:50%;background-color:{{ $productColorGroupVal->colorDetails->code }}"></span>
                                    {{ $productColorGroupVal->colorDetails->name }}
                                </td>
                                <td>
                                    <form action="" method="post" enctype="multipart/form-data">@csrf
                                        <input type="file" name="image[]" id="prodVar{{$productColorKey}}" class="d-none" multiple>
                                        <label for="prodVar{{$productColorKey}}">Browse</label>

                                        <input type="hidden" name="product_id" value="{{$id}}">
                                        <input type="hidden" name="color_id" value="{{$productColorGroupVal->color}}">
                                        <button type="submit" class="btn btn-sm btn-success">+</button>
                                    </form>
                                    <hr>
                                </td>
                                <td>
                                    <form action="{{route('admin.product.variation.size.add')}}" class="sizeUpload" method="post">
                                        @csrf
                                        <select name="size">
                                            <option value="" selected>Select...</option>
                                            @php
                                                $sizes = \App\Models\Size::get();
                                                foreach ($sizes as $key => $value) {
                                                    echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                                }
                                            @endphp
                                        </select>
                                        <input type="text" name="price" placeholder="Price">
                                        <input type="text" name="offer_price" placeholder="Offer Price">
                                        <input type="hidden" name="product_id" value="{{$id}}">
                                        <input type="hidden" name="color_id" value="{{$productColorGroupVal->color}}">
                                        <button type="submit" class="btn btn-sm btn-success">+ Add size</button>
                                    </form>
                                    <hr>
                                    @php
                                        $productVariationColorSizes = \App\Models\ProductColorSize::where('product_id', $id)->where('color', $productColorGroupVal->color)->get();

                                        $prodSizesDIsplay = '<table class="table table-sm table-hover"><tbody>';
                                        foreach($productVariationColorSizes as $productSizeKey => $productSizeVal) {
                                            $prodSizesDIsplay .= '<tr><td>'.$productSizeVal->sizeDetails->name.'</td><td>Price Rs '.$productSizeVal->price.'</td><td>Offer Rs '.$productSizeVal->offer_price.'</td><td><a href='.route('admin.product.variation.size.delete', $productSizeVal->id).' class="text-danger">Delete size</a></td></tr>';
                                        }
                                        $prodSizesDIsplay .= '</tbody></table>';
                                    @endphp
                                    {!!$prodSizesDIsplay!!}
                                </td>
                                <td>
                                    <a href="{{ route('admin.product.variation.color.delete',['productId' => $id, 'colorId' => $productColorGroupVal->color]) }}" class="text-danger">Delete Color</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}

            {{-- <div class="row">
                <div class="col-12">
                    <h3>Add data</h3>
                </div>
                <div class="col-md-12">
                    <table class="table table-sm" id="timePriceTable">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control" name="color[]">
                                        <option value="" disabled hidden selected>Select...</option>
                                        @foreach($colors as $colorIndex => $colorValue)
                                            <option value="{{$colorValue->id}}" @if (old('color') && in_array($colorValue,old('color'))){{('selected')}}@endif>{{$colorValue->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="size[]">
                                        <option value="" disabled hidden selected>Select...</option>
                                        @foreach($sizes as $sizeIndex => $sizeValue)
                                            <option value="{{$sizeValue->id}}">{{$sizeValue->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><a class="btn btn-sm btn-success actionTimebtn addNewTime">+</a></td>
                            </tr>
                        </tbody>
                    </table>
                    @error('time')<p class="text-danger">{{$message}}</p>@enderror
                    @error('price')<p class="text-danger">{{$message}}</p>@enderror
                </div>
            </div> --}}

        </div>
    </div>
</section>

<div class="modal fade" tabindex="-1" id="addColorModal">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add new color</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.product.variation.color.add')}}" method="post">@csrf
                <input type="hidden" name="product_id" value="{{$id}}">
                {{-- <input type="hidden" name="color" value="{{$productColorGroupVal->color}}"> --}}
                <div class="form-group mb-3">
                <select class="form-control" name="color" id="">
                    <option value="" selected>Select color...</option>
                    @php
                        $color = \App\Models\Color::orderBy('name', 'asc')->get();
                        foreach ($color as $key => $value) {
                            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                        }
                    @endphp
                </select>
                </div>
                <div class="form-group mb-3">
                <select class="form-control" name="size" id="">
                    <option value="" selected>Select size...</option>
                    @php
                        $sizes = \App\Models\Size::get();
                        foreach ($sizes as $key => $value) {
                            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                        }
                    @endphp
                </select>
                </div>
                <div class="form-group mb-3">
                <input class="form-control" type="text" name="price" id="" placeholder="Price">
                </div>
                <div class="form-group mb-3">
                <input class="form-control" type="text" name="offer_price" id="" placeholder="Offer Price">
                </div>
                <div class="form-group">
                <button type="submit" class="btn btn-sm btn-success">+ Add size</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="editColorModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit color</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.product.variation.color.edit')}}" method="post">@csrf
                    <input type="hidden" name="product_id" value="{{$id}}">
                    <input type="hidden" name="current_color" value="">
                    <div class="form-group">
                        <p>Style no: <strong>{{$data->style_no}}</strong></p>
                        <p>Product: <strong>{{$data->name}}</strong></p>
                        <p>Current Color: <strong><span id="colorName"></span></strong></p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editColorCode">Change color</label>
                        <select class="form-control" name="update_color" id="editColorCode">
                            <option value="" disabled selected>Select color...</option>
                            @php
                                $color = \App\Models\Color::orderBy('name', 'asc')->get();
                                foreach ($color as $key => $value) {
                                    echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                }
                            @endphp
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-success">Change color</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
		function editColorModalOpen(colorId, colorName) {
            $('#colorName').text(colorName);
            $('input[name="current_color"]').val(colorId);
            $('#editColorModal').modal('show');
        }

        ClassicEditor
        .create( document.querySelector( '#product_des' ) )
        .catch( error => {
            console.error( error );
        });
        ClassicEditor
        .create( document.querySelector( '#product_short_des' ) )
        .catch( error => {
            console.error( error );
        });

        $(document).on('click','.addNewTime',function(){
            var thisClickedBtn = $(this);
            thisClickedBtn.removeClass(['addNewTime','btn-success']);
            thisClickedBtn.addClass(['removeTimePrice','btn-danger']).text('X');

            var toAppend = `
            <tr>
                <td>
                    <select class="form-control" name="color[]">
                        <option value="" hidden selected>Select...</option>
                        @foreach($colors as $colorIndex => $colorValue)
                            <option value="{{$colorValue->id}}" @if (old('color') && in_array($colorValue,old('color'))){{('selected')}}@endif>{{$colorValue->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control" name="size[]">
                        <option value="" hidden selected>Select...</option>
                        @foreach($sizes as $sizeIndex => $sizeValue)
                            <option value="{{$sizeValue->id}}">{{$sizeValue->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td><a class="btn btn-sm btn-success actionTimebtn addNewTime">+</a></td>
            </tr>
            `;

            $('#timePriceTable').append(toAppend);
        });

        $(document).on('click','.removeTimePrice',function(){
            var thisClickedBtn = $(this);
            thisClickedBtn.closest('tr').remove();
        });

        function sizeCheck(productId, colorId) {
            $.ajax({
                url : '{{route("admin.product.size")}}',
                method : 'POST',
                data : {'_token' : '{{csrf_token()}}', productId : productId, colorId : colorId},
                success : function(result) {
                    if (result.error === false) {
                        let content = '<div class="btn-group" role="group" aria-label="Basic radio toggle button group">';

                        $.each(result.data, (key, val) => {
                            content += `<input type="radio" class="btn-check" name="productSize" id="productSize${val.sizeId}" autocomplete="off"><label class="btn btn-outline-primary px-4" for="productSize${val.sizeId}">${val.sizeName}</label>`;
                        })

                        content += '</div>';

                        $('#sizeContainer').html(content);
                    }
                },
                error: function(xhr, status, error) {
                    // toastFire('danger', 'Something Went wrong');
                }
            });
        }

        function deleteImage(imgId, id1, id2) {
            $.ajax({
                url : '{{route("admin.product.variation.image.delete")}}',
                method : 'POST',
                data : {'_token' : '{{csrf_token()}}', id : imgId},
                beforeSend : function() {
                    $('#img__holder_'+id1+'_'+id2+' a').text('Deleting...');
                },
                success : function(result) {
                    $('#img__holder_'+id1+'_'+id2).hide();
                    toastFire('success', result.message);
                },
                error: function(xhr, status, error) {
                    // toastFire('danger', 'Something Went wrong');
                }
            });
        }

        $(".row_position").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('.row_position > .single-color-holder').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });

        function updateOrder(data) {
            // $('.loading-data').show();
            $.ajax({
                url : "{{route('admin.product.variation.color.position')}}",
                type : 'POST',
                data: {
                    _token : '{{csrf_token()}}',
                    position : data
                },
                success:function(data) {
                    // toastFire('success', 'Color position updated successfully');
                    // $('.loading-data').hide();
                    // console.log();
                    if (data.status == 200) {
                        toastFire('success', data.message);
                    } else {
                        toastFire('error', data.message);
                    }
                }
            });
        }

        // product color status change
        function colorStatusToggle(id, productId, colorId) {
            $.ajax({
                url : '{{route("admin.product.variation.color.status.toggle")}}',
                method : 'POST',
                data : {
                    _token : '{{csrf_token()}}',
                    productId : productId,
                    colorId : colorId,
                },
                success : function(result) {
                    if (result.status == 200) {
                        // toastFire('success', result.message);

                        if (result.type == 'active') {
                            $('#'+id+' .color_box').css('background', '#fff');
                        } else {
                            $('#'+id+' .color_box').css('background', '#c1080a59');
                        }
                    } else {
                        toastFire('error', result.message);
                    }
                }
            });
        }





        /* $('.sizeUpload').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url : $(this).attr('action'),
                method : $(this).attr('method'),
                data : $(this).parent().serialize(),
                // {
                    // _token : '{{csrf_token()}}',
                    // product_id : $('#sizeAddProduct_id').val(),
                    // color_id : $('#sizeAddColor_id').val(),
                    // size : $('#sizeAddsize').val(),
                    // price : $('#sizeAddPrice').val(),
                    // offer_price : $('#sizeAddOffer_price').val(),
                // },
                success : function(result) {
                    console.log(result);
                }
            });
        }); */
    </script>
@endsection
