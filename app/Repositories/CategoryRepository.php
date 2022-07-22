<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CategoryRepository implements CategoryInterface
{
    use UploadAble;

    /**
     * This method is to fetch list of all categories
     */
    public function getAllCategories()
    {
        return Category::latest('id', 'desc')->get();
        // return Category::orderBy('position', 'asc')->paginate(5);
    }

    /**
     * This method is to fetch list of all categories in admin section
     */
    public function getCategories($cat= 5)
    {

        return Category::orderBy('position', 'asc')->paginate($cat);
        //return Category::orderBy('position', 'asc')->paginate(5);
    }

    public function getAllSizes()
    {
        return Size::latest('id', 'desc')->get();
    }

    public function getAllColors()
    {
        return Color::latest('id', 'desc')->get();
    }

    /**
     * This method is to get category details by id
     * @param str $categoryId
     */
    public function getCategoryById($categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    /**
     * This method is to get category details by slug
     * @param str $slug
     */
    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)->with('ProductDetails')->first();
    }

    /**
     * This method is to delete category
     * @param str $categoryId
     */
    public function deleteCategory($categoryId)
    {
        Category::destroy($categoryId);
    }

    /**
     * This method is to create category
     * @param arr $categoryDetails
     */
    public function createCategory(array $categoryDetails)
    {
        $upload_path = "uploads/category/";
        $collection = collect($categoryDetails);
        $category = new Category;
        $category->name = $collection['name'];
        $category->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Category::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount+1);
        $category->slug = $slug;

        // icon image
        $image = $collection['icon_path'];
        $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $category->icon_path = $upload_path.$uploadedImage;

        // thumb image
        $image = $collection['image_path'];
        $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $category->image_path = $upload_path.$uploadedImage;

        // banner image
        $bannerImage = $collection['banner_image'];
        $bannerImageName = time().".".mt_rand().".".$bannerImage->getClientOriginalName();
        $bannerImage->move($upload_path, $bannerImageName);
        $uploadedImage = $bannerImageName;
        $category->banner_image = $upload_path.$uploadedImage;

        $category->save();

        return $category;
    }

    /**
     * This method is to update category details
     * @param str $categoryId
     * @param arr $newDetails
     */
    public function updateCategory($categoryId, array $newDetails)
    {
        $upload_path = "uploads/category/";
        $category = Category::findOrFail($categoryId);
        $collection = collect($newDetails);

        $category->name = $collection['name'];
        $category->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Category::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount+1);
        $category->slug = $slug;

        if (isset($newDetails['icon_path'])) {
            // dd('here');
            $image = $collection['icon_path'];
            $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $category->icon_path = $upload_path.$uploadedImage;
        }

        if (isset($newDetails['image_path'])) {
            // dd('here');
            $image = $collection['image_path'];
            $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $category->image_path = $upload_path.$uploadedImage;
        }

        if (isset($newDetails['banner_image'])) {
            // dd('here');
            $bannerImage = $collection['banner_image'];
            $bannerImageName = time().".".mt_rand().".".$bannerImage->getClientOriginalName();
            $bannerImage->move($upload_path, $bannerImageName);
            $uploadedImage = $bannerImageName;
            $category->banner_image = $upload_path.$uploadedImage;
        }
        // dd('outside');

        $category->save();

        return $category;
    }

    /**
     * This method is to toggle category status
     * @param str $categoryId
     */
    public function toggleStatus($id){
        $category = Category::findOrFail($id);

        $status = ( $category->status == 1 ) ? 0 : 1;
        $category->status = $status;
        $category->save();

        return $category;
    }

    public function getSearchCategory(string $term)
    {
        return Category::where([['name', 'LIKE', '%' . $term . '%']])
        ->paginate(5);
    }
}
