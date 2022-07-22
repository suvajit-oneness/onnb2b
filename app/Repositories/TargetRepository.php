<?php

namespace App\Repositories;

use App\Interfaces\TargetInterface;
use App\Models\Target;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class TargetRepository implements TargetInterface
{
    use UploadAble;
    /**
     * This method is to fetch list of all targets
     */
    public function getAlltargets()
    {
        //return Category::latest('id', 'desc')->get();
        return Target::orderBy('position', 'asc')->get();
    }


    /**
     * This method is to get target details by id
     * @param str $targetId
     */
    public function getTargetById($targetId)
    {
        return Target::findOrFail($targetId);
    }

    /**
     * This method is to delete category
     * @param str $targetId
     */
    public function deleteTarget($targetId)
    {
        Target::destroy($targetId);
    }
    /**
     * This method is to create target
     * @param arr $targetDetails
     */
    public function createTarget(array $targetDetails)
    {

        $collection = collect($targetDetails);

        $category = new Target;
        $category->collection_id = $collection['collection_id'];
        $category->amount = $collection['amount'];
        $category->title = $collection['title'];
        $category->user_id = $collection['user_id'];
        $category->user_type = $collection['user_type'];
        $category->year_from = $collection['year_from'];
        $category->year_to = $collection['year_to'];
        $category->remarks = $collection['remarks'];
        $category->save();
        return $category;
    }
    /**
     * This method is to update target details
     * @param str $targetId
     * @param arr $newDetails
     */
    public function updateTarget($id, array $newDetails)
    {

        $category = Target::findOrFail($id);
        $collection = collect($newDetails);
        $category->collection_id = $collection['collection_id'];
        $category->amount = $collection['amount'];
        $category->title = $collection['title'];
        $category->user_id = $collection['user_id'];
        $category->user_type = $collection['user_type'];
        $category->year_from = $collection['year_from'];
        $category->year_to = $collection['year_to'];
        $category->remarks = $collection['remarks'];
        $category->save();
        return $category;
    }
    /**
     * This method is to toggle target status
     * @param str $targetId
     */
    public function targetStatus($targetId){
        $category = Target::findOrFail($targetId);
        $status = ( $category->status == 1 ) ? 0 : 1;
        $category->status = $status;
        $category->save();
        return $category;
    }



    /**
     * This method is to get category details by filter
     * @param str $collectionId,$yearFrom,$yearTo
     */
    public function getTargetByFilter($collection,$year_from,$year_to,$userId,$userType)
    {
        $target = Target::
        when($collection, function($query) use ($collection){
            $query->where('collection_id', '=', $collection);
        })
        ->when($year_from, function($query) use ($year_from){
            $query->where('year_from', '=', $year_from);
        })
        ->when($year_to!='', function($query) use ($year_to){
            $query->where('year_to', '=', $year_to);
        })
        ->when($userId!='', function($query) use ($userId){
            $query->where('user_id', '=', $userId);
        })

        ->when($userType!='', function($query) use ($userType){
            $query->where('user_type', '=', $userType);
        })


        ->get();

        return $target;
    }
}
