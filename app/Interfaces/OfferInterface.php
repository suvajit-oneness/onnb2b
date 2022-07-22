<?php

namespace App\Interfaces;

interface OfferInterface
{
    /**
     * This method is for show offer list
     *
     */
    public function listAll();
    /**
     * This method is for show offer details
     * @param  $id
     *
     */
    public function listById($id);
     /**
     * This method is for offer create
     * @param array $data
     * return in array format
     */
    public function create(array $data);
     /**
     * This method is for offer update
     * @param array $newDetails
     * return in array format
     */
    public function update($id, array $data);
    /**
     * This method is for  update offer status
     * @param  $id
     *
     */
    public function toggle($id);
    /**
     * This method is for offer delete
     * @param  $id
     *
     */
    public function delete($id);


    public function getSearchOffer(string $term);
}
