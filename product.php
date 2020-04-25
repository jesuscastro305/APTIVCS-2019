<?php
  $page_title = 'Lista de productos';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  //$products = join_product_table();
  $sql=("SELECT componentes.id,componentes.name,componentes.media_id,componentes.Descripcion,modelos.modelo,media.file_name FROM componentes INNER JOIN comp_modelo ON componentes.id = comp_modelo.id_comp INNER JOIN modelos ON modelos.id = comp_modelo.id_mod LEFT JOIN media ON media.id = componentes.media_id
");
  $componentes= $db->query($sql); 
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary">Agregar Material</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Imagen</th>
                <th> N. de parte </th>
                  <th> Descripcion </th>
                <th class="text-center" style="width: 10%;"> Modelo </th>
                <th class="text-center" style="width: 10%;"> Agregado </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($componentes as $pro):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td>
                  <?php if($pro['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                  <img class="img-avatar " src="uploads/products/<?php echo $pro['file_name']; ?>" alt="">
                <?php endif; ?>
                </td>
                <td> <?php echo remove_junk($pro['name']); ?></td>
                  <td> <?php echo remove_junk($pro['Descripcion']); ?></td>
                <td class="text-center"> <?php echo remove_junk($pro['modelo']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    
                     <a href="delete_product.php?id=<?php echo (String)$pro['name'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
