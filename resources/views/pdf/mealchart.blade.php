@include('pdf/fpdf182/fpdf')
<?php 

class PDF extends FPDF
{
function Header()
{
	
}	
function Footer()
  {
       // Position at 1.5 cm from bottom
     $this->SetY(-15);
       // Arial italic 8
      $this->SetFont('Arial','I',12);
       // Page number
     $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}'.',  Date: '.date('d-M-Y, h:i:sA'),0,0,'C');
  }		
}					  	
	
   $pdf= new PDF('L','mm','A4');
   $pdf->AliasNbPages();     
   $pdf->AddPage();
  
    $pdf->SetFont('Times','I',20);
		$pdf->Cell(279,7, '','LRT',1 , 'L' ); 	
        $pdf->Cell(279,10,manager_info()['hall_name'],'LR',1 , 'C' );

   $pdf->SetFont('Times','I',17);	    
        $pdf->Cell(279,12,'Meal Chart: '.$month1.', Section : '.$section,'LR',1 ,'C' ); 		
    $pdf->SetFont('Times','I',13);
        
	     $pdf->Cell(279,5,' ','LR',1 , 'C' );
         $pdf->Cell(10,5,' ','L',0 , 'C' );
         $pdf->Cell(50,5,' ',0,0 , 'L' );
         $pdf->Cell(50,5,'Active  Invoice  : '.$t_invoice,0,0, 'L' );
		 $pdf->Cell(67,5,'Total Invoice Meal   : '.($t_meal+$refund_meal),0,0 , 'L' );	
         $pdf->Cell(50,5,'Total Meal ON  : '.$t_meal,0,0 , 'L' );
         $pdf->Cell(52,5,'Total Refund Meal :'.$refund_meal ,'R',1 , 'L' );
       
	  
	    $pdf->Cell(10,5,'','L',0,'C');
        $pdf->Cell(75,5,'From Date: '.$meal_start_date,0,0,'L');
        $pdf->Cell(75,5,'',0,0,'L');
        $pdf->Cell(50,5,'',0,0 , 'L' );
        $pdf->Cell(69,5,'','R',1 , 'L' );		 
	 
	    $pdf->Cell(279,2,'','LR',1,'C' );
	 
	    $pdf->SetFont('Times','I',12);	
		$pdf->Cell(15,7,'Invoice',1,0,'C');
		$pdf->Cell(10,7,'Card',1,0,'L');
        $pdf->Cell(17,7,'Reg/Seat',1,0,'L');
		$meal_start= date('d',strtotime($meal_start_date));
	for($x=$meal_start; $x<$meal_start+$cur_day; $x++){
		$pdf->Cell(7,7,$x,1,0 ,'L');
	}

	for($x=1; $x<=31-($cur_day); $x++){
		$pdf->Cell(7,7,'',1,0 ,'L');
	}
	
        $pdf->Cell(10,7,'ON',1,0,'L');
		$pdf->Cell(10,7,'OFF',1,1,'L');
	

	  $pdf->SetFont('Times','I',10);
	  foreach($invoice as $row){
	     $pdf->Cell(15,6,$row['id'],1,0, 'C' );
		 $pdf->Cell(10,6,$row['card'],1,0 , 'L' );
         $pdf->Cell(17,6,$row['registration'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date1'])?"":$row['b1'].$row['l1'].$row['d1'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date2'])?"":$row['b2'].$row['l2'].$row['d2'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date3'])?"":$row['b3'].$row['l3'].$row['d3'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date4'])?"":$row['b4'].$row['l4'].$row['d4'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date5'])?"":$row['b5'].$row['l5'].$row['d5'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date6'])?"":$row['b6'].$row['l6'].$row['d6'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date7'])?"":$row['b7'].$row['l7'].$row['d7'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date8'])?"":$row['b8'].$row['l8'].$row['d8'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date9'])?"":$row['b9'].$row['l9'].$row['d9'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date10'])?"":$row['b10'].$row['l10'].$row['d10'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date11'])?"":$row['b11'].$row['l11'].$row['d11'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date12'])?"":$row['b12'].$row['l12'].$row['d12'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date13'])?"":$row['b13'].$row['l13'].$row['d13'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date14'])?"":$row['b14'].$row['l14'].$row['d14'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date15'])?"":$row['b15'].$row['l15'].$row['d15'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date16'])?"":$row['b16'].$row['l16'].$row['d16'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date17'])?"":$row['b17'].$row['l17'].$row['d17'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date18'])?"":$row['b18'].$row['l18'].$row['d18'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date19'])?"":$row['b19'].$row['l19'].$row['d19'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date20'])?"":$row['b20'].$row['l20'].$row['d20'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date21'])?"":$row['b21'].$row['l21'].$row['d21'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date22'])?"":$row['b22'].$row['l22'].$row['d22'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date23'])?"":$row['b23'].$row['l23'].$row['d23'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date24'])?"":$row['b24'].$row['l24'].$row['d24'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date25'])?"":$row['b25'].$row['l25'].$row['d25'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date26'])?"":$row['b26'].$row['l26'].$row['d26'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date27'])?"":$row['b27'].$row['l27'].$row['d27'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date28'])?"":$row['b28'].$row['l28'].$row['d28'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date29'])?"":$row['b29'].$row['l29'].$row['d29'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date30'])?"":$row['b30'].$row['l30'].$row['d30'],1,0 , 'L' );
		 $pdf->Cell(7,6,empty($row['date31'])?"":$row['b31'].$row['l31'].$row['d31'],1,0 , 'L' );
		 $pdf->Cell(10,6,$row['breakfast_onmeal'].','.$row['lunch_onmeal'].','.$row['dinner_onmeal'],1,0,'L' );
		 $pdf->Cell(10,6,$row['breakfast_offmeal'].','.$row['lunch_offmeal'].','.$row['dinner_offmeal'],1,1,'L' );
		
		
		//date  input 
	
   	  }
	  
		
		
   $pdf->Output($file,'I');
   exit;
   
   

?>