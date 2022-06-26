<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class excel_import_product implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            //
            'id' => $row[0],
            'name' => $row[1],
            'slug' => $row[2],
            'description' => $row[3],
            'content' => $row[4],
            'thumbnail' => $row[5],

            'price' => $row[6],
            'sale_price' => $row[7],
            'user_id' => $row[8],
            'product_cat_id' => $row[9],
            'qty_sold' => $row[10],
            'qty' => $row[11],
            'status' => $row[12],

            'browse' => $row[13],
            'featured' => $row[14],
            'best_seller' => $row[15],
            'views_count' => $row[16],
            'deleted_at' => $row[17],
            'created_at' => $row[18],
            'update_at' => $row[19]
        ]);
    }
}
