<?php

namespace App\Http\Controllers\Api;

use App\Interfaces\OfferInterface;


use App\Models\Offer;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class OfferController extends Controller
{
    private OfferInterface $offerRepository;

    public function __construct(OfferInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }
    /**
     * This method is for show offer list
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {

        $offer = $this->offerRepository->listAll();

        return response()->json(['error'=>false, 'resp'=>'Offer data fetched successfully','data'=>$offer]);
    }
    /**
     * This method is for create offer
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'title', 'is_current', 'image', 'pdf',  'start_date', 'end_date'
        ]);

        return response()->json(
            [
                'data' => $this->offerRepository->create($data)
            ],
            Response::HTTP_CREATED
        );
    }
    /**
     * This method is for show offer details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $id = $request->route('id');

       $offer = $this->offerRepository->listById($id);

        return response()->json(['error'=>false, 'resp'=>'Offer data fetched successfully','data'=>$offer]);
    }
    /**
     * This method is for offer update
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $newDetails = $request->only([
            'title', 'is_current', 'image', 'pdf',  'start_date', 'end_date'
        ]);

        return response()->json([
            'data' => $this->offerRepository->update($id, $newDetails)
        ]);
    }
    /**
     * This method is for offer delete
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $this->offerRepository->delete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
