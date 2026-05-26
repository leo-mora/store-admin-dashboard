

<?php $__env->startSection('content'); ?>

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">👤 Historial del Cliente</h2>
        <p class="section-subtitle">Consulte la información y compras registradas del cliente</p>
    </div>

    <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-outline-secondary px-4">
        Volver
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-3">
                <h6 class="text-muted mb-2">Cliente</h6>
                <h3 class="fw-bold mb-0"><?php echo e($cliente->nombre); ?></h3>
            </div>

            <div class="col-md-3">
                <h6 class="text-muted mb-2">Identificación</h6>
                <div class="fs-5"><?php echo e($cliente->identificacion); ?></div>
            </div>

            <div class="col-md-3">
                <h6 class="text-muted mb-2">Correo electrónico</h6>
                <div class="fs-5"><?php echo e($cliente->email); ?></div>
            </div>

            <div class="col-md-3">
                <h6 class="text-muted mb-2">Teléfono</h6>
                <div class="fs-5"><?php echo e($cliente->telefono); ?></div>
            </div>

            <div class="col-12">
                <h6 class="text-muted mb-2">Dirección</h6>
                <div class="fs-6"><?php echo e($cliente->direccion); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted">Cantidad de compras</h6>
                <h3 class="fw-bold text-primary mb-0"><?php echo e($cantidadCompras); ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted">Total gastado</h6>
                <h3 class="fw-bold text-success mb-0">₡ <?php echo e(number_format($totalGastado, 2)); ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted">Última compra</h6>
                <?php if($ultimaCompra): ?>
                    <h5 class="fw-bold mb-1">Venta #<?php echo e($ultimaCompra->id); ?></h5>
                    <p class="mb-0 text-muted"><?php echo e($ultimaCompra->created_at->format('d/m/Y H:i')); ?></p>
                <?php else: ?>
                    <p class="mb-0 text-muted">Este cliente no tiene compras registradas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <h4 class="mb-4">Compras realizadas</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>ID Venta</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $cliente->ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>#<?php echo e($venta->id); ?></td>
                            <td><?php echo e($venta->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <span class="badge bg-success fs-6">
                                    ₡ <?php echo e(number_format($venta->total, 2)); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('ventas.show', $venta->id)); ?>" class="btn btn-sm btn-info text-white">
                                    Ver venta
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Este cliente no tiene compras registradas todavía.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/clientes/show.blade.php ENDPATH**/ ?>