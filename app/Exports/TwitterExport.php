<?php

namespace App\Exports;

use App\TwitterBountyUser;
use Thujohn\Twitter\Facades\Twitter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TwitterExport implements FromQuery, WithHeadings, WithColumnFormatting
{
    use Exportable;

    public function headings(): array
    {
        return [
            '#',
            'Twitter ID',
            'Twitter Username',
            'Ethereum Address',
            'Is Following',
            'Has Retweeted'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function __construct()
    {

    }

    public function query()
    {
        return TwitterBountyUser::query();

    }
}