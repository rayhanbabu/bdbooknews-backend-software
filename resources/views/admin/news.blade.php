@extends('admin.layout')
@section('page_title','Admin Panel')
@section($category.'_select','active')

 @section('scripts')
   <script src="{{ asset('js/news.js') }}"></script>
 @endsection

@section('content')
  <div class="card mt-3 mb-0"> 
    <div class="card-header ">
      <div class="row">
               <div class="col-6"> <h4 class="mt-0"> </h4></div>
                     <div class="col-3">
                         <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            
                         </div>
                     </div>
                     <div class="col-3">
                         <div class="d-grid gap-2 d-md-flex ">
                         <a class="btn btn-primary" href="{{url('/admin/news_create/')}}" role="button">Add</a>
              </div>
        </div> 
 </div> 

 <div class="form-group mx-2 my-2">
                           @if(Session::has('fail'))
                   <div  class="alert alert-danger"> {{Session::get('fail')}}</div>
                                @endif
                             </div>

                             <div class="form-group  mx-2 my-2">
                           @if(Session::has('success'))
                   <div  class="alert alert-success"> {{Session::get('success')}}</div>
                                @endif
                             </div>
   
  </div>
      

  <div class="card-body">
    <div class="row">
        <div class="col-md-3 p-2">
              <select class="form-select form-select-sm" id="range" name="range" aria-label="Default select example " required>
                    <option  value="10">10 </option>
                    <option  value="20">20 </option>
                    <option  value="50">50 </option>
                    <option  value="100">100 </option>
              </select>             
        </div> 
       <div class="col-md-6"> </div>       
            
    <div class="col-md-3 p-2">
     <div class="form-group">
         <input type="text" name="search" id="search" placeholder="Enter Search " class="form-control form-control-sm"  autocomplete="off"  />
     </div>
    </div>
   </div>
   
   <div id="success_message"></div>
				
<div class="table-responsive">		
<div class="x_content">
 <table id="employee_data"  class="table table-bordered table-hover table-sm shadow">
    <thead>
    <tr>
         <th width="10%" class="sorting" data-sorting_type="asc" data-column_name="created_at" style="cursor: pointer">Date 
           <span id="created_at_icon" ><i class="fas fa-sort-amount-up-alt"></i></span> </th>
          
            <th width="10%" class="sorting" data-sorting_type="asc" data-column_name="highlight_serial" style="cursor: pointer">Highlight Serial
            <span id="highlight_serial_icon"><i class="fas fa-sort-amount-up-alt"></span></th>   
          
            <th width="10%" class="sorting" data-sorting_type="asc" data-column_name="geater_serial" style="cursor: pointer">Most Read 
            <span id="geater_serial_icon"><i class="fas fa-sort-amount-up-alt"></span></th>   
            
            <th width="10%" class="sorting" data-sorting_type="asc" data-column_name="category_name" style="cursor: pointer">Category Name
            <span id="category_name_icon"><i class="fas fa-sort-amount-up-alt"></span></th>   

            <th width="10%" class="sorting" data-sorting_type="asc" data-column_name="subcategory_name" style="cursor: pointer">Sub Category Name
            <span id="subcategory_name_icon"><i class="fas fa-sort-amount-up-alt"></span></th>   
  

            <th width="20%" class="sorting" data-sorting_type="asc" data-column_name="title" style="cursor: pointer">Title
            <span id="title_icon"><i class="fas fa-sort-amount-up-alt"></span></th>          
          
            <th  width="10%">Image</th>

            <th width="20%" class="sorting" data-sorting_type="asc" data-column_name="division" style="cursor: pointer">Division
                  <span id="division_icon"><i class="fas fa-sort-amount-up-alt"></span></th>

            <th width="20%" class="sorting" data-sorting_type="asc" data-column_name="district" style="cursor: pointer">District
                  <span id="district_icon"><i class="fas fa-sort-amount-up-alt"></span></th>  
                  
        <th width="20%" class="sorting" data-sorting_type="asc" data-column_name="upazila" style="cursor: pointer">Upazila
                  <span id="upazila_icon"><i class="fas fa-sort-amount-up-alt"></span></th>         

           
		      <th  width="10%">View</th>
		      <th  width="10%"></th>
          <th  width="10%"></th>
      </tr>


       <tr>
          <td colspan="5">
            <div  class="loader_page text-center">
                <img src="{{ asset('images/abc.gif') }}" alt="" style="width: 50px;height:50px;">
            </div>
         </td>
      </tr>         
    </thead>
    <tbody>
       
    </tbody>
  </table>
       
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
 
 
</div>
</div>
</div>

</div>

@endsection 