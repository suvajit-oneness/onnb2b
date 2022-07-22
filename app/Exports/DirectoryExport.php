<?php

namespace App\Exports;

use App\Models\Directory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
// used for autosizing columns
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DirectoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Directory::all();
    }

    public function map($categories): array
    {
        return [
            $categories->image,
            $categories->name,
            $categories->email,
            $categories->mobile,
            $categories->address,
            $categories->category_id,
            $categories->establish_year,
            $categories->ABN,
            $categories->monday,
            $categories->tuesday,
            $categories->wednesday,
            $categories->thursday,
            $categories->friday,
            $categories->saturday,
            $categories->sunday,
            $categories->public_holiday,
            $categories->category_tree,
            $categories->pin,
            $categories->lat,
            $categories->lon,
            $categories->description,
            $categories->service_description,
            $categories->opening_hour,
            $categories->website,
            $categories->facebook_link,
            $categories->twitter_link,
            $categories->instagram_link,
            ($categories->status == 1) ? 'Active' : 'Inactive',
            $categories->created_at,
        ];
    }

    public function headings(): array
    {
        return ['Image', 'Name', 'Email', 'Mobile','Address','Category','Establish Year','ABN','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','public_holiday','category_tree','pin','lat','lon','description','service_description','opening_hour','website','facebook_link','twitter_link','	instagram_link','Status','Created at'];
    }

}

