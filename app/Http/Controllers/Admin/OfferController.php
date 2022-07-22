<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\OfferInterface;
use App\Models\Offer;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    private OfferInterface $offerRepository;

    public function __construct(OfferInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }
    /**
     * This method is for show offer list
     *
     */
    public function index(Request $request)
    {
        if (!empty($request->term)) {
        $data = $this->offerRepository->getSearchOffer($request->term);
    } else {
        if($request->export_all){
            $category=Offer::count();

        $data = $this->offerRepository->listAllOffer($category);
        }
        else{
            $data = $this->offerRepository->listAllOffer();
        }
    }
        return view('admin.offer.index', compact('data'));
    }
    /**
     * This method is for create offer
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|max:255",
            "image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "pdf" => "required|mimes:pdf,doc,docs,excel|max:10000000",
            "is_current" => "required|integer",

            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->offerRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.offer.index');
        } else {
            return redirect()->route('admin.offer.create')->withInput($request->all());
        }
    }
    /**
     * This method is for show offer details
     * @param  $id
     *
     */
    public function show(Request $request, $id)
    {
        $data = $this->offerRepository->listById($id);
        return view('admin.offer.detail', compact('data'));
    }
    /**
     * This method is for offer update
     *
     *
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "title" => "required|string|max:255",
          //  "image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            //"pdf" => "required|mimes:pdf,doc,docs,excel|max:10000000",
            "is_current" => "required|integer",

            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->offerRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.offer.index');
        } else {
            return redirect()->route('admin.offer.create')->withInput($request->all());
        }
    }
    /**
     * This method is for update offer status
     * @param  $id
     *
     */
    public function status(Request $request, $id)
    {
        $storeData = $this->offerRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.offer.index');
        } else {
            return redirect()->route('admin.offer.create')->withInput($request->all());
        }
    }
    /**
     * This method is for offer delete
     * @param  $id
     *
     */
    public function destroy(Request $request, $id)
    {
        $this->offerRepository->delete($id);

        return redirect()->route('admin.offer.index');
    }


}
