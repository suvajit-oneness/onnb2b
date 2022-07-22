<?php

namespace App\Interfaces;

interface CartInterface
{
    public function listAll();
    public function listById($userId);
    public function listDistributorById($id);
    public function couponCheck($coupon_code);
    public function couponRemove();
    public function addToCart(array $data);
    public function bulkAddCart(array $data);
    public function bulkAddCartDistributor(array $data);
    public function viewByIp();
    public function viewByUserId();
    public function delete($id);
    public function deleteDistributor($id);
    public function empty();
    public function qtyUpdate($id,$type);
    public function listDistributororderAll();
    public function DistributoraddToCart(array $data);

   
    


}
