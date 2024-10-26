<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\News;
use App\Models\Subcategory;

use Cookie;
use Session;
use DOMDocument;
use Illuminate\Support\Str;
use Spatie\Image\Image;

class NewsController extends Controller
{

     public function index(Request $request){
         $dept_id = $request->header('dept_id');
         $category=Category::where('dept_id',$dept_id)->orderby('serial','asc')->orderBy('id','desc')->get();
         return view('admin.news',['category' => $category]);
      }

     public function fetch(Request $request ){
         $dept_id = $request->header('dept_id');
         $teacher_id = $request->header('id');
         $data=DB::table('news')->leftjoin('categories','categories.category_name', '=','news.category_name_id')
            ->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id')
            ->leftjoin('divisions','divisions.id', '=','news.division_id')
            ->leftjoin('districts','districts.id', '=','news.district_id')
            ->leftjoin('upazilas','upazilas.id', '=','news.upazila_id')
         ->where('news.dept_id',$dept_id)
         ->select('categories.category_name','subcategories.subcategory_name','divisions.bn_name as division'
         ,'districts.bn_name as district','upazilas.bn_name as upazila','news.*')->orderBy('id','desc')->paginate(10);
         return view('admin.news_data',compact('data'));
      }

    function fetch_data(Request $request)
      {
       $dept_id = $request->header('dept_id');
       $teacher_id = $request->header('id');
       if($request->ajax())
        {
          $sort_by = $request->get('sortby');
          $sort_type = $request->get('sorttype'); 
             $search = $request->get('search');
             $range = $request->get('range');
             $search = str_replace(" ", "%", $search);
             $data=DB::table('news')->leftjoin('categories','categories.category_name', '=','news.category_name_id')
            ->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id')
            ->leftjoin('divisions','divisions.id', '=','news.division_id')
            ->leftjoin('districts','districts.id', '=','news.district_id')
            ->leftjoin('upazilas','upazilas.id', '=','news.upazila_id')
            ->where('news.dept_id',$dept_id)
           ->where(function($query) use ($search) {
              $query->orWhere('title', 'like', '%'.$search.'%');
              $query->orWhere('categories.category_name', 'like', '%'.$search.'%');
              $query->orWhere('subcategories.subcategory_name', 'like', '%'.$search.'%');
              $query->orWhere('news.created_at', 'like', '%'.$search.'%');
              $query->orWhere('districts.id', 'like', '%'.$search.'%');
              
           })->select('categories.category_name','subcategories.subcategory_name','divisions.bn_name as division'
           ,'districts.bn_name as district','upazilas.bn_name as upazila','news.*')->orderBy($sort_by,$sort_type)->paginate($range);

          return view('admin.news_data',compact('data'))->render();
        }
     }


      public function news_create (Request $request)
       {
           $dept_id = $request->header('dept_id');
           return view('admin.news_create');
       }


    public function store(Request $request) {

        $dept_id = $request->header('dept_id');
        $teacher_id = $request->header('id');

        $validated = $request->validate([
             'desc'=>'required',
             'image' =>'file|mimes:jpeg,png,jpg|max:10240',
             'title'=>'required',
             'category_name_id'=>'required',
             'image1' =>'file|mimes:jpeg,png,jpg,pdf|max:10240',
         ]);

      

        $model= new News();
        $model->category_name_id=$request->input('category_name_id');
        $model->dept_id=$dept_id;
        $model->subcategory_name_id=$request->input('subcategory_name_id');
        $model->title=$request->input('title');
        $model->desc=$request->input('desc');
        $model->title_color=$request->input('title_color');

        $model->division_id=$request->input('division_id');
        $model->district_id=$request->input('district_id');
        $model->upazila_id=$request->input('upazila_id');

        $model->highlight_serial=$request->input('highlight_serial');
      
        $model->desc1=$request->input('desc1');

        $model->image_title=$request->input('image_title');
        $model->image_title1=$request->input('image_title1');
      
        if($request->hasfile('image')){
          $image = $request->file('image');
          $mimeType = $image->getClientMimeType();
              $filename = time() .'.'. $image->getClientOriginalExtension();
              $filePath = public_path('uploads/admin/') . $filename;
              $image = Image::load($image->getPathname())
              ->width(600)
              ->save($filePath);
              $model->image=$filename;
         }


         if($request->hasfile('image1')){
            $image = $request->file('image1');
            $mimeType = $image->getClientMimeType();
            if ($mimeType === 'application/pdf') {
                $filename = time() .'image1.'.$image->getClientOriginalExtension();
                $image->move(public_path('uploads/admin'), $filename);
                $model->image1=$filename;
             }elseif (in_array($mimeType, ['image/jpeg','image/jpg','image/png'])) {
                $filename = time() .'image1.'.$image->getClientOriginalExtension();
                $filePath = public_path('uploads/admin/') . $filename;
                $image = Image::load($image->getPathname())
                ->width(840)
                ->save($filePath);
                $model->image1=$filename;
             }
           }
        $model->save();

        return redirect('admin/news_view')->with('success','Data Added Successfuly');
       
     }


