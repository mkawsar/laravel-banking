<?php

namespace App\Exports;

use App\Models\Members\Member;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MemberExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $routeID;

    public function __construct($id)
    {
        $this->routeID = $id;
    }


    public function view(): View
    {
        $members = Member::where('member_route_id', '=', $this->routeID)->get();
        return \view('excel.members', compact('members'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
