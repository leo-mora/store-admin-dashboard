

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="section-title">📋 Bitácora del sistema</h2>
        <p class="section-subtitle">Consulte las acciones realizadas por los usuarios</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="<?php echo e(route('bitacoras.index')); ?>">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por usuario, módulo, acción o descripción..."
                        value="<?php echo e(request('buscar')); ?>"
                    >
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary px-4">
                        Buscar
                    </button>

                    <?php if(request('buscar')): ?>
                        <a href="<?php echo e(route('bitacoras.index')); ?>" class="btn btn-outline-secondary">
                            Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Módulo</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bitacoras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bitacora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>#<?php echo e($bitacora->id); ?></td>
                            <td>
                                <div class="fw-semibold">
                                    <?php echo e($bitacora->usuario?->name ?? 'Usuario eliminado'); ?>

                                </div>
                                <small class="text-muted">
                                    <?php echo e($bitacora->usuario?->email ?? 'Sin correo'); ?>

                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <?php echo e($bitacora->modulo); ?>

                                </span>
                            </td>
                            <td><?php echo e($bitacora->accion); ?></td>
                            <td><?php echo e($bitacora->descripcion); ?></td>
                            <td><?php echo e($bitacora->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay registros en la bitácora.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($bitacoras->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($bitacoras->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/bitacoras/index.blade.php ENDPATH**/ ?>