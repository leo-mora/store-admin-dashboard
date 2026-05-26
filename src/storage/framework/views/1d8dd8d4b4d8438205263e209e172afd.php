

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h2 class="section-title">🧾 Venta #<?php echo e($venta->id); ?></h2>
    <p class="section-subtitle">Detalle completo de la venta registrada</p>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-2">
                <div class="text-muted small">Cliente</div>
                <div class="fw-semibold fs-5"><?php echo e($venta->cliente?->nombre ?? 'Cliente eliminado'); ?></div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Identificación</div>
                <div class="fw-semibold"><?php echo e($venta->cliente?->identificacion ?? 'Sin registro'); ?></div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Fecha</div>
                <div class="fw-semibold"><?php echo e($venta->created_at->format('d/m/Y H:i:s')); ?></div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Subtotal</div>
                <div class="fw-semibold">₡ <?php echo e(number_format($subtotal, 2)); ?></div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Descuento</div>
                <div class="fw-semibold text-danger">
                    <?php if($venta->tipo_descuento === 'porcentaje'): ?>
                        <?php echo e(number_format($venta->valor_descuento, 2)); ?>% (-₡ <?php echo e(number_format($venta->descuento, 2)); ?>)
                    <?php else: ?>
                        ₡ <?php echo e(number_format($venta->descuento, 2)); ?>

                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Total pagado</div>
                <div class="fw-bold fs-4 text-primary">₡ <?php echo e(number_format($venta->total, 2)); ?></div>
            </div>

            <div class="col-md-6">
                <div class="text-muted small">Correo electrónico</div>
                <div class="fw-semibold"><?php echo e($venta->cliente?->email ?? 'Sin correo'); ?></div>
            </div>

            <div class="col-md-4">
                <div class="text-muted small">Teléfono</div>
                <div class="fw-semibold"><?php echo e($venta->cliente?->telefono ?? 'Sin teléfono'); ?></div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Estado</div>
                <span class="badge bg-<?php echo e($claseEstado); ?> fs-6">
                    <?php echo e($estadoDevolucion); ?>

                </span>
            </div>

            <div class="col-12">
                <div class="text-muted small">Dirección</div>
                <div class="fw-semibold"><?php echo e($venta->cliente?->direccion ?? 'Sin dirección registrada'); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-muted small">Total devuelto acumulado</div>
                <div class="fw-bold fs-3 text-danger">₡ <?php echo e(number_format($totalDevuelto, 2)); ?></div>
            </div>

            <div class="col-md-4">
                <div class="text-muted small">Monto neto actual</div>
                <div class="fw-bold fs-3 text-success">₡ <?php echo e(number_format($montoNeto, 2)); ?></div>
            </div>

            <div class="col-md-4 d-flex align-items-end justify-content-md-end">
                <?php if($montoNeto > 0): ?>
                    <a href="<?php echo e(route('devoluciones.create', $venta->id)); ?>" class="btn btn-warning">
                        ↩️ Registrar devolución
                    </a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>
                        Venta completamente devuelta
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if($montoNeto <= 0): ?>
            <div class="alert alert-danger mt-4 mb-0">
                Esta venta fue devuelta completamente. La factura y el envío por correo están deshabilitados.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($detalle->producto?->nombre ?? 'Producto eliminado'); ?></td>
                            <td><?php echo e($detalle->producto?->sku ?? 'N/A'); ?></td>
                            <td><?php echo e($detalle->producto?->marca ?? 'N/A'); ?></td>
                            <td>₡ <?php echo e(number_format($detalle->precio, 2)); ?></td>
                            <td><?php echo e($detalle->cantidad); ?></td>
                            <td>₡ <?php echo e(number_format($detalle->precio * $detalle->cantidad, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Subtotal:</th>
                        <th>₡ <?php echo e(number_format($subtotal, 2)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Descuento aplicado:</th>
                        <th class="text-danger">₡ <?php echo e(number_format($venta->descuento, 2)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Total pagado:</th>
                        <th class="text-primary">₡ <?php echo e(number_format($venta->total, 2)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Total devuelto:</th>
                        <th class="text-danger">₡ <?php echo e(number_format($totalDevuelto, 2)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Monto neto actual:</th>
                        <th class="text-success">₡ <?php echo e(number_format($montoNeto, 2)); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-outline-secondary">
                Volver a ventas
            </a>

            <?php if($venta->cliente): ?>
                <a href="<?php echo e(route('clientes.show', $venta->cliente->id)); ?>" class="btn btn-outline-primary">
                    Ver historial del cliente
                </a>
            <?php endif; ?>

            <?php if($montoNeto > 0): ?>
                <a href="<?php echo e(route('ventas.factura', $venta->id)); ?>" class="btn btn-danger">
                    Descargar PDF
                </a>

                <form action="<?php echo e(route('ventas.enviarFactura', $venta->id)); ?>" method="POST" class="form-enviar-factura m-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success">
                        Enviar por correo
                    </button>
                </form>
            <?php else: ?>
                <button class="btn btn-danger" disabled>
                    Descargar PDF
                </button>

                <button class="btn btn-success" disabled>
                    Enviar por correo
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if($venta->devoluciones->count() > 0): ?>
    <div class="card">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4">Historial de devoluciones</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Total devuelto</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $venta->devoluciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $devolucion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>#<?php echo e($devolucion->id); ?></td>
                                <td><?php echo e($devolucion->created_at->format('d/m/Y H:i')); ?></td>
                                <td><?php echo e($devolucion->motivo ?: 'Sin motivo registrado'); ?></td>
                                <td class="text-danger">₡ <?php echo e(number_format($devolucion->total_devuelto, 2)); ?></td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('devoluciones.show', $devolucion->id)); ?>" class="btn btn-sm btn-info text-white">
                                        Ver devolución
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/ventas/show.blade.php ENDPATH**/ ?>