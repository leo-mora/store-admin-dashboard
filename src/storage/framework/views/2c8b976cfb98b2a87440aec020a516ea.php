

<?php $__env->startSection('content'); ?>

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">👤 Clientes</h2>
        <p class="section-subtitle">Administre los clientes registrados</p>
    </div>

    <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-primary px-4">
        + Nuevo Cliente
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">

        <form method="GET" action="<?php echo e(route('clientes.index')); ?>">
            <div class="row g-3 align-items-center">

                <div class="col-md-8">
                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por nombre, identificación, email o teléfono..."
                        value="<?php echo e(request('buscar')); ?>"
                    >
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary px-4">
                        Buscar
                    </button>

                    <?php if(request('buscar')): ?>
                        <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-outline-secondary">
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
            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th>Compras</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>#<?php echo e($cliente->id); ?></td>

                            <td>
                                <div class="fw-semibold"><?php echo e($cliente->nombre); ?></div>
                                <small class="text-muted">ID: <?php echo e($cliente->identificacion); ?></small>
                            </td>

                            <td>
                                <div><?php echo e($cliente->email); ?></div>
                                <small class="text-muted"><?php echo e($cliente->telefono); ?></small>
                            </td>

                            <td>
                                <small class="text-muted"><?php echo e($cliente->direccion); ?></small>
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    <?php echo e($cliente->ventas_count); ?> compra(s)
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">

                                    <a href="<?php echo e(route('clientes.show', $cliente->id)); ?>" class="btn btn-sm btn-info text-white">
                                        Historial
                                    </a>

                                    <a href="<?php echo e(route('clientes.edit', $cliente->id)); ?>" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    <form
                                        action="<?php echo e(route('clientes.destroy', $cliente->id)); ?>"
                                        method="POST"
                                        class="form-eliminar m-0"
                                    >
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button class="btn btn-sm btn-danger">
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay clientes registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            <?php echo e($clientes->links()); ?>

        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/clientes/index.blade.php ENDPATH**/ ?>