@foreach($data as $row)
           <tr>
                  <td>  {{ $row->id}}</td>
                  <td>  {{ $row->university}}</td>
                  <td>  {{ $row->dept_name}}</td>
                  <td>  {{ $row->dept_address}}</td>
                  <td>  {{ $row->phone}}</td>
                  <td>  {{ $row->email}}</td>
                  <td>  {{ $row->password}}</td>  
                  <td>  {{ $row->login_code}}</td>  
           <td>
            @if($row->teacher_status == 1)
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