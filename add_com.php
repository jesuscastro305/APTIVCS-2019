<?php
	include_once('includes/load.php');
	$page_title = "Comentarios";
	page_require_level(1);
?>
<?php
	if (isset($_POST['btn_agregar'])) {
      $req_fields = array('code','com','state','area');

   		validate_fields($req_fields);
   		if(empty($errors)){
     		$code  = remove_junk($db->escape($_POST['code']));
       		$com = remove_junk($db->escape($_POST['com']));
       		$state= remove_junk($db->escape($_POST['state']));
          $area= remove_junk($db->escape($_POST['area']));

       		$tabla= $db->query("select codigo from comentarios where codigo ='$code'");
       		if (mysqli_num_rows($tabla)==0){
       			if ($db->query("insert into comentarios (`codigo`,`comentario`,`estado`,`area`) values ('$code','$com','$state', '$area')")) {
       				$session->msg('s',"Se agregó correctamente");
       				redirect("comentarios.php", false);
       			}
       			else{
       				$session->msg('d',"Ocurrió durante la consulta.");
       				redirect('add_com.php', false);
       			}
       		}
       		else{
       			$session->msg('w',"El código ya existe: '".$code."'");
       			redirect('add_com.php', false);
       		}
       	}
       	else{
       		$session->msg('d',$errors);
       		redirect('add_com.php', false);
       	}
	}

  include_once('layouts/header.php');
?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  	<div class="col-xs-12 col-md-8 col-md-offset-2">
      	<div class="panel panel-default">
        	<div class="panel-heading">
          		<strong>
            		<span class="glyphicon glyphicon-th"></span>
              		<span>Agregar comentario</span>
         		</strong>
        	</div>
        	<div class="panel-body">
        		<div class="col-md-12">
          			<form method="POST" class="clearfix">
          				<div class="form-group col-xs-12 col-md-3">
          					<label for="codigo">Código:</label>
          					<input type="text" name="code" id="codigo" placeholder="ej: JC1" class="form-control" required>
          				</div>
          				<div class="form-group col-xs-12 col-md-9">
          					<label for="comentario">Comentario:</label>
          					<input type="text" name="com" id="comentario" placeholder="ej: Problema con la produccion" class="form-control" required>
          				</div>
          				<div class="form-group col-xs-12 col-md-6">
          					<label for="estado">Estado:</label>
          					<select name="state" id="estado" class="form-control">
          						<option value="Indefinido">Selecciona un estado</option>
          						<option value="produccion">Produccion</option>
          						<option value="cambio">Cambio # de parte</option>
          						<option value="proceso">Procesos</option>
          						<option value="tool">Tool Room</option>
          						<option value="mantenimiento" >Mantenimiento</option>
          						<option value="material">Material no disponible</option>
          						<option value="personal">Personal no disponible</option>
          						<option value="estado">Paros planeados</option>
          					</select>
          				</div>
                  <div class="form-group col-xs-12 col-md-6">
                    <label for="estado">Area:</label>
                    <select name="area" id="area" class="form-control">
                      <option value="Ensamble" >Ensamble</option>
                      <option value="Moldeo" >Moldeo</option>
                    </select>
                  </div>
                  <div class="col-xs-12 col-md-4 col-md-offset-2">
                      <button  name="btn_agregar" class="btn btn-success btn-block">Agregar comentario</Button>
                  </div>
                  <div class="col-xs-12 col-md-4 ">
                      <a href="comentarios.php" class="btn btn-danger btn-block">Cancelar</a>
                  </div>
          			</form>
         		</div>
        	</div>
      	</div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>