<?php
    $subtotal = $venta->detalles->sum(function ($detalle) {
        return $detalle->precio * $detalle->cantidad;
    });
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura Venta #<?php echo e($venta->id); ?></title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2b2b2b;
            margin: 30px;
        }

        .header{
            width: 100%;
            margin-bottom: 25px;
        }

        .empresa{
            float: left;
            width: 55%;
        }

        .empresa h1{
            margin: 0;
            font-size: 24px;
            color: #0d6efd;
        }

        .empresa p{
            margin: 4px 0;
            color: #555;
        }

        .factura-info{
            float: right;
            width: 40%;
            text-align: right;
        }

        .factura-info h2{
            margin: 0;
            font-size: 20px;
            color: #222;
        }

        .factura-numero{
            margin-top: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #0d6efd;
        }

        .clear{
            clear: both;
        }

        .bloque{
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }

        .bloque h3{
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #333;
        }

        .dato{
            margin-bottom: 5px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th{
            background: #f3f6fb;
            border: 1px solid #dcdcdc;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }

        table td{
            border: 1px solid #dcdcdc;
            padding: 9px 10px;
            vertical-align: top;
        }

        .text-right{
            text-align: right;
        }

        .resumen{
            margin-top: 20px;
            width: 100%;
        }

        .resumen-box{
            float: right;
            width: 340px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            padding: 14px;
        }

        .resumen-row{
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .total-final{
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #dcdcdc;
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
        }

        .footer{
            margin-top: 60px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        .small-muted{
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="empresa">
            <h1>Sistema de Ventas</h1>
            <p>Comprobante generado por el sistema</p>
            <p>Documento interno de venta</p>
        </div>

        <div class="factura-info">
            <h2>FACTURA</h2>
            <div class="factura-numero">
                N.° FAC-<?php echo e(str_pad($venta->id, 6, '0', STR_PAD_LEFT)); ?>

            </div>
        </div>

        <div class="clear"></div>
    </div>

    <div class="bloque">
        <h3>Información de la venta</h3>

        <div class="dato">
            <strong>Cliente:</strong>
            <?php echo e($venta->cliente?->nombre ?? 'Cliente eliminado'); ?>

        </div>

        <?php if($venta->cliente?->identificacion): ?>
            <div class="dato">
                <strong>Identificación:</strong>
                <?php echo e($venta->cliente->identificacion); ?>

            </div>
        <?php endif; ?>

        <?php if($venta->cliente?->email): ?>
            <div class="dato">
                <strong>Correo:</strong>
                <?php echo e($venta->cliente->email); ?>

            </div>
        <?php endif; ?>

        <?php if($venta->cliente?->telefono): ?>
            <div class="dato">
                <strong>Teléfono:</strong>
                <?php echo e($venta->cliente->telefono); ?>

            </div>
        <?php endif; ?>

        <?php if($venta->cliente?->direccion): ?>
            <div class="dato">
                <strong>Dirección:</strong>
                <?php echo e($venta->cliente->direccion); ?>

            </div>
        <?php endif; ?>

        <div class="dato">
            <strong>Fecha:</strong>
            <?php echo e($venta->created_at->format('d/m/Y H:i:s')); ?>

        </div>

        <div class="dato">
            <strong>ID de venta:</strong>
            #<?php echo e($venta->id); ?>

        </div>

        <div class="dato">
            <strong>Tipo de descuento:</strong>
            <?php echo e($venta->tipo_descuento === 'porcentaje' ? 'Porcentaje' : 'Monto fijo'); ?>

        </div>

        <div class="dato">
            <strong>Valor descuento:</strong>
            <?php if($venta->tipo_descuento === 'porcentaje'): ?>
                <?php echo e(number_format($venta->valor_descuento, 2)); ?>%
            <?php else: ?>
                ₡ <?php echo e(number_format($venta->valor_descuento, 2)); ?>

            <?php endif; ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>SKU</th>
                <th>Marca</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($detalle->producto?->nombre ?? 'Producto eliminado'); ?></td>
                    <td><?php echo e($detalle->producto?->sku ?? 'N/A'); ?></td>
                    <td><?php echo e($detalle->producto?->marca ?? 'N/A'); ?></td>
                    <td class="text-right">₡ <?php echo e(number_format($detalle->precio, 2)); ?></td>
                    <td class="text-right"><?php echo e($detalle->cantidad); ?></td>
                    <td class="text-right">₡ <?php echo e(number_format($detalle->precio * $detalle->cantidad, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="resumen">
        <div class="resumen-box">
            <div class="resumen-row">
                <span>Total de productos:</span>
                <span><?php echo e($venta->detalles->sum('cantidad')); ?></span>
            </div>

            <div class="resumen-row">
                <span>Subtotal:</span>
                <span>₡ <?php echo e(number_format($subtotal, 2)); ?></span>
            </div>

            <div class="resumen-row">
                <span>Descuento aplicado:</span>
                <span>- ₡ <?php echo e(number_format($venta->descuento, 2)); ?></span>
            </div>

            <div class="resumen-row total-final">
                <span>Total final:</span>
                <span>₡ <?php echo e(number_format($venta->total, 2)); ?></span>
            </div>
        </div>

        <div class="clear"></div>
    </div>

    <div class="footer">
        Gracias por su compra. <br>
        Documento generado automáticamente por el Sistema de Ventas.
    </div>

</body>
</html><?php /**PATH /var/www/resources/views/ventas/factura.blade.php ENDPATH**/ ?>