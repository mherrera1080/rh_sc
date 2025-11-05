</div>
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditarUsuario">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_usuario" name="id_usuario">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="identificacion" class="form-label">Identificación</label>
                            <input type="text" class="form-control"
                                value="<?= $_SESSION['PersonalData']['identificacion'] ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="no_empleado" class="form-label">No. Empleado</label>
                            <input type="text" class="form-control"
                                value="<?= $_SESSION['PersonalData']['no_empleado'] ?>" disabled>
                        </div>
                        <div class="col-md-5">
                            <label for="nombres" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control"
                                value="<?= $_SESSION['PersonalData']['nombre_completo'] ?>" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                            <input type="date" class="form-control"
                                value="<?= $_SESSION['PersonalData']['fecha_ingreso'] ?>" disabled>
                        </div>
                        <div class="col-md-5">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" value="<?= $_SESSION['PersonalData']['correo'] ?>"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="rol" class="form-label">Rol</label>
                            <input type="text" class="form-control" value="<?= $_SESSION['PersonalData']['rol_usuario'] ?>"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" class="form-control" value="<?= $_SESSION['PersonalData']['estado'] ?>"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="firma_digital" class="form-label">Firma Digital</label>
                            <input type="file" class="form-control" id="firma_digital" name="firma_digital"
                                accept="image/*,application/pdf">
                        </div>
                        <div class="col-md-6">
                            <label for="contraseña" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña">
                        </div>
                        <div class="col-md-6">
                            <label for="contraseña" class="form-label">Confirmacion Contraseña</label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
</div>
<!--   Core JS Files   -->
<script src="<?= media(); ?>/js/core/jquery-3.7.1.min.js"></script>
<script src="<?= media(); ?>/js/core/popper.min.js"></script>
<script src="<?= media(); ?>/js/core/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- Chart JS -->
<script src="<?= media(); ?>/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="<?= media(); ?>/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="<?= media(); ?>/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="<?= media(); ?>/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="<?= media(); ?>/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="<?= media(); ?>/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="<?= media(); ?>/js/plugin/jsvectormap/world.js"></script>

<!-- Sweet Alert -->
<script src="<?= media(); ?>/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Plugins que usan jQuery -->
<script src="<?= media(); ?>/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Kaiadmin -->
<script src="<?= media(); ?>/js/kaiadmin.min.js"></script>

<!-- DataTables y extensiones -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- Librerías extra para exportar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
    $('#lineChart').sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#177dff',
        fillColor: 'rgba(23, 125, 255, 0.14)'
    });

    $('#lineChart2').sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#f3545d',
        fillColor: 'rgba(243, 84, 93, .14)'
    });

    $('#lineChart3').sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#ffa534',
        fillColor: 'rgba(255, 165, 52, .14)'
    });
</script>
<script>
    const media_url = "<?= media(); ?>";
    const base_url = "<?= base_url(); ?>";
</script>
<script src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>"></script>
</body>



</html>