<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

// used for autosizing columns
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data= Order::all();


        return $data;
    }

    public function map($data): array
    {
        foreach($data->orderProducts as $productKey => $productValue){
        return [


                $citations[] = array(
                    'Order No'       => $data->order_no,
                    'Order Time'      => $data->order_time,
                    'Order Type' => $data->order_type,
                    'Product'      => $productValue->product_name,
                    'Size' =>         $productValue->size,
                    'Quantity' =>  $productValue->qty,
                      'color'   =>   $productValue->colorDetails->name,
                    'Latitude' => $data->order_lat,
                    'Longitude' => $data->order_lng,
                    'Email' => $data->email,
                    'Mobile'=> $data->mobile,
                    'Amount'=> $data->amount,
                    'Final Amount'=> $data->final_amount,
                    'Status'=> $data->status,
                    'Created at'=> $data->created_at,
                    )

        ];

    }
    if(isset($citations))
    {
        $collection = collect($citations);
    }
    else
    {
        $citations = [];
        $collection = collect($citations);

    }

    return $collection;
    }

    public function headings(): array
    {
        return ['Order No', 'Order Time','Order Type','Product ','Size','Quantity','Color','Latitude','Longitude','Email','Mobile','Amount','Final Amount', 'Status','Created at'];
    }

}

