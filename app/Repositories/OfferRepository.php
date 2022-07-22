<?php
namespace App\Repositories;

use App\Interfaces\OfferInterface;

use App\Models\Offer;

class OfferRepository implements OfferInterface
{
    /**
     * This method is for show offer list
     *
     */
    public function listAll()
    {
        return Offer::get();
    }

    /**
     * This method is for show offer list
     *
     */
    public function listAllOffer($cat=5)
    {
        return Offer::latest('id','desc')->paginate($cat);
    }
    /**
     * This method is for show offer details
     * @param  $id
     *
     */
    public function listById($id)
    {
        return Offer::findOrFail($id);
    }

    /**
     * This method is for offer create
     * @param array $data
     * return in array format
     */
    public function create(array $data)
    {
        $collectedData = collect($data);

        $newEntry = new Offer;
        $newEntry->title = $collectedData['title'];

        if (!empty($collectedData['is_current'])) {
            $newEntry->is_current = $collectedData['is_current'];
        }
        $upload_path = "uploads/offer/";
        $image = $collectedData['image'];
        $imageName = time().".".$image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $newEntry->image= $upload_path.$uploadedImage;
        $upload_path = "uploads/offer/";
        $pdf = $collectedData['pdf'];
        $pdfName = time().".".$pdf->getClientOriginalName();
        $pdf->move($upload_path, $pdfName);
        $uploadedPdf = $pdfName;
        $newEntry->pdf= $upload_path.$uploadedPdf;

        $newEntry->start_date = $collectedData['start_date'];
        $newEntry->end_date = $collectedData['end_date'];
        $newEntry->save();

        return $newEntry;
    }
    /**
     * This method is for offer update
     * @param array $newDetails
     * return in array format
     */
    public function update($id, array $newDetails)
    {
        $upload_path = "uploads/offer/";
        $offer = Offer::findOrFail($id);
        $collection = collect($newDetails);
        // dd($newDetails);

        $offer->title = $collection['title'];
        $offer->is_current = $collection['is_current'];
       $offer->start_date = $collection['start_date'];
        $offer->end_date = $collection['end_date'];

            // dd('here');

            if (isset($newDetails['image'])) {
                // dd('here');
                $image = $collection['image'];
                $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
                $image->move($upload_path, $imageName);
                $uploadedImage = $imageName;
                $offer->image = $upload_path.$uploadedImage;
            }

            if (isset($newDetails['pdf'])) {
                // dd('here');
                $pdf = $collection['pdf'];
                $pdfName = time().".".mt_rand().".".$pdf->getClientOriginalName();
                $pdf->move($upload_path, $pdfName);
                $uploadedImage = $pdfName;
                $offer->pdf = $upload_path.$uploadedImage;
            }
        // dd('outside');

        $offer->save();

        return $offer;
    }
    /**
     * This method is for  update offer status
     * @param  $id
     *
     */
    public function toggle($id){
        $updatedEntry = Offer::findOrFail($id);

        $status = ( $updatedEntry->is_current == 1 ) ? 0 : 1;
        $updatedEntry->is_current = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }
    /**
     * This method is for offer delete
     * @param  $id
     *
     */
    public function delete($id)
    {
        Offer::destroy($id);
    }
    public function getSearchOffer(string $term)
    {
        return Offer::where([['title', 'LIKE', '%' . $term . '%']])
        ->paginate(5);
    }


}
