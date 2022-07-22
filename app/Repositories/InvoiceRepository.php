<?php

namespace App\Repositories;

use App\Interfaces\InvoiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FacadesFile;

class InvoiceRepository implements InvoiceInterface
{
    public function invoicedetails()
    {
        return DB::table('invoice_retailer')->get();
    }
    public function invoicedetailsById($id)
    {
        return DB::table('invoice_retailer')->where('id', $id)->get();
    }
    public function addInvoice($inv_data)
    {
        $data = DB::table('invoice_retailer')->insert([
            'retailer_id' => Auth::guard('web')->user()->id,
            'amount' => $inv_data['amount'],
            'description' => $inv_data['description'],
            'latitude' => $inv_data['latitude'],
            'longitude' => $inv_data['longitude'],
            'image' => $inv_data['image'],
        ]);

        return $data;
    }
    public function updateInvoice($id, $inv_data)
    {
        $update = DB::table('invoice_retailer')->where('id', $id)->update([
            'amount' => $inv_data['amount'],
            'description' => $inv_data['description'],
            'latitude' => $inv_data['latitude'],
            'longitude' => $inv_data['longitude'],
            'image' => $inv_data['image'],
        ]);
        return $update;
    }
    public function deleteInvoiceById($id)
    {
        $image = public_path() . '/uploads/invoice//' . DB::table('invoice_retailer')->where('id', $id)->get('image')[0]->image;
        FacadesFile::delete($image);
        return DB::table('invoice_retailer')->where('id', $id)->delete();
    }
}
