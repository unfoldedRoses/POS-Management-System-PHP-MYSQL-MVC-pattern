<?php

require_once "../../../controllers/sales.controller.php";
require_once "../../../models/sales.model.php";

require_once "../../../controllers/customers.controller.php";
require_once "../../../models/customers.model.php";

require_once "../../../controllers/users.controller.php";
require_once "../../../models/users.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

class printBill{

public $code;

public function getBillPrinting(){

//WE BRING THE INFORMATION OF THE SALE

$itemSale = "code";
$valueSale = $this->code;

$answerSale = ControllerSales::ctrShowSales($itemSale, $valueSale);

$saledate = substr($answerSale["saledate"],0,-8);
$products = json_decode($answerSale["products"], true);
$netPrice = number_format($answerSale["netPrice"],2);
$tax = number_format($answerSale["tax"],2);
$totalPrice = number_format($answerSale["totalPrice"],2);


//TRAEMOS LA INFORMACIÓN DEL Customer

$itemCustomer = "id";
$valueCustomer = $answerSale["idCustomer"];

$answerCustomer = ControllerCustomers::ctrShowCustomers($itemCustomer, $valueCustomer);

//TRAEMOS LA INFORMACIÓN DEL Seller

$itemSeller = "id";
$valueSeller = $answerSale["idSeller"];
// $buyersordernumber=$buyersordernumber["buyersordernumber"];
// $dispatch=$dispatch["dispatch"];
// $deliveryn=$delivery["delivery"];



$answerSeller = ControllerUsers::ctrShowUsers($itemSeller, $valueSeller);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->startPageGroup();

$pdf->AddPage();

// ---------------------------------------------------------

$block1 = <<<EOF

	<table>
		
		<tr>
			
			<td style="width:150px"><img src="images/logo.jpg"></td>

			<td style="background-color:white; width:140px">
				
				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
					NIT: 71.759.963-9

					<br>
					ADDRESS: HI TECH ENTERPRISES                   R.T.O Ground Backside, <br>
					plot No.1054, K.I.D.B.A. Kanbargi Auto Nagar Belgaum- 590015            
					<br>        GSTIN: 29AAHFH1238A1ZS

				</div>

			</td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
					CELLPHONE: 300 786 52 49
					
					<br>
					crcoatingsolutions@gmail.com

					<br>
					Buyers order No:

					<br>
					Order Dispatch through:
					
					
					<br>
					

				</div>
				
			</td>

			

		

			<td style="background-color:white; width:110px; text-align:center; color:red"><br><br>Invoice No.<br>$valueSale</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($block1, false, false, false, false, '');

// ---------------------------------------------------------

$block2 = <<<EOF

	<table>
		
		<tr>
			
			<td style="width:540px"><img src="images/back.jpg"></td>
		
		</tr>

	</table>

	<table style="font-size:10px; padding:5px 10px;">
	
		<tr>
		
			<td style="border: 1px solid #666; background-color:white; width:390px">

				Customer: $answerCustomer[name]

			</td>

			<td style="border: 1px solid #666; background-color:white; width:150px; text-align:right">
			
				Date: $saledate

			</td>

		</tr>

		<tr>
		
			<td style="border: 1px solid #666; background-color:white; width:540px">Seller: $answerSeller[name]</td>

		</tr>

		<tr>
		
		<td style="border-bottom: 1px solid #666; background-color:white; width:540px"></td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($block2, false, false, false, false, '');

// ---------------------------------------------------------

$block3 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>
		
		<td style="border: 1px solid #666; background-color:white; width:200px; text-align:center">Product</td>
		<td style="border: 1px solid #666; background-color:white; width:80px; text-align:center">quantity</td>
		<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">value Unit.</td>
		<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">value Total</td>
		<td style="border: 1px solid #666; background-color:white; width:90px; text-align:center">HSN</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($block3, false, false, false, false, '');

// ---------------------------------------------------------

foreach ($products as $key => $item) {

$itemProduct = "description";
$valueProduct = $item["description"];
$hsn = $item["dispatchthrough"];

$orden = null;

$answerProduct = ControllerProducts::ctrShowProducts($itemProduct, $valueProduct, $orden,$hsn);

$valueUnit = number_format($answerProduct["sellingPrice"], 2);

$totalPrice = number_format($item["totalPrice"], 2);

$totalPrice = (string)$totalPrice ;

$hsn=$answerProduct["dispatchthrough"];





$block4 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>
			
			<td style="border: 1px solid #666; color:#333; background-color:white; width:200px; text-align:center">
				$item[description]
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
				$item[quantity]
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center"> 
				$valueUnit rs
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center"> 
				$totalPrice rs
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center"> 
			$hsn
			</td>


		</tr>

	</table>


EOF;

$pdf->writeHTML($block4, false, false, false, false, '');

}

// ---------------------------------------------------------

$block5 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>

			<td style="color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border-bottom: 1px solid #666; background-color:white; width:100px; text-align:center"></td>

			<td style="border-bottom: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center"></td>

		</tr>
		
		<tr>
		
			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border: 1px solid #666;  background-color:white; width:100px; text-align:center">
				Net:
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				 $netPrice rs
			</td>

		</tr>

		<tr>

			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">
			sgst+cgst	Tax:
			</td>
		
			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				 $tax rs
			</td>

		</tr>

		<tr>
		
			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">
				Total:
			</td>
			
			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
			$totalPrice
			</td>

		</tr>


	</table>

EOF;

$pdf->writeHTML($block5, false, false, false, false, '');

$block6 = <<<EOF
<br>
<br>
<br>

	<table style="border:1px solid black;">
		
		<tr style="border:1px solid black;">
			
		
			<td style="background-color:white; width:270px;border:1px solid black;">
				
				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
					Comapany's PAN: AAKFC3220E

					<br>
					Declaration:
					<br>
					We Declare That this invoice shows the actual Price of the goods
					<br>
					described and that all particular are true and correct
				
				</div>

			</td>

	

			
			<td style="background-color:white; width:270px;border:1px solid black;">

			<div style="font-size:8.5px; text-align:right; line-height:15px;">
			<br>
			Issued Date:
			<br>
				<br>
				Company Bank Details:
				<br>
				Bank Name: KARNATAKA BANK
				
				<br>
				A/c No:0957000300114001
				<br>

				Branch: Malmarr
				<br>
				for CR COATING SOLUTIONS
				<br>
				IFSC CODE:KARB0000095
			</div>
			
		</td>

		</tr>


		


		<tr style="border:1px solid white;">			
		<td style="background-color:white; width:550px;border:1px solid white;">

		<div style="font-size:8.5px; text-align:right; line-height:15px;">
		<br>
		Terms And Conditions
		<br>
			<br>
		1.Intrest @ 2% p.m will be Charged After 15 Days From The Date Of Invoice Issued
			<br>
		2.	Goods Once Sold Cannot Be taken Back Or Exchanged
			
			<br>
		3.	We Are Not responsible For Any Damages After Delivery
			<br>

		
			
		4. SUBJECT TO BELGAVI JURIDICTION
		
		</div>
		
	</td>
				
	
</tr>












	</table>

EOF;

$pdf->writeHTML($block6, false, false, false, false, '');



// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 

$pdf->Output('bill.pdf', 'D');

}

}

$bill = new printBill();
$bill -> code = $_GET["code"];
$bill -> getBillPrinting();

?>










