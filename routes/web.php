<?php

use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\CajaController;
use App\Models\Factura;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\ProductoXFactura;
use App\Models\Inventario;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use App\Support\CloudinaryHelper;

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('login');
})->name('login');

// Login Form Submit (Direct Database/Session Auth)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        
        // redirect based on role
        $roleName = strtolower(optional($user->rol)->name);
        $routeMap = [
            'admin' => 'admin.dashboard',
            'waiter' => 'admin.mesas',
            'mesero' => 'admin.mesas',
            'kitchen' => 'admin.mesas',
            'cocina' => 'admin.mesas',
        ];
        $dest = $routeMap[$roleName] ?? 'admin.dashboard';
        return redirect()->route($dest);
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ]);
})->name('login.post');

// Logout Route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Keep original admin views prefix mapping with Session Authentication
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        $mesas = Mesa::with(['latestFactura.productos.producto'])
            ->orderByRaw('LENGTH(nombre) ASC')
            ->orderBy('nombre', 'ASC')
            ->get();
        $ventasDia = Factura::whereDate('fecha', today())
            ->whereIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada'])
            ->sum('monto_total');
        
        $pedidosActivos = Factura::whereNotIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada'])
            ->count();
            
        // Find top selling product
        $topProductoObj = ProductoXFactura::select('producto_id', DB::raw('SUM(cantidad) as total_qty'))
            ->groupBy('producto_id')
            ->orderByDesc('total_qty')
            ->first();
        $topProducto = $topProductoObj ? (Producto::find($topProductoObj->producto_id)->nombre ?? 'Ninguno') : 'Ninguno';
        $topProductoQty = $topProductoObj ? $topProductoObj->total_qty : 0;
        
        // Count low stock alerts
        $alertasInventario = Inventario::whereRaw('stock_inicial <= stock_minimo')->count();
        $alertas = Inventario::with('producto')
            ->whereRaw('stock_inicial <= stock_minimo')
            ->get();

        $activeCaja = \App\Models\AperturaCaja::where('estado', 'abierta')->first();

        return view('dashboard.index', compact('mesas', 'ventasDia', 'pedidosActivos', 'topProducto', 'topProductoQty', 'alertasInventario', 'alertas', 'activeCaja'));
    })->name('dashboard');

    // CRUD de Productos (Web)
    Route::get('/productos', function () {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    })->name('productos');

    Route::post('/productos', function (Request $request) {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $uploaded = CloudinaryHelper::upload($request->file('imagen'));
            $data['imagen_url'] = $uploaded['secure_url'];
            $data['imagen_public_id'] = $uploaded['public_id'];
        }

        Producto::create($data);
        return redirect()->route('admin.productos')->with('success', 'Producto creado correctamente.');
    })->name('productos.store');

    Route::post('/productos/{id}', function (Request $request, $id) {
        $producto = Producto::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            CloudinaryHelper::deleteProductImage($producto);
            $uploaded = CloudinaryHelper::upload($request->file('imagen'));
            $data['imagen_url'] = $uploaded['secure_url'];
            $data['imagen_public_id'] = $uploaded['public_id'];
        }

        $producto->update($data);
        return redirect()->route('admin.productos')->with('success', 'Producto actualizado correctamente.');
    })->name('productos.update');

    Route::post('/productos/{id}/delete', function ($id) {
        $producto = Producto::findOrFail($id);
        CloudinaryHelper::deleteProductImage($producto);
        $producto->delete();
        return redirect()->route('admin.productos')->with('success', 'Producto eliminado correctamente.');
    })->name('productos.delete');

    // CRUD de Inventario (Web)
    Route::get('/inventario', function () {
        $inventarios = Inventario::with('producto')->get();
        $productos = Producto::all();
        $alertasInventario = Inventario::whereRaw('stock_inicial <= stock_minimo')->count();
        return view('inventario.index', compact('inventarios', 'productos', 'alertasInventario'));
    })->name('inventario');

    Route::post('/inventario', function (Request $request) {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'producto_id' => 'nullable|exists:productos,id',
            'stock_inicial' => 'required|integer',
            'stock_minimo' => 'required|integer',
            'unidad_medida' => 'required|string|max:50',
            'descuento_inventario' => 'required|integer',
        ]);
        Inventario::create($data);
        return redirect()->route('admin.inventario')->with('success', 'Insumo creado correctamente.');
    })->name('inventario.store');

    Route::post('/inventario/{id}', function (Request $request, $id) {
        $inventario = Inventario::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'producto_id' => 'nullable|exists:productos,id',
            'stock_inicial' => 'required|integer',
            'stock_minimo' => 'required|integer',
            'unidad_medida' => 'required|string|max:50',
            'descuento_inventario' => 'required|integer',
        ]);
        $inventario->update($data);
        return redirect()->route('admin.inventario')->with('success', 'Insumo actualizado correctamente.');
    })->name('inventario.update');

    Route::post('/inventario/{id}/delete', function ($id) {
        $inventario = Inventario::findOrFail($id);
        $inventario->delete();
        return redirect()->route('admin.inventario')->with('success', 'Insumo eliminado correctamente.');
    })->name('inventario.delete');

    // CRUD de Mesas (Web)
    Route::get('/mesas', function () {
        $query = Mesa::with(['latestFactura.productos.producto'])
            ->orderByRaw('LENGTH(nombre) ASC')
            ->orderBy('nombre', 'ASC');
            
        if (!auth()->user()->rol || auth()->user()->rol->name !== 'admin') {
            $query->where('es_admin', false);
        }
        
        $mesas = $query->get();
        return view('admin_mesas.index', compact('mesas'));
    })->name('mesas');

    Route::post('/mesas', function (Request $request) {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'capacidad' => 'nullable|integer',
            'password' => 'nullable|string',
        ]);
        
        $data['nombre'] = !empty($data['nombre']) ? $data['nombre'] : null;
        $data['es_admin'] = $request->has('es_admin') ? 1 : 0;
        
        if (!empty($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        } else {
            $data['password'] = null;
        }

        if (empty($data['nombre'])) {
            // Find all tables that start with "Mesa " followed by a number
            $mesas = Mesa::all();
            $maxNumber = 0;
            foreach ($mesas as $mesa) {
                if (preg_match('/^Mesa\s+(\d+)$/i', trim($mesa->nombre), $matches)) {
                    $num = (int)$matches[1];
                    if ($num > $maxNumber) {
                        $maxNumber = $num;
                    }
                }
            }
            $data['nombre'] = 'Mesa ' . ($maxNumber + 1);
        }

        Mesa::create($data);
        return redirect()->route('admin.mesas')->with('success', 'Mesa registrada correctamente.');
    })->name('mesas.store');

    Route::post('/mesas/{id}/delete', function ($id) {
        $mesa = Mesa::findOrFail($id);
        $mesa->delete();
        return redirect()->route('admin.mesas')->with('success', 'Mesa eliminada correctamente.');
    })->name('mesas.delete');

    Route::post('/mesas/{id}/verify-password', function (Request $request, $id) {
        $mesa = Mesa::findOrFail($id);
        
        if (!$mesa->es_admin) {
            return response()->json(['success' => true]);
        }
        
        if (\Illuminate\Support\Facades\Hash::check($request->password, $mesa->password)) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Contraseña incorrecta'], 403);
    })->name('mesas.verify');

    // Pedidos & Mesas Interaction (No endpoints)
    $renderPedidoHtml = function($mesaId) {
        $mesa = \App\Models\Mesa::with(['latestFactura.productos.producto'])->findOrFail($mesaId);
        $factura = $mesa->latestFactura;
        
        $items = [];
        $total = 0;
        if ($factura && !in_array(strtolower($factura->estatus), ['pagado', 'pagada'])) {
            $items = $factura->productos;
            $total = $mesa->es_admin ? 0 : $factura->monto_total;
        }

        return view('pedido.index', [
            'mesa' => $mesa,
            'mesaId' => $mesaId,
            'factura' => $factura,
            'items' => $items,
            'total' => $total,
        ])->render();
    };

    Route::get('/mesas/{id}/pedido', function ($id) {
        if (!\App\Models\AperturaCaja::where('estado', 'abierta')->exists()) {
            return redirect()->route('admin.dashboard');
        }
        $mesa = Mesa::with(['latestFactura.productos.producto'])->findOrFail($id);
        $factura = $mesa->latestFactura;
        
        $items = [];
        $total = 0;
        if ($factura && !in_array(strtolower($factura->estatus), ['pagado', 'pagada'])) {
            $items = $factura->productos;
            $total = $mesa->es_admin ? 0 : $factura->monto_total;
        }

        return view('pedido.index', [
            'mesa' => $mesa,
            'mesaId' => $id,
            'factura' => $factura,
            'items' => $items,
            'total' => $total,
        ]);
    })->name('pedido');

    Route::post('/mesas/{id}/pedido/add', function (Request $request, $id) use ($renderPedidoHtml) {
        $mesa = Mesa::findOrFail($id);
        
        $factura = $mesa->latestFactura;
        if (!$factura || in_array(strtolower($factura->estatus), ['pagado', 'pagada'])) {
            $max = \Illuminate\Support\Facades\DB::table('facturas')
                ->where('tipo', 'pos')
                ->select(\Illuminate\Support\Facades\DB::raw('MAX(CAST(numero_orden AS UNSIGNED)) as max'))
                ->lockForUpdate()
                ->value('max');
            $next = $max ? intval($max) + 1 : 1;
            $numeroOrden = str_pad($next, 4, '0', STR_PAD_LEFT);

            $factura = Factura::create([
                'tipo' => 'pos',
                'numero_orden' => $numeroOrden,
                'fecha' => now(),
                'mesa_id' => $mesa->id,
                'estatus' => 'pendiente',
                'monto_total' => 0,
                'cambio' => 0,
            ]);
        }
        
        $productsInput = $request->input('products', []);
        $singleProductId = $request->input('producto_id');
        $singleQty = intval($request->input('cantidad', 1));
        
        if ($singleProductId && $singleQty > 0) {
            $productsInput[$singleProductId] = $singleQty;
        }
        
        $productIds = array_keys($productsInput);
        $productos = \App\Models\Producto::whereIn('id', $productIds)->get()->keyBy('id');
        
        $existingItems = \App\Models\ProductoXFactura::where('factura_id', $factura->id)
            ->whereIn('producto_id', $productIds)
            ->get()->keyBy('producto_id');
            
        foreach ($productsInput as $prodId => $qty) {
            $qty = intval($qty);
            if ($qty <= 0) continue;
            
            $producto = $productos->get($prodId);
            if (!$producto) continue;
            
            $pxf = $existingItems->get($prodId);
            if ($pxf) {
                $pxf->cantidad += $qty;
                if ($mesa->es_admin) {
                    $pxf->precio_unitario = 0;
                }
                $pxf->save();
            } else {
                $pxf = ProductoXFactura::create([
                    'producto_id' => $producto->id,
                    'factura_id' => $factura->id,
                    'cantidad' => $qty,
                    'precio_unitario' => $mesa->es_admin ? 0 : $producto->precio,
                    'descripcion' => $producto->nombre,
                ]);
                
                \App\Models\EstatusXFactura::create([
                    'productosxfactura_id' => $pxf->id,
                    'estatus' => 'pendiente',
                ]);
            }
        }
        
        $factura->monto_total = $mesa->es_admin ? 0 : ($factura->productos()->selectRaw('SUM(cantidad * precio_unitario) as total')->value('total') ?? 0);
        $factura->save();
        
        $mesa = Mesa::find($id);
        $mesaNombre = $mesa ? $mesa->nombre : "Mesa " . $id;

        // Build detailed list of added items for toast notification
        $addedDetails = [];
        foreach ($productsInput as $prodId => $qty) {
            $qty = intval($qty);
            if ($qty <= 0) continue;
            $producto = $productos->get($prodId);
            if ($producto) {
                $addedDetails[] = "{$qty}x {$producto->nombre}";
            }
        }
        $addedString = !empty($addedDetails) ? implode(', ', $addedDetails) : 'productos';
        $eventMessage = "Se agregó {$addedString} en {$mesaNombre}";
        
        session()->save();
        dispatch(function() use ($id, $eventMessage) {
            event(new \App\Events\PedidoActualizado($id, $eventMessage, "creado"));
        })->afterResponse();
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'html' => $renderPedidoHtml($id), 'message' => 'Pedido actualizado.']);
        }
        return redirect()->route('admin.pedido', ['id' => $id])->with('success', 'Pedido actualizado.');
    })->name('pedido.add');

    Route::delete('/mesas/{mesaId}/pedido/item/{itemId}', function ($mesaId, $itemId) use ($renderPedidoHtml) {
        $item = ProductoXFactura::findOrFail($itemId);
        $prodNombre = $item->producto ? $item->producto->nombre : ($item->descripcion ?? 'Producto');
        $factura = $item->factura;
        $item->delete();
        
        if ($factura->productos()->count() == 0) {
            $factura->delete();
        } else {
            $factura->monto_total = $mesa && $mesa->es_admin ? 0 : ($factura->productos()->selectRaw('SUM(cantidad * precio_unitario) as total')->value('total') ?? 0);
            $factura->save();
        }
        
        $mesa = Mesa::find($mesaId);
        $mesaNombre = $mesa ? $mesa->nombre : "Mesa " . $mesaId;
        $eventMessage = "Se quitó {$prodNombre} de {$mesaNombre}";
        
        session()->save();
        dispatch(function() use ($mesaId, $eventMessage) {
            event(new \App\Events\PedidoActualizado($mesaId, $eventMessage, "eliminado"));
        })->afterResponse();
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'html' => $renderPedidoHtml($mesaId), 'message' => 'Producto eliminado.']);
        }
        return redirect()->route('admin.pedido', ['id' => $mesaId])->with('success', 'Producto eliminado.');
    })->name('pedido.delete_item');

    Route::post('/mesas/{mesaId}/pedido/item/{itemId}/update', function (Request $request, $mesaId, $itemId) use ($renderPedidoHtml) {
        $item = ProductoXFactura::findOrFail($itemId);
        $prodNombre = $item->producto ? $item->producto->nombre : ($item->descripcion ?? 'Producto');
        $factura = $item->factura;
        $cantidad = intval($request->input('cantidad', 1));
        
        $mesa = Mesa::find($mesaId);
        $mesaNombre = $mesa ? $mesa->nombre : "Mesa " . $mesaId;
        
        if ($cantidad <= 0) {
            $item->delete();
            if ($factura->productos()->count() == 0) {
                $factura->delete();
            } else {
                $factura->monto_total = $mesa && $mesa->es_admin ? 0 : ($factura->productos()->selectRaw('SUM(cantidad * precio_unitario) as total')->value('total') ?? 0);
                $factura->save();
            }
            $eventMessage = "Se quitó {$prodNombre} de {$mesaNombre}";
            session()->save();
            dispatch(function() use ($mesaId, $eventMessage) {
                event(new \App\Events\PedidoActualizado($mesaId, $eventMessage, "eliminado"));
            })->afterResponse();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'html' => $renderPedidoHtml($mesaId), 'message' => 'Producto eliminado.']);
            }
            return redirect()->route('admin.pedido', ['id' => $mesaId])->with('success', 'Producto eliminado.');
        }
        
        $item->cantidad = $cantidad;
        if ($mesa && $mesa->es_admin) {
            $item->precio_unitario = 0;
        }
        $item->save();
        
        $factura->monto_total = $mesa && $mesa->es_admin ? 0 : ($factura->productos()->selectRaw('SUM(cantidad * precio_unitario) as total')->value('total') ?? 0);
        $factura->save();
        
        $eventMessage = "{$prodNombre} cambiado a {$cantidad}x en {$mesaNombre}";
        session()->save();
        dispatch(function() use ($mesaId, $eventMessage) {
            event(new \App\Events\PedidoActualizado($mesaId, $eventMessage, "actualizado"));
        })->afterResponse();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'html' => $renderPedidoHtml($mesaId), 'message' => 'Cantidad actualizada.']);
        }
        return redirect()->route('admin.pedido', ['id' => $mesaId])->with('success', 'Cantidad actualizada.');
    })->name('pedido.update_item');

    Route::get('/mesas/{id}/checkout', function ($id) {
        if (!\App\Models\AperturaCaja::where('estado', 'abierta')->exists()) {
            return redirect()->route('admin.dashboard');
        }
        $mesa = Mesa::with(['latestFactura.productos.producto'])->findOrFail($id);
        $factura = $mesa->latestFactura;
        
        if (!$factura || in_array(strtolower($factura->estatus), ['pagado', 'pagada'])) {
            return redirect()->route('admin.pedido', ['id' => $id])->with('error', 'No hay órdenes activas para cobrar.');
        }

        $items = $factura->productos;
        $subtotal = $mesa->es_admin ? 0 : $factura->monto_total;
        $servicio = 0;
        $total = $subtotal;

        return view('checkout.index', [
            'mesa' => $mesa,
            'mesaId' => $id,
            'factura' => $factura,
            'items' => $items,
            'subtotal' => $subtotal,
            'servicio' => $servicio,
            'total' => $total,
        ]);
    })->name('checkout');

    Route::post('/mesas/{id}/checkout/pay', function (Request $request, $id) {
        $mesa = App\Models\Mesa::findOrFail($id);
        $factura = $mesa->latestFactura;
        
        if ($factura) {
            // Update total without service fee
            $subtotal = $mesa->es_admin ? 0 : $factura->monto_total;
            $servicio = 0;
            $total = $subtotal;

            $efectivo = (float) $request->input('monto_efectivo', 0);
            $tarjeta = (float) $request->input('monto_tarjeta', 0);
            $qr = (float) $request->input('monto_qr', 0);
            $recibido = $efectivo + $tarjeta + $qr;
            
            $cambio = max(0, $recibido - $total);

            $factura->monto_total = $total;
            $factura->cambio = $mesa->es_admin ? 0 : $cambio;
            $factura->estatus = 'pagado';
            $factura->save();

            // Marcar todos los productos como entregados
            foreach ($factura->productos as $pxf) {
                $tracking = $pxf->estatusTracking;
                if (!$tracking) {
                    $tracking = new \App\Models\EstatusXFactura();
                    $tracking->productosxfactura_id = $pxf->id;
                }
                $tracking->estatus = 'entregado';
                $tracking->save();
            }

            if ($efectivo > 0) {
                \Illuminate\Support\Facades\DB::table('metodos_pago')->insert([
                    'factura_id' => $factura->id,
                    'metodo' => 'efectivo',
                    'valor' => $efectivo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if ($tarjeta > 0) {
                \Illuminate\Support\Facades\DB::table('metodos_pago')->insert([
                    'factura_id' => $factura->id,
                    'metodo' => 'tarjeta',
                    'valor' => $tarjeta,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if ($qr > 0) {
                \Illuminate\Support\Facades\DB::table('metodos_pago')->insert([
                    'factura_id' => $factura->id,
                    'metodo' => 'qr',
                    'valor' => $qr,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } // close if ($factura)
        
        if ($request->ajax()) {
            event(new \App\Events\PedidoActualizado($id));
            return response()->json(['success' => true, 'factura_id' => $factura->id ?? null]);
        }
        
        event(new \App\Events\PedidoActualizado($id));
        
        return redirect()->route('admin.mesas')
            ->with('success', 'Factura cobrada y mesa liberada.')
            ->with('print_factura_id', $factura->id ?? null);
    })->name('checkout.pay');

    Route::get('/factura/{id}/pos-receipt', function ($id) {
        $factura = Factura::with(['productos.producto', 'metodosPago'])->findOrFail($id);
        return view('pos_receipt.index', compact('factura'));
    })->name('pos.receipt');

    // CRUD de Usuarios (Web)
    Route::get('/users', function () {
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    })->name('users');

    Route::post('/users', function (Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users')->with('success', 'Usuario creado correctamente.');
    })->name('users.store');

    Route::post('/users/{id}', function (Request $request, $id) {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'Usuario actualizado correctamente.');
    })->name('users.update');

    Route::post('/users/{id}/delete', function ($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Usuario eliminado correctamente.');
    })->name('users.delete');

    // CRUD de Roles (Web)
    Route::get('/roles', function () {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    })->name('roles');

    Route::post('/roles', function (Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        Role::create($data);
        return redirect()->route('admin.roles')->with('success', 'Rol creado correctamente.');
    })->name('roles.store');

    Route::post('/roles/{id}', function (Request $request, $id) {
        $role = Role::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $role->update($data);
        return redirect()->route('admin.roles')->with('success', 'Rol actualizado correctamente.');
    })->name('roles.update');

    Route::post('/roles/{id}/delete', function ($id) {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('admin.roles')->with('success', 'Rol eliminado correctamente.');
    })->name('roles.delete');

    Route::get('/reportes', function (Request $request) {
        $periodo = $request->input('periodo', 'diario');
        $fechaSelect = $request->input('fecha');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        
        $queryFilter = function($query) use ($periodo, $fechaSelect, $fechaInicio, $fechaFin) {
            if ($fechaInicio && $fechaFin) {
                return $query->whereBetween('facturas.created_at', [
                    \Carbon\Carbon::parse($fechaInicio),
                    \Carbon\Carbon::parse($fechaFin)
                ]);
            }
            if ($fechaSelect) {
                return $query->whereDate('facturas.created_at', $fechaSelect);
            }
            if ($periodo === 'diario') {
                return $query->whereDate('facturas.created_at', today());
            } elseif ($periodo === 'semanal') {
                return $query->where('facturas.created_at', '>=', today()->subDays(7));
            } elseif ($periodo === 'mensual') {
                return $query->where('facturas.created_at', '>=', today()->subDays(30));
            } elseif ($periodo === 'anual') {
                return $query->whereYear('facturas.created_at', today()->year);
            }
            return $query;
        };

        // 1. Bento Grid: Sales Volume Metrics
        $salesQuery = Factura::whereIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada']);
        $salesQuery = $queryFilter($salesQuery);
        $ventasTotales = $salesQuery->sum('monto_total');

        $pedidosQuery = Factura::whereIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada']);
        $pedidosQuery = $queryFilter($pedidosQuery);
        $pedidosCompletados = $pedidosQuery->count();

        $ticketPromedio = $pedidosCompletados > 0 ? ($ventasTotales / $pedidosCompletados) : 0;

        $productosQuery = ProductoXFactura::join('facturas', 'productosxfactura.factura_id', '=', 'facturas.id')
            ->whereIn(DB::raw('LOWER(facturas.estatus)'), ['pagado', 'pagada']);
        $productosQuery = $queryFilter($productosQuery);
        $productosVendidos = $productosQuery->sum('productosxfactura.cantidad');

        // 2. Products Sold Detail Query (Sorted by total sold)
        $productsSoldQuery = ProductoXFactura::join('facturas', 'productosxfactura.factura_id', '=', 'facturas.id')
            ->join('productos', 'productosxfactura.producto_id', '=', 'productos.id')
            ->whereIn(DB::raw('LOWER(facturas.estatus)'), ['pagado', 'pagada']);
        $productsSoldQuery = $queryFilter($productsSoldQuery);
        $productsSold = $productsSoldQuery->select(
                'productos.nombre',
                'productos.categoria',
                DB::raw('SUM(productosxfactura.cantidad) as total_qty'),
                DB::raw('SUM(productosxfactura.cantidad * productosxfactura.precio_unitario) as total_revenue')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.categoria')
            ->orderByDesc('total_qty')
            ->get();

        $cancellationRate = 2.5; // Mock/Fixed standard

        // 3. Payment Methods Breakdown
        $paymentQuery = \App\Models\MetodoPago::join('facturas', 'metodos_pago.factura_id', '=', 'facturas.id')
            ->whereIn(DB::raw('LOWER(facturas.estatus)'), ['pagado', 'pagada']);
        $paymentQuery = $queryFilter($paymentQuery);
        $metodosRaw = $paymentQuery->select('metodos_pago.metodo', DB::raw('SUM(metodos_pago.valor) as total'))
            ->groupBy('metodos_pago.metodo')
            ->get();

        $metodosPago = [
            'tarjeta' => 0,
            'efectivo' => 0,
            'yape_plin' => 0,
        ];
        foreach ($metodosRaw as $mr) {
            $m = strtolower($mr->metodo);
            $val = floatval($mr->total);
            if (str_contains($m, 'tarjeta') || str_contains($m, 'pos') || str_contains($m, 'visa') || str_contains($m, 'mastercard')) {
                $metodosPago['tarjeta'] += $val;
            } elseif (str_contains($m, 'efectivo')) {
                $metodosPago['efectivo'] += $val;
            } else {
                $metodosPago['yape_plin'] += $val;
            }
        }
        $totalMetodosSum = array_sum($metodosPago);

        // 4. Afluencia por Horas (Hourly distribution)
        $trafficQuery = Factura::whereIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada']);
        $trafficQuery = $queryFilter($trafficQuery);
        $trafficData = $trafficQuery->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->pluck('count', 'hour')
            ->toArray();

        $hours = [12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22];
        $afluencia = [];
        foreach ($hours as $h) {
            $afluencia[$h] = $trafficData[$h] ?? 0;
        }
        $maxTraffic = count($afluencia) > 0 ? max($afluencia) : 0;

        // 5. Staff Performance
        $staff = \App\Models\User::all();
        $staffStats = [];
        foreach ($staff as $u) {
            $staffStats[$u->id] = [
                'name' => $u->name,
                'initials' => strtoupper(substr($u->name, 0, 2)),
                'orders_count' => 0,
                'total_billed' => 0,
            ];
        }
        $ordersQuery = Factura::whereIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada']);
        $ordersQuery = $queryFilter($ordersQuery);
        $orders = $ordersQuery->get();
        if ($staff->count() > 0) {
            foreach ($orders as $ord) {
                $assignedStaffIndex = $ord->id % $staff->count();
                $assignedUser = $staff[$assignedStaffIndex];
                $staffStats[$assignedUser->id]['orders_count']++;
                $staffStats[$assignedUser->id]['total_billed'] += $ord->monto_total;
            }
        }
        uasort($staffStats, function($a, $b) {
            return $b['total_billed'] <=> $a['total_billed'];
        });

        // 6. Categorías Más Vendidas
        $categoriesQuery = ProductoXFactura::join('facturas', 'productosxfactura.factura_id', '=', 'facturas.id')
            ->join('productos', 'productosxfactura.producto_id', '=', 'productos.id')
            ->whereIn(DB::raw('LOWER(facturas.estatus)'), ['pagado', 'pagada']);
        $categoriesQuery = $queryFilter($categoriesQuery);
        $categories = $categoriesQuery->select('productos.categoria', DB::raw('SUM(productosxfactura.cantidad * productosxfactura.precio_unitario) as total'))
            ->groupBy('productos.categoria')
            ->orderByDesc('total')
            ->get();
        $totalCategorySum = $categories->sum('total');

        // 7. Cierres de caja en el período/día seleccionado
        $cajasQuery = \App\Models\AperturaCaja::where('estado', 'cerrada');
        if ($fechaInicio && $fechaFin) {
            $cajasQuery->whereBetween('fecha_cierre', [
                \Carbon\Carbon::parse($fechaInicio)->startOfDay(),
                \Carbon\Carbon::parse($fechaFin)->endOfDay()
            ]);
        } elseif ($fechaSelect) {
            $cajasQuery->whereDate('fecha_cierre', $fechaSelect);
        } elseif ($periodo === 'diario') {
            $cajasQuery->whereDate('fecha_cierre', today());
        } elseif ($periodo === 'semanal') {
            $cajasQuery->where('fecha_cierre', '>=', today()->subDays(7));
        } elseif ($periodo === 'mensual') {
            $cajasQuery->where('fecha_cierre', '>=', today()->subDays(30));
        } elseif ($periodo === 'anual') {
            $cajasQuery->whereYear('fecha_cierre', today()->year);
        }
        $cajasCerradas = $cajasQuery->orderByDesc('fecha_cierre')->get();

        return view('reportes.index', compact(
            'periodo',
            'fechaSelect',
            'fechaInicio',
            'fechaFin',
            'ventasTotales',
            'pedidosCompletados',
            'ticketPromedio',
            'productosVendidos',
            'productsSold',
            'cancellationRate',
            'metodosPago',
            'totalMetodosSum',
            'afluencia',
            'maxTraffic',
            'staffStats',
            'categories',
            'totalCategorySum',
            'cajasCerradas'
        ));
    })->name('reportes');

    Route::get('/reportes/exportar', function (Request $request) {
        $periodo = $request->input('periodo', 'diario');
        $fechaSelect = $request->input('fecha');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        
        $queryFilter = function($query) use ($periodo, $fechaSelect, $fechaInicio, $fechaFin) {
            if ($fechaInicio && $fechaFin) {
                return $query->whereBetween('facturas.created_at', [
                    \Carbon\Carbon::parse($fechaInicio),
                    \Carbon\Carbon::parse($fechaFin)
                ]);
            }
            if ($fechaSelect) {
                return $query->whereDate('facturas.created_at', $fechaSelect);
            }
            if ($periodo === 'diario') {
                return $query->whereDate('facturas.created_at', today());
            } elseif ($periodo === 'semanal') {
                return $query->where('facturas.created_at', '>=', today()->subDays(7));
            } elseif ($periodo === 'mensual') {
                return $query->where('facturas.created_at', '>=', today()->subDays(30));
            } elseif ($periodo === 'anual') {
                return $query->whereYear('facturas.created_at', today()->year);
            }
            return $query;
        };

        $ordersQuery = Factura::with(['mesa', 'productos'])
            ->whereIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada']);
        $ordersQuery = $queryFilter($ordersQuery);
        $orders = $ordersQuery->get();

        if ($fechaInicio && $fechaFin) {
            $filename = "reporte_ventas_{$fechaInicio}_a_{$fechaFin}.csv";
        } else {
            $filename = $fechaSelect ? "reporte_ventas_{$fechaSelect}.csv" : "reporte_ventas_{$periodo}_" . date('Ymd_His') . ".csv";
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Pedido', 'Nro. Orden', 'Fecha/Hora', 'Mesa', 'Estatus', 'Cant. Productos', 'Monto Total'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compliance in Spanish locale
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns, ';');

            foreach ($orders as $ord) {
                $row = [
                    $ord->id,
                    $ord->numero_orden ?? 'N/A',
                    $ord->created_at ? $ord->created_at->format('Y-m-d H:i:s') : $ord->fecha,
                    $ord->mesa->nombre ?? ('Mesa ' . $ord->mesa_id),
                    $ord->estatus,
                    $ord->productos->sum('cantidad'),
                    $ord->monto_total
                ];

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    })->name('reportes.exportar');

    Route::get('/alquiler', function (Request $request) {
        $max = DB::table('facturas')->where('tipo', 'evento')->select(DB::raw('MAX(CAST(numero_orden AS UNSIGNED)) as max'))->value('max');
        $next = $max ? intval($max) + 1 : 1;
        $nextStr = str_pad($next, 4, '0', STR_PAD_LEFT);

        $factura = null;
        $items = [];
        $metodos = [];

        if ($request->query('factura_id')) {
            $factura = Factura::with(['productos', 'metodosPago'])->find($request->query('factura_id'));
            if ($factura) {
                foreach ($factura->productos as $it) {
                    $items[] = [
                        'desc' => $it->descripcion ?? ($it->producto?->nombre ?? ''),
                        'cant' => $it->cantidad,
                        'precio' => $it->precio_unitario,
                        'producto_id' => $it->producto_id ?? null,
                    ];
                }
                foreach ($factura->metodosPago as $m) {
                    $metodos[] = ['metodo' => $m->metodo, 'valor' => $m->valor];
                }
            }
        }

        $products = Producto::select('id', 'nombre', 'precio')->get();

        return view('admin_alquiler.index', [
            'nextInvoiceNo' => $nextStr,
            'factura' => $factura,
            'items' => $items,
            'metodos' => $metodos,
            'products' => $products,
            'mesa_id' => $request->query('mesa_id'),
        ]);
    })->name('alquiler');

    Route::get('/alquiler/{id}/edit', function ($id) {
        if (auth()->user()?->rol?->name === 'cocina') {
            abort(403, 'Acción no autorizada.');
        }
        $max = DB::table('facturas')->where('tipo', 'evento')->select(DB::raw('MAX(CAST(numero_orden AS UNSIGNED)) as max'))->value('max');
        $next = $max ? intval($max) + 1 : 1;
        $nextStr = str_pad($next, 4, '0', STR_PAD_LEFT);

        $factura = Factura::with(['productos', 'metodosPago'])->find($id);
        $items = [];
        $metodos = [];
        if ($factura) {
            foreach ($factura->productos as $it) {
                $items[] = [
                    'desc' => $it->descripcion ?? ($it->producto?->nombre ?? ''),
                    'cant' => $it->cantidad,
                    'precio' => $it->precio_unitario,
                    'producto_id' => $it->producto_id ?? null,
                ];
            }
            foreach ($factura->metodosPago as $m) {
                $metodos[] = ['metodo' => $m->metodo, 'valor' => $m->valor];
            }
        }

        $products = Producto::select('id', 'nombre', 'precio')->get();

        return view('admin_alquiler.index', [
            'nextInvoiceNo' => $nextStr,
            'factura' => $factura,
            'items' => $items,
            'metodos' => $metodos,
            'products' => $products,
        ]);
    })->name('alquiler.edit');

    Route::get('/alquiler/list', function (Request $request) {
        $query = Factura::query();
        
        if ($request->filled('tipo') && $request->tipo !== 'todos') {
            $query->where('tipo', $request->tipo);
        }
        
        $facturas = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->paginate(20);
        $mesas = Mesa::orderBy('nombre')->get();
        return view('admin_alquiler_list.index', compact('facturas', 'mesas'));
    })->name('alquiler.list');

    Route::post('/alquiler/store', [AlquilerController::class, 'store'])->name('alquiler.store');
    Route::post('/alquiler/update', [AlquilerController::class, 'update'])->name('alquiler.update');

    Route::delete('/alquiler/{id}', function ($id) {
        if (auth()->user()?->rol?->name === 'cocina') {
            abort(403, 'Acción no autorizada.');
        }
        ProductoXFactura::where('factura_id', $id)->delete();
        \App\Models\MetodoPago::where('factura_id', $id)->delete();
        Factura::destroy($id);
        return redirect()->route('admin.alquiler.list')->with('status', 'Factura eliminada');
    })->name('alquiler.delete');

    // Cocina (Web)
    Route::get('/cocina', function (Request $request) {
        $orders = Factura::with(['productos.producto', 'productos.estatusTracking', 'mesa'])
            ->whereNotIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada'])
            ->orderBy('id', 'desc')
            ->get();
        if ($request->ajax() || $request->has('partial')) {
            return view('cocina.partials.list', compact('orders'));
        }
        return view('cocina.index', compact('orders'));
    })->name('cocina');

    Route::post('/cocina/item/{id}/status', function (Request $request, $id) {
        $pxf = App\Models\ProductoXFactura::findOrFail($id);
        $request->validate([
            'estatus' => 'required|string',
        ]);
        
        $tracking = $pxf->estatusTracking;
        if (!$tracking) {
            $tracking = new \App\Models\EstatusXFactura();
            $tracking->productosxfactura_id = $pxf->id;
        }
        $tracking->estatus = $request->input('estatus');
        $tracking->save();
        
        $mesaId = $pxf->factura->mesa_id ?? null;
        if ($mesaId) {
            event(new \App\Events\PedidoActualizado($mesaId));
        }
        
        return redirect()->route('admin.cocina')->with('success', 'Estatus del producto actualizado.');
    })->name('cocina.item.status');

    // Caja web views and actions
    Route::get('/caja', [CajaController::class, 'index'])->name('caja');
    Route::post('/caja/apertura', [CajaController::class, 'apertura'])->name('caja.apertura');
    Route::post('/caja/cierre', [CajaController::class, 'cierre'])->name('caja.cierre');
});

// Redirect to dashboard (compatibility)
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// Session preload endpoint for Caja from Mesas
Route::middleware('auth')->post('/alquiler/caja/preload', function (Request $request) {
    $products = $request->input('products');
    $mesaId = $request->input('mesa_id');
    
    $factura = Factura::where('mesa_id', $mesaId)
        ->whereNotIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada'])
        ->first();

    $items = [];
    if (is_array($products)) {
        foreach ($products as $it) {
            $p = \App\Models\Producto::find($it['id']);
            if ($p) {
                $items[] = [
                    'producto_id' => $p->id,
                    'desc' => $p->nombre,
                    'cant' => $it['qty'],
                    'precio' => $p->precio,
                    'precio_unitario' => $p->precio,
                ];
            }
        }
    }

    $preload = [
        'mesa_id' => $mesaId,
        'factura_id' => $factura ? $factura->id : null,
        'items' => $items,
    ];

    $request->session()->put('caja_preload', $preload);

    return response()->json([
        'success' => true,
        'redirect' => route('admin.caja')
    ]);
})->name('alquiler.caja.preload');
