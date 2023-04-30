<?php if(!empty($d)):?>

<ul class="list-group">
  <?php foreach($d as $m): ?>
  <?php echo sprintf('<li class="list-group-item">%s <button class="btn btn-danger btn-sm float-right eliminar_materia_profesor" title="eliminar" type="submit" data-id="%s"><i class="fas fa-trash"></i> eliminar</button>  </li>', $m->nombre,$m->id); ?>
  <?php endforeach; ?>
</ul>
<?php else: ?>


<div class="text-center py-5">
  <img src="<?php echo get_image('documento.png');?>" alt="No hay registros." class="img-fluid" style="width: 200px;">
  <hr>
  <p class="text-muted">No hay materias asignadas al profesor</p>
</div>
<?php endif;?>