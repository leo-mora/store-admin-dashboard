

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="section-title">💰 Ventas</h2>
        <p class="section-subtitle">Consulte y administre las ventas registradas</p>
    </div>

    <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-primary px-4">
        + Nueva Venta
    </a>
</div>

<div class="card">
    <div class="card-body p-4">

        <form method="GET" action="<?php echo e(route('ventas.index')); ?>" class="row g-3 mb-4">
            <div class="col-md-6">
                <input
                    type="text"
                    name="buscar"
                    class="form-control"
                    placeholder="Buscar por nombre del cliente..."
                    value="<?php echo e(request('buscar')); ?>"
                >
            </div>

            <div class="col-md-auto">
                <button class="btn btn-outline-primary">
                    Buscar
                </button>
            </div>

            <?php if(request('buscar')): ?>
                <div class="col-md-auto">
                    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                </div>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($venta->id); ?></td>

                            <td>
                                <strong><?php echo e($venta->cliente?->nombre ?? 'Cliente eliminado'); ?></strong>
                            </td>

                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    ₡ <?php echo e(number_format($venta->total, 2)); ?>

                                </span>

                                <?php if($venta->descuento > 0): ?>
                                    <div class="text-danger small mt-1">
                                        Descuento: -₡ <?php echo e(number_format($venta->descuento, 2)); ?>

                                    </div>
                                <?php endif; ?>
                            </td>

                            <td><?php echo e($venta->created_at->format('d/m/Y H:i')); ?></td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="<?php echo e(route('ventas.show', $venta->id)); ?>" class="btn btn-info btn-sm text-white">
                                        Ver
                                    </a>

                                    <form action="<?php echo e(route('ventas.destroy', $venta->id)); ?>" method="POST" class="form-eliminar m-0">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay ventas registradas.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($ventas->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($ventas->links()); ?>

            </div>
        <?php endif; ?>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/ventas/index.blade.php ENDPATH**/ ?>