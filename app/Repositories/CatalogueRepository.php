<?php

namespace App\Repositories;

use App\Interfaces\CatalogueInterface;
use App\ProductCatalogue;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CatalogueRepository implements CatalogueInterface
{
    use UploadAble;

    public function getAllCatalogue($cat=5)
    {
        return ProductCatalogue::paginate($cat);
    }

    public function getSearchCatalogue(string $term)
    {
        return ProductCatalogue::where([['title', 'LIKE', '%' . $term . '%']])->paginate(5);
    }

    public function getCatalogueById($catalogueId)
    {
        return ProductCatalogue::findOrFail($catalogueId);
    }

    public function getCatalogueBySlug($slug, array $request = null)
    {
        return ProductCatalogue::where('slug', $slug)->with('ProductDetails')->first();
    }

    public function deleteCatalogue($catalogueId)
    {
        ProductCatalogue::destroy($catalogueId);
    }

    public function createCatalogue(array $collectionDetails)
    {
        $upload_path = "uploads/catalogue/";
        $collection = collect($collectionDetails);

        $modelDetails = new ProductCatalogue;
        $modelDetails->title = $collection['title'];
        $modelDetails->start_date = $collection['start_date'];
        $modelDetails->end_date = $collection['end_date'];


        // image image
        $image = $collection['image'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->image = $upload_path . $uploadedImage;

        // pdf icon
        $image = $collection['pdf'];
        $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $modelDetails->pdf = $upload_path . $uploadedImage;



        $modelDetails->save();

        return $modelDetails;
    }

    public function updateCatalogue($id, array $newDetails)
    {
        $upload_path = "uploads/catalogue/";
        $modelDetails = ProductCatalogue::findOrFail($id);
        $collection = collect($newDetails);
        // dd($newDetails);

        $modelDetails->title = $collection['title'];
        $modelDetails->start_date = $collection['start_date'];
        $modelDetails->end_date = $collection['end_date'];





        if (isset($newDetails['image'])) {
            $image = $collection['image'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->image = $upload_path . $uploadedImage;
        }

        if (isset($newDetails['pdf'])) {
            $image = $collection['pdf'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $modelDetails->pdf = $upload_path . $uploadedImage;
        }



        $modelDetails->save();

        return $modelDetails;
    }

    public function toggleStatus($catalogueId)
    {
        $collection = ProductCatalogue::findOrFail($catalogueId);

        $status = ($collection->is_current == 1) ? 0 : 1;
        $collection->is_current = $status;
        $collection->save();

        return $collection;
    }


}
