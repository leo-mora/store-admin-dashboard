<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ventas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body{
            background: #f4f6fb;
            color: #212529;
        }

        .navbar{
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding-top: 14px;
            padding-bottom: 14px;
        }

        .navbar-brand{
            font-weight: 700;
            color: #0d6efd !important;
            letter-spacing: 0.3px;
            margin-right: 18px;
        }

        .nav-menu{
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .nav-link{
            color: #495057 !important;
            font-weight: 500;
            padding: 10px 16px !important;
            border-radius: 12px;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .nav-link:hover{
            background: #f1f5ff;
            color: #0d6efd !important;
        }

        .nav-link.active-link{
            background: #eef4ff;
            color: #0d6efd !important;
        }

        .main-content{
            padding-top: 28px;
            padding-bottom: 32px;
        }

        .page-container{
            max-width: 1280px;
        }

        .card{
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        }

        .user-badge{
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 14px;
            color: #6c757d;
            white-space: nowrap;
        }

        .navbar-section{
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .table{
            margin-bottom: 0;
        }

        .table thead th{
            border-bottom: none;
            font-weight: 600;
        }

        .table tbody tr:last-child td{
            border-bottom: none;
        }

        .form-control,
        .form-select{
            border-radius: 12px;
            padding: 10px 14px;
            border: 1px solid #dbe2ea;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus{
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.12);
        }

        .btn{
            border-radius: 12px;
        }

        .btn-sm{
            border-radius: 10px;
        }

        .section-title{
            font-weight: 700;
            margin-bottom: 4px;
        }

        .section-subtitle{
            color: #6c757d;
            margin-bottom: 0;
        }

        @media (max-width: 992px){
            .topbar-wrapper{
                flex-direction: column;
                align-items: flex-start !important;
                gap: 16px;
            }

            .navbar-section{
                width: 100%;
                gap: 10px;
            }

            .navbar-actions{
                width: 100%;
                justify-content: space-between;
            }

            .nav-menu{
                width: 100%;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar shadow-sm">
    <div class="container page-container">
        <div class="d-flex justify-content-between align-items-center w-100 topbar-wrapper">

            <div class="d-flex align-items-center flex-wrap gap-4">
                <a class="navbar-brand fs-4" href="/">
                    Sistema de Ventas
                </a>

                <div class="nav-menu">
                    <a href="/" class="nav-link <?php echo e(request()->is('/') ? 'active-link' : ''); ?>">Principal</a>
                    <a href="/categorias" class="nav-link <?php echo e(request()->is('categorias*') ? 'active-link' : ''); ?>">Categorías</a>
                    <a href="/productos" class="nav-link <?php echo e(request()->is('productos*') ? 'active-link' : ''); ?>">Productos</a>
                    <a href="/clientes" class="nav-link <?php echo e(request()->is('clientes*') ? 'active-link' : ''); ?>">Clientes</a>
                    <a href="/ventas" class="nav-link <?php echo e(request()->is('ventas*') ? 'active-link' : ''); ?>">Ventas</a>
                    <a href="/reportes" class="nav-link <?php echo e(request()->is('reportes*') ? 'active-link' : ''); ?>">Reportes</a>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->rol == 'admin'): ?>
                            <a href="/usuarios" class="nav-link <?php echo e(request()->is('usuarios*') ? 'active-link' : ''); ?>">Usuarios</a>
                            <a href="/bitacoras" class="nav-link <?php echo e(request()->is('bitacoras*') ? 'active-link' : ''); ?>">Bitácora</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(auth()->guard()->check()): ?>
            <div class="d-flex align-items-center gap-3 navbar-actions">
                <span class="user-badge">
                    👤 <?php echo e(auth()->user()->name); ?> (<?php echo e(ucfirst(auth()->user()->rol)); ?>)
                </span>

                <form method="POST" action="/logout" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm px-3">
                        Cerrar sesión
                    </button>
                </form>
            </div>
            <?php endif; ?>

        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container page-container">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formulariosEliminar = document.querySelectorAll('.form-eliminar');
        const formulariosEnviarFactura = document.querySelectorAll('.form-enviar-factura');

        formulariosEliminar.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Está seguro?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        formulariosEnviarFactura.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Enviar factura?',
                    text: 'Se enviará la factura al correo del cliente.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        <?php if(session('success')): ?>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: <?php echo json_encode(session('success'), 15, 512) ?>,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: <?php echo json_encode(session('error'), 15, 512) ?>,
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        <?php endif; ?>

        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'error',
                title: 'Hay errores en el formulario',
                text: 'Revise los campos e intente nuevamente.'
            });
        <?php endif; ?>
    });
</script>

</body>
</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>