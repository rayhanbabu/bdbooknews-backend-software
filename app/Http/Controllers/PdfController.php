<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Programe;
use App\Models\Year;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Week;
use App\Models\Semester_routine;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
     public function semester_routine_pdf(Request $request){
         try{  
            $dept_id = $request->header('dept_id');
            $year_id=$request->year_id;
            $programe_id=$request->programe_id;
            $semester_id=$request->semester_id;

      $data=Semester_routine::where('dept_id',$dept_id)->where('year_id',$year_id)
        ->where('programe_id',$programe_id)->where('semester_id',$semester_id)
        ->select('semester_routines.class_date',DB::raw("Max(year_id) as year_id")
        ,DB::raw("Max(programe_id) as programe_id"),DB::raw("Max(semester_id) as semester_id")
        ,DB::raw("Max(dept_id) as dept_id"))
       ->groupBy('class_date')->get();

        $file='Class-Routine'; 
       
        $pdf=PDF::setPaper('a4','portrait')->loadView('pdf.semester_routine',['data'=>$data,'dept_id'=>$dept_id]);
             //return $pdf->download($file); portrait landscape 
         return  $pdf->stream($file,array('Attachment'=>false)); 
      
      }catch (Exception $e) { return  view('errors.error',['error'=>$e]);}
}

}
