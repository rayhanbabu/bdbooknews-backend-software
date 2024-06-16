@foreach($data as $row)
           <tr>
                <td> <img src="{{ asset('/uploads/'.$row->image) }}" width="100" class="img-thumbnail" alt="Image"></td>
                <td> {{ $row->category_name }} </td>
                <td> {{ $row->name }} </td>
                <td> {{ $row->serial }} </td>
                <td> {{ $row->menu_ber }} </td>
                <td>
                  @if($row->category_status == 1)
                   <a href="#" class="btn btn-success btn-sm">Active<a>
                  @else
                    <a href="#"  class="btn btn-danger btn-sm"> Inactive<a>
                  @endif
                </td>
               <td> <button type="button" value="{{ $row->id}}" class="btn btn-primary btn-sm editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">Edit</button>  </td>
               <td> <button type="button" value="{{ $row->id}}" class="btn btn-danger btn-sm deleteIcon" >Delete</button>  </td>
                 
            </tr>            
@endforeach
  <tr class="pagin_link">
        <td colspan="4" align="center">
           {!! $data->links() !!}
        </td>
   </tr>  