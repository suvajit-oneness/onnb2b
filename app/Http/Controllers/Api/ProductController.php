<?php

namespace App\Http\Controllers\Api;

use App\Interfaces\ProductInterface;
use App\Models\Product;
use App\Models\Distributor;
use App\Models\ProductColorSize;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    // private ProductInterface $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * This method is to get product details
     *
     */
    public function list(Request $request): JsonResponse
    {

        $products = $this->productRepository->listAll();

        return response()->json(['error'=>false, 'resp'=>'Product data fetched successfully','data'=>$products]);

    }
    /**
     * This method is to get product details by CategoryId
     *
     */
    public function productlist($categoryId): JsonResponse
   {

        $products = $this->productRepository->getProductByCategoryId($categoryId);

        return response()->json(['error'=>false, 'resp'=>'Product data fetched successfully','data'=>$products]);
   }
    /**
     * This method is to get product details by collectionId
     *
     */
    public function productindex($id): JsonResponse
    {
        $products = $this->productRepository->getProductByCollectionId($id);

        return response()->json(['error'=>false, 'resp'=>'Product data fetched successfully','data'=>$products]);
    }

    /**
     * This method is for create product
     *
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'cat_id', 'sub_cat_id', 'collection_id', 'name', 'short_desc', 'desc', 'price', 'offer_price', 'slug', 'meta_title', 'meta_desc', 'meta_keyword', 'style_no', 'image'
        ]);

        return response()->json(
            [
                'data' => $this->productRepository->create($data)
            ],
            Response::HTTP_CREATED
        );
    }
    /**
     * This method is for show product details
     * @param  $id
     *
     */
    public function show(Request $request): JsonResponse
    {
        $id = $request->route('id');


        $products = $this->productRepository->listById($id);

        return response()->json(['error'=>false, 'resp'=>'Product data fetched successfully','data'=>$products]);
    }
    /**
     * This method is for show product details
     * @param  $slug
     *
     */
    public function view(Request $request): JsonResponse
    {
        $slug = $request->route('slug');


        $products = $this->productRepository->listBySlug($slug);

        return response()->json(['error'=>false, 'resp'=>'Product data fetched successfully','data'=>$products]);
    }
    /**
     * This method is for product update
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $newDetails = $request->only([
            'cat_id', 'sub_cat_id', 'collection_id', 'name', 'short_desc', 'desc', 'price', 'offer_price', 'slug', 'meta_title', 'meta_desc', 'meta_keyword', 'style_no', 'image'
        ]);

        return response()->json([
            'data' => $this->productRepository->update($id, $newDetails)
        ]);
    }
    /**
     * This method is for product delete
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $this->productRepository->delete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }



     /**
     * This method is for show product color
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function color(Request $request){
        return response()->json([

            $data = $this->productRepository->colorList()

        ]);
        }

    /**
     * This method is for show product color
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function productcolor(Request $request,$ProductId){
		$colors = DB::select("select DISTINCT color_name as name, color as code from product_color_sizes
where product_id='$ProductId' order by position ASC");
        //$colors = $this->productRepository->primaryColorSizes($ProductId)[0]['colors'];
        
        // echo "<pre>";
        // print_r($colors);
        // die();
        
        return response()->json([
            'error' => false,
            'resp' => 'Product with colors & sizes',
            'data' => $colors
        ]);
    }
     /**
     * This method is for show product color
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function productsize(Request $request,$ProductId,$colorId){
        return response()->json([
            'error' => false,
            'resp' => 'Product and Color wise Size',
            'data' => $this->productRepository->productsize($ProductId,$colorId)
        ]);
    }


    public function colorsize(Request $request): JsonResponse
    {
        $productId = $request->route('productid');

        return response()->json([
            'data' => $this->productRepository->productcolorsizeById($productId)
        ]);
    }



    /**
     * This method is to get product details  by use
     * @param
     * @return \Illuminate\Http\JsonResponse
     */
    public function productuse(Request $request): JsonResponse
    {
        $only_for = $request->route('only_for');



        $products = $this->productRepository->productlistByuse($only_for);

        return response()->json(['error'=>false, 'resp'=>'Product data fetched successfully','data'=>$products]);
    }



    public function areaList(Request $request,$id): JsonResponse
    {
        $data = DB::table('areas')->where('status',1)->where('user_id',$id)->get();
        return response()->json(['error' => false, 'resp' => 'Area list fetch successfull', 'data' => $data]);
    }



    public function distributorList(Request $request): JsonResponse
    {
        // $data = DB::table('distributors')->with('user')->get();
        $data = Distributor::with('user')->latest('id', 'desc')->get();
        return response()->json(['error' => false, 'resp' => 'Distributor list fetch successfull', 'data' => $data]);
    }

    public function distributorVisitStart(Request $request): JsonResponse {
        $data[] = [
            'user_id' => $request->user_id ?? null,
            'distributor_id' => $request->distributor_id ?? null,
            'date' => $request->date ?? null,
            'time' => $request->time ?? null,
            'type' => $request->type ?? null,
            'comment' => $request->comment ?? null,
            'location' => $request->location ?? null,
            'lat' => $request->lat ?? null,
            'lng' => $request->lng ?? null,
            'start_time' => $request->start_time ?? null,
            'end_time' => $request->end_time ?? null,
            'visit_started' => $request->visit_started ?? null
        ];
        DB::table('distributors_visits')->insert($data);
        return response()->json(['error' => false, 'resp' => 'Distributor visit created successfully']);
    }

    public function distributorVisitEnd(Request $request): JsonResponse {
        $data[] = [
            'user_id' => $request->user_id ?? null,
            'distributor_id' => $request->distributor_id ?? null,
            'end_time' => $request->end_time ?? null,
            'visit_started' => $request->visit_started ?? null
        ];
        DB::table('distributors_visits')->where('user_id', $request->user_id)->where('distributor_id', $request->distributor_id)->where('end_time', NULL)->update(['end_time'=>$request->end_time, 'visit_started'=>$request->visit_started]);
        return response()->json(['error' => false, 'resp' => 'Distributor visit end time added successfully']);
    }

    public function distributorVisitStatus(Request $request): JsonResponse {
        $data[] = [
            'user_id' => $request->user_id ?? null,
            'distributor_id' => $request->distributor_id ?? null,
        ];
        $data = DB::table('distributors_visits')->where('user_id', $request->user_id)->where('distributor_id', $request->distributor_id)->where('end_time', NULL)->get();
        return response()->json(['error' => false, 'resp' => 'Distributor visit status fetched successfully', 'data' => $data]);
    }

    public function catalogueList(Request $request): JsonResponse
    {
        $data = DB::table('product_catalogues')->get();
        // $data = Distributor::with('user')->latest('id', 'desc')->get();
        return response()->json(['error' => false, 'resp' => 'Catalogue list fetch successfull', 'data' => $data]);
    }

}
