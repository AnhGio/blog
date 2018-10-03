<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\CalotteryNumber;

class CrawlCalotteryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:calottery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://scalecalservice.calottery.com/api/v1.5/drawgames/22?drawscount=1');

        $responseData = json_decode($response->getBody(), true);
        $drawsData = $responseData["draws"][0];
        $drawNumber = $drawsData["DrawNumber"];
        $blueNumbers = [];
        $redNumber = 0;
        foreach ($drawsData["WinningNumbers"] as $value) {
            if ($value["IsBullseye"]) {
                $redNumber = $value["Number"];
            }
            $blueNumbers[] = $value["Number"];
        }
        sort($blueNumbers);
        
        $calotteryNumber = new CalotteryNumber;
        if ($calotteryNumber->latest()->first()->draw_number == $drawNumber) {
            return;
        }
        $calotteryNumber->draw_number = $drawNumber;
        $calotteryNumber->blue_numbers = implode(",", $blueNumbers);
        $calotteryNumber->red_number = $redNumber;
        dd($calotteryNumber->save());
    }
}
