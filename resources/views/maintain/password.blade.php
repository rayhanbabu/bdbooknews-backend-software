@extends('maintain.layout')
@section('page_title','Maintain Panel')
@section('dashboard','active')
@section('content')

 <h3> Password Change</h3>
	   <div class="row">
	     <div class="col-sm-1">
		       
		   </div>

  <div class="col-sm-6" style="background-color:#f2f2f2">
        @if($errors->any())
            <ul class="alert alert-danger">
               @foreach($errors->all() as $error)
              <li>{{$error}}</li>
                 @endforeach
    
               </ul>
          @endif

		  <form action="{{url('maintain/password/')}}" method="post" class="myform"  enctype="multipart/form-data" >
         {!! csrf_field() !!}
          
			   <div class="form-group">
			       <br>
                  <label>&nbsp; Old Password </label>
                  <input type="password" name="oldpassword" class="form-control" autocomplete="off" required >
              </div> 
			  
               <div class="form-group">
                  <label>&nbsp;New Password</label>
                  <input type="password" name="npass" class="form-control" required >
              </div> 
			  
              <div class="form-group">
                  <label>&nbsp;Confirm Password</label>
                  <input type="password" name="cpass" class="form-control" required >
              </div> 	
                <br>
                <br>		  
				  <button type="submit"   class="btn btn-success">Submit</button> 
                  <br> <br>

                  @if(session('fail'))
            <h5 class="alert alert-danger">{{ session('fail')}} </h5>
                       @endif
                       @if(session('success'))
            <h5 class="alert alert-success">{{ session('success')}} </h5>
                       @endif

                  </form>
		  </div>
		 
		  <div class="col-sm-5">
		       
		   </div>
    </div>
  

@endsection 