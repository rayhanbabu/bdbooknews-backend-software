<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
   
      public function category_view(Request $request){
          try{  
               return view('admin.category');
           }catch (Exception $e) { return  view('errors.error',['error'=>$e]);}
       }
 
      public function store(Request $request){
         $dept_id = $request->header('dept_id');
         $teacher_id = $request->header('id');
         $validator=\Validator::make($request->all(),[    
            'category_name'=>'required|regex:/^\S*$/|unique:categories',
            'image'=>'image|mimes:jpeg,png,jpg|max:400',
            'menu_ber'=>'required',
            'name'=>'required',
           ],
         );
 
       if($validator->fails()){
             return response()->json([
               'status'=>700,
               'message'=>$validator->messages(),
            ]);
        }else{
             $model= new Category;
             $model->dept_id=$dept_id;
             $model->category_name=$request->input('category_name');
             $model->name=$request->input('name');
             $model->menu_ber=$request->input('menu_ber');
             $model->created_by=$teacher_id;
             if($request->hasfile('image')){
                   $imgfile = 'booking-';
                   $image = $request->file('image');
                   $new_name = $imgfile.rand().'.'.$image->getClientOriginalExtension();
                   $image->move(public_path('uploads'),$new_name);
                   $model->image = $new_name;
               }
             $model->save();
 
             return response()->json([
                   'status'=>200,  
                   'message'=>'Data Added Successfull',
              ]);     
         }
     }
 
    public function category_edit(Request $request) {
      $id = $request->id;
      $data = category::find($id);
      return response()->json([
          'status'=>200,  
          'data'=>$data,
       ]);
    }
 
 
    public function category_update(Request $request ){
         $validator=\Validator::make($request->all(),[    
           'category_name'=>'required|regex:/^\S*$/|unique:categories,category_name,'.$request->input('edit_id'),
           'image'=>'image|mimes:jpeg,png,jpg|max:400',
           'name'=>'required',
         ]);
 
      $teacher_id = $request->header('id');
      if($validator->fails()){
          return response()->json([
            'status'=>700,
            'message'=>$validator->messages(),
         ]);
      }else{
         $model=category::find($request->input('edit_id'));

         if($model){
           $model->category_name=$request->input('category_name');
           $model->category_status=$request->input('category_status');
           $model->menu_ber=$request->input('menu_ber');
           $model->name=$request->input('name');
           $model->serial=$request->input('serial');
           $model->updated_by=$teacher_id;

         if($request->hasfile('image')) {
               $imgfile = 'booking-';
               $path = public_path('uploads').'/'.$model->image;
               if(File::exists($path)){
                   File::delete($path);
                 }
                $image = $request->file('image');
                $new_name = $imgfile . rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $new_name);
                $model->image = $new_name;  
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
 
 
   public function category_delete(Request $request) { 
 
       
           $model=category::find($request->input('id'));
           $filePath = public_path('uploads') . '/' . $model->image;
           if(File::exists($filePath)){
                 File::delete($filePath);
            }
           $model->delete();
           return response()->json([
              'status'=>300,  
              'message'=>'Data Deleted Successfully',
         ]);
         
    } 
   
 
 
   public function fetch(Request $request){
       $dept_id = $request->header('dept_id');
       $data=category::where('dept_id',$dept_id)->orderBy('id','desc')->paginate(10);
       return view('admin.category_data',compact('data'));
    }
 
 
 
   function fetch_data(Request $request)
   {
    if($request->ajax())
      {
           $dept_id = $request->header('dept_id');
           $sort_by = $request->get('sortby');
           $sort_type = $request->get('sorttype'); 
             $search = $request->get('search');
             $range = $request->get('range');
             $search = str_replace("","%", $search); 
             $data = Category::where('dept_id',$dept_id)
               ->where(function($query) use ($search) {
                  $query->where('category_name', 'like', '%'.$search.'%')
                     ->orWhere('menu_ber', 'like', '%'.$search.'%')
                     ->orWhere('category_status', 'like', '%'.$search.'%');
               })->orderBy($sort_by, $sort_type)->paginate($range);
                   return view('admin.category_data', compact('data'))->render();             
          }
       }

  }
