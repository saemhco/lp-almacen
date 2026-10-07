<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Almacen API',
    description: 'API para administrar items, categorias, proveedores, ubicaciones y movimientos.'
)]
abstract class Controller
{
    //
}
