<?php

namespace App\Exports;

use App\Models\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
// used for autosizing columns
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CollectionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Collection::all();
    }

    public function map($categories): array
    {
        return [

            $categories->title,
            $categories->short_description,
            $categories->bottom_content,
            $categories->description,

            $categories->pin_code,
            $categories->suburb_id,
            $categories->meta_title,
            $categories->meta_key,
            $categories->meta_description,
            ($categories->status == 1) ? 'Active' : 'Inactive',
            $categories->created_at,
        ];
    }

    public function headings(): array
    {
        return ['Title', 'Short Description','Bottom Content','Description', 'Pincode','Suburb','Meta Title','Meta Key','Meta Description','Status','Created at'];
    }

}

