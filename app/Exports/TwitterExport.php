<?php

namespace App\Exports;

use App\TwitterBountyUser;
use Thujohn\Twitter\Facades\Twitter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class TwitterExport implements FromQuery, WithHeadings
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

    public function __construct()
    {

    }

    public function query()
    {
        return TwitterBountyUser::query();
        
    }
}