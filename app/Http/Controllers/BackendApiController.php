<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\Dept;
use App\Models\Collor;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Notice;
use App\Models\Member;
use App\Models\News;
use App\Models\Subcategory;

class BackendApiController extends Controller
{

    


    public function department_view(Request $request ,$dept_id){
         $data= Dept::where('id',$dept_id)->first();
         return response()->json([
              'status'=>'success',
              'data'=>$data 
          ],200);
      }

      public function collor_view(Request $request ,$dept_id){
        $data= Collor::where('dept_id',$dept_id)->get();
        return response()->json([
             'status'=>'success',
             'data'=>$data 
         ],200);
      }


      public function category_view(Request $request,$dept_id){
          $data= Category::where('dept_id',$dept_id)->orderby('serial','asc')->orderby('id','desc')->get();
          return response()->json([
               'status'=>'success',
               'data'=>$data 
          ],200);
       }


      public function category_nav(Request $request,$dept_id){
         $data= Category::where('dept_id',$dept_id)->where('menu_ber','Nav')->orderby('serial','asc')->orderby('id','desc')->get();
         return response()->json([
               'status'=>'success',
               'data'=>$data 
           ],200);
       }

       public function category_side(Request $request,$dept_id){
        $data= Category::where('dept_id',$dept_id)->where('menu_ber','Side')->orderby('serial','asc')->orderby('id','desc')->get();
          return response()->json([
              'status'=>'success',
              'data'=>$data 
           ],200);
        }

        public function sub_category(Request $request,$dept_id,$category){
         $data= Subcategory::where('dept_id',$dept_id)->where('category_name_id',$category)->orderby('serial','asc')->orderby('id','desc')->get();
           return response()->json([
               'status'=>'success',
               'data'=>$data 
            ],200);
         }
 


      public function news_category(Request $request ,$dept_id,$category){
         $data= News::leftjoin('categories','categories.category_name', '=','news.category_name_id')
         ->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id')
         ->where('news.dept_id',$dept_id)->where('news.category_name_id',$category)
         ->select('categories.name','subcategories.sub_name','news.*')->orderby('serial','asc')->orderby('id','desc')->get();
           return response()->json([
                'status'=>'success',
                'data'=>$data 
            ],200);
       }


       public function news_subcategory(Request $request ,$dept_id,$category,$subcategory){
            $data= News::leftjoin('categories','categories.category_name','=','news.category_name_id')
            ->leftjoin('subcategories','subcategories.subcategory_name','=','news.subcategory_name_id')
            ->where('news.dept_id',$dept_id)->where('news.category_name_id',$category)
            ->where('news.subcategory_name_id',$subcategory)
            ->select('categories.name','subcategories.sub_name','news.*')->orderby('serial','asc')->orderby('id','desc')->get();
               return response()->json([
                  'status'=>'success',
                  'data'=>$data 
               ],200);
         }


        public function news_details_category(Request $request ,$dept_id,$category,$id){
           $data= News::leftjoin('categories','categories.category_name','=','news.category_name_id')
             ->leftjoin('subcategories','subcategories.subcategory_name','=','news.subcategory_name_id')
             ->where('news.dept_id',$dept_id)->where('news.category_name_id',$category)
             ->where('news.id',$id)
             ->select('categories.name','subcategories.sub_name','news.*')->orderby('serial','asc')->orderby('id','desc')->first();
                 return response()->json([
                    'status'=>'success',
                    'data'=>$data 
                 ],200);
        }


        public function news_highlight(Request $request ,$dept_id){
          $data= News::leftjoin('categories','categories.category_name','=','news.category_name_id')
            ->leftjoin('subcategories','subcategories.subcategory_name','=','news.subcategory_name_id')
            ->where('news.dept_id',$dept_id)
            ->where('news.highlight_serial','>',0)
            ->select('categories.name','subcategories.sub_name','news.*')->orderby('highlight_serial','asc')->orderby('id','desc')->get();
                return response()->json([
                   'status'=>'success',
                   'data'=>$data 
                ],200);
         }


         public function latest_news(Request $request ,$dept_id){
              $data= News::leftjoin('categories','categories.category_name','=','news.category_name_id')
              ->leftjoin('subcategories','subcategories.subcategory_name','=','news.subcategory_name_id')
              ->where('news.dept_id',$dept_id)
              ->select('categories.name','subcategories.sub_name','news.*')->orderby('id','desc')->get();
                 return response()->json([
                    'status'=>'success',
                    'data'=>$data 
                  ],200);
            }


  
     public function contact_form(Request $request,$dept_id){
        $validator=\Validator::make($request->all(),[    
           'collor_name'=>'required',
           'image'=>'image|mimes:jpeg,png,jpg|max:400',
         ],
         );
  
       if($validator->fails()){
              return response()->json([
                'status'=>700,
                'message'=>$validator->messages(),
             ]);
       }else{
              $model= new Collor;
              $model->dept_id=$dept_id;
              $model->collor_name=$request->input('collor_name');
              $model->collor_des=$request->input('collor_des');
              $model->email=$request->input('email');
              $model->phone=$request->input('phone');
              $model->subject=$request->input('subject');
              if($request->hasfile('image')) {
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
               ],200);     
          }
      }

     
}