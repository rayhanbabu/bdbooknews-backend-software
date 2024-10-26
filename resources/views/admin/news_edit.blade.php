@extends('admin.layout')
@section('page_title','Admin Panel')
@section('notice_select','active')
@section('content')

   @section('scripts')
       <script src="{{ asset('js/news.js') }}"></script>
    @endsection
<div class="row mt-4 mb-3">
               <div class="col-6"> <h4 class="mt-0"> Edit Form</h4></div>
                     <div class="col-3">
                         <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            
                         </div>
                     </div>
                     <div class="col-3">
                         <div class="d-grid gap-2 d-md-flex ">
                         <a class="btn btn-primary" href="{{url('/admin/news_view/')}}" role="button">Back</a>  
              </div>
        </div> 
 </div> 

 <div class="form-group  mx-2 my-2">
                           @if(Session::has('fail'))
                   <div  class="alert alert-danger"> {{Session::get('fail')}}</div>
                                @endif
                             </div>

                             <div class="form-group  mx-2 my-2">
                           @if(Session::has('success'))
                   <div  class="alert alert-success"> {{Session::get('success')}}</div>
                                @endif
                             </div>

 @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div>
  @endif

 <div class="container shadow p-4">
      <form  method="POST" id="edit_news_form" enctype="multipart/form-data">
  
         <input type="hidden" name="edit_id" id="edit_id" class="form-control">
       
         <div class="row">
          
        <div class="col-sm-2 my-2">
                 <label class=""> Category  <span style="color:red;"> * </span></label>
                  <select class="form-select" name="category_name_id" id="category_name_id" aria-label="Default select example" required>
                      <option   value=""> Select One </option>
                        @foreach(category() as $row)
                            <option   value="{{$row->category_name}}">{{$row->name}}</option>
                        @endforeach  
                   </select>
          </div>

          <div class="col-sm-2 my-2">
               <label class=""> Sub Category  </label>
                 <select class="form-select" name="subcategory_name_id" id="subcategory_name_id" aria-label="Default select example" >
                           <option   value=""> Select One </option> 
                           @foreach(subcategory() as $row)
                            <option   value="{{$row->subcategory_name}}">{{$row->sub_name }}</option>
                        @endforeach                
                 </select>
          </div>

          <div class="col-sm-2 my-2">
             <label class=""> Division  </label>
                      <select class="form-select" name="division_id" id="division_id" aria-label="Default select example" >
                        <option   value=""> Select One </option>
                          @foreach(division() as $row)
                                <option   value="{{$row->id}}">{{$row->bn_name}}</option>
                          @endforeach  
                      </select>
             </div>

           <div class="col-sm-2 my-2">
             <label class=""> District  </label>
                      <select class="form-select" name="district_id" id="district_id" aria-label="Default select example" >
                         <option  value=""> Select One </option>
                          @foreach(district() as $row)
                                <option   value="{{$row->id}}">{{$row->bn_name}}</option>
                          @endforeach  
                      </select>
           </div>

                <div class="col-sm-2 my-2">
                  <label class=""> Upazila  </label>
                         <select class="form-select" name="upazila_id" id="upazila_id" aria-label="Default select example" >
                             <option   value=""> Select One </option>  
                              @foreach(upazila() as $row)
                                 <option   value="{{$row->id}}">{{$row->bn_name}}</option>
                              @endforeach                      
                         </select>
                 </div>

           <div class="col-sm-2 my-2">
               <label class=""> Highlight  </label>
                <input type="number" name="highlight_serial" id="highlight_serial" class="form-control" value="" placeholder="" required>
           </div>

           <div class="col-sm-9 my-2">
                 <label for="exampleFormControlTextarea1" class="form-label"> Title <span style="color:red;"> * </span> </label>
                 <input type="text" name="title" id="title" class="form-control" value="" placeholder="" required>
           </div>

            <div class="col-sm-1 my-2">
                 <label  class="form-label">  Most Read   </label>
                 <input type="number" name="geater_serial" id="geater_serial" class="form-control" value="" placeholder="" required>
            </div>

            <div class="col-sm-2 my-2">
                   <label class="form-label"> Title  Color <span style="color:red;"> * </span> </label>
                      <select class="form-select" name="title_color" id="title_color" aria-label="Default select example" >
                               <option   value="0"> Black</option>
                               <option   value="1"> Red </option>
                      </select>
               </div>

           <div class="col-sm-3 my-2">
               <label for="roll"> Image <span style="color:red;"> * </span></label>
               <input type="file" name="image" id="image" class="form-control" placeholder="">  
              
               <label for="roll"> Image Title <span style="color:red;"> * </span> </label>
               <input type="text" name="image_title" id="image_title" value="" class="form-control" placeholder="" required>
           </div>

           <div class="col-sm-9 my-2">
              <label for="Textarea2" class="form-label"> Description  <span style="color:red;"> * </span></label>
              <textarea name="desc" id="summernote1" cols="30" rows="10" >  </textarea required>
          </div>


          <div class="col-sm-3 my-2">
              <label for="roll"> Image 1 (Max:300KB) </label>
              <input type="file" name="image1" id="image1" class="form-control" placeholder="" >   
              <label for="roll"> Image Title 1 </label>
              <input type="text" name="image_title1" id="image_title1" value="" class="form-control" > 
           </div>

           <div class="col-sm-9 my-2">
              <label for="Textarea" class="form-label"> Description 1</label>
              <textarea name="desc1" id="summernote2" cols="30" rows="10" >  </textarea >
          </div>

        </div>

        <button  type="submit" id="edit_news_btn" class="btn btn-primary">Update</button>

  </form>


</div>


<script>

$(document).ready(function(){

$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

$("#edit_news_form").submit(function(e) {
  e.preventDefault();

  // alert('Rayhan babu');
   const fd = new FormData(this);
    $.ajax({
      type:'POST',
      url:"/admin/news_update",
      data: fd,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
    beforeSend : function(){
          $("#edit_news_btn").prop('disabled', true).text('Updating...');
      },
     success: function(response){
            $("#edit_news_btn").prop('disabled', false).text('Update');
             if(response.status=='success'){
                  console.log(response);
                  Swal.fire("Success",response.message, "success");
                //  window.location.href="/admin/news_view/"       
             }else{
                  Swal.fire("Warning",response.message, "warning");
             }       
       }
    });
 });



   
   fetch();
  function fetch() {

       $.ajaxSetup({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            } });
     
      $.ajax({
           type: 'GET',
           url: '/admin/news_fatch/{{$id}}',
           success: function(response) {
              console.log(response);
             if (response.status == 404) {
               $('#success_message').html("");
               $('#success_message').addClass('alert alert-danger');
               $('#success_message').text(response.message);
             } else {
               $('#edit_id').val(response.data.id);
               $('#category_name_id').val(response.data.category_name_id);
               $('#subcategory_name_id').val(response.data.subcategory_name_id);
               $('#division_id').val(response.data.division_id);
               $('#district_id').val(response.data.district_id);
               $('#upazila_id').val(response.data.upazila_id);
               $('#union_id').val(response.data.union_id);
               $('#highlight_serial').val(response.data.highlight_serial);
               $('#title').val(response.data.title);  
               $('#geater_serial').val(response.data.geater_serial);   
               $('#image_title').val(response.data.image_title);  
               $('#summernote1').summernote('code', response.data.desc);   
               $('#image_title1').val(response.data.image_title1);  
               $('#summernote2').summernote('code', response.data.desc1);     
             }
           }
         })
  }


 


});
</script>



 @endsection             