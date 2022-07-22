<?php

namespace App\Repositories;

use App\Interfaces\FaqInterface;
use App\Models\Faq;
use Illuminate\Support\Facades\Hash;

class FaqRepository implements FaqInterface
{
    /**
     * This method is for show faq list
     *
     */
    public function listAll()
    {
        return Faq::all();
    }
    /**
     * This method is for show faq details
     * @param  $id
     *
     */
    public function listById($id)
    {
        return Faq::findOrFail($id);
    }
    /**
     * This method is for create faq
     *
     */
    public function create(array $data)
    {
        $collectedData = collect($data);
        $newEntry = new Faq;
        $newEntry->question = $collectedData['question'];
        $newEntry->answer = $collectedData['answer'];
        $newEntry->save();

        return $newEntry;
    }
    /**
     * This method is for faq update
     *
     *
     */
    public function update($id, array $newDetails)
    {
        $updatedEntry = Faq::findOrFail($id);
        $collectedData = collect($newDetails);
        $updatedEntry->question = $collectedData['question'];
        $updatedEntry->answer = $collectedData['answer'];
        $updatedEntry->save();

        return $updatedEntry;
    }
    /**
     * This method is for update faq status
     * @param  $id
     *
     */
    public function toggle($id){
        $updatedEntry = Faq::findOrFail($id);

        $status = ( $updatedEntry->status == 1 ) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }
     /**
     * This method is for faq delete
     * @param  $id
     *
     */
    public function delete($id)
    {
        Faq::destroy($id);
    }
}
