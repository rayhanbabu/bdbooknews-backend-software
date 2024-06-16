<?php
 use Illuminate\Support\Facades\URL;
?>
@extends('admin.layout')
@section('page_title','Admin Panel')
@section('notice_select','active')
@section('content')

<div class="row mt-4 mb-3">
               <div class="col-6"> <h4 class="mt-0">   View  Details</h4></div>
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



 <div class="container p-4 ">
    <div class="row justify-content-md-center">
        <div class="col-md-12">
            <div class="text-center">
              Category Name : <b> {{$data->category_name}} </b> Sub Category : <b>  {{$data->subcategory_name}}  </b> 
              Date:<b> {{$data->created_at}}</b><br>
              Title:<b> {!!$data->title !!}</b><br>
              <img src="{{ asset('/uploads/admin/'.$data->image) }}" width="100" class="img-thumbnail" alt="Image">
              <img src="{{ asset('/uploads/admin/'.$data->image1) }}" width="100" class="img-thumbnail" alt="Image">
            </div>

            <a  target="_blank"  href="<?php echo URL::to('/uploads/admin/'.$data->image) ?>">
                      <?php echo URL::to('/uploads/admin/'.$data->image) ?></a>  
                      <span>{{$data->image_title}} </span>
                      <br>
            <a  target="_blank"  href="<?php echo URL::to('/uploads/admin/'.$data->image1) ?>">
                    <?php echo URL::to('/uploads/admin/'.$data->image1) ?></a>  
                    <span>{{$data->image_title1}} </span>
                  <br>          

           
              <br>
             Description
             <hr>
             <div>
                   {!! $data->desc !!}
             </div>
             <br>
             <hr>
             Description 1
             <hr>
             <div>
                   {!! $data->desc1 !!}
             </div>
        </div>
    </div>
</div>




</div>



    <script>
      $('#summernote').summernote({
        placeholder: 'Description...',
        tabsize: 2,
        height: 100
      });
    </script>


  




 @endsection             