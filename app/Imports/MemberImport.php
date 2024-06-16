<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;

class MemberImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Member([
            'hall_id '=>$row[0],
            'name'=>$row[1],
            'phone '=>$row[2],
            'card'=>$row[3],
            'registration'=>$row[4],
            'email_verify'=>$row[5],
            'email '=>$row[6],
            'email2'=>$row[7],
            'password'=>$row[8], 
        ]);
    }
}
