

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h2 class="section-title">💳 Nueva Venta</h2>
    <p class="section-subtitle">Seleccione productos, cliente y registre la venta</p>
</div>

<form action="<?php echo e(route('ventas.store')); ?>" method="POST" id="formVenta">
    <?php echo csrf_field(); ?>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold">Productos disponibles</h5>
                </div>

                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <input
                            type="text"
                            id="buscarProducto"
                            class="form-control"
                            placeholder="Buscar por nombre, marca o SKU..."
                        >
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tablaProductos">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($producto->nombre); ?></div>

                                            <?php if($producto->marca): ?>
                                                <small class="text-muted d-block">Marca: <?php echo e($producto->marca); ?></small>
                                            <?php endif; ?>

                                            <?php if($producto->sku): ?>
                                                <small class="text-secondary d-block">SKU: <?php echo e($producto->sku); ?></small>
                                            <?php endif; ?>
                                        </td>

                                        <td>₡ <?php echo e(number_format($producto->precio, 2)); ?></td>

                                        <td>
                                            <?php if($producto->stock <= 0): ?>
                                                <span class="badge bg-danger">Agotado</span>
                                            <?php elseif($producto->stock <= 5): ?>
                                                <span class="badge bg-warning text-dark"><?php echo e($producto->stock); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><?php echo e($producto->stock); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-success"
                                                onclick="agregarProducto(
                                                    <?php echo e($producto->id); ?>,
                                                    '<?php echo e(addslashes($producto->nombre)); ?>',
                                                    '<?php echo e(addslashes($producto->marca ?? '')); ?>',
                                                    '<?php echo e(addslashes($producto->sku ?? '')); ?>',
                                                    <?php echo e($producto->precio); ?>,
                                                    <?php echo e($producto->stock); ?>

                                                )"
                                                <?php echo e($producto->stock <= 0 ? 'disabled' : ''); ?>

                                            >
                                                Agregar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold">Carrito</h5>
                </div>

                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table align-middle" id="carrito">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="carritoBody">
                                <tr id="filaVacia">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No hay productos agregados.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <label class="form-label fw-semibold">Cliente</label>
                        <select name="cliente_id" class="form-select <?php $__errorArgs = ['cliente_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Seleccione un cliente</option>
                            <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cliente->id); ?>" <?php echo e(old('cliente_id') == $cliente->id ? 'selected' : ''); ?>>
                                    <?php echo e($cliente->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['cliente_id'];
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

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de descuento</label>
                            <select
                                name="tipo_descuento"
                                id="tipo_descuento"
                                class="form-select <?php $__errorArgs = ['tipo_descuento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                onchange="calcularTotal()"
                            >
                                <option value="monto" <?php echo e(old('tipo_descuento', 'monto') == 'monto' ? 'selected' : ''); ?>>
                                    Monto fijo
                                </option>
                                <option value="porcentaje" <?php echo e(old('tipo_descuento') == 'porcentaje' ? 'selected' : ''); ?>>
                                    Porcentaje
                                </option>
                            </select>
                            <?php $__errorArgs = ['tipo_descuento'];
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

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Valor descuento</label>
                            <input
                                type="number"
                                name="valor_descuento"
                                id="valor_descuento"
                                class="form-control <?php $__errorArgs = ['valor_descuento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Ej: 5000 o 10"
                                min="0"
                                step="0.01"
                                value="<?php echo e(old('valor_descuento', 0)); ?>"
                                oninput="calcularTotal()"
                            >
                            <?php $__errorArgs = ['valor_descuento'];
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

                    <div class="mt-4 p-3 rounded" style="background:#f8f9fc;">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Subtotal</span>
                            <span>₡ <span id="subtotal">0.00</span></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold text-danger">Descuento aplicado</span>
                            <span class="text-danger">₡ <span id="descuentoVista">0.00</span></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold text-muted">Detalle</span>
                            <span class="text-muted"><span id="detalleDescuento">Sin descuento</span></span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Total final</span>
                            <h4 class="mb-0 text-primary">₡ <span id="total">0.00</span></h4>
                        </div>
                    </div>

                    <button type="submit" id="btnRegistrar" class="btn btn-primary w-100 mt-4">
                        Registrar Venta
                    </button>
                </div>
            </div>
        </div>

    </div>

    <input type="hidden" name="productos" id="productosInput" value="<?php echo e(old('productos', '[]')); ?>">
</form>

<script>
let carrito = [];

try {
    carrito = JSON.parse(document.getElementById('productosInput').value || '[]');
    if (!Array.isArray(carrito)) {
        carrito = [];
    }
} catch (e) {
    carrito = [];
}

function formatoMoneda(valor) {
    return Number(valor).toLocaleString('es-CR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function actualizarBoton() {
    const btn = document.getElementById('btnRegistrar');
    if (btn) {
        btn.disabled = carrito.length === 0;
    }
}

function agregarProducto(id, nombre, marca, sku, precio, stock) {
    let existe = carrito.find(p => p.id === id);

    if (existe) {
        if (existe.cantidad >= stock) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuficiente',
                    text: 'No puede agregar más unidades de las disponibles.'
                });
            }
            return;
        }
        existe.cantidad++;
    } else {
        carrito.push({
            id: id,
            nombre: nombre,
            marca: marca,
            sku: sku,
            precio: Number(precio),
            cantidad: 1,
            stock: Number(stock)
        });
    }

    renderCarrito();
}

