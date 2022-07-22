<?php

namespace App\Exports;

use App\Models\Store;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
// used for autosizing columns
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StoreExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data= \DB::select('SELECT ro.retailer,ro.vp,ro.distributor_name,ro.ase,ro.asm,ro.rsm ,ro.state,s.status,s.id,s.store_name,s.email,s.contact,s.bussiness_name ,s.address,s.area,s.city FROM `retailer_list_of_occ` AS ro INNER JOIN stores AS s ON ro.retailer = s.store_name');
       // return Store::all();
        return collect($data);

    }

    public function map($data): array
    {
        return [

            $data->store_name,
            $data->bussiness_name,
            $data->vp,
            $data->rsm,
            $data->asm,
            $data->ase,
            $data->email,
            $data->contact,
            $data->address,
            $data->area,
            $data->city,
            $data->state,
            ($data->status == 1) ? 'Active' : 'Inactive',

        ];
    }

    public function headings(): array
    {
        return ['Name', 'Distributor','VP','Rsm','Asm','Ase','Email','Contact','Address','Area','City','State','Status'];
    }

}

