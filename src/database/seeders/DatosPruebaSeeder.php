<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('detalle_devoluciones')->truncate();
        DB::table('devoluciones')->truncate();
        DB::table('detalle_ventas')->truncate();
        DB::table('ventas')->truncate();
        DB::table('productos')->truncate();
        DB::table('clientes')->truncate();
        DB::table('categorias')->truncate();
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'rol' => 'admin',
        ]);

        User::create([
            'name' => 'Empleado',
            'email' => 'empleado@empleado.com',
            'password' => Hash::make('12345678'),
            'rol' => 'empleado',
        ]);

        DB::table('categorias')->insert([
            ['nombre' => 'Electrónica', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Hogar', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cocina', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Audio y Video', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Computación', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Accesorios', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('productos')->insert([
            ['nombre' => 'Laptop HP 15"', 'marca' => 'HP', 'sku' => 'PROD-0001', 'descripcion' => 'Laptop para estudio y oficina.', 'precio' => 350000, 'stock' => 12, 'categoria_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Mouse Inalámbrico Logitech', 'marca' => 'Logitech', 'sku' => 'PROD-0002', 'descripcion' => 'Mouse inalámbrico ergonómico.', 'precio' => 12000, 'stock' => 35, 'categoria_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Teclado Mecánico Redragon', 'marca' => 'Redragon', 'sku' => 'PROD-0003', 'descripcion' => 'Teclado mecánico con iluminación.', 'precio' => 25000, 'stock' => 18, 'categoria_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Televisor Samsung 50 Pulgadas', 'marca' => 'Samsung', 'sku' => 'PROD-0004', 'descripcion' => 'Smart TV 4K UHD.', 'precio' => 450000, 'stock' => 6, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Barra de Sonido Sony', 'marca' => 'Sony', 'sku' => 'PROD-0005', 'descripcion' => 'Barra de sonido bluetooth.', 'precio' => 135000, 'stock' => 9, 'categoria_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Microondas LG', 'marca' => 'LG', 'sku' => 'PROD-0006', 'descripcion' => 'Microondas digital de cocina.', 'precio' => 90000, 'stock' => 1, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Refrigeradora Samsung 420L', 'marca' => 'Samsung', 'sku' => 'PROD-0007', 'descripcion' => 'Refrigeradora familiar.', 'precio' => 600000, 'stock' => 3, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Ventilador de Torre', 'marca' => 'Mabe', 'sku' => 'PROD-0008', 'descripcion' => 'Ventilador vertical.', 'precio' => 45000, 'stock' => 5, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Plancha Black and Decker', 'marca' => 'Black and Decker', 'sku' => 'PROD-0009', 'descripcion' => 'Plancha para ropa.', 'precio' => 22000, 'stock' => 14, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cocina Eléctrica Mabe', 'marca' => 'Mabe', 'sku' => 'PROD-0010', 'descripcion' => 'Cocina eléctrica de cuatro quemadores.', 'precio' => 140000, 'stock' => 0, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Parlante JBL Flip', 'marca' => 'JBL', 'sku' => 'PROD-0011', 'descripcion' => 'Parlante portátil recargable.', 'precio' => 85000, 'stock' => 5, 'categoria_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Aspiradora Panasonic', 'marca' => 'Panasonic', 'sku' => 'PROD-0012', 'descripcion' => 'Aspiradora para el hogar.', 'precio' => 78000, 'stock' => 4, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Monitor LG 24"', 'marca' => 'LG', 'sku' => 'PROD-0013', 'descripcion' => 'Monitor Full HD.', 'precio' => 98000, 'stock' => 8, 'categoria_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Impresora Epson EcoTank', 'marca' => 'Epson', 'sku' => 'PROD-0014', 'descripcion' => 'Impresora multifuncional.', 'precio' => 165000, 'stock' => 6, 'categoria_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Tablet Lenovo 10"', 'marca' => 'Lenovo', 'sku' => 'PROD-0015', 'descripcion' => 'Tablet Android.', 'precio' => 145000, 'stock' => 7, 'categoria_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Freidora de Aire Oster', 'marca' => 'Oster', 'sku' => 'PROD-0016', 'descripcion' => 'Freidora de aire.', 'precio' => 72000, 'stock' => 7, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Licuadora Oster', 'marca' => 'Oster', 'sku' => 'PROD-0017', 'descripcion' => 'Licuadora de vidrio.', 'precio' => 35000, 'stock' => 11, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Horno Eléctrico Oster', 'marca' => 'Oster', 'sku' => 'PROD-0018', 'descripcion' => 'Horno eléctrico doméstico.', 'precio' => 65000, 'stock' => 9, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Lavadora LG 18kg', 'marca' => 'LG', 'sku' => 'PROD-0019', 'descripcion' => 'Lavadora automática.', 'precio' => 380000, 'stock' => 4, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Secadora Whirlpool', 'marca' => 'Whirlpool', 'sku' => 'PROD-0020', 'descripcion' => 'Secadora eléctrica.', 'precio' => 310000, 'stock' => 6, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cafetera Black and Decker', 'marca' => 'Black and Decker', 'sku' => 'PROD-0021', 'descripcion' => 'Cafetera doméstica.', 'precio' => 28000, 'stock' => 10, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Smartphone Samsung A25', 'marca' => 'Samsung', 'sku' => 'PROD-0022', 'descripcion' => 'Teléfono inteligente.', 'precio' => 210000, 'stock' => 13, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('clientes')->insert([
            ['nombre' => 'Carlos Pérez', 'identificacion' => '123456789', 'telefono' => '88881111', 'email' => 'carlos@gmail.com', 'direccion' => 'Liberia, Guanacaste, 300 metros al norte del parque.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Daniela Sánchez', 'identificacion' => '223456789', 'telefono' => '88882222', 'email' => 'daniela@gmail.com', 'direccion' => 'Santa Cruz, Guanacaste, barrio central.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Luis Herrera', 'identificacion' => '323456789', 'telefono' => '88883333', 'email' => 'luis@gmail.com', 'direccion' => 'Nicoya, Guanacaste, contiguo al banco.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Valeria Gómez', 'identificacion' => '423456789', 'telefono' => '88884444', 'email' => 'valeria@gmail.com', 'direccion' => 'Filadelfia, Guanacaste, calle principal.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Jorge Castillo', 'identificacion' => '523456789', 'telefono' => '88885555', 'email' => 'jorge@gmail.com', 'direccion' => 'Bagaces, Guanacaste, 100 metros al sur de la iglesia.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'José Ramírez', 'identificacion' => '623456789', 'telefono' => '88886666', 'email' => 'jose@gmail.com', 'direccion' => 'Cañas, Guanacaste, barrio norte.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Sofía Vargas', 'identificacion' => '723456789', 'telefono' => '88887777', 'email' => 'sofia@gmail.com', 'direccion' => 'Tilarán, Guanacaste, frente al parque.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Leonardo Mora', 'identificacion' => '823456789', 'telefono' => '88888888', 'email' => 'leo@gmail.com', 'direccion' => 'Santa Cruz, Guanacaste, cerca del colegio.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'María López', 'identificacion' => '923456789', 'telefono' => '88889999', 'email' => 'maria@gmail.com', 'direccion' => 'Carrillo, Guanacaste, del supermercado 50 metros al este.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Andrés Solano', 'identificacion' => '133456789', 'telefono' => '88771122', 'email' => 'andres@gmail.com', 'direccion' => 'La Cruz, Guanacaste, avenida central.', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Paula Jiménez', 'identificacion' => '144456789', 'telefono' => '88773344', 'email' => 'paula@gmail.com', 'direccion' => 'Nandayure, Guanacaste, costado norte del parque.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $fechas = [
            Carbon::now()->subDays(6)->setTime(9, 15, 0),
            Carbon::now()->subDays(6)->setTime(11, 40, 0),
            Carbon::now()->subDays(5)->setTime(10, 20, 0),
            Carbon::now()->subDays(4)->setTime(8, 50, 0),
            Carbon::now()->subDays(4)->setTime(15, 10, 0),
            Carbon::now()->subDays(3)->setTime(9, 30, 0),
            Carbon::now()->subDays(2)->setTime(13, 25, 0),
            Carbon::now()->subDays(2)->setTime(16, 45, 0),
            Carbon::now()->subDay()->setTime(10, 5, 0),
            Carbon::now()->subDay()->setTime(14, 20, 0),
            Carbon::now()->setTime(9, 0, 0),
            Carbon::now()->setTime(11, 15, 0),
        ];

        DB::table('ventas')->insert([
            ['id' => 1, 'cliente_id' => 1, 'total' => 612000, 'descuento' => 0, 'tipo_descuento' => 'monto', 'valor_descuento' => 0, 'created_at' => $fechas[0], 'updated_at' => $fechas[0]],
            ['id' => 2, 'cliente_id' => 2, 'total' => 143080, 'descuento' => 3920, 'tipo_descuento' => 'porcentaje', 'valor_descuento' => 2.67, 'created_at' => $fechas[1], 'updated_at' => $fechas[1]],
            ['id' => 3, 'cliente_id' => 3, 'total' => 110000, 'descuento' => 25000, 'tipo_descuento' => 'monto', 'valor_descuento' => 25000, 'created_at' => $fechas[2], 'updated_at' => $fechas[2]],
            ['id' => 4, 'cliente_id' => 4, 'total' => 177000, 'descuento' => 0, 'tipo_descuento' => 'monto', 'valor_descuento' => 0, 'created_at' => $fechas[3], 'updated_at' => $fechas[3]],
            ['id' => 5, 'cliente_id' => 5, 'total' => 510000, 'descuento' => 20000, 'tipo_descuento' => 'monto', 'valor_descuento' => 20000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],
            ['id' => 6, 'cliente_id' => 6, 'total' => 132670, 'descuento' => 1330, 'tipo_descuento' => 'porcentaje', 'valor_descuento' => 0.99, 'created_at' => $fechas[5], 'updated_at' => $fechas[5]],
            ['id' => 7, 'cliente_id' => 7, 'total' => 12000, 'descuento' => 0, 'tipo_descuento' => 'monto', 'valor_descuento' => 0, 'created_at' => $fechas[6], 'updated_at' => $fechas[6]],
            ['id' => 8, 'cliente_id' => 1, 'total' => 1062000, 'descuento' => 0, 'tipo_descuento' => 'monto', 'valor_descuento' => 0, 'created_at' => $fechas[7], 'updated_at' => $fechas[7]],
            ['id' => 9, 'cliente_id' => 8, 'total' => 483700, 'descuento' => 2300, 'tipo_descuento' => 'porcentaje', 'valor_descuento' => 0.47, 'created_at' => $fechas[8], 'updated_at' => $fechas[8]],
            ['id' => 10, 'cliente_id' => 9, 'total' => 284000, 'descuento' => 8000, 'tipo_descuento' => 'monto', 'valor_descuento' => 8000, 'created_at' => $fechas[9], 'updated_at' => $fechas[9]],
            ['id' => 11, 'cliente_id' => 10, 'total' => 210000, 'descuento' => 0, 'tipo_descuento' => 'monto', 'valor_descuento' => 0, 'created_at' => $fechas[10], 'updated_at' => $fechas[10]],
            ['id' => 12, 'cliente_id' => 11, 'total' => 98000, 'descuento' => 0, 'tipo_descuento' => 'monto', 'valor_descuento' => 0, 'created_at' => $fechas[11], 'updated_at' => $fechas[11]],
        ]);

        DB::table('detalle_ventas')->insert([
            ['venta_id' => 1, 'producto_id' => 7, 'cantidad' => 1, 'precio' => 600000, 'created_at' => $fechas[0], 'updated_at' => $fechas[0]],
            ['venta_id' => 1, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'created_at' => $fechas[0], 'updated_at' => $fechas[0]],

            ['venta_id' => 2, 'producto_id' => 3, 'cantidad' => 2, 'precio' => 25000, 'created_at' => $fechas[1], 'updated_at' => $fechas[1]],
            ['venta_id' => 2, 'producto_id' => 2, 'cantidad' => 8, 'precio' => 12000, 'created_at' => $fechas[1], 'updated_at' => $fechas[1]],

            ['venta_id' => 3, 'producto_id' => 5, 'cantidad' => 1, 'precio' => 135000, 'created_at' => $fechas[2], 'updated_at' => $fechas[2]],

            ['venta_id' => 4, 'producto_id' => 14, 'cantidad' => 1, 'precio' => 165000, 'created_at' => $fechas[3], 'updated_at' => $fechas[3]],
            ['venta_id' => 4, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'created_at' => $fechas[3], 'updated_at' => $fechas[3]],

            ['venta_id' => 5, 'producto_id' => 19, 'cantidad' => 1, 'precio' => 380000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],
            ['venta_id' => 5, 'producto_id' => 9, 'cantidad' => 1, 'precio' => 22000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],
            ['venta_id' => 5, 'producto_id' => 21, 'cantidad' => 1, 'precio' => 28000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],
            ['venta_id' => 5, 'producto_id' => 17, 'cantidad' => 1, 'precio' => 35000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],
            ['venta_id' => 5, 'producto_id' => 18, 'cantidad' => 1, 'precio' => 65000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],
            ['venta_id' => 5, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],
            ['venta_id' => 5, 'producto_id' => 8, 'cantidad' => 1, 'precio' => 45000, 'created_at' => $fechas[4], 'updated_at' => $fechas[4]],

            ['venta_id' => 6, 'producto_id' => 11, 'cantidad' => 1, 'precio' => 85000, 'created_at' => $fechas[5], 'updated_at' => $fechas[5]],
            ['venta_id' => 6, 'producto_id' => 2, 'cantidad' => 4, 'precio' => 12000, 'created_at' => $fechas[5], 'updated_at' => $fechas[5]],

            ['venta_id' => 7, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'created_at' => $fechas[6], 'updated_at' => $fechas[6]],

            ['venta_id' => 8, 'producto_id' => 7, 'cantidad' => 1, 'precio' => 600000, 'created_at' => $fechas[7], 'updated_at' => $fechas[7]],
            ['venta_id' => 8, 'producto_id' => 4, 'cantidad' => 1, 'precio' => 450000, 'created_at' => $fechas[7], 'updated_at' => $fechas[7]],
            ['venta_id' => 8, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'created_at' => $fechas[7], 'updated_at' => $fechas[7]],

            ['venta_id' => 9, 'producto_id' => 1, 'cantidad' => 1, 'precio' => 350000, 'created_at' => $fechas[8], 'updated_at' => $fechas[8]],
            ['venta_id' => 9, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'created_at' => $fechas[8], 'updated_at' => $fechas[8]],
            ['venta_id' => 9, 'producto_id' => 3, 'cantidad' => 1, 'precio' => 25000, 'created_at' => $fechas[8], 'updated_at' => $fechas[8]],
            ['venta_id' => 9, 'producto_id' => 17, 'cantidad' => 1, 'precio' => 35000, 'created_at' => $fechas[8], 'updated_at' => $fechas[8]],
            ['venta_id' => 9, 'producto_id' => 18, 'cantidad' => 1, 'precio' => 65000, 'created_at' => $fechas[8], 'updated_at' => $fechas[8]],

            ['venta_id' => 10, 'producto_id' => 15, 'cantidad' => 1, 'precio' => 145000, 'created_at' => $fechas[9], 'updated_at' => $fechas[9]],
            ['venta_id' => 10, 'producto_id' => 16, 'cantidad' => 1, 'precio' => 72000, 'created_at' => $fechas[9], 'updated_at' => $fechas[9]],
            ['venta_id' => 10, 'producto_id' => 21, 'cantidad' => 1, 'precio' => 28000, 'created_at' => $fechas[9], 'updated_at' => $fechas[9]],
            ['venta_id' => 10, 'producto_id' => 17, 'cantidad' => 1, 'precio' => 35000, 'created_at' => $fechas[9], 'updated_at' => $fechas[9]],
            ['venta_id' => 10, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'created_at' => $fechas[9], 'updated_at' => $fechas[9]],

            ['venta_id' => 11, 'producto_id' => 22, 'cantidad' => 1, 'precio' => 210000, 'created_at' => $fechas[10], 'updated_at' => $fechas[10]],

            ['venta_id' => 12, 'producto_id' => 13, 'cantidad' => 1, 'precio' => 98000, 'created_at' => $fechas[11], 'updated_at' => $fechas[11]],
        ]);

        DB::table('devoluciones')->insert([
            ['id' => 1, 'venta_id' => 3, 'motivo' => 'Producto defectuoso', 'total_devuelto' => 55000, 'created_at' => $fechas[10], 'updated_at' => $fechas[10]],
            ['id' => 2, 'venta_id' => 7, 'motivo' => 'Cliente no lo necesitaba', 'total_devuelto' => 12000, 'created_at' => $fechas[10], 'updated_at' => $fechas[10]],
            ['id' => 3, 'venta_id' => 5, 'motivo' => 'Falla en uno de los productos entregados', 'total_devuelto' => 28000, 'created_at' => $fechas[11], 'updated_at' => $fechas[11]],
        ]);

        DB::table('detalle_devoluciones')->insert([
            ['devolucion_id' => 1, 'producto_id' => 5, 'cantidad' => 1, 'precio' => 55000, 'subtotal' => 55000, 'created_at' => $fechas[10], 'updated_at' => $fechas[10]],
            ['devolucion_id' => 2, 'producto_id' => 2, 'cantidad' => 1, 'precio' => 12000, 'subtotal' => 12000, 'created_at' => $fechas[10], 'updated_at' => $fechas[10]],
            ['devolucion_id' => 3, 'producto_id' => 21, 'cantidad' => 1, 'precio' => 28000, 'subtotal' => 28000, 'created_at' => $fechas[11], 'updated_at' => $fechas[11]],
        ]);
    }
}