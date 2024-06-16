@extends('maintain.layout')
@section('page_title','Maintain  Panel')
@section('week','active')
@section('content')

 

 <div class="row mt-2 mb-0 mx-1 shadow p-1">
    
       <div class="col-sm-6 my-2">
           <form  method="get" enctype="multipart/form-data">   
             <label> Select Client </label>
                <select name="dept_id" id="dept_id" class="js-example-disabled-results" style="width:300px;" aria-label="Default select example" required>
                      <option value=""> Select Client </option>
                        @foreach($dept as $row)  
                           @if($row->id==($dept_id?$dept_id->id:0))
                                <option  value="{{$row->id}}" selected> 
                               {{$row->dept_name}}</option>
                            @else
                               <option value="{{$row->id}}">{{$row->dept_name}}</option>
                            @endif
                        @endforeach
                </select>


      </div>
    

        <div class="col-sm-2 mt-2">
              <button type="submit" name="search" class="btn btn-primary btn-sm">Search</button>
         </div>
      </form>
    </div>


    @if($dept_id!="") 
      <div class="row mt-3 mb-0 mx-2">
                <div class="col-sm-3 my-2"> <h4 class="mt-0">{{$dept_id?$dept_id->dept_name:""}} </h4></div>
                    
                 <div class="col-sm-3 my-2">
                  <div class="d-grid gap-2 d-flex justify-content-end"> 
                       <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add</button>  
                 </div>    
                 </div>
   </div>


 <div class="table-responsive">
           <div class="card-body" id="show_all_employees">     
                 <h1 class="text-center text-secondary my-5">Loading...</h1>
              </div>
     </div>



     {{-- add new Student modal start --}}
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form  method="POST" id="add_employee_form" enctype="multipart/form-data">

        <div class="modal-body p-4 bg-light">
          <div class="row">


          <div class="col-lg-12 my-2">
               <label for="roll">Serial <span style="color:red;"> * </span></label>
               <input type="number" name="serial" id="edit" class="form-control" placeholder="" required>
               <p class="text-danger error_serial"></p>
            </div>

           <div class="col-lg-12 my-2">
               <select class="form-select" id="category_name" name="category_name" aria-label="Default select example " required>
                       <option  value="">Select One </option>
                       <option  value="Event">Event </option>
                       <option  value="Member">Member </option>
                       <option  value="Week">Week</option>          
                </select>
           </div>
					
         <div class="col-lg-12 my-2">
               <label for="roll"> Name<span style="color:red;"> * </span></label>
               <input type="text" name="week" id="week" class="form-control" placeholder="" required>
               <p class="text-danger error_week"></p>
         </div>

         <input type="hidden" name="dept_id"  id="admin_category" value="{{$dept_id->id}}" >
       <div class="col-lg-12 my-2">
         <label for="avatar">Select Image<span style="color:red;"> (Image must be 300*300px) </span></label>
         <input type="file" name="image"  id="image" class="form-control" >
      </div>

            
          </div>    
          <div class="loader">
            <img src="{{ asset('images/abc.gif') }}" alt="" style="width: 50px;height:50px;">
          </div>

        <div class="mt-4">
          <button type="submit" id="add_employee_btn" class="btn btn-primary">Submit </button>
       </div>  

      </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       
        </div>
      </form>
    </div>
  </div>
</div>

{{-- add new employee modal end --}}





{{-- edit employee modal start --}}
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form  method="POST" id="edit_employee_form" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" id="edit_id">
          <div class="modal-body p-4 bg-light">
             <div class="row">

          <div class="col-lg-12 my-2">
               <label for="roll">Serial <span style="color:red;"> * </span></label>
               <input type="number" name="serial" id="edit_serial" class="form-control" placeholder="" required>
             <p class="text-danger edit_error_serial"></p>
           </div>

           <div class="col-lg-12 my-2">
               <select class="form-select" id="edit_category_name" name="category_name" aria-label="Default select example " required>
                       <option  value="">Select One </option>
                       <option  value="Event">Event </option>
                       <option  value="Member">Member </option>
                       <option  value="Week">Week</option>          
                </select>
            </div>

             <div class="col-lg-12 my-2">
               <label for="roll">week Name<span style="color:red;"> * </span></label>
               <input type="text" name="week" id="edit_week" class="form-control" placeholder="" required>
               <p class="text-danger edit_error_week"></p>
            </div>

            <div class="col-lg-12 my-2">
               <label for="avatar">Select Image<span style="color:red;"> (Image must be 300*300px) </span></label>
                <input type="file" name="image"  id="imageedit" class="form-control" >
            </div>
         

         </div>

            <div class="mt-2" id="avatar">

             </div>

         
          <div class="loader">
            <img src="{{ asset('images/abc.gif') }}" alt="" style="width: 50px;height:50px;">
          </div>

        <div class="mt-4">
            <button type="submit" id="edit_employee_btn" class="btn btn-success">Update </button>
       </div>  

      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit employee modal end --}}



