<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\CalotteryNumber;

class CalotteryExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use Exportable;

    public function forYear($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        return $this;
    }

    public function forId($fromId, $toId)
    {
        $this->fromId = $fromId;
        $this->toId = $toId;
        return $this;
    }

    public function forColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function collection()
    {
        $query;
        if ($this->fromId != null && $this->toId != null) {
            $query = CalotteryNumber::query()->where("draw_number", ">=", $this->fromId)
                                             ->where("draw_number", "<=", $this->toId);
        } else {
            $query = CalotteryNumber::query()->where("created_at", ">=", $this->fromDate . " 00:00:00")
                                             ->where("created_at", "<=", $this->toDate . " 23:59:59");
        }

        if ($this->color == 1) {
            $query = $query->select(["draw_number", "blue_numbers"]);
        } else {
            $query = $query->select(["draw_number", "red_number"]);
        }

        $collection = $query->groupBy('draw_number')->orderBy('draw_number')->get();
        $numberArray = $query->select(["draw_number"])->get()->toArray();

        $fromNumber = $collection->first()->toArray()['draw_number'];
        $toNumber = $collection->last()->toArray()['draw_number'];
        for ($i = $fromNumber + 1; $i < $toNumber ; $i++) { 
            $existCalotteryNumber = in_array(array("draw_number" => $i), $numberArray);
            if (!$existCalotteryNumber) {
                $calotteryNumber = new CalotteryNumber;
                $calotteryNumber->draw_number = $i;    
                $collection->put($collection->count(), $calotteryNumber);
            }
        }
        $collection = $collection->sortBy('draw_number');
        return $collection;
    }

    public function headings(): array
    {
        $headingsResult = ["STT"];
        if ($this->color == 1) {
            for ($i=1; $i <= 20; $i++) { 
                $headingsResult[] = "Số {$i}";
            }
        } else {
            $headingsResult[] = "Số 1";
        }
        return $headingsResult;
    }

    public function map($calotteryQuery): array
    {
        $resurltArray=[$calotteryQuery["draw_number"]];
        $nunberList = [];
        if ($this->color == 1) {
            $nunberList = explode(",", $calotteryQuery["blue_numbers"]);
        } else {
            $nunberList = explode(",", $calotteryQuery["red_number"]);
        }
        $resurltArray = array_merge($resurltArray, $nunberList);
        return $resurltArray;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = "A:U";
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

}
