<?php

namespace App\Exports;

use App\Models\Child;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ChildrenExport implements WithMultipleSheets
{
    public function __construct(private Collection $children) {}

    public function sheets(): array
    {
        $sheets = [];

        // Summary sheet first
        $sheets[] = new Sheets\SummarySheet($this->children);

        // One sheet per child
        foreach ($this->children as $child) {
            $sheets[] = new Sheets\ChildSheet($child);
        }

        return $sheets;
    }
}
