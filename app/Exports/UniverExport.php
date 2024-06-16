<?php

namespace App\Exports;

use App\Models\Univer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class UniverExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */
  
    public function __construct(int $export_id)
    {
        $this->id = $export_id;
    
    }


    public function query(){
        //return Univer::all();
        return Univer::query()->select([ 
          'university',
        
        ])->where('id','>=' ,$this->id);
    }
}