     public function view(Request $request ,$id)
      {  
         $data=DB::table('news')->leftjoin('categories','categories.category_name', '=','news.category_name_id')
            ->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id')
            ->leftjoin('divisions','divisions.id', '=','news.division_id')
            ->leftjoin('districts','districts.id', '=','news.district_id')
            ->leftjoin('upazilas','upazilas.id', '=','news.upazila_id')
            ->where('news.id',$id)
           ->select('categories.category_name','subcategories.subcategory_name','divisions.bn_name as division'
          ,'districts.bn_name as district','upazilas.bn_name as upazila','news.*')->orderBy('id','desc')->first();
          return view('admin.news_view',['data'=>$data]);
      }


     public function edit(Request $request ,$id)
      {
         $data = News::find($id);
         return view('admin.news_edit',['id'=>$id,'data'=>$data]);
       }


       public function edit_fatch(Request $request) {
         $id = $request->id;
         $data = News::find($id);
         return response()->json([
             'status'=>200,  
             'data'=>$data,
          ]);
       }

     public function update(Request $request) {

      $validator=\Validator::make($request->all(),[    
         'desc'=>'required',
         'image' =>'file|mimes:jpeg,png,jpg|max:10240',
         'title'=>'required',
         'category_name_id'=>'required',
         'image1' =>'file|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

    if($validator->fails()){
           return response()->json([
              'status' => 'fail',
              'message' => 'All fields are required',
             ]);
     }else{

        $model = News::find($request->input('edit_id'));
        $model->category_name_id=$request->input('category_name_id');
        $model->subcategory_name_id=$request->input('subcategory_name_id');
        $model->title=$request->input('title');
        $model->title_color=$request->input('title_color');
        $model->desc=$request->input('desc');

        $model->division_id=$request->input('division_id');
        $model->district_id=$request->input('district_id');
        $model->upazila_id=$request->input('upazila_id');

        $model->highlight_serial=$request->input('highlight_serial');
        $model->geater_serial=$request->input('geater_serial');
        $model->desc1=$request->input('desc1');

        $model->image_title=$request->input('image_title');
        $model->image_title1=$request->input('image_title1');
      
        if($request->hasfile('image')){
          $image = $request->file('image');
          $mimeType = $image->getClientMimeType();
              $filename = time() .'.'. $image->getClientOriginalExtension();
              $filePath = public_path('uploads/admin/') . $filename;
              $image = Image::load($image->getPathname())
              ->width(840)
              ->save($filePath);
              $model->image=$filename;
         }


         if($request->hasfile('image1')){
            $image = $request->file('image1');
            $mimeType = $image->getClientMimeType();
            if ($mimeType === 'application/pdf') {
                $filename = time() .'image1.'.$image->getClientOriginalExtension();
                $image->move(public_path('uploads/admin'), $filename);
                $model->image1=$filename;
             }elseif (in_array($mimeType, ['image/jpeg','image/jpg','image/png'])) {
                $filename = time() .'image1.'.$image->getClientOriginalExtension();
                $filePath = public_path('uploads/admin/') . $filename;
                $image = Image::load($image->getPathname())
                ->width(840)
                ->save($filePath);
                $model->image1=$filename;
             }
           }
        $model->save();

          return response()->json([
            'status' => 'success',
            'message' => 'Data Updated successfully',
           ]);

      }
   }


   public function destroy(Request $request,$id)
     {
       $post = News::find($id);  
       $path=public_path('uploads/admin/').$post->image;
        if(File::exists($path)){
          File::delete($path);
        }

        $path1=public_path('uploads/admin/').$post->image1;
        if(File::exists($path1)){
          File::delete($path1);
        }

       $post->delete();
       return redirect('/admin/news_view/')->with('success','Data Deleted  successfully');
    }


    public function category_id_fetch(Request $request){
      $dept_id = $request->header('dept_id');
      $data =Subcategory::where('dept_id',$dept_id)->where('category_name_id',$request->category_id)->where('subcategory_status',1)->orderBy('id','desc')->get();
       if(count($data) > 0) {
          return response()->json($data);
       }
    }

     public function district_id_fetch(Request $request){
        $data=DB::table('districts')->where('division_id',$request->division_id)->orderBy('bn_name','asc')->get();
        if(count($data) > 0) {
          return response()->json($data);
        }
     }
     
     public function upazila_id_fetch(Request $request){
        $data=DB::table('upazilas')->where('district_id',$request->district_id)->orderBy('bn_name','asc')->get();
        if(count($data) > 0) {
          return response()->json($data);
        }
      }



}