<?php

// parameter
define("SCRIPTVERSION", "1.1");
$cFuel = array('Diesel', 'Benzin');





include "fpdf/fpdf_code128.php";

if (isset($_POST['fm'])) {
	$fm = $_POST['fm'];

	// starting coordinates
	$actx = 20;
	$acty = 50;

	// size of key tag
	$width = 50;
	$height = 30;

	$pdf = new PDF_Code128('P', 'mm', 'A4');
	$pdf->SetAutoPageBreak(False);
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	$pdf->SetDisplayMode('fullwidth', 'single');
	$pdf->Open();

	$pdf->AddPage('P');
	$pdf->SetFont('arial','',10);
	$pdf->SetTextColor(0,0,0);

	// headline
	$pdf->SetXY($actx-2, $acty-30);
	$pdf->SetFont('arial','B',16);
	$pdf->Write(6, utf8_decode("Ihr Schlüsselanhänger:"));

	// key tag
	$pdf->SetFont('arial','',10);
	$pdf->SetXY($actx, $acty);

	// crop marks
	$pdf->SetDrawColor(100,100,100);
	$pdf->Line($actx-3, $acty, $actx+$width+3, $acty);
	$pdf->Line($actx-3, $acty+$height, $actx+$width+3, $acty+$height);
	$pdf->Line($actx, $acty-3, $actx, $acty+$height+3);
	$pdf->Line($actx+$width, $acty-3, $actx+$width, $acty+$height+3);



	// license plate
	$pdf->SetFont('arial','B',17);
	$pdf->SetXY($actx+13, $acty+7);
	$pdf->Cell(0,0,strtoupper($fm['plate']),0,0,"L");

	// ACRISS
	$pdf->SetFont('arial','B',8);
	$pdf->SetXY($actx+2, $acty+13);
	$pdf->Cell(0,0,strtoupper($fm['acriss']),0,0,"L");

	// car type
	$pdf->SetFont('arial','B',8);
	$pdf->SetXY($actx+13, $acty+13);
	$pdf->Cell(0,0,strtoupper(utf8_decode($fm['car'])),0,0,"L");

	// fuel
	$pdf->SetFont('arial','',8);
	$pdf->SetXY($actx+2, $acty+18);
	$pdf->Cell(0,0,$fm['fuel'],0,0,"L");

	// color
	$pdf->SetFont('arial','',8);
	$pdf->SetXY($actx+13, $acty+18);
	$pdf->Cell(0,0,utf8_decode($fm['color']),0,0,"L");

	// unit
	$pdf->SetFont('arial','',8);
	$pdf->SetXY($actx+30, $acty+18);
	$pdf->Cell(0,0,$fm['unit'],0,0,"L");

	// code128 barcode
	$pdf->Code128($actx+3, $acty+21, "EC".strtoupper($fm['plate']), 42, 7);

	// STR
	if (isset($fm['str'])) {
		$pdf->SetFont('arial','',8);
		$pdf->SetXY($actx+2, $acty+4);
		$pdf->Cell(0,0,'STR',0,0,"L");
	}

	// NVS
	if (isset($fm['nvs'])) {
		$pdf->SetFont('arial','',8);
		$pdf->SetXY($actx+2, $acty+7);
		$pdf->Cell(0,0,'NVS',0,0,"L");
	}

	// AHK
	if (isset($fm['ahk'])) {
		$pdf->SetFont('arial','',8);
		$pdf->SetXY($actx+2, $acty+10);
		$pdf->Cell(0,0,'AHK',0,0,"L");
	}

	$pdf->Output();
	die();
}


?>







<!DOCTYPE html>

<html>

<head>
	<title>Europcar key tag generator <?php echo SCRIPTVERSION; ?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/standard.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script>
		function setUppercase(whatfield) {
			whatfield.value = whatfield.value.toUpperCase();
		}
	</script>
</head>

<body>


<div class="container">

	<h1>Europcar key tag generator</h1>

	<table border="0" cellpadding="0" cellspacing="5">
	<form method="post" action="index.php" target="_blank">

		<tr>
			<td align="right">Kennzeichen</td>
			<td><input type="text" maxlength="10" name="fm[plate]" id="plate" class="gross" /></td>
		</tr>
		<tr>
			<td align="right">Fahrzeug-Typ</td>
			<td><input type="text" maxlength="20" name="fm[car]" id="car" style="width:200px;" /></td>
		</tr>
		<tr>
			<td align="right">Unit-No.</td>
			<td><input type="text" maxlength="10" name="fm[unit]" id="unit" style="width:100px;" /></td>
		</tr>
		<tr>
			<td align="right">ACRISS</td>
			<td><input type="text" maxlength="4" name="fm[acriss]" id="acriss" style="width:50px;" /></td>
		</tr>

		<tr><td colspan="2"><hr /></tr>

		<tr>
			<td align="right">Farbe</td>
			<td><input type="text" maxlength="10" name="fm[color]" id="color" /></td>
		</tr>
		<tr>
			<td align="right">Kraftstoff</td>
			<td>
				<select name="fm[fuel]" id="fuel" size="1">
					<?php foreach ($cFuel as $sFuel) { ?>
						<option value="<?php echo $sFuel; ?>"><?php echo $sFuel; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">STR</td>
			<td><input type="checkbox" value="1" name="fm[str]" id="str" style="width:20px;" /></td>
		</tr>
		<tr>
			<td align="right">NVS</td>
			<td><input type="checkbox" value="1" name="fm[nvs]" id="nvs" style="width:20px;" /></td>
		</tr>
		<tr>
			<td align="right">AHK</td>
			<td><input type="checkbox" value="1" name="fm[ahk]" id="ahk" style="width:20px;" /></td>
		</tr>

		<tr><td colspan="2"><hr /></tr>

		<tr>
			<td colspan="2">
				<input type="reset" value="löschen" style="float:left; width:80px; cursor:pointer;" />
				<input type="submit" value="Einleger generieren" style="float:right;" />
			</td>
		</tr>

	</form>
	</table>


</div>

</body>
</html>
