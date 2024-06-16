
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
	  <meta name="csrf-token" content="{{ csrf_token() }}">

   <style>
      .verifyform{
         display:none;
      }	

</style>

	</head>
	<script>
	    function showpass()
		{
		   var pass = document.getElementById('pass');
		   if(document.getElementById('check').checked)
		   {
		     pass.setAttribute('type','text');
		   }
		   else{
		     pass.setAttribute('type','password'); 
		   }
		}
	 </script>
	<body>
	<section class="ftco-section">


	<div class="loginform"> 
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
				<h5></h5>
		      	<h3 class="text-center mb-4"> Ancova Maintain Login </h3>
				  <form method="post"  id="email_form"  class="myform"  enctype="multipart/form-data" >


		      		 <div class="form-group">
		      		   	<input type="text" class="form-control rounded-left" autocomplete="off" id="phone"  name="phone" placeholder="Phone Number" >
							 <p class="text-danger error_phone"> </p>
		      		 </div>
	           
				   <div class="form-group ">
	                 <input type="password" class="form-control rounded-left" id="pass"  name="password" placeholder="Password" >
				        <small>  <input type="checkbox" id="check" onclick="showpass();"/>Show Password</small> 
						<p class="text-danger error_password"> </p>
	                </div>
				
				 
	            <div class="form-group">
			     	<button type="submit" id="add_employee_btn" class="form-control btn btn-primary rounded submit px-3">Submit </button>	
	            </div>
				 
				
	            <div class="form-group d-md-flex">
	            	<div class="w-50">
	            		
								</div>
								<div class="w-50 text-md-right">
									<a href="{{url('maintain/forget')}}">Forgot Password</a>
								</div>
	               </div>
	            </form>
	         </div>
				</div>
			</div>
		 </div>
	</div>	


	<div class="verifyform"> 
		<div class="container">
		    <div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
				   <div class="login-wrap p-4 p-md-5">
				 <h5></h5>
		    <h3 class="text-center mb-4"> Send OTP Your E-mail </h3>
		     	<form method="post"  id="verify_form"  class="myform"  enctype="multipart/form-data" >
		      		 <div class="form-group">
		      		   	<input type="text" class="form-control rounded-left" autocomplete="off" id="otp"  name="otp" placeholder="Enter OTP">
							 <p class="text-danger error_otp"></p>
		      		 </div>
					   <input type="hidden" id="verify_phone"  name="verify_phone">
					   <input type="hidden" id="verify_email"  name="verify_email">
				
	            <div class="form-group">
				   <button type="submit" id="add_verify_btn" class="form-control btn btn-primary rounded submit px-3">Submit </button>	
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
        let emailData=new FormData($('#email_form')[0]);

		$.ajax({
             type:'POST',
             url:'/maintain/login-insert',
             data:emailData,
             contentType: false,
             processData:false,
             beforeSend : function()
               {
                  $("#add_employee_btn").prop('disabled',true);
               },
              success:function(response){ 
                  //console.log(response);
			     $("#add_employee_btn").prop('disabled', false);
				    if(response.status == 200){
					    	$('#verify_email').val(response.email);
						    $('#verify_phone').val(response.phone);
						    $('.error_phone').text("");
						    $('.error_password').text("");
							$('.loginform').hide();
							$('.verifyform').show();
				    }else if(response.status == 300){
					     $('.error_phone').text(response.message);
				    }else if(response.status == 400){
					     $('.error_password').text(response.message);
				    }else if(response.status == 600){
					     $('.error_password').text(response.message);
				    }else if(response.status == 700){
					     $('.error_phone').text(response.validate_err.phone);
						 $('.error_password').text(response.validate_err.password);
						 $('.error_password').text(response.validate_err.category);
				     }
              }
          });
	});



	$(document).on('submit', '#verify_form', function(e){
        e.preventDefault();
        let verifyData=new FormData($('#verify_form')[0]);

		$.ajax({
             type:'POST',
             url:'/maintain/login-verify',
             data:verifyData,
             contentType: false,
             processData:false,
             beforeSend : function()
              {
                  $("#add_verify_btn").prop('disabled',true);
              },
             success:function(response){ 
                  //console.log(response);
			     $("#add_verify_btn").prop('disabled', false);
				      if(response.status == 200){
						    $('.error_otp').text("");
							location.href='/maintain/dashboard';
				        }else if(response.status == 300){
					         $('.error_otp').text(response.message);
                        }else if(response.status == 700){
					         $('.error_otp').text(response.message.otp);
				        }
				}
          });
	});




	  




   
    });



   




    







</script>
  



	</body>
</html>
