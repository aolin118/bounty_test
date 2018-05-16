<?php

namespace App\Exports;

use DB;
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
            'Twitter Username',
            'Twitter ID',
            'Followers Count',
            'Referrer Twitter ID',
            'Ethereum Address',
            'Refer Count',
            'Is Following',
            'Has Retweeted'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function __construct()
    {

    }

    public function query()
    {
        return TwitterBountyUser::select("twitter_bounty_users.id", "twitter_bounty_users.twitter_username", "twitter_bounty_users.twitter_id", "twitter_bounty_users.twitter_followers_count", "twitter_bounty_users.referrer", "twitter_bounty_users.eth_address", "twitter_bounty_users.is_following", "twitter_bounty_users.has_retweeted", DB::raw('(t2.count IS NOT NULL) as refer_count'), "twitter_bounty_users.created_at", "twitter_bounty_users.updated_at")
                                    ->leftJoin(DB::raw("(SELECT referrer, count(*) as count FROM `twitter_bounty_users` GROUP BY referrer) as t2"), 'twitter_bounty_users.username', '=', 't2.referrer');

    }
}