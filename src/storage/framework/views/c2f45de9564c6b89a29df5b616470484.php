

<?php $__env->startSection('content'); ?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestión de Categorías</h2>
            <p class="text-muted mb-0">Administra las categorías del sistema</p>
        </div>

        <a href="/categorias/create" class="btn btn-primary px-4">
            + Nueva Categoría
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form method="GET" action="/categorias" class="row g-2 mb-4">
                <div class="col-md-6">
                    <input
                        type="text"
                        name="buscar"
                        value="<?php echo e($buscar ?? ''); ?>"
                        class="form-control"
                        placeholder="Buscar categoría..."
                    >
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Buscar</button>
                </div>

                <div class="col-md-2">
<?php if(request('buscar')): ?>
    <a href="<?php echo e(route('categorias.index')); ?>" class="btn btn-outline-secondary">
        Limpiar
    </a>
<?php endif; ?>                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Productos</th>
                            <th style="width: 190px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($categoria->id); ?></td>
                                <td>
                                    <span class="fw-semibold"><?php echo e($categoria->nombre); ?></span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill <?php echo e($categoria->productos_count > 0 ? 'bg-info text-dark' : 'bg-light text-dark border'); ?>">
                                        <?php echo e($categoria->productos_count); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="/categorias/<?php echo e($categoria->id); ?>/edit" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="/categorias/<?php echo e($categoria->id); ?>" method="POST" class="d-inline form-eliminar">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            <?php echo e($categoria->productos_count > 0 ? 'disabled' : ''); ?>

                                            title="<?php echo e($categoria->productos_count > 0 ? 'No se puede eliminar porque tiene productos asociados' : 'Eliminar categoría'); ?>"
                                        >
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No se encontraron categorías.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(session('success')): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: <?php echo json_encode(session('success'), 15, 512) ?>,
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
</script>
<?php endif; ?>

<?php if(session('error')): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: <?php echo json_encode(session('error'), 15, 512) ?>,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
<?php endif; ?>

<script>
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const boton = form.querySelector('button');

            if (boton.hasAttribute('disabled')) {
                return;
            }

            Swal.fire({
                title: '¿Eliminar categoría?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
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
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/categorias/index.blade.php ENDPATH**/ ?>