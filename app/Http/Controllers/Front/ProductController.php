<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function detail(Request $request, $slug)
    {
        $data = $this->productRepository->listBySlug($slug);

        if ($data) {
            $images = $this->productRepository->listImagesById($data->id);
            $relatedProducts = $this->productRepository->relatedProducts($data->id);
            $wishlistCheck = $this->productRepository->wishlistCheck($data->id);
            $primaryColorSizes = $this->productRepository->primaryColorSizes($data->id);

            return view('front.product.detail', compact('data', 'images', 'relatedProducts', 'wishlistCheck', 'primaryColorSizes'));
        } else {
            return view('front.404');
        }
    }

    public function detailAjax(Request $request)
    {
        $result = (object)[];
        $result->data = $this->productRepository->listBySlug($request->productSlug);
        $result->images = $this->productRepository->listImagesById($result->data->id);
        // $result->relatedProducts = $this->productRepository->relatedProducts($result->data->id);
        // $result->wishlistCheck = $this->productRepository->wishlistCheck($result->data->id);
        $result->primaryColorSizes = $this->productRepository->primaryColorSizes($result->data->id);

        if ($result) {
            return response()->json(['status' => 200, 'message' => 'Product fetched successfully', 'response' => $result]);
            // return view('front.product.detail', compact('data', 'images', 'relatedProducts', 'wishlistCheck', 'primaryColorSizes'));
        } else {
            return response()->json(['status' => 400, 'message' => 'Product fetch failure']);
            // return view('front.404');
        }
    }

    public function size(Request $request)
    {
        $productId = $request->productId;
        $colorId = $request->colorId;

        $data = ProductColorSize::where('product_id', $productId)->where('color', $colorId)->orderBy('id')->get();
        // $dataImage = ProductImage::where('product_id', $productId)->where('color_id', $colorId)->orderBy('id')->get();

        $resp = [];

        foreach ($data as $dataKey => $dataValue) {
            if ($dataValue->size != 0) {
                $resp[] = [
                    'variationId' => $dataValue->id,
                    'sizeId' => $dataValue->size,
                    'sizeName' => $dataValue->sizeDetails->name,
					 'SizeDetails' => $dataValue->size_details,
                    'price' => $dataValue->price,
                    'offerPrice' => $dataValue->offer_price,
                ];
            }
        }

        // $respImage = [];

        // if ($dataImage->count() > 0) {
        //     foreach ($dataImage as $dataKey => $dataValue) {
        //         $respImage[] = [
        //             'image' => asset($dataValue->image),
        //         ];
        //     }
        // } else {
        //     $mainImage = Product::select('image')->where('id', $productId)->first();
        //     $respImage[] = [
        //         'image' => asset($mainImage->image)
        //     ];
        // }

        // return response()->json(['error' => false, 'data' => $resp, 'images' => $respImage]);
        return response()->json(['error' => false, 'data' => $resp]);
    }

    public function search(Request $request)
    {
        // dd($request->name);
        $data = Product::where('name', 'LIKE', '%'.$request->name.'%')->orWhere('style_no', 'LIKE', '%'.$request->name.'%')->get();

        if (count($data) > 0) {
            $resp = [];
            foreach($data as $item) {
                $resp[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'style_no' => $item->style_no,
                ];
            }

            return response()->json(['error' => false, 'message'=> 'Store found', 'data'  => $resp]);
        } else {
            return response()->json(['error' => true, 'message' => 'No data found']);
        }
    }
}
