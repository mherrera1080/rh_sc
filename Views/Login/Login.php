<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['page_title']; ?></title>
    <link rel="icon" type="image/x-icon" href="<?= media(); ?>/images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/login.css">
</head>

<body
    style="background: url('<?= media(); ?>/img/fondo_carso.jpg') no-repeat center center fixed; background-size: cover;">
    <div class="login-wrapper">
        <div class="login-card shadow">
            <div class="login-header text-center">
                <img src="<?= media(); ?>/img/carso_logo.png" alt="Logo" class="logo mb-3">
                <h2 class="fw-bold">Bienvenido</h2>
                <p class="text-muted">Ingresa con tu correo empresarial</p>
            </div>

            <form class="login-form mt-4" name="formLogin" id="formLogin">
                <div class="form-group mb-3 position-relative">
                    <label for="correo_empresarial" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo_empresarial" id="correo_empresarial" class="form-control ps-5"
                        placeholder="tucorreo@empresa.com" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>

                <div class="form-group mb-3 position-relative">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control ps-5"
                        placeholder="••••••••" required>
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>

                <div class="divider my-4"><span>o</span></div>

                <button type="submit" id="infoSubmitBtn" class="btn btn-primary w-100 py-2">
                    Ingresar <i class="fas fa-arrow-right ms-2"></i>
                    <span id="infoSpinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Modal de verificación -->
    <div id="modalEspera" class="modal fade" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalEsperaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEsperaLabel">Verificación de Token</h5>
                </div>
                <div class="modal-body text-center">
                    <form id="tokenForm">
                        <input type="hidden" id="correoToken" name="correo_empresarial">
                        <p>Hemos enviado un token a tu correo. Ingresa el código recibido para continuar.</p>
                        <input type="text" id="token" name="token" class="form-control text-center my-3"
                            placeholder="Código de verificación" required>
                        <div class="modal-footer border-0">
                            <button type="submit" id="tokenSubmitBtn" class="btn btn-primary w-100">
                                Validar
                                <span id="tokenSpinner" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>/Assets/js/functions_login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const base_url = "<?= base_url(); ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>

</html>