

<?php $__env->startSection('content'); ?>

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">👥 Usuarios</h2>
        <p class="section-subtitle">Administre los usuarios del sistema</p>
    </div>

    <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-primary px-4">
        + Nuevo Usuario
    </a>
</div>

<div class="card">
    <div class="card-body p-4">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>#<?php echo e($u->id); ?></td>

                            <td>
                                <strong><?php echo e($u->name); ?></strong>
                            </td>

                            <td><?php echo e($u->email); ?></td>

                            <td>
                                <?php if($u->rol === 'admin'): ?>
                                    <span class="badge bg-primary px-3 py-2">Administrador</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-3 py-2">Empleado</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="<?php echo e(route('usuarios.edit', $u->id)); ?>" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="<?php echo e(route('usuarios.destroy', $u->id)); ?>" method="POST" class="form-eliminar d-inline m-0">
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
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/usuarios/index.blade.php ENDPATH**/ ?>