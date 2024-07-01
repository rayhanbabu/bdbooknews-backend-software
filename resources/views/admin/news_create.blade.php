@extends('admin.layout')
@section('page_title','Admin Panel')
@section('notice_select','active')
@section('scripts')
    <script src="{{ asset('js/news.js') }}"></script>
 @endsection
@section('content')
  <div class="row mt-4 mb-3">
               <div class="col-6"> <h4 class="mt-0">  Create  Form</h4></div>
                     <div class="col-3">
                         <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            
                         </div>
                     </div>
                     <div class="col-3">
                         <div class="d-grid gap-2 d-md-flex ">
                         <a class="btn btn-primary" href="{{url('/admin/news_view')}}" role="button">Back</a>  
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
      <form method="POST" action="{{url('admin/news_insert')}}" enctype="multipart/form-data">
        @csrf
  
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
                          
                      </select>
           </div>


             <div class="col-sm-2 my-2">
                <label class=""> Upazila  </label>
                       <select class="form-select" name="upazila_id" id="upazila_id" aria-label="Default select example" >
                         <option   value=""> Select One </option>
                           
                      </select>
              </div>

           <div class="col-sm-2 my-2">
               <label class=""> Highlight  </label>
                <input type="number" name="highlight_serial" id="highlight_serial" class="form-control" value="0" placeholder="" required>
           </div>


          <div class="col-sm-12 my-2">
              <label for="exampleFormControlTextarea1" class="form-label"> Title <span style="color:red;"> * </span> </label>

              <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="" required>
            </div>

          <div class="col-sm-3 my-2">
              <label for="roll"> Image <span style="color:red;"> * </span></label>
              <input type="file" name="image" id="image" class="form-control" placeholder="" required>  
              
              <label for="roll">Image Title <span style="color:red;"> * </span> </label>
              <input type="text" name="image_title" id="image_title" value="{{ old('image_title') }}" class="form-control" placeholder="" required>
           </div>

           <div class="col-sm-9 my-2">
              <label for="Textarea2" class="form-label"> Description  <span style="color:red;"> * </span></label>
              <textarea name="desc" id="summernote1" cols="30" rows="10" > {{ old('desc') }} </textarea required>
          </div>


          <div class="col-sm-3 my-2">
              <label for="roll"> Image 1 (Max:300KB)</label>
              <input type="file" name="image1" id="image1" class="form-control" placeholder="" >   
              <label for="roll">Image Title 1 </label>
              <input type="text" name="image_title1" id="image_title1" value="{{ old('image_title1') }}" class="form-control" > 
           </div>

           <div class="col-sm-9 my-2">
              <label for="Textarea" class="form-label"> Description 1</label>
              <textarea name="desc1" id="summernote2" cols="30" rows="10" > {{ old('desc1') }}  </textarea >
          </div>

        </div>

 <button type="submit" class="btn btn-primary">Submit</button>

</form>
</div>
 @endsection   
 
