@extends('maintain.layout')
@section('page_title','Maintain  Panel')
@section('maintain','active')
@section('content')

 <div class="row mt-3 mb-0 mx-2">
                <div class="col-sm-3 my-2"> <h4 class="mt-0">Maintain  View </h4></div>
                  
                 <div class="col-sm-3 my-2">
                 <div class="d-grid gap-2 d-flex justify-content-end"> 
                          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add</button>  
                </div>    
                </div>

                <div class="col-sm-6 my-2 ">
                 <div class="d-grid gap-3 d-flex justify-content-end">
                   
                 </div>
                </div>




               @if(Session::has('success'))
                  <div  class="alert alert-success"> {{Session::get('success')}}</div>
               @endif
 
               @if(Session::has('fail'))
                     <div  class="alert alert-danger"> {{Session::get('fail')}}</div>
               @endif

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
              <select class="form-select" id="university_assign" name="university_assign" aria-label="Default select example " required>
                       <option  value="">Select One </option>
                        @foreach($data as $row)
                            <option   value="{{$row->id}}">{{$row->university}}</option>
                        @endforeach  
               </select>
           </div>
          
            <div class="col-lg-12 my-2">
               <label for="roll">Name <span style="color:red;"> * </span></label>
               <input type="text" name="name" id="name" class="form-control" placeholder="" required>
               <p class="text-danger error_name"></p>
            </div>


            <div class="col-lg-12 my-2">
                <label for="roll">E-mail <span style="color:red;"> * </span></label>
                <input type="text" name="email" id="email" class="form-control" placeholder="" required>
                <p class="text-danger error_email"></p>
            </div>

            <div class="col-lg-12 my-2">
                <label for="roll">Phone Number <span style="color:red;"> * </span></label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="" required>
                <p class="text-danger error_phone"></p>
            </div>

            <div class="col-lg-12 my-2">
                <label for="roll">Password <span style="color:red;"> * </span></label>
                <input type="text" name="password" id="password" class="form-control" placeholder="" required>
                <p class="text-danger error_password"></p>
            </div>




            <div class="col-lg-12 my-2">
             <label for="avatar">Select Image<span style="color:red;"> (Image must be 300*300px) </span></label>
             <input type="file" name="image"  id="image" class="form-control" >
          </div>

            
          </div>    
          <div class="loader">
            <img src="{{ asset('images/abc.gif') }}" alt="" style="width: 50px;height:50px;">
          </div>

          <ul id="add_errorlist"> </ul>

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
              <select class="form-select" id="edit_university_assign" name="university_assign" aria-label="Default select example " required>
                       <option  value="">Select One </option>
                        @foreach($data as $row)
                            <option   value="{{$row->id}}">{{$row->university}}</option>
                        @endforeach  
               </select>
           </div>
        
          <div class="col-lg-12 my-2">
               <label for="roll">Name <span style="color:red;"> * </span></label>
               <input type="text" name="name" id="edit_name" class="form-control" placeholder="" required>
            </div>


            <div class="col-lg-12 my-2">
                <label for="roll">E-mail <span style="color:red;"> * </span></label>
                <input type="text" name="email" id="edit_email" class="form-control" placeholder="" required>
            </div>

            <div class="col-lg-12 my-2">
                <label for="roll">Phone Number <span style="color:red;"> * </span></label>
                <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="" required>
            </div>

            <div class="col-lg-12 my-2">
                <label for="roll">Password <span style="color:red;"> * </span></label>
                <input type="text" name="password" id="edit_password" class="form-control" placeholder="" required>
                <p class="text-danger error_password"></p>
            </div>

            <div class="col-lg-6 my-2">
                  <label class=""><b>Status</b></label>
                   <select class="form-select" name="status" id="edit_status" aria-label="Default select example">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                  </select>
            </div>
         
            <ul id="edit_errorlist"> </ul>




            <div class="col-lg-12 my-2">
               <label for="avatar">Select Image<span style="color:red;"> (Image must be 300*300px) </span></label>
                <input type="file" name="image"  id="imageedit" class="form-control" >
            </div>

           

         </div>

         <div class="mt-2" id="avatar"> </div>
       
         
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
          url:'/maintain/store',
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
            if(response.status == 200){
               Swal.fire("Added",response.message, "success");
               $("#add_employee_btn").text('Submit');
               $("#add_employee_form")[0].reset();
               $("#addEmployeeModal").modal('hide');
               $('#add_errorlist').html("");
               $('#add_errorlist').addClass('');
              
               fetchAll();
              }else if(response.status == 400){
                Swal.fire("Warning",response.message,"warning");
              }else if(response.status == 700){
                    $('#add_errorlist').html("");
                    $('#add_errorlist').addClass('alert alert-danger');
                    $.each(response.message,function(key,err_values){ 
                    $('#add_errorlist').append('<li>'+err_values+'</li>');
                    });     
              }  
          }
        });

       
      });


      fetchAll();
      function fetchAll() {
        $.ajax({
          type:'GET',
          url:'/maintain/fetchAll',
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
          url:'/maintain/edit',
          data: {
            id: id,
          },
          success: function(response){
                $("#edit_name").val(response.data.name);
                $("#edit_phone").val(response.data.phone);
                $("#edit_email").val(response.data.email);
                $("#edit_status").val(response.data.status);
                $("#edit_password").val(response.data.password);
                $("#edit_university_assign").val(response.data.university_assign);
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
          url:'/maintain/update',
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
               $('#edit_errorlist').html("");
                $('#edit_errorlist').addClass('');
               fetchAll();
             }else if(response.status == 300){
              Swal.fire("Warning",response.message, "warning");
             }else if(response.status == 400){
              Swal.fire("Warning",response.message, "warning");
            }else if(response.status == 700){
                     $('#edit_errorlist').html("");
                     $('#edit_errorlist').addClass('alert alert-danger');
                    $.each(response.message,function(key,err_values){ 
                    $('#edit_errorlist').append('<li>'+err_values+'</li>');
                    });  
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
          text: "delete this item!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url:'/maintain/delete',
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







@endsection 