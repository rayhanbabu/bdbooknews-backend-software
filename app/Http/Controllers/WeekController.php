<?php

namespace App\Http\Controllers;

use App\Models\Dept;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Teacher;




class WeekController extends Controller
{
    
      public function week_view() {

           $dept=Dept::get();     
           if(isset($_GET['dept_id'])){
               $dept_id=Dept::where('id',$_GET['dept_id'])->first();
           }else{
               $dept_id='';  
            }

          return view('maintain.week-view',['dept'=>$dept,'dept_id'=>$dept_id]);
       }


    public function store(Request $request){

         $validator=\Validator::make($request->all(),[    
            'week'=>'required',
            
          ],
           [
            'week.required'=>'week  is required',
           ]);

      if($validator->fails()){
             return response()->json([
               'status'=>700,
               'message'=>$validator->messages(),
            ]);
      }else{
                 
             $model= new Week;
             $model->week=$request->input('week');
             $model->category_name=$request->input('category_name');  
             $model->serial=$request->input('serial');
             $model->dept_id=$request->input('dept_id');
             if($request->hasfile('image')){
                $imgfile='maintain-';
                $size = $request->file('image')->getsize(); 
                $file=$_FILES['image']['tmp_name'];
                $hw=getimagesize($file);
                $w=$hw[0];
                $h=$hw[1];	 
                   if($size<512000){
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
                     }else{
                         return response()->json([
                         'status'=>400,  
                         'message'=>'Image Size geather than 500KB',
                       ]);
                     }
              }

             $model->save();
             return response()->json([
                 'status'=>100,  
                 'message'=>'Data Added Successfull',
            ]);
               
     }

    }



    public function fetchAll($dept_id) {
      
        $data= Week::where('dept_id',$dept_id)->get();
        $output = '';
        if ($data->count()> 0) {
          $output.=' <h5 class="text-success"> Total Row : '.$data->count().' </h5>';	
           $output .= '<table class="table table-bordered table-sm text-start align-middle">
           <thead>
              <tr>
                <th>Id </th>
                <th>Category Name </th>
                <th>Serial </th>
                <th>Image </th>
                <th>Name </th>
                <th>Action </th>
              </tr>
           </thead>
           <tbody>';
           foreach ($data as $row){
            if(!$row->image){$image="";}else{$image='<i class="fa fa-download"></i>';}
             $output .= '<tr>
                 <td>'.$row->id.'</td>
                 <td>'.$row->category_name.'</td>
                <td>'.$row->serial.'</td>
                <td> <a href=/uploads/'.$row->image.' download id="' . $row->id . '" class="text-success mx-1">'.$image.' </a></td>
                <td>'.$row->week.'</td>
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
        $data = Week::find($id);
        return response()->json([
          'status'=>100,  
          'data'=>$data,
        ]);
      }
    
   
      public function update(Request $request ){
        $validator=\Validator::make($request->all(),[    
            'week'=>'required',
          ],
          [
          'week.required'=>'week  is required',
          ]);

      if($validator->fails()){
             return response()->json([
               'status'=>700,
               'message'=>$validator->messages(),
            ]);
      }else{
        $model=Week::find($request->input('edit_id'));
        if($model){
            $model->week=$request->input('week');  
            $model->category_name=$request->input('category_name');  
            $model->serial=$request->input('serial');
          
            if($request->hasfile('image')){
              $imgfile='maintain-';
              $size = $request->file('image')->getsize(); 
              $file=$_FILES['image']['tmp_name'];
              $hw=getimagesize($file);
              $w=$hw[0];
              $h=$hw[1];	 
                  if($size<512000){
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
                    }else{
                        return response()->json([
                          'status'=>400,  
                          'message'=>'Image Size geather than 500KB',
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
 
 
      public function delete(Request $request) { 
             $model=Week::find($request->input('id'));
             $path=public_path('uploads/'.$model->image);
            //  if(File::exists($path)){
            //        File::delete($path);
            //   }
            //   $model->delete();
              return response()->json([
                 'status'=>200,  
                 'message'=>'Data Deleted Successfully',
             ]);
          
        }  



        



   
}