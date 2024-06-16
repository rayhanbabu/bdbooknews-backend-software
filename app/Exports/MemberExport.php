<?php
namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MemberExport implements FromQuery,WithHeadings
{

    public function __construct($hall_id)
     {
          $this->hall_id = $hall_id; 
     }

     public function headings(): array{
        return [
            'hall_id',
            'name',
            'Phone Number',
            'Card',
            'Du Registration',
            'E-mail',
            'E-mail2',
            'Password',
            'Profile image',
            'Father Name',
            'Mother name',
            'Department',
        ];
     }

     public function query(){
          return Member::query()->select([ 
            'hall_id',
            'name',
            'phone',
            'card',
            'registration',
            'email',
            'email2',
            'password',
            'profile_image',
            'father',
            'mother',
            'dept',
          ])->where('hall_id',$this->hall_id);
      }


}
