<?php

use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
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
        $mesas = Mesa::with(['latestFactura.productos.producto'])->orderBy('nombre')->get();
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

        return view('admin.dashboard', compact('mesas', 'ventasDia', 'pedidosActivos', 'topProducto', 'topProductoQty', 'alertasInventario', 'alertas'));
    })->name('dashboard');

    // CRUD de Productos (Web)
    Route::get('/productos', function () {
        $productos = Producto::all();
        return view('admin.productos', compact('productos'));
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
        return view('admin.inventario', compact('inventarios', 'productos', 'alertasInventario'));
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
        $mesas = Mesa::with(['latestFactura.productos.producto'])->orderBy('nombre')->get();
        return view('admin.mesas', compact('mesas'));
    })->name('mesas');

    Route::post('/mesas', function (Request $request) {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'capacidad' => 'nullable|integer',
        ]);
        Mesa::create($data);
        return redirect()->route('admin.mesas')->with('success', 'Mesa registrada correctamente.');
    })->name('mesas.store');

    Route::post('/mesas/{id}/delete', function ($id) {
        $mesa = Mesa::findOrFail($id);
        $mesa->delete();
        return redirect()->route('admin.mesas')->with('success', 'Mesa eliminada correctamente.');
    })->name('mesas.delete');

    // Pedidos & Mesas Interaction (No endpoints)
    Route::get('/mesas/{id}/pedido', function ($id) {
        $mesa = Mesa::with(['latestFactura.productos.producto'])->findOrFail($id);
        $factura = $mesa->latestFactura;
        
        $items = [];
        $total = 0;
        if ($factura && !in_array(strtolower($factura->estatus), ['pagado', 'pagada'])) {
            $items = $factura->productos;
            $total = $factura->monto_total;
        }

        return view('admin.pedido', [
            'mesa' => $mesa,
            'mesaId' => $id,
            'factura' => $factura,
            'items' => $items,
            'total' => $total,
        ]);
    })->name('pedido');

    Route::post('/mesas/{id}/pedido/add', function (Request $request, $id) {
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
        
        foreach ($productsInput as $prodId => $qty) {
            $qty = intval($qty);
            if ($qty <= 0) continue;
            
            $producto = Producto::find($prodId);
            if (!$producto) continue;
            
            $pxf = ProductoXFactura::where('factura_id', $factura->id)->where('producto_id', $producto->id)->first();
            if ($pxf) {
                $pxf->cantidad += $qty;
                $pxf->save();
            } else {
                $pxf = ProductoXFactura::create([
                    'producto_id' => $producto->id,
                    'factura_id' => $factura->id,
                    'cantidad' => $qty,
                    'precio_unitario' => $producto->precio,
                    'descripcion' => $producto->nombre,
                ]);
                
                \App\Models\EstatusXFactura::create([
                    'productosxfactura_id' => $pxf->id,
                    'estatus' => 'pendiente',
                ]);
            }
        }
        
        $factura->monto_total = $factura->productos->sum(function($item) {
            return $item->cantidad * $item->precio_unitario;
        });
        $factura->save();
        
        event(new \App\Events\PedidoActualizado($id));
        
        return redirect()->route('admin.pedido', ['id' => $id])->with('success', 'Pedido actualizado.');
    })->name('pedido.add');

    Route::delete('/mesas/{mesaId}/pedido/item/{itemId}', function ($mesaId, $itemId) {
        $item = ProductoXFactura::findOrFail($itemId);
        $factura = $item->factura;
        $item->delete();
        
        if ($factura->productos()->count() == 0) {
            $factura->delete();
        } else {
            $factura->monto_total = $factura->productos->sum(function($it) {
                return $it->cantidad * $it->precio_unitario;
            });
            $factura->save();
        }
        
        event(new \App\Events\PedidoActualizado($mesaId));
        
        return redirect()->route('admin.pedido', ['id' => $mesaId])->with('success', 'Producto eliminado.');
    })->name('pedido.delete_item');

    Route::get('/mesas/{id}/checkout', function ($id) {
        $mesa = Mesa::with(['latestFactura.productos.producto'])->findOrFail($id);
        $factura = $mesa->latestFactura;
        
        if (!$factura || in_array(strtolower($factura->estatus), ['pagado', 'pagada'])) {
            return redirect()->route('admin.pedido', ['id' => $id])->with('error', 'No hay órdenes activas para cobrar.');
        }

        $items = $factura->productos;
        $subtotal = $factura->monto_total;
        $servicio = 0;
        $total = $subtotal;

        return view('admin.checkout', [
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
            $subtotal = $factura->monto_total;
            $servicio = 0;
            $total = $subtotal;

            $efectivo = (float) $request->input('monto_efectivo', 0);
            $tarjeta = (float) $request->input('monto_tarjeta', 0);
            $qr = (float) $request->input('monto_qr', 0);
            $recibido = $efectivo + $tarjeta + $qr;
            
            $cambio = max(0, $recibido - $total);

            $factura->monto_total = $total;
            $factura->cambio = $cambio;
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
        return view('admin.pos_receipt', compact('factura'));
    })->name('pos.receipt');

    // CRUD de Usuarios (Web)
    Route::get('/users', function () {
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('admin.users', compact('users', 'roles'));
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
        return view('admin.roles', compact('roles'));
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

    Route::get('/reportes', function () {
        return view('admin.reportes');
    })->name('reportes');

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

        return view('admin.alquiler', [
            'nextInvoiceNo' => $nextStr,
            'factura' => $factura,
            'items' => $items,
            'metodos' => $metodos,
            'products' => $products,
            'mesa_id' => $request->query('mesa_id'),
        ]);
    })->name('alquiler');

    Route::get('/alquiler/{id}/edit', function ($id) {
        if (auth()->user()->rol && auth()->user()->rol->name === 'cocina') {
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

        return view('admin.alquiler', [
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
        return view('admin.alquiler_list', compact('facturas', 'mesas'));
    })->name('alquiler.list');

    Route::post('/alquiler/store', [AlquilerController::class, 'store'])->name('alquiler.store');
    Route::post('/alquiler/update', [AlquilerController::class, 'update'])->name('alquiler.update');

    Route::delete('/alquiler/{id}', function ($id) {
        if (auth()->user()->rol && auth()->user()->rol->name === 'cocina') {
            abort(403, 'Acción no autorizada.');
        }
        ProductoXFactura::where('factura_id', $id)->delete();
        \App\Models\MetodoPago::where('factura_id', $id)->delete();
        Factura::destroy($id);
        return redirect()->route('admin.alquiler.list')->with('status', 'Factura eliminada');
    })->name('alquiler.delete');

    // Cocina (Web)
    Route::get('/cocina', function () {
        $orders = Factura::with(['productos.producto', 'productos.estatusTracking', 'mesa'])
            ->whereNotIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada'])
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.cocina', compact('orders'));
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
});

// Redirect to dashboard (compatibility)
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');
