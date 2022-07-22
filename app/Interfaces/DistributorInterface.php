<?php

namespace App\Interfaces;

interface DistributorInterface
{
    /**
     * This method is for show distributor/retailer list
     *
     */
    public function listAll();

    public function listAllDistributor();

    public function listAllRetailer();

     /**
     * This method is for show distributor/retailer details
     * @param  $id
     *
     */
    public function listById($id);

    /**
     * This method is for show distributor details
     * @param  $id
     *
     */
    public function getDistributorById($id,$user_type);
     /**
     * This method is for show retailer details
     * @param  $id
     *
     */
    public function getRetailerById($id);
    /**
     * This method is for create distributor/retailer
     *
     */
    public function create(array $data);
    /**
     * This method is for distributor/retailer update
     *
     *
     */
    public function update($id, array $data);
    /**
     * This method is for update distributor/retailer status
     * @param  $id
     *
     */
    public function toggle($id);
    /**
     * This method is for update distributor/retailer verification
     * @param  $id
     *
     */
    public function verification($id);
    /**
     * This method is for distributor/retailer delete
     * @param  $id
     *
     */
    public function delete($id);
    public function customSearch(String $term);

    /**
     * @return mixed
     */
    public function getSearchDirectorymom(string $term);
    public function searchOrderlist(string $term);

}