<script>  
  $(document).ready(function(){ 
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
 
       // add new employee ajax request
       $("#add_employee_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $.ajax({
          type:'POST',
          url:'/week/store',
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          beforeSend : function()
               {
               $('.loader').show();
               $("#add_employee_btn").prop('disabled', true);
               },
          success: function(response){
            $('.loader').hide();
            $("#add_employee_btn").prop('disabled', false);
            if(response.status == 100){
               Swal.fire("Added",response.message, "success");
               $("#add_employee_btn").text('Submit');
               $("#add_employee_form")[0].reset();
               $("#addEmployeeModal").modal('hide');
               $('.error_weeksity').text('');
               fetchAll();
              }else if(response.status == 300){
                Swal.fire("Warning",response.message,"warning");
              }else if(response.status == 400){
                Swal.fire("Warning",response.message,"warning");
              }else if(response.status == 700){
                    $('.error_weeksity').text(response.validate_err.weeksity);                
              }                     
          }
        });

       
      });


      fetchAll();
      function fetchAll() {
        $.ajax({
          type:'GET',
          url:'/week/fetchAll/{{$dept_id->id}}',
          success: function(response) {
            $("#show_all_employees").html(response);
            $("table").DataTable({
              order: [0, 'asc'],
              lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]]
            });
          }
        });
      }


        // edit employee ajax request
        $(document).on('click', '.editIcon', function(e) {
          e.preventDefault();
          let id = $(this).attr('id');
        $.ajax({
          type:'GET',
          url:'/week/edit',
          data: {
            id: id,
          },
          success: function(response){
               $("#edit_week").val(response.data.week);
               $("#edit_text").val(response.data.text);
               $("#edit_category_name").val(response.data.category_name);
               $("#edit_text2").val(response.data.twxt2);
               $("#edit_serial").val(response.data.serial);
               $("#edit_weeksity_established_date").val(response.data.weeksity_established_date);
               $("#avatar").html(
                `<img src="/uploads/${response.data.image}" width="100" class="img-fluid img-thumbnail">`);
               $("#edit_id").val(response.data.id);
          }
        });
      });




       // update employee ajax request
       $("#edit_employee_form").submit(function(e) {
        e.preventDefault();
      
        const fd = new FormData(this);

     
        $.ajax({
          type:'POST',
          url:'/week/update',
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          beforeSend : function()
               {
               $('.loader').show();
               },
          success: function(response){
            if (response.status == 200){
               Swal.fire("Updated",response.message, "success");
               $("#edit_employee_btn").text('Update');
               $("#edit_employee_form")[0].reset();
               $("#editEmployeeModal").modal('hide');
               fetchAll();
             }else if(response.status == 300){
              Swal.fire("Warning",response.message, "warning");
             }else if(response.status == 400){
              Swal.fire("Warning",response.message, "warning");
            }else if(response.status == 400){
                    $('.edit_error_weeksity').text(response.validate_err.weeksity);
              }
          
            $('.loader').hide();
          }
         
        });
      
      });


        
        // delete employee ajax request
        $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url:'/week/delete',
              method: 'delete',
              data: {
                id: id,
              },
              success: function(response) {
                //console.log(response);
                 if(response.status == 200){
                    Swal.fire("Warning",response.message, "warning");
                 }else if(response.status == 300)
                    Swal.fire("Deleted",response.message, "success");
                   fetchAll();
              }
            });
          }
        })
      });

	
  });

  </script>
  @endif

<script type="text/javascript">
        $('.js-example-basic-multiple').select2();
        $(".js-example-disabled-results").select2();
</script>



@endsection 