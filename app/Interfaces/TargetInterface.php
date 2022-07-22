<?php

namespace App\Interfaces;

interface TargetInterface
{
    /**
     * This method is to fetch list of all targets
     */
    public function getAlltargets();


    /**
     * This method is to get target details by id
     * @param str $targetId
     */
    public function getTargetById($targetId);

    /**
     * This method is to create target
     * @param arr $targetDetails
     */
    public function createTarget(array $targetDetails);

    /**
     * This method is to update target details
     * @param str $targetId
     * @param arr $newDetails
     */
    public function updateTarget($targetId, array $newDetails);

    /**
     * This method is to toggle target status
     * @param str $targetId
     */
    public function targetStatus($targetId);

    /**
     * This method is to delete category
     * @param str $targetId
     */
    public function deleteTarget($targetId);

   /**
     * This method is to get category details by filter
     * @param str $collectionId,$yearFrom,$yearTo
     */
    public function getTargetByFilter($collection,$year_from,$year_to,$userId,$userType);




}

