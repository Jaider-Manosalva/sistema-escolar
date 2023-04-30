<?php require_once INCLUDES.'inc_header.php'; ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><?php echo $d->title;?></h6>
  </div>
  <div class="card-body">

    <?php if(!empty($d->profesores->rows)): ?>
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Documento</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Estado</th>
            <th width="5%">Accion</th>
          </tr>
        </thead>
        <tbody>
          <?php	foreach($d->profesores->rows as $p):?>
          <tr>
            <td><?php	echo sprintf('<a href="profesores/ver/%s">%s</a>',$p->documento, $p->documento);?></td>
            <td><?php echo empty($p->nombre) ? 'sin nombre' : add_ellipsis($p->nombre,50); ?></td>
            <td><?php echo empty($p->apellido) ? 'sin apellido' : add_ellipsis($p->apellido,50); ?></td>
            <td><?php echo empty($p->email) ? 'sin email' : add_ellipsis($p->email,50); ?></td>
            <td><?php echo format_status_user($p->estado); ?></td>
            <td>
              <div class="btn-group">
                <a href="<?php echo 'profesores/ver/'.$p->documento;?>" class="btn btn-sm btn-success"><i
                    class="fas fa-eye"></i></a>
                <a class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#confirmModal"><i
                    class="fas fa-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php	endforeach?>
        </tbody>
      </table>
      <?php echo $d->profesores->pagination; ?>
    </div>

    <!-- Modal -->
    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <!-- Contenido del modal de confirmación -->
          <div class="modal-header">
            <h5 class="modal-title" id="confirmModalLabel">Confirmar Borrado</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>¿Estás seguro de que deseas borrar este profesor?</p>
          </div>
          <div class="modal-footer">
            <a class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
            <a class="btn btn-danger" href="<?php echo buildURL('profesores/borrar/'.$p->id);?>">Borrar</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin Modal -->
    <?php else: ?>
    <div class="py-5 text-center">
      <img src="<?php echo IMAGES.'documento.png'?>" alt="No hay registros" style="width: 250px; margin: 20px">
      <p class="text-muted">No hay registros en la base de datos.</p>
    </div>
    <?php endif; ?>

  </div>
</div>

<?php require_once INCLUDES.'inc_footer.php'; ?>