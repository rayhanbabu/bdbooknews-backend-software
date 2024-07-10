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
use Illuminate\Support\Collection;
use App\Models\Ads;

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
 
         public function news_highlight_category(Request $request ,$dept_id,$category){
            $data= News::leftjoin('categories','categories.category_name', '=','news.category_name_id')
            ->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id')
            ->where('news.dept_id',$dept_id)->where('news.category_name_id',$category)
            ->select('categories.name','subcategories.sub_name','news.*')->orderby('serial','asc')->orderby('id','desc')->limit(7)->get();
              return response()->json([
                   'status'=>'success',
                   'data'=>$data 
               ],200);
          }



      public function news_category(Request $request ,$dept_id,$category){

           $data_7= News::where('dept_id',$dept_id)->where('category_name_id',$category)
            ->select('id','serial')->orderby('serial','asc')->orderby('id','desc')->limit(7)->get();

            foreach ($data_7 as $row) {
                $array[] = $row->id;  // Add the ID to the array
            }
            // If you want unique IDs (though they should already be unique in this case)
            $array = array_unique($array);

            //$perPage=$request->input('perPage',18);
            $perPage=8;
            $page=$request->input('page',1);
            $query=News::query();
            if($search=$request->search){
                 $query->whereRaw("title LIKE '%".$search."%'")
                   ->orWhereRaw("desc LIKE '%".$search."%'");
             }
           
            $query->leftjoin('categories','categories.category_name', '=','news.category_name_id');
            $query->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id');
            $query->where('news.dept_id',$dept_id)->where('news.category_name_id',$category)->whereNotIn('news.id', $array);
            $query->select('categories.name','subcategories.sub_name','news.*');
           
            $total=$query->count();
            $query->orderBy("serial", 'asc')->orderby('id','desc');
            $result=$query->offset(($page-1) * $perPage)->limit($perPage)->get();
  
               return response()->json([
                    'status'=>'success',
                    'array'=>$array,
                    'data'=>$result, 
                    'total'=>$total,
                    'page'=>$page,
                    'last_page'=>ceil($total/$perPage)
             
                 ],200);
           }


           public function news_search(Request $request ,$dept_id){
            
               //$perPage=$request->input('perPage',18);
               $perPage=10;
               $page=$request->input('page',1);
               $query=News::query();
               $search=$request->search;
                
            
               $query->leftjoin('categories','categories.category_name', '=','news.category_name_id');
               $query->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id');
               $query->where('news.dept_id',$dept_id);
               $query->select('categories.name','subcategories.sub_name','news.*');

               if ($search) {
                  $query->where(function($q) use ($search) {
                      $q->where('news.title', 'like', '%' . $search . '%')
                        ->orWhere('news.category_name_id', 'like', '%' . $search . '%');
                  });
              }
            
               $total=$query->count();
               $query->orderBy("serial", 'asc')->orderby('id','desc');
               $result=$query->offset(($page-1) * $perPage)->limit($perPage)->get();
   
                return response()->json([
                     'status'=>'success',
                     'data'=>$result, 
                     'total'=>$total,
                     'page'=>$page,
                     'last_page'=>ceil($total/$perPage)
              
                  ],200);
            }
 


           public function news_highlight_subcategory(Request $request ,$dept_id,$category,$subcategory){
               $data= News::leftjoin('categories','categories.category_name', '=','news.category_name_id')
                ->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id')
                ->where('news.dept_id',$dept_id)->where('news.category_name_id',$category)->where('news.subcategory_name_id',$subcategory)
                ->select('categories.name','subcategories.sub_name','news.*')->orderby('serial','asc')->orderby('id','desc')->limit(1)->get();
                return response()->json([
                     'status'=>'success',
                     'data'=>$data 
                  ],200);
             }

       public function news_subcategory(Request $request ,$dept_id,$category,$subcategory){

         $data_7= News::where('dept_id',$dept_id)->where('category_name_id',$category)
         ->where('subcategory_name_id',$subcategory)
         ->select('id','serial')->orderby('serial','asc')->orderby('id','desc')->limit(7)->get();

         foreach ($data_7 as $row) {
             $array[] = $row->id;  // Add the ID to the array
         }
         // If you want unique IDs (though they should already be unique in this case)
         $array = array_unique($array);

         //$perPage=$request->input('perPage',18);
         $perPage=8;
         $page=$request->input('page',1);
         $query=News::query();
         if($search=$request->search){
              $query->whereRaw("title LIKE '%".$search."%'")
                ->orWhereRaw("desc LIKE '%".$search."%'");
          }

          $query->leftjoin('categories','categories.category_name', '=','news.category_name_id');
          $query->leftjoin('subcategories','subcategories.subcategory_name', '=','news.subcategory_name_id');
          $query->where('news.dept_id',$dept_id)->where('news.category_name_id',$category)
         ->where('news.subcategory_name_id',$subcategory)->whereNotIn('news.id', $array);
          $query->select('categories.name','subcategories.sub_name','news.*');
         
          $total=$query->count();
          $query->orderBy("serial", 'asc')->orderby('id','desc');
          $result=$query->offset(($page-1) * $perPage)->limit($perPage)->get();

             return response()->json([
                  'status'=>'success',
                  'array'=>$array,
                  'data'=>$result, 
                  'total'=>$total,
                  'page'=>$page,
                  'last_page'=>ceil($total/$perPage)
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
             ->select('categories.name','subcategories.sub_name','news.*')->orderby('highlight_serial','asc')->orderby('id','desc')->limit(8)->get();
                return response()->json([
                   'status'=>'success',
                   'data'=>$data 
                ],200);
          }


         public function latest_news(Request $request ,$dept_id){
              $data= News::leftjoin('categories','categories.category_name','=','news.category_name_id')
              ->leftjoin('subcategories','subcategories.subcategory_name','=','news.subcategory_name_id')
              ->where('news.dept_id',$dept_id)
              ->select('categories.name','subcategories.sub_name','news.*')->orderby('id','desc')->limit(7)->get();
                 return response()->json([
                    'status'=>'success',
                    'data'=>$data 
                  ],200);
            }

            public function most_read(Request $request ,$dept_id){
               $data= News::leftjoin('categories','categories.category_name','=','news.category_name_id')
               ->leftjoin('subcategories','subcategories.subcategory_name','=','news.subcategory_name_id')
               ->where('news.dept_id',$dept_id)
               ->select('categories.name','subcategories.sub_name','news.*')->orderby('geater_serial','desc')->orderby('id','desc')->limit(7)->get();
                  return response()->json([
                     'status'=>'success',
                     'data'=>$data 
                   ],200);
             }

         public function ads_show(Request $request ,$dept_id){
             $data= Ads::where('dept_id',$dept_id)->orderby('serial','desc')->orderby('id','desc')->get();
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