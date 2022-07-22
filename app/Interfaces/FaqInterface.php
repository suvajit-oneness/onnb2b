<?php

namespace App\Interfaces;

interface FaqInterface
{
    /**
     * This method is for show faq list
     *
     */
    public function listAll();
     /**
     * This method is for show faq details
     * @param  $id
     *
     */
    public function listById($id);
    /**
     * This method is for create faq
     *
     */
    public function create(array $data);
    /**
     * This method is for faq update
     *
     *
     */
    public function update($id, array $data);
    /**
     * This method is for update faq status
     * @param  $id
     *
     */
    public function toggle($id);
    /**
     * This method is for faq delete
     * @param  $id
     *
     */
    public function delete($id);
}
