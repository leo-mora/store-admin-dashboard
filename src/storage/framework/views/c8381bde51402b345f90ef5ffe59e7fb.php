

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h2 class="section-title">✏️ Editar Cliente</h2>
    <p class="section-subtitle">Modifique la información del cliente</p>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="<?php echo e(route('clientes.update', $cliente->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <?php echo $__env->make('clientes.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary px-4">
                    Actualizar
                </button>

                <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/clientes/edit.blade.php ENDPATH**/ ?>