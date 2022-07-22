<?php

namespace App\Repositories;

use App\Interfaces\CollectionInterface;
use App\Models\Collection;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CollectionRepository implements CollectionInterface
{
    use UploadAble;

    public function getAllCollections()
    {
        return Collection::where('status',1)->orderBy('position','asc')->get();
    }
    /**
     * this method is use to get all collection in admin section
     */
    public function getCollections($cat= 5)
    {
        return Collection::paginate($cat);
    }

    public function getSearchCollections(string $term)
    {
        return Collection::where([['name', 'LIKE', '%' . $term . '%']])->paginate(5);
    }

    public function getAllSizes()
    {
        return Size::all();
    }

    public function getAllColors()
    {
        return Color::all();
    }

    public function getCollectionById($collectionId)
    {
        return Collection::findOrFail($collectionId);
    }

    public function getCollectionBySlug($slug, array $request = null)
    {
        return Collection::where('slug', $slug)->with('ProductDetails')->first();
    }

    public function deleteCollection($collectionId)
    {
        Collection::destroy($collectionId);
    }

    public function createCollection(array $data)
    {
        $upload_path = "uploads/collection/";
        $collection = collect($data);

        $modelDetails = new Collection;
        $modelDetails->name = $collection['name'];
        $modelDetails->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Collection::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        $modelDetails->slug = $slug;

        // icon image
        $image = $collection['icon_path'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->icon_path = $upload_path . $uploadedImage;

        // sketch icon
        $image = $collection['sketch_icon'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->sketch_icon = $upload_path . $uploadedImage;

        // thumb image
        $image = $collection['image_path'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->image_path = $upload_path . $uploadedImage;

        // banner image
        $bannerImage = $collection['banner_image'];
        $bannerImageName = time() . "." . mt_rand() . "." . $bannerImage->getClientOriginalName();
        $bannerImage->move($upload_path, $bannerImageName);
        $uploadedImage = $bannerImageName;
        $modelDetails->banner_image = $upload_path . $uploadedImage;

        $modelDetails->save();

        return $modelDetails;
    }

    public function updateCollection($id, array $newDetails)
    {
        $upload_path = "uploads/collection/";
        $modelDetails = Collection::findOrFail($id);
        $collection = collect($newDetails);
        // dd($newDetails);

        $modelDetails->name = $collection['name'];
        $modelDetails->description = $collection['description'];
        // $modelDetails->slug = $collection['slug'];

        // if (in_array('image_path', $newDetails)) {
        //     $image = $collection['image_path'];
        //     $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
        //     $image->move($upload_path, $imageName);
        //     $uploadedImage = $imageName;
        //     $modelDetails->image_path = $upload_path.$uploadedImage;
        // }

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Collection::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        $modelDetails->slug = $slug;

        if (isset($newDetails['icon_path'])) {
            $image = $collection['icon_path'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->icon_path = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['sketch_icon'])) {
            $image = $collection['sketch_icon'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->sketch_icon = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['image_path'])) {
            $image = $collection['image_path'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->image_path = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['banner_image'])) {
            // dd('here');
            $bannerImage = $collection['banner_image'];
            $bannerImageName = time() . "." . mt_rand() . "." . $bannerImage->getClientOriginalName();
            $bannerImage->move($upload_path, $bannerImageName);
            $uploadedImage = $bannerImageName;
            $modelDetails->banner_image = $upload_path . $uploadedImage;
        }

        $modelDetails->save();

        return $modelDetails;
    }

    public function toggleStatus($id)
    {
        $collection = Collection::findOrFail($id);

        $status = ($collection->status == 1) ? 0 : 1;
        $collection->status = $status;
        $collection->save();

        return $collection;
    }

    public function productsByCollection(int $collectionId, array $filter = null)
    {
        try {
            $productsQuery = Product::where('collection_id', $collectionId);
            // $productsQuery = DB::statement('SELECT * FROM `products` WHERE collection_id = '.$collectionId);

            // handling collection
            if (isset($filter['collection'])) {
                foreach ($filter['collection'] as $collectionKey => $collectionValue) {
                    // if (count($filter['collection']) == 1) {
                    //     $products = $productsQuery->where('collection_id', $collectionValue);
                    // } else {
                    // $rawQuery = "(collection_id = '.$collectionValue.' OR )";
                    $products = $productsQuery->where(function ($query) {
                        $query->orWhere('collection_id', $collectionValue);
                    });
                    // }
                }
                // $products = $productsQuery->whereRaw("'".$rawQuery."'");
            }

            // handling sort by
            if (isset($filter['orderBy'])) {
                $orderBy = "id";
                $order = "desc";

                if ($filter['orderBy'] == "new_arr") {
                    $orderBy = "id";
                    $order = "desc";
                } elseif ($filter['orderBy'] == "mst_viw") {
                    $orderBy = "view_count";
                    $order = "desc";
                } elseif ($filter['orderBy'] == "prc_low") {
                    $orderBy = "offer_price";
                    $order = "asc";
                } elseif ($filter['orderBy'] == "prc_hig") {
                    $orderBy = "offer_price";
                    $order = "desc";
                }

                $products = $productsQuery->orderBy($orderBy, $order);
            }

            $products = $productsQuery->with('colorSize')->get();

            $response = [];
            foreach ($products as $productKey => $productValue) {
                if (count($productValue->colorSize) > 0) {
                    $varArray = [];
                    foreach ($productValue->colorSize as $productVariationKey => $productVariationValue) {
                        $varArray[] = $productVariationValue->offer_price;
                    }
                    $bigger = $varArray[0];
                    for ($i = 1; $i < count($varArray); $i++) {
                        if ($bigger < $varArray[$i]) {
                            $bigger = $varArray[$i];
                        }
                    }

                    $smaller = $varArray[0];
                    for ($i = 1; $i < count($varArray); $i++) {
                        if ($smaller > $varArray[$i]) {
                            $smaller = $varArray[$i];
                        }
                    }

                    $displayPrice = $smaller . ' - ' . $bigger;

                    if ($smaller == $bigger) $displayPrice = $smaller;
                    $show_price = $displayPrice;
                } else {
                    $show_price = $productValue['offer_price'];
                }

                $response[] = [
                    'name' => $productValue['name'],
                    'slug' => $productValue['slug'],
                    'image' => $productValue['image'],
                    'styleNo' => $productValue['style_no'],
                    'displayPrice' => $show_price,
                ];
            }

            return $response;
        } catch (\Throwable $th) {
            throw $th;
            // return $th;
        }
    }


    public function getSearchCollection(string $term)
    {
        return Collection::where([['name', 'LIKE', '%' . $term . '%']])


        ->paginate(5);
    }
}
