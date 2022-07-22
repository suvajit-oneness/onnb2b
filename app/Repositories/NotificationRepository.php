<?php

namespace App\Repositories;

use App\Interfaces\NotificationInterface;
use App\Models\Notification;
use App\Models\Size;
use App\Models\Color;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class NotificationRepository implements NotificationInterface
{
    use UploadAble;
    /**
     * This method is to fetch list of all notification
     */
    public function getAlllist()
    {
        return Notification::all();
    }


    /**
     * This method is to get notification details by id
     * @param str $Id
     */
    public function getNotificationById($Id)
    {
        return Notification::where('user_id', $Id)->get();
    }
    /**
     * This method is to get category details by slug
     * @param str $slug
     */
    public function getCategoryBySlug($slug)
    {
        return Notification::where('slug', $slug)->with('ProductDetails')->first();
    }
    /**
     * This method is to delete category
     * @param str $Id
     */
    public function delete($Id)
    {
        Notification::destroy($Id);
    }
    /**
     * This method is to create category
     * @param arr $data
     */
    public function create(array $data)
    {
        $upload_path = "uploads/category/";
        $collection = collect($data);

        $category = new Notification;
        $category->name = $collection['name'];
        $category->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Notification::where('slug', $slug)->count();
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
     * @param str $Id
     * @param arr $newDetails
     */
    public function update($Id, array $newDetails)
    {
        $upload_path = "uploads/category/";
        $category = Notification::findOrFail($Id);
        $collection = collect($newDetails);

        $category->name = $collection['name'];
        $category->description = $collection['description'];

        // generate slug
        $slug = Str::slug($collection['name'], '-');
        $slugExistCount = Notification::where('slug', $slug)->count();
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
        $category = Notification::findOrFail($id);

        $status = ( $category->status == 1 ) ? 0 : 1;
        $category->status = $status;
        $category->save();

        return $category;
    }
}
