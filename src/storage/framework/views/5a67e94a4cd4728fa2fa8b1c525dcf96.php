

<?php $__env->startSection('content'); ?>

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">↩️ Devolución #<?php echo e($devolucion->id); ?></h2>
        <p class="section-subtitle">Detalle completo de la devolución registrada</p>
    </div>

    <a href="<?php echo e(route('ventas.show', $devolucion->venta_id)); ?>" class="btn btn-outline-secondary">
        Volver a la venta
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-muted small">Venta relacionada</div>
                <div class="fw-semibold fs-5">#<?php echo e($devolucion->venta_id); ?></div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Cliente</div>
                <div class="fw-semibold fs-5"><?php echo e($devolucion->venta?->cliente?->nombre ?? 'Cliente eliminado'); ?></div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Fecha</div>
                <div class="fw-semibold"><?php echo e($devolucion->created_at->format('d/m/Y H:i:s')); ?></div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Total devuelto</div>
                <div class="fw-bold fs-4 text-danger">₡ <?php echo e(number_format($devolucion->total_devuelto, 2)); ?></div>
            </div>
        </div>

        <?php if($devolucion->motivo): ?>
            <hr>
            <div>
                <div class="text-muted small mb-1">Motivo</div>
                <div><?php echo e($devolucion->motivo); ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad devuelta</th>
                        <th>Subtotal devuelto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $devolucion->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($detalle->producto?->nombre ?? 'Producto eliminado'); ?></td>
                            <td>₡ <?php echo e(number_format($detalle->precio, 2)); ?></td>
                            <td><?php echo e($detalle->cantidad); ?></td>
                            <td class="text-danger">₡ <?php echo e(number_format($detalle->subtotal, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total devuelto:</th>
                        <th class="text-danger">₡ <?php echo e(number_format($devolucion->total_devuelto, 2)); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/devoluciones/show.blade.php ENDPATH**/ ?>