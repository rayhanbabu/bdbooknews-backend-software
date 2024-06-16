<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Week;

use Cookie;
use Session;
use DOMDocument;
use Illuminate\Support\Str;
use Spatie\Image\Image;

class NoticeController extends Controller
{
    public function index($category){
      $category_name=Week::where('category_name','Event')->where('id',$category)->orderby('serial','asc')->first();
        return view('admin.notice',['category' => $category,'category_name' => $category_name]);
    }

    public function fetch(Request $request ,$category){
       $dept_id = $request->header('dept_id');
       $teacher_id = $request->header('id');
        $data=DB::table('notices')->where('dept_id',$dept_id)->where('category',$category)->orderBy('id','desc')->paginate(10);
          return view('admin.notice_data',compact('data'));
       
     }

    function fetch_data(Request $request,$category)
    {
       $dept_id = $request->header('dept_id');
       $teacher_id = $request->header('id');

       if($request->ajax())
        {
          $sort_by = $request->get('sortby');
          $sort_type = $request->get('sorttype'); 
             $search = $request->get('search');
             $search = str_replace(" ", "%", $search);
         $data=DB::table('notices')->where('dept_id',$dept_id)
                 ->where('category',$category)
           ->where(function($query) use ($search) {
              $query->orWhere('title', 'like', '%'.$search.'%');
              $query->orWhere('text', 'like', '%'.$search.'%');
              $query->orWhere('category', 'like', '%'.$search.'%');
              $query->orWhere('date', 'like', '%'.$search.'%');
           })->orderBy($sort_by, $sort_type)->paginate(10);

          return view('admin.notice_data', compact('data'))->render();
        }
     }


    public function notice_create (Request $request,$category)
      {
        $category_name=Week::where('category_name','Event')->where('id',$category)->orderby('serial','asc')->first();
        return view('admin.notice_create',['category'=>$category,'category_name'=>$category_name]);
      }


    public function store(Request $request) {

       $dept_id = $request->header('dept_id');
       $teacher_id = $request->header('id');

       $validated = $request->validate([
            'desc'=>'required',
            'image' =>'file|mimes:jpeg,png,jpg,pdf|max:10240',
            'title'=>'required',
       ]);

        $model= new Notice;
        $model->date=$request->input('date');
        $model->category=$request->input('category');
        $model->dept_id=$dept_id;
        $model->title=$request->input('title');
        $model->text=$request->input('text');
        $model->desc=$request->input('desc');
        $model->link=$request->input('link');
        $model->serial=$request->input('serial');
        $model->short_desc=$request->input('short_desc');

       

       
        if($request->hasfile('image')){

          $image = $request->file('image');
          $mimeType = $image->getClientMimeType();
          if ($mimeType === 'application/pdf') {
             $filename = time() . '.' . $image->getClientOriginalExtension();
             $image->move(public_path('uploads/admin'), $filename);
             $model->image=$filename;
           }elseif (in_array($mimeType, ['image/jpeg','image/jpg','image/png'])) {
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
              $model->image=$filename;
           }

         }


        $model->save();

        return redirect()->back()->with('success','Data Added Successfuly');
       
     }


     public function view(Request $request ,$id,$category)
      {
         $data = Notice::find($id);
         $category_name=Week::where('category_name','Event')->where('id',$category)->orderby('serial','asc')->first();
         return view('admin.notice_view',['data'=>$data,'category'=>$category,'category_name'=>$category_name]);
      }


     public function edit(Request $request ,$id,$category)
     {
         $data = Notice::find($id);
         $category_name=Week::where('category_name','Event')->where('id',$category)->orderby('serial','asc')->first();
         return view('admin.notice_edit',['data'=>$data,'category'=>$category,'category_name'=>$category_name]);
     }

     public function update(Request $request, $id)
     {

        $validated = $request->validate([
            'date'=>'required',
            'image' =>'image|mimes:jpeg,png,jpg|max:10240',
            'title'=>'required',
        ]);

        $model = Notice::find($id);
        $model->date=$request->input('date');
        $model->category=$request->input('category');
        $model->title=$request->input('title');
        $model->desc=$request->input('desc');
        $model->link=$request->input('link');
        $model->serial=$request->input('serial');
        $model->short_desc=$request->input('short_desc');

        if($request->hasfile('image')){
            $path=public_path('uploads/admin/').$model->image;
            if(File::exists($path)){
             File::delete($path);
             }

           $image = $request->file('image');
          $mimeType = $image->getClientMimeType();
          if ($mimeType === 'application/pdf') {
             $filename = time() . '.' . $image->getClientOriginalExtension();
             $image->move(public_path('uploads/admin'), $filename);
             $model->image=$filename;
           }elseif (in_array($mimeType, ['image/jpeg','image/jpg','image/png'])) {
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
              $model->image=$filename;
           }
         }
        $model->save();

        return redirect()->back()->with('success','Data Updated Successfuly');
   }


   public function destroy(Request $request,$id,$category)
   {
       $post = Notice::find($id);  
       $path=public_path('uploads/admin/').$post->image;
        if(File::exists($path)){
          File::delete($path);
        }
       $post->delete();
       return redirect('/admin/notice/'.$category)->with('success','Data Deleted  successfully');

   }


}