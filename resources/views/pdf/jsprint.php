<!DOCTYPE html>
<html>

<head>
  <script>
    function printContent(e1) {
      var restorepage = document.body.innerHTML;
      var printcontent = document.getElementById(e1).innerHTML;
      document.body.innerHTML = printcontent;
      window.print();
      document.body.innerHTML = restorepage;
    }
  </script>
  <title> </title>

  <style>
    @media print {
      @page {
        margin: 0;
      }

      body {
        margin: 1.6cm;
      }
    }

    table,
    td,
    th {
      border: 2px solid #acacac;

    }

    table {
      border-collapse: collapse;
    }

    th,
    td {
      padding: 2px;
      font-size: 14px;
    }

    .area {

      width: 700px;
    }

    .btn {
      border: none;
      color: white;
      padding: 14px 28px;
      font-size: 14px;
      cursor: pointer;
    }

    .success {
      background-color: #4CAF50;
    }

    /* Green */
    .success:hover {
      background-color: #46a049;
    }
  </style>

</head>

<body>
  <center>
    <br>
    <button class="btn success" onclick="printContent('div1')">Print </button>
      <div id="div1">

 

         <div class="area">
               <center>
                 <h4> {{ manager_info()['hall_name'] }} <br>
                       @if($section) {{$month1}} - {{$section}} @else  @endif</h4>
               </center>
                  
                          @php 
                               $sum1=0;
                               $sum2=0;   
                          @endphp

                         
                
                 <h4>Monthly 1st payment Summary </h4>
                 <table>
                        <tr>
                            <th align="left" width="100">Day</th>
                            <th align="left" width="150">No of Payment</th>
                            <th align="right" width="200">Payment Amount</th>
                        </tr>

                        @foreach($payment1 as $user)
                               @php
                                   $sum1=$sum1+$user->payble_amount1;
                               @endphp
                          <tr>
                              <td align="left">{{ $user->payment_day }}</td>
                              <td align="left">{{ $user->id_total }}</td>
                              <td align="right">{{ $user->payble_amount1 }}TK</td>
                           </tr>
                         @endforeach

                          <tr>
                                <td> </td>
                                <td></td>
                                <td align="right">Total Payment:{{$sum1}} TK</td>
                          </tr>
                    </table>


                    <h4>Monthly 2nd payment Summary </h4>
                 <table>
                        <tr>
                            <th align="left" width="100">Day</th>
                            <th align="left" width="150">No of Payment</th>
                            <th align="right" width="200">Payment Amount</th>
                        </tr>
                        
                          
                         @foreach($payment2 as $user)
                               @php
                                   $sum2=$sum2+$user->payble_amount2;
                               @endphp
                            <tr>
                                <td align="left">{{ $user->payment_day }}</td>
                                <td align="left">{{ $user->id_total }}</td>
                                <td align="right">{{ $user->payble_amount2 }}TK</td>
                            </tr>
                          @endforeach

                          <tr>
                                <td> </td>
                                <td></td>
                                <td align="right">Total Payment:{{$sum2}}TK</td>
                          </tr>
                    </table>


                    <h4>Monthly total payment : {{$sum1+$sum2}}TK </h4>

              

    


           </div>


       </div>
    <br>

  </center>



</body>

</html>