<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\CalotteryNumber;

class CalotteryExport implements FromQuery, WithMapping, WithHeadings, WithEvents
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

    public function query() 
    {
        $query;
        if ($this->fromId != null && $this->toId != null) {
            dd($this->fromId);
            $query = CalotteryNumber::query()->where("draw_number", ">=", $this->fromId)
                                             ->where("draw_number", "<=", $this->toId);
        } else {
            dd($this->fromId);
            $query = CalotteryNumber::query()->where("created_at", ">=", $this->fromDate . " 00:00:00")
                                             ->where("created_at", "<=", $this->toDate . " 23:59:59");
        }

        if ($this->color == 1) {
            return $query->select(["draw_number", "blue_numbers"]);
        } else {
            return $query->select(["draw_number", "red_number"]);
        }
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
