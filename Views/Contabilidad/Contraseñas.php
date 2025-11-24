<?php headerAdmin($data); ?>

<div class="main p-3">

    <div class="page-header">
        <h3 class="fw-bold mb-3"><?= $_SESSION['PersonalData']['nombre_area'] ?? 'N/A' ?></h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="<?= base_url(); ?>/Dashboard">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#"><?= $_SESSION['PersonalData']['nombre_area'] ?? 'N/A' ?></a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Contraseña</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableContraseña" class="display table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Contraseña</th>
                                    <th>Area</th>
                                    <th>Registro</th>
                                    <th>id_proveedor</th>
                                    <th>Total</th>
                                    <th>Fecha Pago</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin($data); ?>