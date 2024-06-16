<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use App\Models\Maintain;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\MaintainJWTToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Exports\MemberExport;
use App\Imports\MemberImport;
use App\Models\Univer;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;




class MaintainController extends Controller
{
     public function login(Request $request)
     {
        try{
            return view('maintain.login'); 
          }catch (Exception $e) { return  view('errors.error',['error'=>$e]);}
     }

     public function dashboard(Request $request)
     {
        try {
         
             return view('maintain.dashboard'); 
        }catch (Exception $e) { return  view('errors.error',['error'=>$e]);}
     }


    public function login_insert(Request $request){

        $validator=\Validator::make($request->all(),[    
            'phone'=>'required',
            'password'=>'required',
          ],
           [
            'phone.required'=>'Phone  is required',
            'password.required'=>'Password is required',
          ]);

          if($validator->fails()){
               return response()->json([
                 'status'=>700,
                 'message'=>$validator->messages(),
              ]); 
         }else{
          $status=1;
          $username=Maintain::where('phone',$request->phone)->first();
          if($username){
                   if($username->password==$request->password){
                      if($username->status==$status){
                            $rand=rand(11111,99999);
                            DB::update("update maintains set login_code ='$rand' where phone = '$username->phone'");
                            SendEmail($username->email,"Maintain Otp code","One Time OTP Code",$rand,"ANCOVA");  
                            return response()->json([
                                 'status'=>200,
                                 'phone'=>$username->phone,
                                 'email'=>$username->email,
                             ]);               
                       }else{
                          return response()->json([
                             'status'=>600,
                             'message'=> 'Acount Inactive',
                          ]); 
                       }    
                   }else{
                     return response()->json([
                        'status'=>400,
                        'message'=> 'Invalid Password',
                     ]); 
                   }
          }else{
               return response()->json([
                   'status'=>300,
                   'message'=> 'Invalid Phone Number',
               ]); 
           }
      }
        //Email($maintain->email,"Maintain Otp code","One Time OTP Code",$otp,"Dining Name");  
          
    }


    public function login_verify(Request $request){
        $validator=\Validator::make($request->all(),[    
            'otp'=>'required|numeric',
          ],
           [
            'otp.required'=>'OTP is required',
          ]);

          if($validator->fails()){
               return response()->json([
                 'status'=>700,
                 'message'=>$validator->messages(),
              ]);
       }else{
          $username=Maintain::where('phone',$request->verify_phone)->where('email',$request->verify_email)
          ->where('login_code',$request->otp)->first();
          if($username){
               DB::update("update maintains set login_code ='null' where phone = '$username->phone'");
                $token_maintain=MaintainJWTToken::CreateToken($username->maintain_username,$username->email,
                $username->id,$username->role, $username->university_assign);
                 Cookie::queue('token_maintain',$token_maintain, 60 * 96);
               return response()->json([
                   'status'=>200,
                   'message'=> 'success',
               ]);   
          }else{
              return response()->json([
                  'status'=>300,
                  'message'=> "Invalid OTP",
               ]); 
           }
      }
          
    }


       public function logout(){
             Cookie::queue('token_maintain','',-1);
             return redirect('maintain/login');
       }


       public function forget(Request $request)
         {
            try {
               return view('maintain.forget'); 
             }catch (Exception $e) { return  view('errors.error',["error"=>$e]);}
         }


        public function forgetemail(request $request){   
            $email=$request->input('email');
            $rand=rand(11111,99999);
            $email_exist=Maintain::where('email',$email)->first();
           if($email_exist){
            DB::update("update maintains set forget_code ='$rand' where email = '$email'");
            SendEmail($email_exist->email,"Password Recovary code","One Time  Code",$rand,"Dining Name");  
               return response()->json([
                  'status'=>500,
                  'errors'=> 'Email exist',
               ]); 
            }else{
                return response()->json([
                  'status'=>600,
                  'errors'=> 'Invalid  Email ',
               ]); 
            }   
      }



      public function forgetcode(request $request){
        
        $email_id=$request->input('email_id');
        $forget_code=$request->input('forget_code');
        $code_exist=Maintain::where('email',$email_id)->where('forget_code',$forget_code)->count('email');
        if($code_exist>=1){ 
             return response()->json([
                'status'=>500,
                'errors'=> 'valid code',
             ]); 
        }else{
            return response()->json([
              'status'=>600,
              'errors'=> 'Invalid  Code ',
           ]); 
        }   
   }


