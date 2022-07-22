<?php

namespace App\Interfaces;

interface OrderInterface
{
    public function listAll();
    public function listAllOrder();
    public function listById($id);
    public function listByIdForDistributor($id);
    public function listByStatus($status);
    public function searchOrder(string $term);
    public function create(array $data);
    public function update($id, array $data);
    public function toggle($id, $status);
    public function listByorderId($id);
    public function listByUserId($id);
    public function storeFilter($id);
    public function placeOrder(array $data);
    public function placeOrderUpdated(array $data);
    public function placeOrderUpdatedDistributor(array $data);
    public function listByuserIdForDistributor($userid);
    public function Totalsales($id);
    public function latestsales($storeId);
    public function lastOrder($storeId);
    public function avgOrder($storeId);
    public function lastVisit($storeId);
    public function Totalamount($storeId,$productId);
    public function TotalOrder($storeId,$productId);
    public function orderByStore($fname);
    public function searchOrderlist(string $term);
    public function OrderUpdatedDistributor(array $data);
    public function listbyDistributorOrder($cat=5);
}
