<?php

namespace App\Repositories;

use App\Interfaces\CouponInterface;
use App\Models\Coupon;

class CouponRepository implements CouponInterface 
{
    public function listAll() 
    {
        return Coupon::all();
    }

    public function listById($id) 
    {
        return Coupon::findOrFail($id);
    }

    public function create(array $data) 
    {
        $collectedData = collect($data);

        $newEntry = new Coupon;
        $newEntry->name = $collectedData['name'];
        $newEntry->coupon_code = $collectedData['coupon_code'];
        if (!empty($collectedData['type'])) {
            $newEntry->type = $collectedData['type'];
        }
        $newEntry->amount = $collectedData['amount'];
        $newEntry->max_time_of_use = $collectedData['max_time_of_use'];
        $newEntry->max_time_one_can_use = $collectedData['max_time_one_can_use'];
        $newEntry->start_date = $collectedData['start_date'];
        $newEntry->end_date = $collectedData['end_date'];

        // $upload_path = "uploads/collection/";
        // $image = $collectedData['image_path'];
        // $imageName = time().".".$image->getClientOriginalName();
        // $image->move($upload_path, $imageName);
        // $uploadedImage = $imageName;
        // $newEntry->image_path = $upload_path.$uploadedImage;

        $newEntry->save();

        return $newEntry;
    }

    public function update($id, array $newDetails) 
    {
        $updatedEntry = Coupon::findOrFail($id);
        $collectedData = collect($newDetails); 
        // dd($newDetails);

        $updatedEntry->name = $collectedData['name'];
        $updatedEntry->coupon_code = $collectedData['coupon_code'];
        if (!empty($collectedData['type'])) {
            $updatedEntry->type = $collectedData['type'];
        }
        $updatedEntry->amount = $collectedData['amount'];
        $updatedEntry->max_time_of_use = $collectedData['max_time_of_use'];
        $updatedEntry->max_time_one_can_use = $collectedData['max_time_one_can_use'];
        $updatedEntry->start_date = $collectedData['start_date'];
        $updatedEntry->end_date = $collectedData['end_date'];

        $updatedEntry->save();

        return $updatedEntry;
    }

    public function toggle($id){
        $updatedEntry = Coupon::findOrFail($id);

        $status = ( $updatedEntry->status == 1 ) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function delete($id) 
    {
        Coupon::destroy($id);
    }
}