<?php

namespace App\Http\Controllers;

use App\Models\Dept;
use App\Models\Teacher;
use App\Models\Univer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;



class DeptController extends Controller
{
  
    public function dept_view(Request $request){
        try{ 
            $role = $request->header('role');
            $data=Univer::orderBy('id','asc')->get();
            return view('maintain.dept',["data"=>$data,"role"=>$role]);
         }catch (Exception $e) { return  view('errors.error',['error'=>$e]);}
       }
  
       public function store(Request $request){
        $validator=\Validator::make($request->all(),[    
           'dept_name'=>'required',
           'dept_address'=>'required',
           'phone'=>'required|unique:teachers,phone',
           'email'=>'required|unique:teachers,email',
           'password'=>'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
           'image'=>'image|mimes:jpeg,png,jpg|max:400',
         ],
          [
           'password.regex'=>'password minimum six characters including one uppercase letter, 
            one lowercase letter and one number'
         ]);

         $maintain_id = $request->header('maintain_id');
         $role = $request->header('role');
         $university_assign = $request->header('university_assign');
  
       if($validator->fails()){
              return response()->json([
                'status'=>700,
                'message'=>$validator->messages(),
             ]);
       }else{
              $model= new Dept;
              if($role=='supperadmin'){
                $model->university_id=$request->input('university_id');
              }else{
                $model->university_id=$university_assign;
              }
              
              $model->dept_name=$request->input('dept_name');
              $model->dept_address=$request->input('dept_address');
              $model->dept_code=$request->input('dept_code');
              $model->faculty=$request->input('faculty');
              $model->established_date=$request->input('established_date');
              $model->created_by=$maintain_id;
              $model->save();
  
              $teacher=new Teacher;
              $teacher->dept_id =$model->id;
              $teacher->teacher_name=$model->dept_name;
              $teacher->designation=$model->dept_address;
              $teacher->email=$request->input('email');
              $teacher->phone =$request->input('phone');
              $teacher->password =Hash::make($request->input('password'));
              $teacher->created_by=$maintain_id;
              $teacher->role='admin';
              $teacher->save();
             
            
              return response()->json([
                    'status'=>200,  
                    'message'=>'Data Added Successfull',
               ]);     
          }
      }
  
     public function dept_edit(Request $request) {
        $id = $request->id;
        $data=Dept::leftjoin('univers','univers.id', '=','depts.university_id')
         ->leftjoin('teachers','teachers.dept_id','=','depts.id')
         ->where('teachers.role','admin')->where('depts.id',$id)
         ->select('univers.university','depts.*','teachers.email','teachers.phone'
         ,'teachers.role','teachers.id as teacher_id','teachers.password'
         ,'teachers.teacher_status','teachers.login_code')->first();
          return response()->json([
             'status'=>200,  
              'data'=>$data,
           ]);
     }
  
     public function dept_update(Request $request ){
      $validator=\Validator::make($request->all(),[    
        'dept_name'=>'required',
        'phone'=>'required|unique:teachers,phone,'.$request->input('teacher_id'),
        'email'=>'required|unique:teachers,email,'.$request->input('teacher_id'),
        'image'=> 'image|mimes:jpeg,png,jpg|max:400',
      ],
       );
  
      $maintain_id = $request->header('maintain_id');
      $role = $request->header('role');
      $university_assign = $request->header('university_assign');
    if($validator->fails()){
           return response()->json([
             'status'=>700,
             'message'=>$validator->messages(),
          ]);
    }else{
           $model=Dept::find($request->input('edit_id'));
      if($model){
       
        if($role=='supperadmin'){
            $model->university_id=$request->input('university_id');
          }else{
            $model->university_id=$university_assign;
          }
       
          $model->dept_name=$request->input('dept_name');
          $model->dept_address=$request->input('dept_address');
          $model->dept_code=$request->input('dept_code');
          $model->faculty=$request->input('faculty');
          $model->established_date=$request->input('established_date');
          $model->created_by=$maintain_id;
          $model->update(); 
          
          $teacher=Teacher::find($request->input('teacher_id'));
          if($teacher){
            $teacher->teacher_name=$model->dept_name;
            $teacher->designation=$model->dept_address;
            $teacher->email=$request->input('email');
            $teacher->phone =$request->input('phone');
            $teacher->teacher_status =$request->input('teacher_status');
            $teacher->created_by=$maintain_id;
            $teacher->update(); 
           }
           
           return response()->json([ 
               'status'=>200,
               'message'=>'Data Updated Successfull'
            ]);
        }else{
          return response()->json([
              'status'=>404,  
              'message'=>'Student not found',
            ]);
      }
  
      }
    }
  
  
    public function dept_delete(Request $request) { 
  
            $model=Dept::find($request->input('id'));
            $model->delete();
            return response()->json([
               'status'=>300,  
               'message'=>'Data Deleted Successfully',
          ]);
     } 
    
  
    public function fetch(){
       $data=Dept::leftjoin('univers','univers.id', '=','depts.university_id')
         ->leftjoin('teachers','teachers.dept_id', '=','depts.id')
         ->where('teachers.role','admin')
         ->select('univers.university','depts.*','teachers.email','teachers.phone','teachers.login_code'
         ,'teachers.role','teachers.id as teacher_id','teachers.password','teachers.teacher_status')->paginate(10);
     
        return view('maintain.dept_data',compact('data'));
     }
  
  
  
    function fetch_data(Request $request)
    {
     if($request->ajax())
     {
           $sort_by = $request->get('sortby');
           $sort_type = $request->get('sorttype'); 
              $search = $request->get('search');
              $search = str_replace("","%", $search);
              $data=Dept::leftjoin('univers','univers.id', '=','depts.university_id')
              ->leftjoin('teachers','teachers.dept_id', '=','depts.id')
              ->where('teachers.role','admin')
              ->where(function($query) use ($search) {
                  $query->where('univers.university', 'like', '%'.$search.'%')
                      ->orWhere('dept_name', 'like', '%'.$search.'%')
                      ->orWhere('email', 'like', '%'.$search.'%')
                      ->orWhere('dept_address', 'like', '%'.$search.'%')
                      ->orWhere('phone', 'like', '%'.$search.'%');
                })->select('univers.university','depts.*','teachers.email','teachers.phone','teachers.login_code'
                ,'teachers.role','teachers.id as teacher_id','teachers.password','teachers.teacher_status')->paginate(10);
           return view('maintain.dept_data', compact('data'))->render();
                   
        }
    }
  


}