<?php

namespace App\Imports;

use App\Product_cat;
use Maatwebsite\Excel\Concerns\ToModel;

class excel_import implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product_cat([
            //
            "id" => $row[0],
            "name" => $row[1],
            "slug" => $row[2],
            "position" => $row[3],
            "parent_id" => $row[4],
            "user_id" => $row[5],
            "created_at" => $row[6],
            "update_at" => $row[7]
        ]);
    }
}
