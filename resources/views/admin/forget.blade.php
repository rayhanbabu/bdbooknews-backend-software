
<!doctype html>
<html lang="en">
  <head>
  	<title>ANCOVA</title>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 	  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="{{asset('dashboardfornt\css\login.css')}}">
      <link rel="icon" type="image/png" href="{{ asset('images/alibrary.png') }}">
	  <script src="{{asset('dashboardfornt\js\jquery-3.5.1.js')}}"></script>
	  <script src="{{asset('dashboardfornt/js/sweetalert.min.js')}}"></script>
	  <meta name="csrf-token" content="{{ csrf_token() }}">

	  <style>
 .codeform{
     display:none;
 }	
 .confirmpass{
   display:none;
 }
 .loader{
     display:none;
 }
</style>

	</head>
	<body>
	<section class="ftco-section">


	<div class="emailform"> 
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
				<h5></h5>
		      	<h3 class="text-center mb-4"> </h3>
				  <form method="post"  id="email_form"  class="myform"  enctype="multipart/form-data" >
		      		 <div class="form-group">
		      		   	<input type="email" class="form-control rounded-left" autocomplete="off" id="email"  name="email" placeholder="Enter Your Email" >
							 <p class="text-danger error_email"> </p>
		      		 </div>

					   <div class="loader">
                  <img src="{{ asset('images/abc.gif') }}" alt="" style="width: 50px;height:50px;">
		      	 </div><br>
	        
	            <div class="form-group">
			     	<button type="submit" id="add_employee_btn" class="form-control btn btn-primary rounded submit px-3">Submit </button>	
	            </div>
				
	            </form>
	         </div>
				</div>
			</div>
		 </div>
	</div>	


	<div class="codeform"> 
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
				<h5></h5>
		      	<h3 class="text-center mb-4"> </h3>
				  <form method="post"  id="code_form"  class="myform"  enctype="multipart/form-data" >
		      		 <div class="form-group">
					   <input type="hidden" name="email_id"  id="email_id" >
		      		   	<input type="text" class="form-control rounded-left" autocomplete="off" id="forget_code"  name="forget_code" placeholder="Enter OTP Code" >
							 <p class="text-danger error_email"> </p>
		      		 </div>

					   <div class="loader">
                  <img src="{{ asset('images/abc.gif') }}" alt="" style="width: 50px;height:50px;">
		      	 </div><br>
	        
	            <div class="form-group">
			     	<button type="submit" id="add_employee_btn" class="form-control btn btn-primary rounded submit px-3">Submit </button>	
	            </div>
				
	            </form>
	         </div>
				</div>
			</div>
		 </div>
	</div>	



	<div class="confirmpass"> 
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
				<h5></h5>
		      	<h3 class="text-center mb-4"> </h3>
				  <form method="post"  id="confirm_pass"  class="myform"  enctype="multipart/form-data" >
		      		 <div class="form-group">
					        <input type="hidden" name="email_id_pass"  id="email_id_pass" >	
                  <input type="hidden" name="forget_code_pass"  id="forget_code_pass" >	    
		      		    	<input type="password" class="form-control rounded-left" autocomplete="off" id="npass"  name="npass" placeholder="New Password" >
							 <p class="text-danger error_email"> </p>
		      		    </div>

					   <div class="form-group">	  
		      		   	    <input type="password" class="form-control rounded-left" autocomplete="off" id="cpass"  name="cpass" placeholder="Confirm Password" >
							 <p class="text-danger error_email"> </p>
		      		    </div>

					   <div class="loader">
                  <img src="{{ asset('images/abc.gif') }}" alt="" style="width: 50px;height:50px;">
		      	 </div><br>
	        
	            <div class="form-group">
			     	<button type="submit" id="add_employee_btn" class="form-control btn btn-primary rounded submit px-3">Submit </button>	
	            </div>
				
	            </form>
	         </div>
				</div>
			</div>
		 </div>
	</div>	



	


	</section>

	<script>  
$(document).ready(function(){ 
	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });


    $(document).on('submit', '#email_form', function(e){
        e.preventDefault();
        var email=$('#email').val();
        let emailData=new FormData($('#email_form')[0]);

        if(email== "")  
        {  
		Swal.fire("E-mail Field  is Required !", "", "warning");
     }else{
        $.ajax({
             type:'POST',
             url:'/maintain/forget',
             data:emailData,
             contentType: false,
             processData:false,
             beforeSend : function()
              {
                $('.loader').show();
              },
             success:function(response){ 
               // console.log(response);
                 if(response.status == 500){
                   $('#email_id').val(email);
                   $('.emailform').hide();
                   $('.codeform').show();
                }else{
					Swal.fire("Invalid Email ", "Please try again", "warning");
                }
                $('.loader').hide();
              }

         });
        }
      });



	  $(document).on('submit', '#code_form', function(e){
        e.preventDefault();
         var email_id=$('#email_id').val();
         var forget_code=$('#forget_code').val();
         let codeData=new FormData($('#code_form')[0]);
        if(forget_code== "")  
        {  
     swal("Forget code Field  is Required !", "", "warning");
     }else{
        $.ajax({
             type:'POST',
             url:'/maintain/forgetcode',
             data:codeData,
             contentType: false,
             processData:false,
             beforeSend : function()
              {
               $('.loader').show();
              },
             success:function(response){ 
               // console.log(response);
                if(response.status == 500){
                $('#email_id_pass').val(email_id);
                $('#forget_code_pass').val(forget_code);
                $('.confirmpass').show();
                $('.emailform').hide();
                $('.codeform').hide();
                }else{
					Swal.fire("Invalid Code ", "Please try again", "warning");
                }  
                
                $('.loader').hide();
              }

         });
        }
      });




	  $(document).on('submit', '#confirm_pass', function(e){
        e.preventDefault();
        var email_id=$('#email_id').val();
        var npass=$('#npass').val();
        var cpass=$('#cpass').val();
        let passData=new FormData($('#confirm_pass')[0]);
        if(npass== "")  
        {  
     swal("New password Field  is Required !", "", "warning");
     }else if(cpass== ""){
      swal("Confirm password Field  is Required !", "", "warning");
     }else{
        $.ajax({
             type:'POST',
             url:'/maintain/confirmpass',
             data:passData,
             contentType: false,
             processData:false,
             beforeSend : function()
              {
               $('.loader').show();
              },
             success:function(response){ 
               // console.log(response);
                if(response.status == 500){
                $('.confirmpass').hide();
                $('.emailform').hide();
                $('.codeform').hide();
                
				setTimeout(myGreeting, 3000);

				   function myGreeting() {
                       location.href='/maintain/login';
                    }
                 Swal.fire("Password Change Successfull", "", "warning");
                }else{
					Swal.fire("New Password & Confirm Password Match ", "Please try again", "warning");
                }  
                
                $('.loader').hide();
              }

         });
        }
      });






	  




   
    });



   




    







</script>
  



	</body>
</html>
