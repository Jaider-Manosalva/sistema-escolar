<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
  <!-- acordion profesor -->
  <div class="col-xl-6 col-md-6 col-12">
    <!-- Collapsable Card Example -->
    <div class="card shadow mb-4">
      <!-- Card Header - Accordion -->
      <a href="#profesor_data" class="d-block card-header py-3" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="profesor_data">
        <h6 class="m-0 font-weight-bold text-primary">
          <?php echo sprintf('Profesor #%s',$d->p->documento); ?>
          <div class="float-right">
            <?php echo format_status_user($d->p->estado); ?>
          </div>
        </h6>
      </a>
      <!-- Card Content - Collapse -->
      <div class="collapse show" id="profesor_data">
        <div class="card-body">
          <form action="profesores/post_editar" method="post">
            <?php echo insert_inputs();?>
            <input type="hidden" name="id" value="<?php echo $d->p->id; ?>" required>

            <div class="form-group">
              <label for="nombre">Nombres</label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $d->p->nombre?>"
                required>
            </div>

            <div class="form-group">
              <label for="apellido">Apellidos</label>
              <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $d->p->apellido?>"
                required>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $d->p->email?>"
                required>
            </div>

            <div class="form-group">
              <label for="password">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
              <label for="telefono">Telefono</label>
              <input type="text" class="form-control" id="telefono" name="telefono"
                value="<?php echo $d->p->telefono?>">
            </div>

            <div class="form-group">
              <label for="creado">Fecha de Creacion</label>
              <input type="text" class="form-control" id="creado" name="creado"
                value="<?php echo format_date($d->p->fecha_Creacion); ?>" disabled>
            </div>

            <button class="btn btn-success" type="submit">Guardar Cambios</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- acordion materias -->
  <div class="col-xl-6 col-md-6 col-12">
    <!-- Collapsable Card Example -->
    <div class="card shadow mb-4">
      <!-- Card Header - Accordion -->
      <a href="#profesor_materia" class="d-block card-header py-3" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="profesor_materia">
        <h6 class="m-0 font-weight-bold text-primary">Listado de materias</h6>

      </a>
      <!-- Card Content - Collapse -->
      <div class="collapse show" id="profesor_materia">
        <div class="card-body">
          <form id="profesor_asig_materia" method="post">
            <?php echo insert_inputs();?>
            <input type="hidden" name="id" value="<?php echo $d->p->id; ?>" required>

            <div class="form-group">
              <label for="materia">Materias disponibles</label>
              <select name="materia" id="materia" class="form-control" require>
                <option value="">Una materia</option>
              </select>
            </div>

            <button class="btn btn-success" type="submit">Agregar</button>
          </form>
          <hr>
          <div class="wrapper-materias-profesor" data-id="<?php echo $d->p->id; ?>">
            <!-- agregar con ajax la lista de materias -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once INCLUDES.'inc_footer.php'; ?>