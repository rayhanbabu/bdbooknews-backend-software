<?php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use App\Helpers\MaintainJWTToken;
    use App\Helpers\TeacherJWTToken;
    use App\Models\Teacher;
    use Illuminate\Support\Facades\Cookie;
    use App\Models\Week;
    use App\Models\Category;

       function prx($arr){
           echo "<pre>";
           print_r($arr);
           die();
       }

       function rayhan(){
          return 'Md Rayhan Babu';
       }

       function admin_name($admin_id){
           $admin=DB::table('admins')->where('id',$admin_id)->first();
           return $admin->name;
        }

        function getDaysBetween2Dates(DateTime $date1, DateTime $date2, $absolute = true)
        {
          $interval = $date2->diff($date1);
          return (!$absolute and $interval->invert) ? - $interval->days : $interval->days;
        }


        function baseimage($path){
            //$path = 'image/slide1.jpg';
             $type = pathinfo($path, PATHINFO_EXTENSION);
             $data = file_get_contents($path);
           return  $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
       }

       function SendEmail($email,$subject,$body,$otp,$name){
           $details = [
             'subject' => $subject,
             'otp_code' =>$otp,
             'body' => $body,
             'name' => $name,
           ];
          Mail::to($email)->send(new \App\Mail\LoginMail($details));
       }


        function maintainaccess(){
            $token_maintain=Cookie::get('token_maintain');
            $result=MaintainJWTToken::ReadToken($token_maintain);
            if($result=="unauthorized"){
                return redirect('/maintain/login');
            }
            else if($result->role=="supperadmin"){
                return true;
            }else{
                return false;
            }
        }


        function member_category(){      
            $token_teacher=Cookie::get('token_teacher');
            $result=TeacherJWTToken::ReadToken($token_teacher);
            $data=Week::where('category_name','Member')->where('dept_id',$result->dept_id)->orderby('serial','asc')->orderby('week','asc')->get();
            return $data;
        }
     
       function event_category(){  
          $token_teacher=Cookie::get('token_teacher');
           $result=TeacherJWTToken::ReadToken($token_teacher);    
            $data=Week::where('category_name','Event')->where('dept_id',$result->dept_id)->orderby('serial','asc')->orderby('week','asc')->get();
            return $data;
        }

     function category(){
            $token_teacher=Cookie::get('token_teacher');
            $result=TeacherJWTToken::ReadToken($token_teacher);    
            $category=Category::where('dept_id',$result->dept_id)->orderby('serial','asc')->orderBy('id','desc')->get();
            return $category;
      }

         function division(){ 
            $data=DB::table('divisions')->orderBy('name','asc')->get();
             return $data;
         }

        
        function teacher_info(){
            $teacher_info=Cookie::get('teacher_info');
            $result=unserialize($teacher_info);
            return $result;
        }

       
        function adminaccess(){
            $token_teacher=Cookie::get('token_teacher');
            $result=TeacherJWTToken::ReadToken($token_teacher);
            if($result=="unauthorized"){
                return redirect('/teacher/login');
            }
            else if($result->role=="admin"){
                return true;
            }else{
                return false;
            }
        }

 


   function exam_category(){
    $token_teacher=Cookie::get('token_teacher');
    $result=TeacherJWTToken::ReadToken($token_teacher);
    $examiner=DB::table('examiners')->where('dept_id',$result->dept_id)->get();
    return $examiner;
}




function week_details($week_id){      
     $data=Week::where('id',$week_id)->where('category_name','Week')->first();
      return $data;
}


function image_resize($size){
   //Resize the image
   $maxWidth = 1420;
   $size=$size;

   // Get the original dimensions of the image
    $currentWidth  = $size[0];
    $currentHeight  = $size[1];

    // Calculate the aspect ratio
    $aspectRatio = $currentWidth / $currentHeight;

    // Calculate the aspect ratio
     $aspectRatio = $currentWidth / $currentHeight;

// Check if the current width is within the desired range
  if ($currentWidth > $maxWidth) {
        $width = $maxWidth;
        $height = intval($maxWidth / $aspectRatio);
    }else{
        $width = $currentWidth;
        $height = intval($currentWidth / $aspectRatio);    
    }
       return [
          'width' => $width,
          'height' => $height,
      ];

   }


 
?>

      
        
