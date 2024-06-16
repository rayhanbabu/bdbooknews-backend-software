<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class SubcategoryController extends Controller
{
    public function subcategory_view(Request $request){
        try{  
           $dept_id = $request->header('dept_id');
           $category=Category::where('dept_id',$dept_id)->orderBy('category_name','asc')->get();
           return view('admin.subcategory',['category'=>$category]);
        }catch (Exception $e) { return  view('errors.error',['error'=>$e]);}
     }
 
      public function store(Request $request){
         $dept_id = $request->header('dept_id');
         $teacher_id = $request->header('id');
         $validator=\Validator::make($request->all(),[    
            'subcategory_name'=>'required|regex:/^\S*$/|unique:subcategories',
            'image'=>'image|mimes:jpeg,png,jpg|max:400',
            'category_name_id'=>'required',
            'sub_name'=>'required',
           ],
         );
 
       if($validator->fails()){
             return response()->json([
               'status'=>700,
               'message'=>$validator->messages(),
            ]);
        }else{
             $model= new Subcategory;
             $model->dept_id=$dept_id;
             $model->subcategory_name=$request->input('subcategory_name');
             $model->category_name_id=$request->input('category_name_id');
             $model->sub_name=$request->input('sub_name');
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
 
    public function subcategory_edit(Request $request) {
      $id = $request->id;
      $data = subcategory::find($id);
      return response()->json([
          'status'=>200,  
          'data'=>$data,
       ]);
    }
 
 
    public function subcategory_update(Request $request ){
         $validator=\Validator::make($request->all(),[    
           'subcategory_name'=>'required|regex:/^\S*$/|unique:subcategories,subcategory_name,'.$request->input('edit_id'),
           'image'=>'image|mimes:jpeg,png,jpg|max:400',
           'category_name_id'=>'required',
           'sub_name'=>'required',  
        ]);
 
      $teacher_id = $request->header('id');
      if($validator->fails()){
          return response()->json([
            'status'=>700,
            'message'=>$validator->messages(),
         ]);
      }else{
         $model=Subcategory::find($request->input('edit_id'));

         if($model){
           $model->subcategory_name=$request->input('subcategory_name');
           $model->subcategory_status=$request->input('subcategory_status');
           $model->category_name_id=$request->input('category_name_id');
           $model->serial=$request->input('serial');
           $model->updated_by=$teacher_id;
           $model->sub_name=$request->input('sub_name');

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
 
 
   public function subcategory_delete(Request $request) { 
 
       
           $model=subcategory::find($request->input('id'));
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
       $data=Subcategory::leftjoin('categories','categories.category_name', '=','subcategories.category_name_id')
        ->where('subcategories.dept_id',$dept_id)
       ->select('categories.category_name','subcategories.*')->orderBy('id','desc')->paginate(10);
       return view('admin.subcategory_data',compact('data'));
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
          $data=Subcategory::leftjoin('categories','categories.category_name', '=','subcategories.category_name_id')
          ->where('subcategories.dept_id',$dept_id)
           ->select('categories.category_name','subcategories.*')
               ->where(function($query) use ($search) {
                  $query->where('subcategory_name', 'like', '%'.$search.'%')
                     ->orWhere('category_name', 'like', '%'.$search.'%')
                     ->orWhere('subcategory_status', 'like', '%'.$search.'%');
               })->orderBy($sort_by, $sort_type)->paginate($range);
                   return view('admin.subcategory_data', compact('data'))->render();             
          }
       }



  }
