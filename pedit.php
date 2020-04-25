<?php
	require_once('includes/load.php');


	$id = $_SESSION['user_id'];
  	$user = find_by_id('users', $id);
  	$dtt = 0;
  	$plantilla = '';
  	$colorturno = '';
		//****************ESTA CONDICION VERIFICA QUE LOS DATOS NECESARIOS HAYAN SIDO ENVIADOS****************
	if(isset($_GET['fecha'])){
		//******************************************DEFINE VARIABLES******************************************
			$f = $_GET['fecha']	;
			$m = $_GET['maqui']	;
			$t = $_GET['tur']	;
  			

		//*******************CONSULTA PARA OBTENRE TODOS LOS DATOS DE LA TABLA h_produccion*******************
		

		$consul = $db->query("SELECT * FROM `h_produccion` where `maquina` = '$m'");

		//************************SABER QUE DIA CORRESPONDE A L4A FECHA QUE SE INGRESO************************
		$cons_dia = $db->query("SELECT CONCAT(ELT(WEEKDAY('$f') + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS dia");

		$dia = mysqli_fetch_row($cons_dia);//GUARDAMOS LOS RESULTADOS EN UN ARRAY

		

		
		
		if($db->num_rows($consul)==0){
			$session->msg("d","No hay datos para mostrar");
			redirect('fecha_prod.php');
		}

		if ($_GET['tur']=='17') {
			if ($dia[0] == 'Viernes' || $dia[0] == 'Sabado' || $dia[0] == 'Domingo') {
				$session->msg("d","La fecha no coincide con el turno");
				redirect('fecha_prod.php');
			}
		}
		else if($_GET['tur']=='18'){
			if ($dia[0] == 'Lunes' || $dia[0] == 'Martes' || $dia[0] == 'Miercoles') {
				$session->msg("d","La fecha no coincide con el turno");
				redirect('fecha_prod.php');
			}
		}
		else if($_GET['tur']=='19'){
			if ($dia[0] == 'Viernes' || $dia[0] == 'Sabado' || $dia[0] == 'Domingo') {
				$session->msg("d","La fecha no coincide con el turno");
				redirect('fecha_prod.php');
			}
		}
		else if($_GET['tur']=='20'){
			if ($dia[0] == 'Lunes' || $dia[0] == 'Martes' || $dia[0] == 'Miercoles') {
				$session->msg("d","La fecha no coincide con el turno");
				redirect('fecha_prod.php');
			}
		}


		if ($dia[0] == 'Jueves') {//verificamos si la fecha que ingresaron corresponde al dia JUEVES

			if ($_GET['tur']=='17') {

				$pedidos= $db->query("SELECT * FROM h_produccion WHERE h_inicio BETWEEN '".$f." 06:00:00' AND '".$f." 12:30:00' AND maquina= '".$m."'");

				$res = $db->query("select TIMESTAMPDIFF(SECOND, '".$f." 06:00:00', `h_inicio`) as `segundos`, `letra`, `comentario` from `h_produccion` WHERE `maquina`= '".$m."' AND h_inicio BETWEEN '".$f." 06:00:00' AND '".$f." 12:30:00' ORDER BY `segundos` DESC");

				$num_parte = $db->query("SELECT ppd.id as `#`, ppd.componente as `numero`, ppd.maquina as `maquina`, ppd.piezas as `cantidad`, componentes.t_ciclo as `tiempo` FROM `ppd` INNER JOIN `componentes` ON componentes.name = ppd.componente WHERE ppd.fecha BETWEEN '".$f." 06:00:00' AND '".$f." 12:30:00' AND ppd.maquina = '".$m."'");

				$num_parte_distinct = $db->query("SELECT distinct(ppd.componente) as `#` FROM `ppd` WHERE ppd.fecha BETWEEN '".$f." 06:00:00' AND '".$f." 12:30:00' AND ppd.maquina = '".$m."'");

				$plantilla = 'hojaproduccion.xlsx'; //DEFINE LAPLANTILLA DE EXCEL QUE USARA EL TURNO
				$colorturno = '0087FF';
			}
			else if($_GET['tur']=='18'){

				$pedidos= $db->query("SELECT * FROM h_produccion WHERE h_inicio BETWEEN '".$f." 12:30:00' AND '".$f." 18:00:00' AND maquina= '".$m."'");

				$res = $db->query("select TIMESTAMPDIFF(SECOND, '".$f." 12:30:00', `h_inicio`) as `segundos`, `letra`, `comentario` from `h_produccion` WHERE `maquina`= '".$m."' AND h_inicio BETWEEN '".$f." 12:30:00' AND '".$f." 18:00:00' ORDER BY `segundos` DESC");

				$num_parte = $db->query("SELECT ppd.id as `#`, ppd.componente as `numero`, ppd.maquina as `maquina`, ppd.piezas as `cantidad`, componentes.t_ciclo as `tiempo` FROM `ppd` INNER JOIN `componentes` ON componentes.name = ppd.componente WHERE ppd.fecha BETWEEN '".$f." 12:30:00' AND '".$f." 18:00:00' AND ppd.maquina = '".$m."'");

				$num_parte_distinct = $db->query("SELECT distinct(ppd.componente) as `#` FROM `ppd` WHERE ppd.fecha BETWEEN '".$f." 12:30:00' AND '".$f." 18:00:00' AND ppd.maquina = '".$m."'");

				$plantilla = 'hojaproduccion.xlsx'; //DEFINE LAPLANTILLA DE EXCEL QUE USARA EL TURNO
				$colorturno = 'FF2D00';
			}
			else if($_GET['tur']=='19'){

				$pedidos= $db->query("SELECT * FROM h_produccion WHERE h_inicio BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 00:30:00', INTERVAL 1 DAY ) AND maquina= '".$m."'");

				$res = $db->query("select TIMESTAMPDIFF(SECOND, '".$f." 18:00:00', `h_inicio`) as `segundos`, `letra`, `comentario` from `h_produccion` WHERE `maquina`= '".$m."' AND h_inicio BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 00:30:00', INTERVAL 1 DAY ) ORDER BY `segundos` DESC");


				$num_parte = $db->query("SELECT ppd.id as `#`, ppd.componente as `numero`, ppd.maquina as `maquina`, ppd.piezas as `cantidad`, componentes.t_ciclo as `tiempo` FROM `ppd` INNER JOIN `componentes` ON componentes.name = ppd.componente WHERE ppd.fecha BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 00:30:00', INTERVAL 1 DAY ) AND ppd.maquina= '".$m."'");

				$num_parte_distinct = $db->query("SELECT distinct(ppd.componente) as `#` FROM `ppd` WHERE ppd.fecha BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 00:30:00', INTERVAL 1 DAY ) AND ppd.maquina= '".$m."'");
				
				$plantilla = 'hojaproduccionV.xlsx'; //DEFINE LAPLANTILLA DE EXCEL QUE USARA EL TURNO
				$colorturno = '000000';
			}
			else if($_GET['tur']=='20'){

				$pedidos= $db->query("SELECT * FROM h_produccion WHERE h_inicio BETWEEN '".$f." 00:30:00' AND '".$f." 06:00:00' AND maquina= '".$m."'");

				$res = $db->query("select TIMESTAMPDIFF(SECOND, '".$f." 00:30:00', `h_inicio`) as `segundos`, `letra`, `comentario` from `h_produccion` WHERE `maquina`= '".$m."' AND h_inicio BETWEEN '".$f." 00:30:00' AND '".$f." 06:00:00' ORDER BY `segundos` DESC");

				$num_parte = $db->query("SELECT ppd.id as `#`, ppd.componente as `Numero de parte`, ppd.maquina as `maquina`, ppd.piezas as `cantidad`, componentes.t_ciclo as `tiempo de cilo` FROM `ppd` INNER JOIN `componentes` ON componentes.name = ppd.componente WHERE ppd.fecha BETWEEN '".$f." 00:30:00' AND '".$f." 06:00:00' AND ppd.maquina= '".$m."'");

				$num_parte_distinct = $db->query("SELECT distinct(ppd.componente) as `#` FROM `ppd` WHERE ppd.fecha BETWEEN '".$f." 00:30:00' AND '".$f." 06:00:00' AND ppd.maquina= '".$m."'");

				$plantilla = 'hojaproduccionV.xlsx'; //DEFINE LAPLANTILLA DE EXCEL QUE USARA EL TURNO
				$colorturno = '3EFF00';
			}
			else{
				$session->msg("d","Selecciona un turno");
				redirect('fecha_prod.php');
			}
			$dtt = 6.5;
		}
		else{


			

			if ($_GET['tur']=='17' || $_GET['tur']=='18') {

				$pedidos= $db->query("SELECT * FROM h_produccion WHERE h_inicio BETWEEN '".$f." 06:00:00' AND '".$f." 18:00:00' AND maquina= '".$m."'");

				$res = $db->query("select TIMESTAMPDIFF(SECOND, '".$f." 06:00:00', `h_inicio`) as `segundos`, `letra`, `comentario` from `h_produccion` WHERE `maquina`= '".$m."' AND h_inicio BETWEEN '".$f." 06:00:00' AND '".$f." 18:00:00' ORDER BY `segundos` DESC");

				$num_parte = $db->query("SELECT ppd.id as `#`, ppd.componente as `numero`, ppd.maquina as `maquina`, ppd.piezas as `cantidad`, componentes.t_ciclo as `tiempo` FROM `ppd` INNER JOIN `componentes` ON componentes.name = ppd.componente WHERE ppd.fecha BETWEEN '".$f." 06:00:00' AND '".$f." 18:00:00' AND ppd.maquina = '".$m."'");

				$num_parte_distinct = $db->query("SELECT distinct(ppd.componente) as `#` FROM `ppd` WHERE ppd.fecha BETWEEN '".$f." 06:00:00' AND '".$f." 18:00:00' AND ppd.maquina = '".$m."'");

				$plantilla = 'hojaproduccion.xlsx';

				$_GET['tur'] == ('17') ? $colorturno = '0087FF' : $colorturno = 'FF2D00' ;
			}
			else if($_GET['tur']=='19' || $_GET['tur']=='20'){

				$pedidos= $db->query("SELECT * FROM h_produccion WHERE h_inicio BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 06:00:00', INTERVAL 1 DAY )  AND maquina= '".$m."'");

				$res = $db->query("select TIMESTAMPDIFF(SECOND, '".$f." 18:00:00', `h_inicio`) as `segundos`, `letra`, `comentario` from `h_produccion` WHERE `maquina`= '".$m."' AND h_inicio BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 06:00:00', INTERVAL 1 DAY ) ORDER BY `segundos` DESC");

				$num_parte = $db->query("SELECT ppd.id as `#`, ppd.componente as `numero`, ppd.maquina as `maquina`, ppd.piezas as `cantidad`, componentes.t_ciclo as `tiempo` FROM `ppd` INNER JOIN `componentes` ON componentes.name = ppd.componente WHERE ppd.fecha BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 06:00:00', INTERVAL 1 DAY ) AND ppd.maquina= '".$m."'");

				$num_parte_distinct = $db->query("SELECT distinct(ppd.componente) as `#` FROM `ppd` WHERE ppd.fecha BETWEEN '".$f." 18:00:00' AND ADDDATE('".$f." 06:00:00', INTERVAL 1 DAY ) AND ppd.maquina= '".$m."'");

					$plantilla = 'hojaproduccionV.xlsx';

					$_GET['tur'] == ('19') ? $colorturno = '000000' : $colorturno = '3EFF00' ;
			}
			else{
				$session->msg("d","Selecciona un turno");
				redirect('fecha_prod.php');
			}
			$dtt = 12;
		}
		//*******************************CONSULTA DE PRUEBA PARA CREAR ALGORITMO*******************************
			// $pedidos= $db->query("SELECT * FROM `prueba` WHERE fecha BETWEEN '2019-06-05 06:00:00' AND '2019-06-05 18:00:00' AND maquina= 'H001'");

		//******VERIFICA QUE LA CONSULTA GENERE RESULTADOS, ESTO PARA EVITAR QUE DESCARGUE DOCUMENTOS INNECESARIOS******
		if($db->num_rows($pedidos) != '0')
		{
			$session->msg("d","No se encontraron datos con la fecha que palanteo");
    		redirect('fecha_prod.php');
		}
		else
		{
		//*********AQUI EMPIEZA LA EDICION DE LA PLANTILLA PARA PLASMAR LA INFORMACION EN EL DOCUMENTO*********
			require_once('Classes/PHPExcel/IOFactory.php');

			date_default_timezone_set('UTC');

			$obExcel = new PHPExcel();

			$obExcel = PHPExcel_IOFactory::createReader('Excel2007');

			$obPHPExcel = $obExcel->load('plantillas/'.$plantilla);

			$obPHPExcel->setActiveSheetIndex(0);

		//*********************************FUNCIONES PARA COLOREAR LOS BORDES*********************************

			function borderRight($cells, $colort){
				global $obPHPExcel;

				$obPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray(array(
					'borders' => array(
						'right' => array(
							'style' => PHPExcel_Style_Border::BORDER_THICK,
								'color' => array(
									'rgb' => $colort
								),
							)
					)
				)
			);
			}
			function borderBottom($cells, $colort){
				global $obPHPExcel;

				$obPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray(array(
					'borders' => array(
						'bottom' => array(
							'style' => PHPExcel_Style_Border::BORDER_THICK,
								'color' => array(
									'rgb' => $colort
								),
							)
					)
				)
			);
			}
			function borderTop($cells, $colort){
				global $obPHPExcel;

				$obPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray(array(
					'borders' => array(
						'top' => array(
							'style' => PHPExcel_Style_Border::BORDER_THICK,
								'color' => array(
									'rgb' => $colort
								),
							)
					)
				)
			);
			}

			function cellcolor($cells,$color){
    			global $obPHPExcel;

				$obPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
    				'type' => PHPExcel_Style_Fill::FILL_SOLID,
        				'startcolor' => array(
            				'rgb' => $color
        				)
    			));
			}
		//**************************************************ALGORITMO**************************************************
			

		//*************************ARREGLOS DECESARIOS PARA FUNCIONAMIENTO DEL ALGORITMO*************************
			$a = array('C' => 'B','D' => 'D','E' => 'F','F' => 'H','G' => 'J','H' => 'L','I' => 'N','J' => 'P');

			$pos = array('B' => '1','D' => '2','F' => '3','H' => '4','J' => '5','L' => '6','N' => '7','P' => '8');

			$cambio = array('B' => 'C','D' => 'E','F' => 'G','H' => 'I','J' => 'K','L' => 'M','N' => 'O','P' => 'Q');

			$volver = array('C' => 'B','E' => 'D','G' => 'F','I' => 'H','K' => 'J','M' => 'L','O' => 'N','Q' => 'P');

			$cambio2 = array('B' => 'B','D' => 'D','F' => 'F','H' => 'H','J' => 'J','L' => 'L','N' => 'N','P' => 'P');

			$comentario = array('C' => 'P0.- ', 'D' => 'CP.- ', 'E' => 'P1.- ', 'F' => 'TR.- ', 'G' => 'EmE.- ', 'H' => 'MnD.- ', 'I' => 'PnD.- ', 'J' => 'PP.- ');

			$tiempos = array('C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0, 'I' => 0, 'J' => 0);

			$letrasDB = array( 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');


			//$res = $db->query("select TIMESTAMPDIFF(SECOND, '".$f." 06:00:00', `h_inicio`) as `segundos`, `letra`, `comentario` from `h_produccion` WHERE `maquina`= '".$m."' AND h_inicio BETWEEN '".$f." 06:00:00' AND '".$f." 18:00:00' ORDER BY `segundos` DESC");
			$valorinicial = 43200;
			$cantcel = 147;
			$celvalidas = 144;
			$lado1 = "";
			$lado2 = "";
			$vari = 0;
			$num_com = 1;

			foreach ($res as $m) {
				$l = $m['letra'];
				$k = $m['segundos'];
				$p = ($celvalidas*300) - $k;
				$v = $p/300;
				$tiempos[$l] = $tiempos[$l] + $v;//SUMA LOS VALORES EN SEGUNDOS DE LOS SUCESOS



				if($vari == 1){
					if($lett > $pos[$l]){//VERIFICA QUE LA POSICION DE LA LETRA ANTERIOR SEA MAS GRANDE QUE LA NUEVO
						$int = $a[$l];
						$lado2 = $cambio[$int].$cantcel;
						if($lado2 < $lad){//VERIFICA QUE CELDA DEFINIDA ES LA MAS GRANDE PARA ALTERNAR LA POSICION
							borderBottom($lado2.":".$lad, $colorturno); //a la izquierda <--
						 $obPHPExcel->getActiveSheet()->SetCellValue('R'.$cantcel,$comentario[$l].$m['comentario']);
						}else{
							borderBottom($lad.":".$lado2, $colorturno); //a la izquierda <--
						 $obPHPExcel->getActiveSheet()->SetCellValue('R'.$cantcel,$comentario[$l].$m['comentario']);
						}
					}
					else {
						$lado1 = $a[$l].$cantcel;
						if($lado2 < $lado1){//VERIFICA QUE CELDA DEFINIDA ES LA MAS GRANDE PARA ALTERNAR LA POSICION
							borderBottom($lado2.":".$lado1, $colorturno);
						 	$obPHPExcel->getActiveSheet()->SetCellValue('R'.$cantcel,$comentario[$l].$m['comentario']);
						}else{
							$original = $a[$l];
							$letcam = $cambio[$original];
							$lado1 = $letcam.$cantcel;


							$original2 = $cambio[$nuevalet];
							$letcam2 = $volver[$original2];
							$lado2 = $letcam2.$cantcel;
							borderBottom($lado1.":".$lado2, $colorturno);
						 	$obPHPExcel->getActiveSheet()->SetCellValue('R'.$cantcel,$comentario[$l].$m['comentario']);
						}
						
					}
					
				}
				

				for ($i=$v; $i > 0 ; $i--) { 
					borderRight($a[$l].$cantcel, $colorturno);
					$cantcel--;
					$celvalidas--;
					$nuevalet = $a[$l];
					$lado1 = $cambio[$nuevalet].$cantcel;
					$lad = $a[$l].$cantcel;
					$lado2 = $cambio[$nuevalet].$cantcel;
					$lett = $pos[$l];
				}

				
				//$valorinicial = $k;	
				$vari=1;
			}

			for ($o='C'; $o < 'K'; $o++) { //TRANSFORMA LOS TIEMPOS EN SEGUNDOS DE LOS SUCESOS A HORAS
				$tiempos[$o] = (($tiempos[$o]*5)/60); 	
			}
			$secciones = array('C' => 'P150','D' => 'D148','E' => 'F148','F' => 'H148','G' => 'J148','H' => 'L148','I' => 'N148','J' => 'P148');
			$NU = 0;
			for ($r='C'; $r < 'K'; $r++) { 
				$obPHPExcel->getActiveSheet()->SetCellValue($secciones[$r], $tiempos[$r]);
				$NU = $NU + $tiempos[$r];
			}
			$NU = $NU - $tiempos['C'];
			$obPHPExcel->getActiveSheet()->SetCellValue('D2', $f);//FECHA
			$obPHPExcel->getActiveSheet()->SetCellValue('I2', $t);//TURNO
			$obPHPExcel->getActiveSheet()->SetCellValue('N2', $user['name']);//EMPLEADO QUE SOLICITO REPORTE
			$obPHPExcel->getActiveSheet()->SetCellValue('Q152', $dtt);//DURACION TOTAL DE TURNO


			$ac = 'A151';
			$dc = 'D151';
			$hc = 'H151';

			foreach ($num_parte as $k) {
				$d = $k['numero'];
				$a = $k['tiempo'];
				$b = $k['cantidad'];
 
				$cantidad[$d] =  $cantidad[$d] + $b;
				$tciclo[$d] = $a;
			}

			foreach ($num_parte_distinct as $u):
				$code = $u['#'];
				$tcl = $db->query("select `cavidades` from `maquina_componente` where `componente` = '$code'");
				$reslutl = mysqli_fetch_row($tcl);

				$newtc = $tciclo[$code] / $reslutl[0];
				$obPHPExcel->getActiveSheet()->SetCellValue($ac, $u['#']);//NUMERO DE PARTE
				$obPHPExcel->getActiveSheet()->SetCellValue($dc, $cantidad[$code]);//PARTES BUENAS
				$obPHPExcel->getActiveSheet()->SetCellValue($hc, $newtc);//TIEMPO DE CICLO
				$dc++;
				$ac++;
				$hc++;
			endforeach;



		//******************************LLAMADO A LA FUNCCION PARA PONER COLOR A LA CELDA******************************
			// cellcolor("C4:C148", 'FF2D00');
		//$obPHPExcel->getActiveSheet()->SetCellValue('B4', 'FUNCIONA');

		//***********************UNA VEZ REALIZADA LA EDICION, GENERA UN DOCUMENTO PARA DESCARGAR***********************
			$nombredoc = 'HP_'.$f.'_'.$t.'_'.$user['name'];
			header('Content-type: application/vnd.ms-excel'); 
			header('Content-Disposition: attachment; filename=".'.$nombredoc.'.xlsx"');
			$obWrite = PHPExcel_IOFactory::createWriter($obPHPExcel, 'Excel2007');
			$obWrite->save("php://output");

		}
	}
	else{
		$session->msg("d","No se enviaron los parametros necesarios");
		//IMPORTANTE!!!!!!! saber a donde tiene que redireccionar en caso de presentar error
    	redirect('fecha_prod.php');
	}
	//ESTE COMENTARIO SE HIZO PARA LLEGAR A LAS 400 LINEAS DE CODIGO
?>