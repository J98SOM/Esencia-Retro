<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MesaController extends Controller
{
    protected $path = 'mesas.json';

    protected function loadMesas()
    {
        if (!Storage::exists($this->path)) {
            // Do not auto-create sample mesas here; return empty list if file missing
            return [];
        }
        $content = Storage::get($this->path);
        return json_decode($content, true) ?: [];
    }

    protected function saveMesas(array $list)
    {
        Storage::put($this->path, json_encode(array_values($list)));
    }

    public function index(Request $request)
    {
        $mesas = $this->loadMesas();

        // If user has roles, you could filter mesas per role here.
        // Example: mesero only sees mesas with id 2 (demo)
        $user = $request->user();
        if ($user && $user->roles->isNotEmpty()) {
            $roleNames = $user->roles->pluck('name')->all();
            if (in_array('mesero', $roleNames)) {
                // filter to a single mesa for demo
                $mesas = array_filter($mesas, fn($m) => $m['id'] === 2);
                $mesas = array_values($mesas);
            }
        }

        return response()->json($mesas);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
            'capacity' => 'nullable|integer',
        ]);
        $mesas = $this->loadMesas();
        $next = empty($mesas) ? 1 : (max(array_column($mesas, 'id')) + 1);
        $new = ['id' => $next, 'identifier' => $data['identifier'], 'capacity' => $data['capacity'] ?? null, 'status' => 'libre'];
        $mesas[] = $new;
        $this->saveMesas($mesas);
        return response()->json($new, 201);
    }

    public function destroy($id)
    {
        $mesas = $this->loadMesas();
        $found = false;
        $mesas = array_filter($mesas, function($m) use ($id, &$found) {
            if ((string)$m['id'] === (string)$id) { $found = true; return false; }
            return true;
        });
        if (! $found) return response()->json(['message' => 'Not found'], 404);
        $this->saveMesas($mesas);
        return response()->json(['deleted' => true]);
    }

    // Mark a mesa as occupied
    public function occupy(Request $request, $id)
    {
        $mesas = $this->loadMesas();
        $found = false;
        $mesas = array_map(function($m) use ($id, &$found) {
            if ((string)$m['id'] === (string)$id) {
                $found = true;
                $m['status'] = 'ocupada';
                // record timestamp if desired
                $m['occupied_at'] = date('c');
            }
            return $m;
        }, $mesas);
        if (! $found) return response()->json(['message' => 'Not found'], 404);
        $this->saveMesas($mesas);
        return response()->json(['id' => $id, 'status' => 'ocupada']);
    }
}
