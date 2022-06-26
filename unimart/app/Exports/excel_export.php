<?php

namespace App\Exports;

use App\Product_cat;
use Maatwebsite\Excel\Concerns\FromCollection;

class excel_export implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product_cat::all();
    }
}
