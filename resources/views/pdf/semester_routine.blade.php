<!DOCTYPE html>
<html>

<head>
    <style>
        table,
        td,
        th {
            border: 1px solid #acacac;
            *text-align: left;
        }

        table {
            border-collapse: collapse;
            *width: 100%;
        }

        th,
        td {
            padding: 0px;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <center>
        <h5> Department of Internation Business <br>
             Executive MBA Programe <br>
             University Of Dhaka <br>
             CLASS ROUTINE OF 01-2024 <br>
             Eactive From : 2024-01-01
        </h5>
    </center>

    <table>

        <tr>
            <th align="left" width="50">Day</th>
            <th>
                <table>
                    <tr>
                        <th align="left" width="60">Course No</th>
                        <th align="left" width="180">Course Title</th>
                        <th align="center" width="40">Section</th>
                        <th align="center" width="60">Time</th>
                        <th align="center" width="50">Teacher</th>
                        <th align="center" width="50">Room No</th>
                    </tr>
                </table>
            </th>
        </tr>

     @foreach($data as $row)
        <tr>
            <td align="left"> {{week_details($row->class_date)->week}}</td>
            <td align="left">
            <table>
                @foreach(semester_details($row->dept_id,$row->class_date,$row->year_id,$row->programe_id,$row->semester_id) as $item)      
                     <tr>
                         <td align="left"  width="60">{{$item->course_code}} </td>
                         <td align="left"  width="180"> {{$item->course_name}} </td>
                         <td align="center"  width="40"> {{$item->section_name}}</td>
                         <td align="center"  width="60"> {{$item->class_time}}</td>
                         <td align="center"  width="50"> {{$item->nickname}}</td>
                         <td align="center" width="50"> {{$item->class_room}}</td>
                     </tr>
                @endforeach    
                   
                </table>
            </td>
        </tr>
    @endforeach

    </table>

</body>

</html>