   public function confirmpass(request $request){
    
    $email_id_pass=$request->input('email_id_pass');
    $forget_code_pass=$request->input('forget_code_pass');
    $npass=$request->input('npass');
    $cpass=$request->input('cpass');
    //$password=Hash::make($npass);
     $rand=rand(11111,99999);
     if($npass == $cpass){
           DB::update("update maintains set password ='$npass' where email = '$email_id_pass' AND forget_code='$forget_code_pass'");
           Cookie::queue('token','',-1);
           DB::update("update maintains set forget_code ='$rand' where email = '$email_id_pass'");
           return response()->json([
                 'status'=>500,
                 'errors'=> 'valid code',
            ]); 
      }else{
           return response()->json([
                'status'=>600,
                'errors'=> 'New password & Confirm password Does not match',
          ]); 
      }   
  }


  public function passwordview(request $request){
     
      return view('maintain.password');
 }


   public function passwordupdate(request $request){

     $this->validate($request, [
        'oldpassword'  => 'required',
        'npass'  => 'required',
        'cpass'  => 'required',
      ]);
      $id=$request->header('maintainID');
      $oldpassword=$request->input('oldpassword');
      $npass=$request->input('npass');
      $cpass=$request->input('cpass');

      $data= Maintain::where('password',$oldpassword)->where('id',$id)->count('email');
      if($data>=1){
          if($npass==$cpass){
           $student= Maintain::find($id);
          //$student->password=Hash::make($npass);
           $student->password=$npass;
           $student->update();
               return redirect('/maintain/password')->with('success','Passsword change  successfully');
           }else{
               return redirect('/maintain/password')->with('fail','New Passsword & Confirm Passsword is not match');
           }  
        }else{
              return redirect('/maintain/password')->with('fail','Invalid Email');
        }  
     }



