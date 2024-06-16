<?php

namespace App\Imports;

use App\Models\Univer;
use Maatwebsite\Excel\Concerns\ToModel;

class UniverImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Univer([
            'uni_en'=>$row[0],
            'uni_bn'=>$row[1],
           
        ]);
    }
}
