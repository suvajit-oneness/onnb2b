<?php

namespace App\Interfaces;

interface NotificationInterface
{
    /**
     * This method is to fetch list of all categories
     */
    public function getAlllist();

    /**
     * This method is to get category details by id
     * @param str $categoryId
     */
    public function getNotificationById($Id);

    /**
     * This method is to create category
     * @param arr $data
     */
    public function create(array $data);

    /**
     * This method is to update category details
     * @param str $categoryId
     * @param arr $newDetails
     */
    public function update($Id, array $newDetails);

    /**
     * This method is to toggle category status
     * @param str $categoryId
     */
    public function toggleStatus($Id);

    /**
     * This method is to delete category
     * @param str $categoryId
     */
    public function delete($Id);

   /**
     * This method is to get category details by slug
     * @param str $slug
     */
    public function getCategoryBySlug($slug);




}

