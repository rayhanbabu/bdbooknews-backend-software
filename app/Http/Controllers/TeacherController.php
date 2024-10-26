<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Helpers\TeacherJWTToken;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Exception;


class TeacherController extends Controller
{
    
    public function login(Request $request){
        try {
               return view('admin.login');
          } catch (Exception $e) {
               return  view('errors.error', ['error' => $e]);
          }
    }

    public function dashboard(Request $request)
    {
        try {
            return view('admin.dashboard');
        } catch (Exception $e) {
            return  view('errors.error', ['error' => $e]);
        }
    }


    public function login_insert(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'phone' => 'required',
                'password' => 'required',
            ],
            [
                'phone.required' => 'Phone is required',
                'password.required' => 'Password is required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 700,
                'message' => $validator->messages(),
            ]);
        } else {

         $username = Teacher::where('phone', $request->phone)->first();
         
          if ($username && $username->teacher_status == 0) {   
              return response()->json([
                  'message' => 'Your account is inactive',
                  'status' => 600,
                ]); // HTTP status code 429 for too many requests
            }
    

         // Rate-limiting (Throttle)
    $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
         $seconds = RateLimiter::availableIn($throttleKey);
         return response()->json([
             'message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
             'status' => 600,
          ]); // HTTP status code 429 for too many requests
       }

      if (!$username || !Hash::check($request->password, $username->password)) {
          // Increment the throttle attempts if login fails
           RateLimiter::hit($throttleKey);  

         return response()->json([
             'message' => 'These credentials do not match our records.',
             'status' => 600,
           ]); // HTTP status code 422 for validation error
         }

              RateLimiter::clear($throttleKey);

              $rand = rand(11111, 99999);
              DB::update("update teachers set login_code ='$rand' where phone = '$username->phone'");
              SendEmail($username->email, "teacher Otp code", "One Time OTP Code", $rand, "ANCOVA");
                 return response()->json([
                       'status' => 200,
                       'phone' => $username->phone,
                       'email' => $username->email,
                   ]);
          }
       }


    public function login_verify(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'otp' => 'required|numeric',
            ],
            [
                'otp.required' => 'OTP is required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 700,
                'message' => $validator->messages(),
            ]);
        } else {
            $username = Teacher::where('phone', $request->verify_phone)->where('email', $request->verify_email)
                ->where('login_code', $request->otp)->first();
            if ($username) {
                DB::update("update maintains set login_code ='null' where phone = '$username->phone'");
                $token_teacher = TeacherJWTToken::CreateToken($username->id, $username->teacher_name, $username->email, $username->dept_id, $username->role);
                Cookie::queue('token_teacher', $token_teacher, 60 * 96); //96 hour
                $teacher_info = [
                     "role" => $username->role, "teacher_name" => $username->teacher_name,
                     "email" => $username->email, "phone" => $username->phone, "dept_id" => $username->dept_id
                ];
                $teacher_info_array = serialize($teacher_info);
                Cookie::queue('teacher_info', $teacher_info_array, 60 * 96);
                return response()->json([
                    'status' => 200,
                    'message' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 300,
                    'message' => "Invalid OTP",
                ]);
            }
        }
    }


    public function logout()
    {
        Cookie::queue('token_teacher', '', -1);
        Cookie::queue('teacher_info', '', -1);
        return redirect('admin/login');
    }


    public function forget(Request $request)
    {
       try {
              return view('admin.forget');
          } catch (Exception $e) {
              return  view('errors.error', ["error" => $e]);
         }
    }


    public function forgetemail(request $request)
    {
        $email = $request->input('email');
        $rand = rand(11111, 99999);
        $email_exist = Teacher::where('email', $email)->first();
        if ($email_exist) {
            DB::update("update teachers set forget_code ='$rand' where email = '$email'");
            SendEmail($email_exist->email, "Password Recovary code", "One Time  Code", $rand, "Dining Name");
            return response()->json([
                'status' => 500,
                'errors' => 'Email exist',
            ]);
        } else {
            return response()->json([
                'status' => 600,
                'errors' => 'Invalid  Email ',
            ]);
        }
    }



    public function forgetcode(request $request)
    {

        $email_id = $request->input('email_id');
        $forget_code = $request->input('forget_code');
        $code_exist = Teacher::where('email', $email_id)->where('forget_code', $forget_code)->count('email');
        if ($code_exist >= 1) {
            return response()->json([
                'status' => 500,
                'errors' => 'valid code',
            ]);
        } else {
            return response()->json([
                'status' => 600,
                'errors' => 'Invalid  Code ',
            ]);
        }
    }


    public function confirmpass(request $request)
    {
        $email_id_pass = $request->input('email_id_pass');
        $forget_code_pass = $request->input('forget_code_pass');
        $npass = $request->input('npass');
        $cpass = $request->input('cpass');
        //$password=Hash::make($npass);
        $rand = rand(11111, 99999);
        if ($npass == $cpass) {
            DB::update("update teachers set password ='$npass' where email = '$email_id_pass' AND forget_code='$forget_code_pass'");
            Cookie::queue('token', '', -1);
            DB::update("update teachers set forget_code ='$rand' where email = '$email_id_pass'");
            return response()->json([
                'status' => 500,
                'errors' => 'valid code',
            ]);
        } else {
            return response()->json([
                'status' => 600,
                'errors' => 'New password & Confirm password Does not match',
            ]);
        }
    }


    public function passwordview(request $request)
    {
        return view('admin.password');
    }


    public function passwordupdate(request $request)
    {
        $this->validate($request, [
            'oldpassword'  => 'required',
            'npass'  => 'required',
            'cpass'  => 'required',
        ]);
        $id = $request->header('id');
        $oldpassword = $request->input('oldpassword');
        $npass = $request->input('npass');
        $cpass = $request->input('cpass');

        $data = Teacher::where('password', $oldpassword)->where('id', $id)->count('email');
        if ($data >= 1) {
            if ($npass == $cpass) {
                $student = Teacher::find($id);
                //$student->password=Hash::make($npass);
                $student->password = $npass;
                $student->update();
                return redirect('/admin/password')->with('success', 'Passsword change  successfully');
            } else {
                return redirect('/admin/password')->with('fail', 'New Passsword & Confirm Passsword is not match');
            }
        } else {
            return redirect('/admin/password')->with('fail', 'Invalid Email');
        }
    }



    public function teacher_view(Request $request){
        try{ 
             
              return view('admin.teacher');
           }catch (Exception $e) { return  view('errors.error',['error'=>$e]);}
      }
 
      public function store(Request $request){

       $dept_id = $request->header('dept_id');
       $teacher_id = $request->header('id');
       $validator=\Validator::make($request->all(),[    
          'teacher_name'=>'required',
          'designation'=>'required',
          'phone'=>'required|unique:teachers,phone',
          'email'=>'required|unique:teachers,email',
          'password'=>'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
          'image'=>'image|mimes:jpeg,png,jpg|max:400',
        ],
        [
         'password.regex'=>'password minimum six characters including one uppercase letter, 
           one lowercase letter and one number'
        ]);
 
      if($validator->fails()){
             return response()->json([
               'status'=>700,
               'message'=>$validator->messages(),
            ]);
      }else{
 
             $model= new Teacher;
             $model->dept_id=$dept_id;
             $model->teacher_name=$request->input('teacher_name');
             $model->designation=$request->input('designation');
             $model->email=$request->input('email');
             $model->role='teacher';
             $model->created_by=$teacher_id;
             $model->phone=$request->input('phone');
             $model->password=$request->input('password');
             $model->nickname=$request->input('nickname');
             $model->present_address=$request->input('present_address');
             $model->permanent_address=$request->input('permanent_address');
             $model->bank_details=$request->input('bank_details');
             if ($request->hasfile('image')) {
               $imgfile = 'booking-';
               $size = $request->file('image')->getsize();
               $file = $_FILES['image']['tmp_name'];
               $hw = getimagesize($file);
               $w = $hw[0];
               $h = $hw[1];
               if ($w < 310 && $h < 310) {
                   $image = $request->file('image');
                   $new_name = $imgfile . rand() . '.' . $image->getClientOriginalExtension();
                   $image->move(public_path('uploads'), $new_name);
                   $model->image = $new_name;
                } else {
                   return response()->json([
                       'status' => 300,
                       'message' => 'Image size must be 300*300px',
                   ]);
                 }
             }
            
             $model->save();
 
             return response()->json([
                   'status'=>200,  
                   'message'=>'Data Added Successfull',
              ]);     
         }
     }
 
    public function teacher_edit(Request $request) {
      $id = $request->id;
      $data = Teacher::find($id);
      return response()->json([
          'status'=>200,  
          'data'=>$data,
       ]);
    }
 
 
    public function teacher_update(Request $request ){

     $validator=\Validator::make($request->all(),[    
        'teacher_name'=>'required',
        'designation'=>'required',
        'phone'=>'required|unique:teachers,phone',
        'email'=>'required|unique:teachers,email',
        'password'=>'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        'image'=>'image|mimes:jpeg,png,jpg|max:400',
        'phone'=>'required|unique:teachers,phone,'.$request->input('edit_id'),
        'email'=>'required|unique:teachers,email,'.$request->input('edit_id'),
     ]);
 
     $teacher_id = $request->header('id');
   if($validator->fails()){
          return response()->json([
            'status'=>700,
            'message'=>$validator->messages(),
         ]);
   }else{
         $model=Teacher::find($request->input('edit_id'));
     if($model){
         $model->teacher_name=$request->input('teacher_name');
         $model->designation=$request->input('designation');
         $model->email=$request->input('email');
         $model->phone=$request->input('phone');
         $model->password=$request->input('password');
         $model->nickname=$request->input('nickname');
         $model->present_address=$request->input('present_address');
         $model->permanent_address=$request->input('permanent_address');
         $model->bank_details=$request->input('bank_details');
         $model->teacher_status=$request->input('teacher_status');
         $model->updated_by=$teacher_id;

        if ($request->hasfile('image')) {
            $imgfile = 'booking-';
            $size = $request->file('image')->getsize();
            $file = $_FILES['image']['tmp_name'];
            $hw = getimagesize($file);
            $w = $hw[0];
            $h = $hw[1];
            if ($w < 310 && $h < 310) {
              $path = public_path('uploads') . '/' . $model->image;
               if(File::exists($path)){
                   File::delete($path);
                 }
                $image = $request->file('image');
                $new_name = $imgfile . rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $new_name);
                $model->image = $new_name;
            } else {
               return response()->json([
                   'status' =>300,
                   'message' =>'Image size must be 300*300px',
               ]);
             }
         }
        $model->update();   

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
 
 
   public function teacher_delete(Request $request) { 
 
       // $hallinfo=Building::where('id',$request->input('id'))->count('id');
       //  if($hallinfo>0){
       //     return response()->json([
       //       'status'=>200,  
       //       'message'=>'Can not delete this record. This hall is used in hall info table.',
       //      ]);
       //   }else{
           $model=Teacher::find($request->input('id'));
           $filePath = public_path('uploads') . '/' . $model->image;
           if(File::exists($filePath)){
                 File::delete($filePath);
            }
           $model->delete();
           return response()->json([
              'status'=>300,  
              'message'=>'Data Deleted Successfully',
         ]);
         
     // }
    } 
   
 
 
   public function fetch(Request $request){
       $dept_id = $request->header('dept_id');
       $data=Teacher::where('role','teacher')->where('dept_id',$dept_id)->orderBy('id','desc')->paginate(10);
       return view('admin.teacher_data',compact('data'));
    }
 
 
 
   function fetch_data(Request $request)
   {
    if($request->ajax())
    {
          $dept_id = $request->header('dept_id');
          $sort_by = $request->get('sortby');
          $sort_type = $request->get('sorttype'); 
             $search = $request->get('search');
             $search = str_replace("","%", $search);
          $data = Teacher::where('role','teacher')->where('dept_id',$dept_id)
              ->where(function($query) use ($search) {
                  $query->where('teacher_name', 'like', '%'.$search.'%')
                     ->orWhere('nickname', 'like', '%'.$search.'%')
                     ->orWhere('phone', 'like', '%'.$search.'%')
                     ->orWhere('designation', 'like', '%'.$search.'%')
                     ->orWhere('present_address', 'like', '%'.$search.'%')
                     ->orWhere('permanent_address', 'like', '%'.$search.'%')
                     ->orWhere('bank_details', 'like', '%'.$search.'%')
                     ->orWhere('nickname', 'like', '%'.$search.'%')
                     ->orWhere('email', 'like', '%'.$search.'%');
               })->orderBy($sort_by, $sort_type)->paginate(10);
                   return view('admin.teacher_data', compact('data'))->render();
                  
       }
   }



}

