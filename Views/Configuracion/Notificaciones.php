<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-3">Configuracion de notificaciones</h3>
            <ul class="breadcrumbs mb-0">
                <li class="nav-home">
                    <a href="<?= base_url(); ?>/Dashboard">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Notificaciones</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableGrupoCorreos" class="display table table-striped table-hover align-middle">
                            <thead class="table-light">

                            </thead>
                            <tbody>
                                <!-- Aquí se llenan los datos dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data); ?>

<div class="modal fade" id="modalGrupoCorreos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-black text-white">
                <h5 class="modal-title">Configuración de Notificaciones por Área</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="id_area" name="area">
                <input type="hidden" id="contraseña" name="grupo">
                <input type="hidden" id="anticipo" name="grupo">
                <input type="hidden" id="solicitud" name="grupo">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="areas" class="form-label fw-semibold mb-1 d-block">Área</label>
                        <input type="text" class="form-control w-100" id="nombre_area" readonly>
                    </div>
                </div>
                <div class="container-fluid py-3">
                    <ul class="nav nav-tabs mb-3" id="tipoTabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-type="1" data-bs-toggle="tab"
                                data-bs-target="#tabContraseña">Contraseña</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-type="2" data-bs-toggle="tab"
                                data-bs-target="#tabSolicitud">Solicitud</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-type="3" data-bs-toggle="tab"
                                data-bs-target="#tabAnticipo">Anticipo</button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <!-- TEMPLATE: cada tab lleva exactamente lo mismo -->
                        <div class="tab-pane fade show active" id="tabContraseña">
                            <div class="row">

                                <!-- COLUMNA IZQUIERDA – Fases -->
                                <div class="col-md-8">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-primary text-white">
                                            <strong>Fases</strong>
                                        </div>
                                        <div id="contenedorFasesContraseña" class="contenedor-fases-scroll">
                                        </div>

                                    </div>
                                </div>

                                <!-- COLUMNA DERECHA – Usuarios -->
                                <div class="col-md-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">
                                            <strong>Usuarios</strong>
                                        </div>
                                        <div class="card-body" id="contenedorUsuariosContraseña">
                                            <!-- ESPERA DATOS DINÁMICOS -->
                                            <!-- Aquí va la lista de usuarios disponibles -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- TAB Solicitud -->
                        <div class="tab-pane fade" id="tabSolicitud">
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-primary text-white">
                                            <strong>Fases</strong>
                                        </div>
                                        <div id="contenedorFasesSolicitud" class="contenedor-fases-scroll">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">
                                            <strong>Usuarios</strong>
                                        </div>
                                        <div class="card-body" id="contenedorUsuariosSolicitud">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- TAB Anticipo -->
                        <div class="tab-pane fade" id="tabAnticipo">
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-primary text-white">
                                            <strong>Fases</strong>
                                        </div>
                                        <div id="contenedorFasesAnticipo" class="contenedor-fases-scroll">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">
                                            <strong>Usuarios</strong>
                                        </div>
                                        <div class="card-body" id="contenedorUsuariosAnticipo">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    let role_id = <?= json_encode($_SESSION['rol_usuario'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>

<style>
    .contenedor-fases-scroll {
        max-height: 550px;
        /* Ajustable: Aumenta o reduce la altura */
        overflow-y: auto;
        /* Scroll vertical */
        padding-right: 5px;
        /* Evita que el scroll tape contenido */
    }

    .fase-item {
        width: 100%;
        max-width: 700px;
        /* aumenta si quieres más ancho */
        background: #ffffff;
    }

    .titulo-fase {
        font-size: 1.1rem;
        display: block;
        margin-bottom: 6px;
    }

    .usuarios-asignados {
        min-height: 70px;
        /* antes 120px */
        max-height: 160px;
        /* antes 260px */
        overflow-y: auto;
        width: 100%;
        border: 1px solid #d1d1d1;
        background: #f8f9fa;
        border-radius: 6px;
    }


    /* Opcional: mejorar apariencia visual */
    .usuarios-asignados::-webkit-scrollbar {
        width: 8px;
    }

    .usuarios-asignados::-webkit-scrollbar-thumb {
        background: #bbb;
        border-radius: 8px;
    }

    /* Contenedor de usuarios dentro de cada fase */
    .usuarios-asignados {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        min-height: 50px;
    }

    /* Chip pequeño */
    .usuario-chip {
        display: flex;
        align-items: center;
        background: #e3f2fd;
        border: 1px solid #90caf9;
        color: #0d47a1;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 13px;
        max-width: 200px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    /* Nombre del usuario */
    .chip-nombre {
        margin-right: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Botón de eliminar */
    .chip-remove {
        background: transparent;
        border: none;
        color: #d32f2f;
        font-size: 14px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
</style>