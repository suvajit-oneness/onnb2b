<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Interfaces\ProductInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Size;
use App\Models\Sale;
use App\Models\ProductColor;
use App\Models\ProductColorSize;
use App\Models\Wishlist;
use App\Traits\UploadAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductInterface
{
    use UploadAble;

    public function listAll()
    {
        return Product::paginate(5);
    }

    public function categoryList()
    {
        return Category::all();
    }
    public function getSearchProducts(string $term)
    {
        return Product::where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('offer_price', 'LIKE', '%' . $term . '%')
            ->orWhere('style_no', 'LIKE', '%' . $term . '%')
            ->orWhere('price', 'LIKE', '%' . $term . '%')
            ->paginate(5);
    }

    public function subCategoryList()
    {
        return SubCategory::all();
    }

    public function collectionList()
    {
        return Collection::all();
    }

    public function colorList()
    {
        return Color::all();
    }

    public function colorListByName()
    {
        return Color::orderBy('name', 'asc')->get();
    }

    public function sizeList()
    {
        return Size::all();
    }

    public function listById($id)
    {
        return Product::where('id',$id)->with('colorSize','category','subcategory','collection')->get();
    }

    public function listBySlug($slug)
    {
        return Product::where('slug', $slug)->with('category', 'subCategory', 'collection', 'colorSize')->first();
    }

    public function relatedProducts($id)
    {
        $product = Product::findOrFail($id);
        $cat_id = $product->cat_id;
        return Product::where('cat_id', $cat_id)->where('id', '!=', $id)->with('category', 'subCategory', 'collection', 'colorSize')->get();
    }
    /**
     * This method is to get product details by category id
     * @param str $categoryId
     */
    public function getProductByCategoryId($categoryId){

        return Product::where('cat_id', $categoryId)->where('status', '1')->with('colorSize','category')->orderBy('position_collection','asc')->get();
    }


    /**
     * This method is to get product details by collection id
     * @param str $collectionId
     */
    public function getProductByCollectionId($collectionId){
        return Product::where('collection_id', $collectionId)->with('collection')->get();
    }
    /**
     * This method is to get product details by category id
     * @param str $categoryId
     */
    public function productlistByuse($only_for)
    {

        return Product::where('only_for', $only_for)->get();
    }
    public function listImagesById($id)
    {
        return ProductImage::where('product_id', $id)->latest('id')->get();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $collectedData = collect($data);
            $newEntry = new Product;
            $newEntry->cat_id = $collectedData['cat_id'];
            $newEntry->sub_cat_id = $collectedData['sub_cat_id'];
            $newEntry->collection_id = $collectedData['collection_id'];
            $newEntry->name = $collectedData['name'];
            $newEntry->short_desc = $collectedData['short_desc'];
            $newEntry->desc = $collectedData['desc'];
            $newEntry->price = $collectedData['price'];
            $newEntry->offer_price = $collectedData['offer_price'];
            $newEntry->meta_title = $collectedData['meta_title'];
            $newEntry->meta_desc = $collectedData['meta_desc'];
            $newEntry->meta_keyword = $collectedData['meta_keyword'];
            $newEntry->style_no = $collectedData['style_no'];

            // slug generate
            $slug = \Str::slug($collectedData['name'], '-');
            $slugExistCount = Product::where('slug', $slug)->count();
            if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
            $newEntry->slug = $slug;

            // main image handling
            $upload_path = "uploads/product/";
            $image = $collectedData['image'];
            $imageName = time() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $newEntry->image = $upload_path . $uploadedImage;
            $newEntry->save();

            // multiple image upload handling
            if (isset($data['product_images'])) {
                $multipleImageData = [];
                foreach ($data['product_images'] as $imagekey => $imagevalue) {
                    $imageName = mt_rand() . '-' . time() . "." . $image->getClientOriginalName();
                    $imagevalue->move($upload_path, $imageName);
                    $image_path = $upload_path . $imageName;
                    $multipleImageData[] = [
                        'product_id' => $newEntry->id,
                        'image' => $image_path
                    ];
                }
                if (count($multipleImageData) > 0) ProductImage::insert($multipleImageData);
            }

            // check color & size
            // dd($data['color'], $data['size']);

            if (!empty($data['color']) && !empty($data['size'])) {
                $multipleColorData = [];

                foreach ($data['color'] as $colorKey => $colorValue) {
                    $multipleColorData[] = [
                        'product_id' => $newEntry->id,
                        'color' => $colorValue,
                    ];
                }

                foreach ($data['size'] as $sizeKey => $sizeValue) {
                    $multipleColorData[$sizeKey]['size'] = $sizeValue;
                }

                // dd($multipleColorData);

                ProductColorSize::insert($multipleColorData);
            }

            DB::commit();
            return $newEntry;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }
    }

    public function update($id, array $newDetails)
    {
        // dd($newDetails);

        DB::beginTransaction();

        try {
            $upload_path = "uploads/product/";
            $updatedEntry = Product::findOrFail($id);
            // dd($updatedEntry);
            $collectedData = collect($newDetails);
            if (!empty($collectedData['cat_id'])) $updatedEntry->cat_id = $collectedData['cat_id'];
            if (!empty($collectedData['sub_cat_id'])) $updatedEntry->sub_cat_id = $collectedData['sub_cat_id'];
            if (!empty($collectedData['collection_id'])) $updatedEntry->collection_id = $collectedData['collection_id'];
            $updatedEntry->name = $collectedData['name'];
            $updatedEntry->short_desc = $collectedData['short_desc'];
            $updatedEntry->desc = $collectedData['desc'];
            $updatedEntry->price = $collectedData['price'];
            $updatedEntry->offer_price = $collectedData['offer_price'];

            // slug generate
            $slug = \Str::slug($collectedData['name'], '-');
            $slugExistCount = Product::where('slug', $slug)->count();
            if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);

            $updatedEntry->slug = $slug;
            $updatedEntry->meta_title = $collectedData['meta_title'];
            $updatedEntry->meta_desc = $collectedData['meta_desc'];
            $updatedEntry->meta_keyword = $collectedData['meta_keyword'];
            $updatedEntry->style_no = $collectedData['style_no'];

            if (isset($newDetails['image'])) {
                // delete old image
                if (Storage::exists($updatedEntry->image)) unlink($updatedEntry->image);

                $image = $collectedData['image'];
                $imageName = time() . "." . $image->getClientOriginalName();
                $image->move($upload_path, $imageName);
                $uploadedImage = $imageName;
                $updatedEntry->image = $upload_path . $uploadedImage;
            }

            $updatedEntry->save();

            // multiple image upload handling
            if (isset($newDetails['product_images'])) {
                $multipleImageData = [];
                foreach ($newDetails['product_images'] as $imagekey => $imagevalue) {
                    $imageName = mt_rand() . '-' . time() . "." . $image->getClientOriginalName();
                    $imagevalue->move($upload_path, $imageName);
                    $image_path = $upload_path . $imageName;
                    $multipleImageData[] = [
                        'product_id' => $id,
                        'image' => $image_path
                    ];
                }

                // dd($multipleImageData);

                if (count($multipleImageData) > 0) {
                    ProductImage::insert($multipleImageData);
                }
            }
            // dd('out');

            DB::commit();
            return $updatedEntry;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
        }
    }

    public function toggle($id)
    {
        $updatedEntry = Product::findOrFail($id);

        $status = ($updatedEntry->status == 1) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function sale($id)
    {
        $saleExist = Sale::where('product_id', $id)->first();

        if ($saleExist) {
            $resp = Sale::where(['product_id' => $id])->delete();
            return $resp;
        } else {
            $resp = Sale::create(['product_id' => $id]);
            return $resp;
        }
    }

    public function delete($id)
    {
        Product::destroy($id);
    }

    public function deleteSingleImage($id)
    {
        ProductImage::destroy($id);
    }

    public function wishlistCheck($productId)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = Wishlist::where('product_id', $productId)->where('ip', $ip)->first();
        return $data;
    }

    public function primaryColorSizes($productId)
    {
        // multi-dimensional in_array
        function in_array_r($needle, $haystack, $strict = false) {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) return true;
            }
            return false;
        }

        // product check
        $product = Product::findOrFail($productId);
        $productDetails = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'offer_price' => $product->offer_price,
            'pack' => $product->pack,
            'only_for' => $product->only_for,
            'master_pack' => $product->master_pack,
            'master_pack_count' => $product->master_pack_count,
        ];
        $uniqueColors = [];

        // colors list
        foreach ($product->colorSize as $variantKey => $variantValue) {
            if (in_array_r($variantValue->colorDetails->code, $uniqueColors)) continue;

            $uniqueColors[] = [
                'id' => $variantValue->colorDetails->id,
                'code' => $variantValue->colorDetails->code,
                'name' => $variantValue->colorDetails->name,
                'color_name' => $variantValue->color_name,
            ];
        }

        // product primary sizes
        $primaryColor = ProductColorSize::select('color')->where('product_id', $productId)->first();

        if ($primaryColor) {
            $sizes = ProductColorSize::where('product_id', $productId)->where('color', $primaryColor->color)->get();
            // $productPrimarySizes = $sizes;

            $sizesList = array();
            foreach($sizes as $sizeKey => $sizeVal) {
                $sizesList[] = [
                    'id' => $sizeVal->sizeDetails->id,
                    'name' => $sizeVal->sizeDetails->name,
                    'price' => $sizeVal->price,
                    'offer_price' => $sizeVal->offer_price,
                    'stock' => $sizeVal->stock,
                    'color_name' => $sizeVal->color_name,
                    'size_details' => $sizeVal->size_details,
                ];
            }
        } else {
            $sizesList = false;
        }

        $respArray[] = [
            'product' => $productDetails,
            'colors' => $uniqueColors,
            'primarySizes' => $sizesList,
        ];

        return $respArray;

        //test
        // $test = DB::select('SELECT pcs.color AS color_id, c.name AS color_name, c.code AS color_code, COUNT(pcs.id) AS size_count FROM product_color_sizes AS pcs INNER JOIN colors AS c ON pcs.color = c.id WHERE pcs.product_id = '.$productId.' GROUP BY pcs.color ORDER BY pcs.color ASC');

        // return $test;
    }
    public function productsize($productId,$colorId){
		$product = DB::select("select * from product_color_sizes where product_id='$productId' and color='$colorId'");
        //$product= ProductColorSize::where('product_id',$productId)->where('color',$colorId)->with('sizeDetails')->get();
        return $product;
    }
    public function productcolorsizeById($productId)
    {
        $product = ProductColorSize::where('product_id', $productId)->get();


        return $product;
        // return ProductColorSize::where('product_id', $productId)->with('product','colorDetails','sizeDetails')->get();
    }
}