function cambiarCantidad(index, accion) {
    if (!carrito[index]) return;

    if (accion === 'sumar') {
        if (carrito[index].cantidad < carrito[index].stock) {
            carrito[index].cantidad++;
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuficiente',
                    text: 'No puede agregar más unidades de las disponibles.'
                });
            }
        }
    }

    if (accion === 'restar') {
        carrito[index].cantidad--;
        if (carrito[index].cantidad <= 0) {
            carrito.splice(index, 1);
        }
    }

    renderCarrito();
}

function eliminarProducto(index) {
    carrito.splice(index, 1);
    renderCarrito();
}

function calcularTotal(subtotalManual = null) {
    let subtotal = subtotalManual;

    if (subtotal === null) {
        subtotal = 0;
        carrito.forEach(p => {
            subtotal += Number(p.precio) * Number(p.cantidad);
        });
    }

    const tipo = document.getElementById('tipo_descuento').value;
    const valorInput = document.getElementById('valor_descuento');

    let valorTexto = (valorInput.value || '').replace(',', '.').trim();
    let valor = parseFloat(valorTexto);

    if (isNaN(valor) || valor < 0) {
        valor = 0;
    }

    let descuentoCalculado = 0;
    let detalle = 'Sin descuento';

    if (tipo === 'porcentaje') {
        if (valor > 100) {
            valor = 100;
            valorInput.value = 100;
        }

        descuentoCalculado = subtotal * (valor / 100);
        detalle = valor > 0 ? `${valor}% aplicado` : 'Sin descuento';
    }

    if (tipo === 'monto') {
        // Solo limitar si ya existe subtotal real
        if (subtotal > 0 && valor > subtotal) {
            valor = subtotal;
            valorInput.value = subtotal.toFixed(2);
        }

        descuentoCalculado = valor;
        detalle = valor > 0
            ? `₡ ${formatoMoneda(valor)} de descuento`
            : 'Sin descuento';
    }

    let total = subtotal - descuentoCalculado;

    if (total < 0) {
        total = 0;
    }

    document.getElementById('subtotal').innerText = formatoMoneda(subtotal);
    document.getElementById('descuentoVista').innerText = formatoMoneda(descuentoCalculado);
    document.getElementById('detalleDescuento').innerText = detalle;
    document.getElementById('total').innerText = formatoMoneda(total);
}

function renderCarrito() {
    let body = document.getElementById('carritoBody');
    let subtotal = 0;

    body.innerHTML = '';

    if (carrito.length === 0) {
        body.innerHTML = `
            <tr id="filaVacia">
                <td colspan="5" class="text-center text-muted py-4">
                    No hay productos agregados.
                </td>
            </tr>
        `;
    } else {
        carrito.forEach((p, index) => {
            let sub = p.precio * p.cantidad;
            subtotal += sub;

            body.innerHTML += `
                <tr>
                    <td>
                        <div class="fw-semibold">${p.nombre}</div>
                        ${p.marca ? `<small class="text-muted d-block">Marca: ${p.marca}</small>` : ''}
                        ${p.sku ? `<small class="text-secondary d-block">SKU: ${p.sku}</small>` : ''}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${index}, 'restar')">-</button>
                            <span>${p.cantidad}</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${index}, 'sumar')">+</button>
                        </div>
                    </td>
                    <td>₡ ${formatoMoneda(p.precio)}</td>
                    <td>₡ ${formatoMoneda(sub)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${index})">X</button>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById('productosInput').value = JSON.stringify(carrito);
    calcularTotal(subtotal);
    actualizarBoton();
}

document.getElementById('formVenta').addEventListener('submit', function() {
    document.getElementById('productosInput').value = JSON.stringify(carrito);
});

document.getElementById('buscarProducto').addEventListener('keyup', function() {
    const texto = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaProductos tbody tr');

    filas.forEach(fila => {
        const contenido = fila.innerText.toLowerCase();
        fila.style.display = contenido.includes(texto) ? '' : 'none';
    });
});

renderCarrito();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/ventas/create.blade.php ENDPATH**/ ?>