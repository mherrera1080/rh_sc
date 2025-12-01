<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?= $data['page_title']; ?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="<?= media(); ?>/img/kaiadmin/favicon.ico" type="image/x-icon" />
	<script src="<?= media(); ?>/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: { "families": ["Public Sans:300,400,500,600,700"] },
			custom: { "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['<?= media(); ?>/css/fonts.min.css'] },
			active: function () {
				sessionStorage.fonts = true;
			}
		});
	</script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?= media(); ?>/css/plugins.min.css">
	<link rel="stylesheet" href="<?= media(); ?>/css/kaiadmin.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
	<div class="wrapper">
		<!-- Sidebar -->
		<div class="sidebar" data-background-color="dark">
			<div class="sidebar-logo">
				<!-- Logo Header -->
				<div class="logo-header" data-background-color="dark">

					<a href="<?= base_url(); ?>/Dashboard" class="logo">
						<img src="<?= media(); ?>/img/carso_logo" alt="navbar brand" class="navbar-brand" height="50">
					</a>
					<div class="nav-toggle">
						<button class="btn btn-toggle toggle-sidebar">
							<i class="gg-menu-right"></i>
						</button>
						<button class="btn btn-toggle sidenav-toggler">
							<i class="gg-menu-left"></i>
						</button>
					</div>
					<button class="topbar-toggler more">
						<i class="gg-more-vertical-alt"></i>
					</button>
				</div>
				<!-- End Logo Header -->
			</div>
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<ul class="nav nav-secondary">
						<li class="nav-item">
							<a href="<?= base_url(); ?>/Dashboard">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
							<?php if (!empty($_SESSION['permisos'][REPORTES]['acceder'])) { ?>
								<a href="<?= base_url(); ?>/Dashboard/Reportes">
									<i class="fa-solid fa-file-export"></i>
									<p>Reportes</p>
								</a>
							<?php } ?>
						</li>
						<?php if (!empty($_SESSION['permisos'][RECEPCION]['acceder'])) { ?>
							<li class="nav-section">
								<span class="sidebar-mini-icon">
									<i class="fa fa-ellipsis-h"></i>
								</span>
								<h4 class="text-section">Recepcion</h4>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/Contraseñas/Recepcion">
									<i class="fa-solid fa-file-circle-plus"></i>
									<p>Contraseñas</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/Contraseñas/Facturas">
									<i class="fa-solid fa-file-invoice-dollar"></i>
									<p>Facturas</p>
								</a>
							</li>
						<?php } ?>

						<?php if (!empty($_SESSION['permisos'][AREAS]['acceder'])) { ?>
							<ul class="nav nav-primary">
								<li class="nav-section">
									<span class="sidebar-mini-icon">
										<i class="fa fa-ellipsis-h"></i>
									</span>
									<h4 class="text-section">Area -
										<?= $_SESSION['PersonalData']['nombre_area'] ?? "N/A" ?>
									</h4>
								</li>
								<li class="nav-item">
									<a href="<?= base_url(); ?>/Contraseñas">
										<i class="fa-solid fa-people-arrows"></i>
										<p>Contraseñas</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url(); ?>/Contraseñas/Facturas">
										<i class="fa-solid fa-file-invoice-dollar"></i>
										<p>Facturas</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url(); ?>/SolicitudFondos">
										<i class="fa-solid fa-hand-holding-dollar"></i>
										<p>Anticipos</p>
									</a>
								</li>
							</ul>

						<?php } ?>

						<?php if (!empty($_SESSION['permisos'][CONTABILIDAD]['acceder'])) { ?>
							<ul class="nav nav-primary">
								<li class="nav-section">
									<span class="sidebar-mini-icon">
										<i class="fa fa-ellipsis-h"></i>
									</span>
									<h4 class="text-section">Aprobacion Contabilidad
									</h4>
								</li>
								<li class="nav-item">
									<a href="<?= base_url(); ?>/Contabilidad/Contraseñas">
										<i class="fa-solid fa-user-check"></i>
										<p>Contraseñas</p>

									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url(); ?>/Contabilidad/Facturas">
										<i class="fa-solid fa-file-invoice-dollar"></i>
										<p>Facturas</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url(); ?>/SolicitudFondos/Contabilidad">
										<i class="fa-solid fa-hand-holding-dollar"></i>
										<p>Solitud de Fondos</p>
									</a>
								</li>
							</ul>

						<?php } ?>

						<?php if (!empty($_SESSION['permisos'][VEHICULOS]['acceder'])) { ?>
							<li class="nav-section">
								<span class="sidebar-mini-icon">
									<i class="fa fa-ellipsis-h"></i>
								</span>
								<h4 class="text-section">Vehiculos</h4>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/Vehiculos">
									<i class="fas fa-solid fa-car"></i>
									<p>Contraseñas</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/Vehiculos/Facturas">
									<i class="fa-solid fa-file-invoice-dollar"></i>
									<p>Facturas</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/SolicitudFondos/Vehiculos">
									<i class="fa-solid fa-hand-holding-dollar"></i>
									<p>Solitud de Fondos</p>
								</a>
							</li>
						<?php } ?>


					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->

		<div class="main-panel">
			<div class="main-header">
				<div class="main-header-logo">
					<!-- Logo Header -->
					<div class="logo-header" data-background-color="dark">

						<a href="index.html" class="logo">
							<img src="<?= media(); ?>/img/kaiadmin/logo_light.svg" alt="navbar brand"
								class="navbar-brand" height="20">
						</a>
						<div class="nav-toggle">
							<button class="btn btn-toggle toggle-sidebar">
								<i class="gg-menu-right"></i>
							</button>
							<button class="btn btn-toggle sidenav-toggler">
								<i class="gg-menu-left"></i>
							</button>
						</div>
						<button class="topbar-toggler more">
							<i class="gg-more-vertical-alt"></i>
						</button>

					</div>
					<!-- End Logo Header -->
				</div>
				<!-- Navbar Header -->
				<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">

					<div class="container-fluid">
						<nav
							class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-search pe-1">
										<i class="fa fa-search search-icon"></i>
									</button>
								</div>
								<input type="text" placeholder="Search ..." class="form-control">
							</div>
						</nav>
						<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
							<li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
								<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
									aria-expanded="false" aria-haspopup="true">
									<i class="fa fa-search"></i>
								</a>
								<ul class="dropdown-menu dropdown-search animated fadeIn">
									<form class="navbar-left navbar-form nav-search">
										<div class="input-group">
											<input type="text" placeholder="Search ..." class="form-control">
										</div>
									</form>
								</ul>
							</li>
							<li class="nav-item topbar-icon dropdown hidden-caret">
								<?php if (
									!empty($_SESSION['permisos'][USUARIOS]['acceder']) || !empty($_SESSION['permisos'][AREAS]['acceder']) || !empty($_SESSION['permisos'][PROVEEDORES]['acceder'])
									|| !empty($_SESSION['permisos'][EMAILS]['acceder']) || !empty($_SESSION['permisos'][ROLES]['acceder']) || !empty($_SESSION['permisos'][FIRMAS]['acceder'])
								) { ?>
									<a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fas fa-cog"></i>
									</a>
								<?php } ?>

								<div class="dropdown-menu quick-actions animated fadeIn">
									<div class="quick-actions-header">
										<span class="title mb-1">Configuracion</span>
									</div>
									<div class="quick-actions-scroll scrollbar-outer">
										<div class="quick-actions-items">
											<div class="row m-0">


												<?php if (!empty($_SESSION['permisos'][USUARIOS]['acceder'])) { ?>
													<a class="col-6 col-md-4 p-0" href="<?= base_url(); ?>/Usuarios">
														<div class="quick-actions-item">
															<div class="avatar-item bg-danger rounded-circle">
																<i class="fas fa-book-open"></i>
															</div>
															<span class="text">Usuarios</span>
														</div>
													</a>
												<?php } ?>
												<?php if (!empty($_SESSION['permisos'][AREAS]['acceder'])) { ?>
													<a class="col-6 col-md-4 p-0"
														href="<?= base_url(); ?>/Configuracion/Areas">
														<div class="quick-actions-item">
															<div class="avatar-item bg-warning rounded-circle">
																<i class="fas fa-map"></i>
															</div>
															<span class="text">Areas</span>
														</div>
													</a>
												<?php } ?>
												<?php if (!empty($_SESSION['permisos'][PROVEEDORES]['acceder'])) { ?>
													<a class="col-6 col-md-4 p-0"
														href="<?= base_url(); ?>/Configuracion/Proveedores">
														<div class="quick-actions-item">
															<div class="avatar-item bg-info rounded-circle">
																<i class="fas fa-file-excel"></i>
															</div>
															<span class="text">Proveedores</span>
														</div>
													</a>
												<?php } ?>
												<?php if (!empty($_SESSION['permisos'][EMAILS]['acceder'])) { ?>
													<a class="col-6 col-md-4 p-0"
														href="<?= base_url(); ?>/Configuracion/Notificaciones">
														<div class="quick-actions-item">
															<div class="avatar-item bg-success rounded-circle">
																<i class="fas fa-envelope"></i>
															</div>
															<span class="text">Emails</span>
														</div>
													</a>
												<?php } ?>
												<?php if (!empty($_SESSION['permisos'][ROLES]['acceder'])) { ?>
													<a class="col-6 col-md-4 p-0"
														href="<?= base_url(); ?>/Configuracion/Roles">
														<div class="quick-actions-item">
															<div class="avatar-item bg-primary rounded-circle">
																<i class="fas fa-file-invoice-dollar"></i>
															</div>
															<span class="text">Roles</span>
														</div>
													</a>
												<?php } ?>
												<?php if (!empty($_SESSION['permisos'][FIRMAS]['acceder'])) { ?>
													<a class="col-6 col-md-4 p-0"
														href="<?= base_url(); ?>/Configuracion/Firmas">
														<div class="quick-actions-item">
															<div class="avatar-item bg-secondary rounded-circle">
																<i class="fas fa-credit-card"></i>
															</div>
															<span class="text">Firmas </span>
														</div>
													</a>
												<?php } ?>

											</div>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item topbar-user dropdown hidden-caret">
								<a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
									aria-expanded="false">
									<div class="avatar-sm">
										<img src="<?= media(); ?>/img/perfil_user.png" alt="..."
											class="avatar-img rounded-circle">
									</div>
									<span class="profile-username">
										<span class="op-7">Hola </span> <span class="fw-bold">
											<?= $_SESSION['PersonalData']['nombres'] ?> !</span>
									</span>
								</a>
								<ul class="dropdown-menu dropdown-user animated fadeIn" style="width: 300px;">
									<div class="dropdown-user-scroll scrollbar-outer">
										<li>
											<div class="user-box d-flex align-items-center">
												<div class="avatar-lg me-3">
													<img src="<?= media(); ?>/img/perfil_user.png" alt="image profile"
														class="avatar-img rounded">
												</div>
												<div class="u-text">
													<h4 class="mb-1"><?= $_SESSION['PersonalData']['nombre_completo'] ?>
													</h4>
													<p class="text-muted mb-0">
														<?= $_SESSION['PersonalData']['correo'] ?>
													</p>
												</div>
											</div>
										</li>
										<li>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#" data-bs-toggle="modal"
												data-bs-target="#modalEditarUsuario">
												Editar Perfil
											</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="<?= base_url() ?>/Login/Logout">Logout</a>
										</li>
									</div>
								</ul>

							</li>
						</ul>
					</div>
				</nav>
				<!-- End Navbar -->
			</div>
			<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<form id="formActualizarPassword">
							<div class="modal-header">
								<h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"
									aria-label="Cerrar"></button>
							</div>
							<div class="modal-body">
								<input type="hidden" name="correo_empresarial"
									value="<?= $_SESSION['PersonalData']['correo'] ?>">

								<div class="row g-3">
									<div class="col-md-4">
										<label for="identificacion" class="form-label"> <strong>Identificación</strong>
										</label>
										<input type="text" class="form-control"
											value="<?= $_SESSION['PersonalData']['identificacion'] ?>" disabled>
									</div>
									<div class="col-md-3">
										<label for="no_empleado" class="form-label"> <strong>No.
												Empleado</strong></label>
										<input type="text" class="form-control"
											value="<?= $_SESSION['PersonalData']['no_empleado'] ?>" disabled>
									</div>
									<div class="col-md-5">
										<label for="nombres" class="form-label"><strong>Nombre Completo</strong></label>
										<input type="text" class="form-control"
											value="<?= $_SESSION['PersonalData']['nombre_completo'] ?>" disabled>
									</div>
									<div class="col-md-4">
										<label for="fecha_ingreso" class="form-label"><strong>Fecha de
												Ingreso</strong></label>
										<input type="date" class="form-control"
											value="<?= $_SESSION['PersonalData']['fecha_ingreso'] ?>" disabled>
									</div>
									<div class="col-md-5">
										<label for="correo" class="form-label"><strong>Correo</strong></label>
										<input type="email" class="form-control"
											value="<?= $_SESSION['PersonalData']['correo'] ?>" disabled>
									</div>
									<div class="col-md-3">
										<label for="rol" class="form-label"><strong>Area</strong></label>
										<input type="text" class="form-control"
											value="<?= $_SESSION['PersonalData']['nombre_area'] ?>" disabled>
									</div>
									<div class="col-md-6">
										<label for="estado" class="form-label"><strong>Estado</strong></label>
										<input type="text" class="form-control"
											value="<?= $_SESSION['PersonalData']['estado'] ?>" disabled>
									</div>
									<!-- <div class="col-md-6">
							<label for="firma_digital" class="form-label"><strong>Firma Digital</strong></label>
							<input type="file" class="form-control" id="firma_digital" name="firma_digital"
								accept="image/*,application/pdf">
						</div> -->
									<div class="col-md-6">
										<label for="contraseña" class="form-label"><strong>Contraseña
												Actual</strong></label>
										<input type="password" class="form-control" id="password" name="password">
									</div>
									<div class="col-md-6">
										<label for="contraseña" class="form-label"><strong>Nueva
												Contraseña</strong></label>
										<input type="password" class="form-control" id="password_new"
											name="password_new">
									</div>
									<div class="col-md-6">
										<label for="contraseña" class="form-label"><strong>Confirmacion
												Contraseña</strong></label>
										<input type="password" class="form-control" id="password_confirmacion"
											name="password_confirmacion">
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary"
									data-bs-dismiss="modal">Cancelar</button>
								<button type="submit" class="btn btn-primary">Guardar Cambios</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal fade" id="codigoModal" tabindex="-1" aria-labelledby="codigoModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header bg-primary text-white">
							<h5 class="modal-title" id="codigoModalLabel">Verificación de Código</h5>
							<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
								aria-label="Cerrar"></button>
						</div>
						<form id="tokenForm">
							<input type="hidden" id="correoToken" name="correo_empresarial">
							<p>Hemos enviado un token a tu correo. Ingresa el código recibido para continuar.</p>
							<input type="text" id="token" name="token" class="form-control text-center my-3"
								placeholder="Código de verificación" required>
							<div class="modal-footer">
								<button type="submit" id="tokenSubmitBtn" class="btn btn-primary btn-validar">
									Validar
									<span id="tokenSpinner" class="spinner-border spinner-border-sm d-none"></span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<script src="<?= base_url(); ?>/Assets/js/functions_password.js"></script>
			<div class="container">