     public function maintainview() {
      $data=Univer::orderBy('id','asc')->get();
      return view('maintain.maintainview',["data"=>$data]);
          
       }


public function store(Request $request){
     $validator=\Validator::make($request->all(),[    
         'name'=>'required',
         'phone'=>'required|unique:maintains,phone',
         'email'=>'required|unique:maintains,email',
         'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
         'image' => 'image|mimes:jpeg,png,jpg|max:400',
        ],
         [
          'password.regex'=>'password minimum six characters including one uppercase letter, 
           one lowercase letter and one number '
        ]);
    if($validator->fails()){
          return response()->json([
            'status'=>700,
            'message'=>$validator->messages(),
         ]);
    }else{           
         $model= new Maintain;
         $model->role='maintain';
         $model->status=1;
         $model->password=$request->input('password');
         $model->name=$request->input('name');
         $model->university_assign=$request->input('university_assign');
         $model->maintain_username=Str::slug(substr($request->input('name'),0,8),'_');
         $model->email=$request->input('email');
         $model->phone=$request->input('phone');
         if($request->hasfile('image')){
            $imgfile='maintain-';
            $size = $request->file('image')->getsize(); 
            $file=$_FILES['image']['tmp_name'];
            $hw=getimagesize($file);
            $w=$hw[0];
            $h=$hw[1];	 
                if($w<310 && $h<310){
                 $image= $request->file('image'); 
                 $new_name = $imgfile.rand() . '.' . $image->getClientOriginalExtension();
                 $image->move(public_path('uploads'), $new_name);
                 $model->image=$new_name;
                    }else{
                      return response()->json([
                         'status'=>300,  
                         'message'=>'Image size must be 300*300px',
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



public function fetchAll() {
  
   // $data= maintain::where('role','maintain')
   $data= Maintain::leftjoin('univers','univers.id', '=','maintains.university_assign')
   ->where('maintains.role','maintain')->select('univers.university','maintains.*')->get();


    $output = '';
    if ($data->count()> 0) {
      $output.=' <h5 class="text-success"> Total Row : '.$data->count().' </h5>';	
       $output .= '<table class="table table-bordered table-sm text-start align-middle">
       <thead>
          <tr>
            <th>Image </th>
            <th>Name </th>
            <th>Phone </th>
            <th>Email </th>
            <th>Passsword </th>
            <th>Status </th>
            <th>Unvisersity Assign </th>
            <th>Action </th>
          </tr>
       </thead>
       <tbody>';
       foreach ($data as $row){
        if(!$row->image){$image="";}else{$image='<i class="fa fa-download"></i>';}
        if($row->status==1){
         $status='<a href="#"class="btn btn-success btn-sm">Active</a>';
        }else{  $status='<a href="#"class="btn btn-danger btn-sm">Inactive</a>';}
        
         $output .= '<tr>
            <td> <a href=/uploads/'.$row->image.' download id="' . $row->id . '" class="text-success mx-1">'.$image.' </a></td>
            <td>'.$row->name.'</td>
            <td>'.$row->phone.'</td>
            <td>'.$row->email.'</td>
            <td>'.$row->password.'</td>
            <td>'.$status.'</td>
            <td>'.$row->university.'</td>
             <td>
                <a href="#" id="' . $row->id . '"class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square h4"></i>Edit</a>
                <a href="#" id="' .$row->id . '"class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i>Delete</a>
             </td>
        </tr>';
     }
       $output .= '</tbody></table>';
       echo $output;
    } else {
       echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
    }
}  


  public function edit(Request $request) {
     $id = $request->id;
     $data = Maintain::find($id);
      return response()->json([
        'status'=>200,  
        'data'=>$data,
       ]);
   }


  public function update(Request $request ){
     $validator=\Validator::make($request->all(),[    
      'name'=>'required',
      'phone'=>'required|unique:maintains,phone,'.$request->input('edit_id'),
      'email'=>'required|unique:maintains,email,'.$request->input('edit_id'),
      'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
      'image' => 'image|mimes:jpeg,png,jpg|max:400',
     ],
      [
       'password.regex'=>'password minimum six characters including one uppercase letter, 
        one lowercase letter and one number '
     ]);

  if($validator->fails()){
         return response()->json([
           'status'=>700,
           'message'=>$validator->messages(),
        ]);
  }else{
    $model=Maintain::find($request->input('edit_id'));
    if($model){
      $model->password=$request->input('password');
      $model->name=$request->input('name');
      $model->university_assign=$request->input('university_assign');
      $model->maintain_username=Str::slug(substr($request->input('name'),0,8),'_');
      $model->email=$request->input('email');
      $model->phone=$request->input('phone');
      $model->status=$request->input('status');
        if($request->hasfile('image')){
          $imgfile='maintain-';
          $size = $request->file('image')->getsize(); 
          $file=$_FILES['image']['tmp_name'];
          $hw=getimagesize($file);
          $w=$hw[0];
          $h=$hw[1];	 
               if($w<310 && $h<310){
                 $path=public_path('uploads/'.$model->image);
                  if(File::exists($path)){
                      File::delete($path);
                   }
                $image = $request->file('image');
                $new_name = $imgfile.rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $new_name);
                $model->image=$new_name;
               }else{
                 return response()->json([
                     'status'=>300,  
                    'message'=>'Image size must be 300*300px',
                  ]);
                 }
             }
        
          $model->update();   
            return response()->json([
               'status'=>200,
               'message'=>'Data Updated Successfull'
            ]);
      
         } 
     }
  }


      public function delete(Request $request) { 
         $model=Maintain::find($request->input('id'));
         $path=public_path('uploads/'.$model->image);
         if(File::exists($path)){
               File::delete($path);
          }
          $model->delete();
          return response()->json([
             'status'=>200,  
             'message'=>'Data Deleted Successfully',
         ]);
      
    }  

   public function member_export(Request $request){
        $hall_id=$request->input('hall_id');
        $codema=$hall_id."-Member List";
        return Excel::download(new MemberExport($hall_id), $codema.'.csv');
    }

    public function member_import(){
      Excel::Import(new MemberImport,request()->file('file'));
      //return redirect()->back();
      return back()->with('success','Data Imported Successfully');
   }


     public function resize(Request $request){   
         return view('maintain.resize');
     }

     public function resize_upload(Request $request){   
      $request->validate([
         'image' => 'required|image|mimes:jpeg,png,jpg,gif',
     ]);

     $image = $request->file('image');
     $filename = time() . '.' . $image->getClientOriginalExtension();
     $filePath = public_path('uploads/admin/') . $filename;

     $size = getimagesize($_FILES['image']['tmp_name']);
     $resize=image_resize($size);

     $width=$resize['width'];
     $height=$resize['height'];

    

     $image = Image::load($image->getPathname())
         ->width($width)
         ->height($height)
         ->save($filePath);
      return response()->json(['success' => 'Image uploaded successfully','filename'=> $filename]);
    }



   
       }

       
      
   
