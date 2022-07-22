<?php

namespace App\Interfaces;

interface CatalogueInterface
{
    /**
     * This method is to fetch list of all collections
     */
    public function getAllCatalogue();

    /**
     * This method is to fetch list of all search collections
     * @param str $term
     */
    public function getSearchCatalogue(string $term);


    /**
     * This method is to get collection details by id
     * @param str $collectionId
     */
    public function getCatalogueById($catalogueId);

    /**
     * This method is to get collection details by slug
     * @param str $slug
     */
    public function getCatalogueBySlug($slug, array $request = null);

    /**
     * This method is to create collection
     * @param arr $collectionDetails
     */
    public function createCatalogue(array $collectionDetails);

    /**
     * This method is to update collection details
     * @param str $collectionId
     * @param arr $newDetails
     */
    public function updateCatalogue($catalogueId, array $newDetails);

    /**
     * This method is to toggle collection status
     * @param str $collectionId
     */
    public function toggleStatus($catalogueId);

    /**
     * This method is to delete collection
     * @param str $collectionId
     */
    public function deleteCatalogue($catalogueId);

    
}
