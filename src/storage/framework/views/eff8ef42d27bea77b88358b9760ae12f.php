<div style="font-family: Arial, sans-serif; background:#f4f6f9; padding:30px; margin:0;">

    <div style="max-width:650px; margin:auto; background:white; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">

        <div style="text-align:center; margin-bottom:25px;">
            <h1 style="margin:0; color:#0d6efd; font-size:28px;">
                🙌 ¡Gracias por su compra!
            </h1>
            <p style="margin:10px 0 0 0; font-size:15px; color:#666;">
                Su comprobante ha sido generado correctamente.
            </p>
        </div>

        <p style="font-size:16px; color:#333; margin-bottom:10px;">
            Hola <strong><?php echo e($venta->cliente->nombre ?? 'cliente'); ?></strong>,
        </p>

        <p style="font-size:15px; color:#555; line-height:1.6; margin-bottom:20px;">
            Hemos recibido su compra correctamente en el <strong>Sistema de Ventas</strong>.
            Adjuntamos la factura en formato PDF con el detalle completo de los productos adquiridos.
        </p>

        <div style="background:#f8f9fa; border:1px solid #e9ecef; padding:18px; border-radius:10px; margin:20px 0;">

            <p style="margin:6px 0; font-size:15px; color:#333;">
                <strong>Número de venta:</strong> #<?php echo e($venta->id); ?>

            </p>

            <?php if($venta->cliente?->identificacion): ?>
                <p style="margin:6px 0; font-size:15px; color:#333;">
                    <strong>Identificación:</strong> <?php echo e($venta->cliente->identificacion); ?>

                </p>
            <?php endif; ?>

            <?php if($venta->cliente?->email): ?>
                <p style="margin:6px 0; font-size:15px; color:#333;">
                    <strong>Correo:</strong> <?php echo e($venta->cliente->email); ?>

                </p>
            <?php endif; ?>

            <?php if($venta->cliente?->telefono): ?>
                <p style="margin:6px 0; font-size:15px; color:#333;">
                    <strong>Teléfono:</strong> <?php echo e($venta->cliente->telefono); ?>

                </p>
            <?php endif; ?>

            <p style="margin:6px 0; font-size:15px; color:#333;">
                <strong>Fecha:</strong> <?php echo e($venta->created_at->format('d/m/Y H:i')); ?>

            </p>

            <p style="margin:12px 0 0 0; font-size:20px; color:#198754;">
                <strong>Total pagado:</strong> ₡ <?php echo e(number_format($venta->total, 2)); ?>

            </p>

        </div>

        <p style="font-size:15px; color:#555; line-height:1.6; margin-bottom:10px;">
            Puede revisar el detalle completo de la compra en el archivo PDF adjunto, donde se incluyen los productos, cantidades, precios, descuento aplicado y total final.
        </p>

       <p style="font-size:15px; color:#666; margin-bottom:0;">
    Si tiene alguna consulta, puede comunicarse con la tienda por los medios de contacto correspondientes.
</p>

        <hr style="margin:30px 0; border:none; border-top:1px solid #e5e5e5;">

        <p style="text-align:center; font-size:13px; color:#999; margin:0;">
            Sistema de Ventas © <?php echo e(date('Y')); ?>

        </p>

    </div>

</div><?php /**PATH /var/www/resources/views/emails/factura.blade.php ENDPATH**/ ?>