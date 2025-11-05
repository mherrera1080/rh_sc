<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?= $data['page_title']; ?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="<?= media(); ?>/img/kaiadmin/favicon.ico" type="image/x-icon" />

	<!-- Fonts and icons -->
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

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?= media(); ?>/css/bootstrap.min.css">

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
						<li class="nav-item active">
							<a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="dashboard">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= base_url(); ?>/Contraseñas">
											<span class="sub-item">Dashboard </span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Recepcion</h4>
						</li>
						<li class="nav-item">
							<a href="<?= base_url(); ?>/Contraseñas/Recepcion">
								<i class="fas fa-file-contract"></i>
								<p>Contraseñas</p>
								<!-- <span class="badge badge-secondary">1</span> -->
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url(); ?>/Contraseñas/Facturas">
								<i class="fas fa-file-contract"></i>
								<p>Facturas</p>
								<!-- <span class="badge badge-secondary">1</span> -->
							</a>
						</li>
						<ul class="nav nav-primary">
							<li class="nav-section">
								<span class="sidebar-mini-icon">
									<i class="fa fa-ellipsis-h"></i>
								</span>
								<h4 class="text-section">Contraseñas -
									<?= $_SESSION['PersonalData']['nombre_area'] ?? "N/A" ?>
								</h4>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/Contraseñas">
									<i class="fas fa-file-contract"></i>
									<p>Contraseñas</p>
									<!-- <span class="badge badge-secondary">1</span> -->
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/Contraseñas">
									<i class="fas fa-file-contract"></i>
									<p>Facturas</p>
									<!-- <span class="badge badge-secondary">1</span> -->
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= base_url(); ?>/SolicitudFondos">
									<i class="fas fa-file-contract"></i>
									<p>Solitud de Fondos</p>
								</a>
							</li>
						</ul>
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
								<a class="nav-link dropdown-toggle"
									href="<?= base_url(); ?>/Configuracion/Notificaciones">
									<i class="fa fa-envelope"></i>
								</a>
							</li>

							<li class="nav-item topbar-icon dropdown hidden-caret">
								<a class="nav-link" data-bs-toggle="dropdown"
									href="<?= base_url(); ?>/Configuracion/Areas" aria-expanded="false">
									<i class="fas fa-cog"></i>
								</a>
								<div class="dropdown-menu quick-actions animated fadeIn">
									<div class="quick-actions-header">
										<span class="title mb-1">Configuracion</span>
									</div>
									<div class="quick-actions-scroll scrollbar-outer">
										<div class="quick-actions-items">
											<div class="row m-0">
												<a class="col-6 col-md-4 p-0" href="<?= base_url(); ?>/Usuarios">
													<div class="quick-actions-item">
														<div class="avatar-item bg-danger rounded-circle">
															<i class="fas fa-book-open"></i>
														</div>
														<span class="text">Usuarios</span>
													</div>
												</a>
												<a class="col-6 col-md-4 p-0"
													href="<?= base_url(); ?>/Configuracion/Areas">
													<div class="quick-actions-item">
														<div class="avatar-item bg-warning rounded-circle">
															<i class="fas fa-map"></i>
														</div>
														<span class="text">Areas</span>
													</div>
												</a>
												<a class="col-6 col-md-4 p-0"
													href="<?= base_url(); ?>/Configuracion/Proveedores">
													<div class="quick-actions-item">
														<div class="avatar-item bg-info rounded-circle">
															<i class="fas fa-file-excel"></i>
														</div>
														<span class="text">Proveedores</span>
													</div>
												</a>
												<!-- <a class="col-6 col-md-4 p-0"
													href="<?= base_url(); ?>/Configuracion/Modulos">
													<div class="quick-actions-item">
														<div class="avatar-item bg-success rounded-circle">
															<i class="fas fa-envelope"></i>
														</div>
														<span class="text">Emails</span>
													</div>
												</a> -->
												<a class="col-6 col-md-4 p-0"
													href="<?= base_url(); ?>/Configuracion/Roles">
													<div class="quick-actions-item">
														<div class="avatar-item bg-primary rounded-circle">
															<i class="fas fa-file-invoice-dollar"></i>
														</div>
														<span class="text">Roles</span>
													</div>
												</a>
												<a class="col-6 col-md-4 p-0"
													href="<?= base_url(); ?>/Configuracion/Firmas">
													<div class="quick-actions-item">
														<div class="avatar-item bg-secondary rounded-circle">
															<i class="fas fa-credit-card"></i>
														</div>
														<span class="text">Firmas </span>
													</div>
												</a>
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
													<h4 class="mb-1"><?= $_SESSION['PersonalData']['nombres'] ?></h4>
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

			<div class="container">