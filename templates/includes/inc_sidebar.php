
<?php
  $slug = isset($d->slug) && !empty($d->slug) ? $d->slug : 'dashboard';
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo URL;?>">
    <div class="sidebar-brand-icon rotate-n-15">
        <img src="<?php echo get_logo();?>">
    </div>
    <div class="sidebar-brand-text mx-3"><?php echo get_sitename()?></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<?php if(is_admin(get_user_role())):?>
    <li class="nav-item <?php echo $slug === 'admin' ? 'active' : null; ?>">
        <a href="admin" class="nav-link">
            <i class="fas fa-fw fa-user-lock"></i>
            <span>Administracion</span>
        </a>
    </li>
<?php endif;?>

<!-- Nav Item - Dashboard -->
<li class="nav-item <?php echo $slug === 'dashboard' ? 'active' : null; ?>">
    <a class="nav-link" href="dashboard">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Menu
</div>

<!-- Nav Item - Profesores -->
<li class="nav-item <?php echo $slug === 'profesores' ? 'active' : null; ?>">
    <a href="profesores" class="nav-link">
        <i class="fas fa-fw fa-users"></i>
        <span>Profesores</span>
    </a>
</li>

<!-- Nav Item - alumnos -->
<li class="nav-item <?php echo $slug === 'alumnos' ? 'active' : null; ?>">
    <a href="alumnos" class="nav-link">
        <i class="fas fa-fw fa-book-reader"></i>
        <span>Alumnos</span>
    </a>
</li>

<!-- Nav Item - alumnos -->
<li class="nav-item <?php echo $slug === 'grupos' ? 'active' : null; ?>">
    <a href="grupos" class="nav-link">
        <i class="fas fa-fw fa-graduation-cap"></i>
        <span>Grupos</span>
    </a>
</li>

<!-- Nav Item - Materias -->
<li class="nav-item <?php echo $slug === 'materias' ? 'active' : null; ?>">
    <a href="materias" class="nav-link">
        <i class="fas fa-fw fa-book"></i>
        <span>Materias</span>
    </a>
</li>

<!-- Nav Item - Lecciones -->
<li class="nav-item <?php echo $slug === 'lecciones' ? 'active' : null; ?>">
    <a href="lecciones" class="nav-link">
        <i class="fas fa-fw fa-chalkboard-teacher"></i>
        <span>Lecciones</span>
    </a>
</li>

<!-- Nav Item - Horarios -->
<li class="nav-item <?php echo $slug === 'horarios' ? 'active' : null; ?>">
    <a href="horarios" class="nav-link">
        <i class="fas fa-fw fa-calendar-alt"></i>
        <span>Horarios</span>
    </a>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

<!-- Sidebar Message 
<div class="sidebar-card d-none d-lg-flex">
    <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
    <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
    <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
</div>
-->

</ul>
<!-- End of Sidebar -->