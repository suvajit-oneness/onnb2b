<?php

namespace App\Http\Controllers\Api;

use App\Interfaces\CollectionInterface;
use App\Models\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    // private CollectionInterface $collectionRepository;

    public function __construct(CollectionInterface $collectionRepository)
    {
        $this->collectionRepository = $collectionRepository;
    }

     /**
     * This method is for show collection list
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {

        $collection = $this->collectionRepository->getAllCollections();

        return response()->json(['error'=>false, 'resp'=>'Collection data fetched successfully','data'=>$collection]);
    }
    /**
     * This method is for create collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $collectionDetails = $request->only([
            'name', 'description', 'image_path', 'slug'
        ]);

        return response()->json(
            [
                'data' => $this->collectionRepository->createCollection($collectionDetails)
            ],
            Response::HTTP_CREATED
        );
    }
    /**
     * This method is for show collection details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $collectionId = $request->route('id');


        $collection = $this->collectionRepository->getCollectionById($collectionId);

        return response()->json(['error'=>false, 'resp'=>'Collection data fetched successfully','data'=>$collection]);
    }
    /**
     * This method is for collection update
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $collectionId = $request->route('id');
        $newDetails = $request->only([
            'name', 'description', 'image_path', 'slug'
        ]);

        return response()->json([
            'data' => $this->collectionRepository->updateCollection($collectionId, $newDetails)
        ]);
    }
    /**
     * This method is for collection delete
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $collectionId = $request->route('id');
        $this->collectionRepository->deleteCollection($collectionId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * this method is use for show collection wise category
     */



     public function categorylist2(Request $request, $collectionId): JsonResponse
    {
        $collection = $this->collectionRepository->getCollectionById($collectionId);

        if(!function_exists('in_array_r')) {
            function in_array_r($needle, $haystack, $strict = false) {
                foreach ($haystack as $item) {
                    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) return true;
                }
                return false;
            }
        }

        if(count($collection->ProductDetails) > 0) {
            $customCats = [];
            foreach ($collection->ProductDetails as $ProductDetailsKey => $ProductDetailsValue) {
                if($ProductDetailsValue->status == 1) {
                    if(in_array_r($ProductDetailsValue->cat_id, $customCats)) continue;

                    if ($ProductDetailsValue->category->status == 1) {
                        $customCats[] = [
                            'id' => $ProductDetailsValue->cat_id,
                            'name' => $ProductDetailsValue->category->name,
                            'icon' => asset($ProductDetailsValue->category->icon_path),
                        ];
                    }
                }
            }
             $customProduct = [];
            foreach($collection->ProductDetails as $collectionProductKey => $categoryProductValue){
             if($categoryProductValue->status == 0) {continue;} {
                $customProduct[] = [
                    'id' => $categoryProductValue->id,
                    'Product name' => $categoryProductValue->name,
                    'collection name'      =>   $categoryProductValue->collection->name,
					 'category id' =>$categoryProductValue->cat_id,
                    'category name' =>$categoryProductValue->category->name,
                    'style_no'=>$categoryProductValue->style_no,
                    'price'=>$categoryProductValue->price,
                    'offer_price'=>$categoryProductValue->offer_price,
                    'icon' => asset($categoryProductValue->image),
                    'position'=>$categoryProductValue->position,
                    'status'=>$categoryProductValue->status,
                ];

            }
        }

            return response()->json([
                'error' => false,
                'resp' => 'Collection wise Category data fetched successfully',
                'data' => $customCats,
                'product'=>$customProduct
            ]);
        } else {
            return response()->json(['error' => true, 'resp' => 'No products found under this Collection']);
        }
    }
	
	
	public function categorylist(Request $request, $collectionId): JsonResponse
	{
		$customCats = DB::select("SELECT p.cat_id AS id, c.name AS name FROM `products` AS p INNER JOIN categories AS c ON p.cat_id = c.id WHERE p.collection_id = ".$collectionId." GROUP BY p.cat_id");

		$products = DB::select("SELECT id, style_no, name, position,master_pack,master_pack_count FROM `products` WHERE collection_id = ".$collectionId." ORDER BY position ASC");

		return response()->json([
			'error' => false,
			'resp' => 'Collection wise Category and product data',
			'data' => $customCats,
			'product' => $products
		]);
	}

	public function collectionCategoryWiseProducts(Request $request, $collectionId, $categoyId): JsonResponse
	{
		$data = DB::select("SELECT id, style_no, name,master_pack,master_pack_count, position FROM products WHERE collection_id = ".$collectionId." AND cat_id = ".$categoyId." ORDER BY position;");

		return response()->json([
			'error' => false,
			'resp' => 'Collection and Category wise product data',
			'data' => $data
		]);
	}
}
