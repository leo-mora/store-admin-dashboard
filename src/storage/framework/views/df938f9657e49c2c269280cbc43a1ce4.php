

<?php $__env->startSection('content'); ?>

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">↩️ Nueva Devolución</h2>
        <p class="section-subtitle">Registre productos devueltos de la venta #<?php echo e($venta->id); ?></p>
    </div>

    <a href="<?php echo e(route('ventas.show', $venta->id)); ?>" class="btn btn-outline-secondary">
        Volver a la venta
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-muted small">Venta</div>
                <div class="fw-semibold fs-5">#<?php echo e($venta->id); ?></div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Cliente</div>
                <div class="fw-semibold fs-5"><?php echo e($venta->cliente?->nombre ?? 'Cliente eliminado'); ?></div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Fecha</div>
                <div class="fw-semibold"><?php echo e($venta->created_at->format('d/m/Y H:i:s')); ?></div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Total pagado en la venta</div>
                <div class="fw-bold fs-4 text-primary">₡ <?php echo e(number_format($venta->total, 2)); ?></div>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo e(route('devoluciones.store', $venta->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="card mb-4">
        <div class="card-body p-4">
            <label class="form-label fw-semibold">Motivo de la devolución</label>
            <textarea
                name="motivo"
                rows="3"
                class="form-control <?php $__errorArgs = ['motivo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                placeholder="Ejemplo: producto defectuoso, cliente cambió de opinión, error de entrega..."
            ><?php echo e(old('motivo')); ?></textarea>

            <?php $__errorArgs = ['motivo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block">
                    <?php echo e($message); ?>

                </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4">Productos vendidos</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Vendidos</th>
                            <th>Ya devueltos</th>
                            <th>Máximo disponible</th>
                            <th>Precio original</th>
                            <th>Neto por línea</th>
                            <th>Cantidad a devolver</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $yaDevueltos = $cantidadesDevueltas[$detalle->producto_id] ?? 0;
                                $maximo = $detalle->cantidad - $yaDevueltos;
                                $linea = $lineas[$detalle->producto_id];
                            ?>

                            <tr>
                                <td><?php echo e($detalle->producto?->nombre ?? 'Producto eliminado'); ?></td>
                                <td><?php echo e($detalle->cantidad); ?></td>
                                <td><?php echo e($yaDevueltos); ?></td>
                                <td>
                                    <span class="badge <?php echo e($maximo > 0 ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($maximo); ?>

                                    </span>
                                </td>
                                <td>₡ <?php echo e(number_format($detalle->precio, 2)); ?></td>
                                <td>₡ <?php echo e(number_format($linea['neto_total'], 2)); ?></td>
                                <td style="max-width: 150px;">
                                    <input
                                        type="number"
                                        name="cantidades[<?php echo e($detalle->producto_id); ?>]"
                                        class="form-control"
                                        min="0"
                                        max="<?php echo e($maximo); ?>"
                                        value="<?php echo e(old('cantidades.' . $detalle->producto_id, 0)); ?>"
                                        <?php echo e($maximo <= 0 ? 'disabled' : ''); ?>

                                    >
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-info mt-4 mb-0">
                El monto devuelto se calculará con base en lo que realmente pagó el cliente, incluyendo el descuento aplicado en la venta.
            </div>

            <div class="mt-4 d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('ventas.show', $venta->id)); ?>" class="btn btn-outline-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    Registrar devolución
                </button>
            </div>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/devoluciones/create.blade.php ENDPATH**/ ?>