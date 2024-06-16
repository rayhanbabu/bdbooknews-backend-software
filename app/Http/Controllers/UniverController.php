<?php

namespace App\Http\Controllers;

use App\Models\Univer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Support\Facades\File;
use App\Exports\UniverExport;
use App\Imports\UniverImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use PDF;
use DOMDocument;



class UniverController extends Controller
{
    
      public function univer_view() {
            return view('maintain.univer-view');
       }


    public function store(Request $request){

         $validator=\Validator::make($request->all(),[    
            'university'=>'required|unique:univers,university',
            
          ],
           [
            'university.required'=>'Universty  is required',
            'university.unique'=>'Universty Name Already Exist',
           ]);

      if($validator->fails()){
             return response()->json([
               'status'=>700,
               'message'=>$validator->messages(),
            ]);
      }else{               
             $model= new Univer;
             $model->university=$request->input('university');
             $model->address=$request->input('address');
             $model->university_code=$request->input('university_code');
             $model->university_established_date=$request->input('university_established_date');
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



    public function fetchAll() {
      
        $data= Univer::get();

    
        $output = '';
        if ($data->count()> 0) {
          $output.=' <h5 class="text-success"> Total Row : '.$data->count().' </h5>';	
           $output .= '<table class="table table-bordered table-sm text-start align-middle">
           <thead>
              <tr>
                <th>Image </th>
                <th>University </th>
                <th>Address </th>
                <th>University code  </th>
                <th>University established date </th>
                <th>Action </th>
              </tr>
           </thead>
           <tbody>';
           foreach ($data as $row){
            if(!$row->image){$image="";}else{$image='<i class="fa fa-download"></i>';}
             $output .= '<tr>
                <td> <a href=/uploads/'.$row->image.' download id="' . $row->id . '" class="text-success mx-1">'.$image.' </a></td>
                <td>'.$row->university.'</td>
                <td>'.$row->address.'</td>
                <td>'.$row->university_code.'</td>
                <td>'.$row->university_established_date.'</td>
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
        $data = Univer::find($id);
        return response()->json([
          'status'=>100,  
          'data'=>$data,
        ]);
      }
    
   
      public function update(Request $request ){
        $validator=\Validator::make($request->all(),[    
            'university' => 'required|unique:univers,university,'.$request->input('edit_id'),
         ],
         [
          'university.required'=>'Universty  is required',
          'university.unique'=>'Universty Name Already Exist',
         ]);

      if($validator->fails()){
             return response()->json([
               'status'=>700,
               'message'=>$validator->messages(),
            ]);
      }else{
        $model=Univer::find($request->input('edit_id'));
        if($model){
            $model->university=$request->input('university');  
            $model->address=$request->input('address');
            $model->university_code=$request->input('university_code');
            $model->university_established_date=$request->input('university_established_date');
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
             $model=Univer::find($request->input('id'));
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


    public function import(){
      Excel::Import(new UniverImport,request()->file('file'));
      //return redirect()->back();
      return back()->with('success','Data Imported Successfully');
   }


   public function export(Request $request) { 
      $export_id=$request->input('export_id');
      $codema="Name";
      return Excel::download(new UniverExport($export_id), 'users.xlsx');
       // return (new UniverExport($export_id))->download('invoices.xlsx');
       //return (new UniverExport())->download($codema.'.csv');
       // return (new UniverExport)->download('invoices.csv', Excel::CSV, ['Content-Type' => 'text/csv']); 
 
   }

   public function dompdf()
   {
    
      $pdf = PDF::loadView('pdf.mpdf');
          return $pdf->stream('mpdffile.pdf');
      }

     public function jsprint(Request $request) {
          return view('pdf.jsprint');
      }



        public function text() {
             $data= Univer::get();
             return view('maintain.text',['data'=>$data]);
         }
      
         public function text_create()
         {
             return view('maintain.textcreate');
         }

        public function text_store(Request $request) {

          $description = $request->description;
 
          $dom = new DOMDocument();
          $dom->loadHTML($description,9);
 
          $images = $dom->getElementsByTagName('img');
 
        foreach ($images as $key => $img) {
            $data = base64_decode(explode(',',explode(';',$img->getAttribute('src'))[1])[1]);
            $image_name = "/upload/" . time(). $key.'.png';
            file_put_contents(public_path().$image_name,$data);
 
            $img->removeAttribute('src');
            $img->setAttribute('src',$image_name);
        }
        $description = $dom->saveHTML();
 
        Univer::create([
            'uni_en' => 'english',
            'uni_bn' => 'bangla',
            'title' => $request->title,
            'description' => $description
        ]);
 
        return back();
           
         }

         public function text_show($id)
         {
             $data = Univer::find($id);
             return view('maintain.textshow',['data'=>$data]);
         }



         public function text_edit($id)
         {
             $data = Univer::find($id);
             return view('maintain.textedit',compact('data'));
         }


    public function text_update(Request $request, $id)
      {

        $post = Univer::find($id);
        $description = $request->description;
        $dom = new DOMDocument();
        $dom->loadHTML($description,9);
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $key => $img){
            // Check if the image is a new one
            if (strpos($img->getAttribute('src'),'data:image')===0) {
                 $data = base64_decode(explode(',',explode(';',$img->getAttribute('src'))[1])[1]);
                 $image_name = "/upload/".time().$key.'.png';
                 file_put_contents(public_path().$image_name,$data);  
                 $img->removeAttribute('src');
                 $img->setAttribute('src',$image_name);
             }
        }

         
        $description = $dom->saveHTML();


 
        $post->update([
           'title' => $request->title,
           'description' => $description
       ]);
 
        return redirect('maintain/text');
 
    }


    public function text_destroy($id)
    {
        $post = Univer::find($id);  
        $dom= new DOMDocument();
        $dom->loadHTML($post->description,9);
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $key => $img){    
            $src = $img->getAttribute('src');
            $path = Str::of($src)->after('/');
            if (File::exists($path)) {
                File::delete($path);   
            }
        }
 
        $post->delete();
        return redirect()->back();
 
    }
      

        



   
}
