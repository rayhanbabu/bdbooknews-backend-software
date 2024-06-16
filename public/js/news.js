$(document).ready(function (){

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });


    
    fetch();
    function fetch(){
       $.ajax({
        type:'GET',
        url:'/admin/news_fetch',
        datType:'json',
        success:function(response){
               $('tbody').html('');
              $('.x_content tbody').html(response);
         }
        });
     }



   

function fetch_data(page, sort_type="", sort_by="", search="",range=""){
   $.ajax({
   url:"/admin/news/fetch_data/?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&search="+search+"&range="+range,
   success:function(data)
   {
   $('tbody').html('');
   $('.x_content tbody').html(data);
   }
   });
    }

  
   $(document).on('keyup', '#search', function(){
      var search = $('#search').val();
      var column_name = $('#hidden_column_name').val();
      var sort_type = $('#hidden_sort_type').val();
      var page = $('#hidden_page').val();
      var range = $('#range').val();
      fetch_data(page, sort_type, column_name, search,range);
   });


   $(document).on('click', '.pagin_link a', function(event){
       event.preventDefault();
       var page = $(this).attr('href').split('page=')[1];
       var column_name = $('#hidden_column_name').val();
       var sort_type = $('#hidden_sort_type').val();
       var search = $('#search').val();
       var range = $('#range').val();
      fetch_data(page, sort_type, column_name, search,range);
   }); 


   $(document).on('click', '.sorting', function(){
     var column_name = $(this).data('column_name');
     var order_type = $(this).data('sorting_type');
     var reverse_order = '';
       if(order_type == 'asc')
        {
       $(this).data('sorting_type', 'desc');
       reverse_order = 'desc';
       $('#'+column_name+'_icon').html('<i class="fas fa-sort-amount-down"></i>');
        }
       else
       {
       $(this).data('sorting_type', 'asc');
       reverse_order = 'asc';
       $('#'+column_name+'_icon').html('<i class="fas fa-sort-amount-up-alt"></i>');
       }
      $('#hidden_column_name').val(column_name);
      $('#hidden_sort_type').val(reverse_order);
      var page = $('#hidden_page').val();
      var search = $('#search').val();
      var range = $('#range').val();
     fetch_data(page, reverse_order, column_name, search,range);
     });

     $(document).on('change', '#range', function(){
        var search = $('#search').val();
        var column_name = $('#hidden_column_name').val();
        var sort_type = $('#hidden_sort_type').val();
        var page = $('#hidden_page').val();
        var range = $('#range').val();
        fetch_data(page, sort_type, column_name, search,range);
      });





    $('#category_name_id').on('change', function () {
       var nameId = this.value;
         $('#subcategory_name_id').html('');
       $.ajax({
           url:'/category_id_fetch?category_id='+nameId,
           type:'get',
           success: function (res) {
               $('#subcategory_name_id').html('<option value="" selected disabled> Select Category</option>');
                $.each(res, function (key, value) {
                    $('#subcategory_name_id').append('<option  value="' + value
                       .subcategory_name + '">' + value.sub_name + '</option>');
              });
          }
      });
  });

  $('#division_id').on('change', function () {
    var nameId = this.value;
      $('#district_id').html('');
    $.ajax({
        url:'/district_id_fetch?division_id='+nameId,
        type:'get',
        success: function (res) {
            $('#district_id').html('<option value="" selected disabled> Select District </option>');
             $.each(res, function (key, value) {
                 $('#district_id').append('<option  value="' + value
                    .id + '">' + value.bn_name + '</option>');
           });
       }
   });
});


$('#district_id').on('change', function () {
    var nameId = this.value;
      $('#upazila_id').html('');
    $.ajax({
        url:'/upazila_id_fetch?district_id='+nameId,
        type:'get',
        success: function (res) {
            $('#upazila_id').html('<option value="" selected disabled> Select District </option>');
             $.each(res, function (key, value) {
                 $('#upazila_id').append('<option  value="' + value
                    .id + '">' + value.bn_name + '</option>');
           });
       }
   });
});


});


$('#summernote').summernote({
placeholder: 'Description...',
tabsize: 2,
height: 60
});

$('#summernote1').summernote({
placeholder: 'Description...',
tabsize: 2,
height: 130
});

$('#summernote2').summernote({
placeholder: 'Description...',
tabsize: 2,
height: 130
});