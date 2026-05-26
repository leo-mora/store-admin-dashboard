

<?php $__env->startSection('content'); ?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestión de Productos</h2>
            <p class="text-muted mb-0">Administra inventario, stock y categorías</p>
        </div>

        <a href="<?php echo e(route('productos.create')); ?>" class="btn btn-primary px-4">
            + Nuevo Producto
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form method="GET" action="<?php echo e(route('productos.index')); ?>" class="row g-2 mb-4">
                <div class="col-md-7">
                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por nombre, marca o SKU..."
                        value="<?php echo e($buscar ?? ''); ?>"
                    >
                </div>

                <div class="col-md-3">
                    <select name="estado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los productos</option>
                        <option value="bajo" <?php echo e(($estado ?? '') == 'bajo' ? 'selected' : ''); ?>>
                            Bajo stock
                        </option>
                        <option value="agotado" <?php echo e(($estado ?? '') == 'agotado' ? 'selected' : ''); ?>>
                            Agotados
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <?php if(request('buscar') || request('estado')): ?>
                        <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-outline-secondary w-100">
                            Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="<?php echo e($producto->stock == 0 ? 'table-danger' : ($producto->stock <= 5 ? 'table-warning' : '')); ?>">
                                <td><?php echo e($producto->id); ?></td>

                                <td>
                                    <div class="fw-semibold"><?php echo e($producto->nombre); ?></div>

                                    <small class="text-muted d-block">
                                        <?php echo e($producto->marca); ?>

                                    </small>

                                    <?php if($producto->descripcion): ?>
                                        <small class="text-secondary d-block">
                                            <?php echo e($producto->descripcion); ?>

                                        </small>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="badge rounded-pill bg-secondary">
                                        <?php echo e($producto->sku); ?>

                                    </span>
                                </td>

                                <td>
                                    <?php if($producto->categoria): ?>
                                        <span class="badge rounded-pill bg-info text-dark">
                                            <?php echo e($producto->categoria->nombre); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-light text-dark border">
                                            Sin categoría
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="fw-semibold">₡ <?php echo e(number_format($producto->precio, 2)); ?></span>
                                </td>

                                <td>
                                    <?php if($producto->stock == 0): ?>
                                        <span class="badge rounded-pill bg-danger">
                                            Agotado
                                        </span>
                                    <?php elseif($producto->stock <= 5): ?>
                                        <span class="badge rounded-pill bg-warning text-dark">
                                            <?php echo e($producto->stock); ?> - Bajo stock
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-success">
                                            <?php echo e($producto->stock); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="<?php echo e(route('productos.edit', $producto)); ?>" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form
                                        action="<?php echo e(route('productos.destroy', $producto)); ?>"
                                        method="POST"
                                        class="d-inline form-eliminar"
                                    >
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No hay productos registrados
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($productos->links()); ?>

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

            Swal.fire({
                title: '¿Eliminar producto?',
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/productos/index.blade.php ENDPATH**/ ?>