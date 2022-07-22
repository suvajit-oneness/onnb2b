<?php

namespace App\Exports;

use App\Models\Suburb;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
// used for autosizing columns
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuburbExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Suburb::all();
    }

    public function map($categories): array
    {
        return [

            $categories->name,
            $categories->pincode,
            $categories->description,
            $categories->image,
            $categories->created_at,
        ];
    }

    public function headings(): array
    {
        return ['Name', 'Pincode', 'Description', 'image', 'Created'];
    }
}
