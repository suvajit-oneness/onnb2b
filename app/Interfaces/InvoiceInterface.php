<?php

namespace App\Interfaces;

interface InvoiceInterface
{
    public function invoicedetails();
    public function invoicedetailsById($id);
    public function addInvoice($inv_data);
    public function updateInvoice($id, $inv_data);
    public function deleteInvoiceById($id);
}
