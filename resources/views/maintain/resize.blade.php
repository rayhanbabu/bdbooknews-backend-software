@extends('maintain.layout')
@section('page_title','Maintain Panel')
@section('dashboard','active')
@section('content')
<div class="grey-bg container-fluid">
  <section id="minimal-statistics">
  <div class="row mt-3 mb-0 mx-2">
         <div class="col-sm-2 my-2"> <h4 class="mt-0"> Dashboard </h4> </div>

         <form action="{{ route('image.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="image">Choose an image:</label>
            <input type="file" name="image" id="image">
        </div>
        <div>
            <button type="submit">Upload Image</button>
        </div>
    </form>


    </div>

    
  </section>
</div>

@endsection 