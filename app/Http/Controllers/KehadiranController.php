<?php

namespace App\Http\Controllers;

use App\Http\Requests\Kehadiran\StoreRequest;
use App\Http\Requests\Kehadiran\UpdateRequest;
use App\Http\Resources\KehadiranResource;
use App\Models\Kehadiran;
use App\Services\KehadiranService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KehadiranController extends Controller
{
    use ApiResponse;

    protected KehadiranService $kehadiranService;

    public function __construct(KehadiranService $kehadiranService)
    {
        $this->kehadiranService = $kehadiranService;
    }

    public function generateKeyAbsensi()
    {
        $token = $this->kehadiranService->generateKeyAbsensi();
        return $this->success('Berhasil membuat token absensi', $token);
    }
    
    public function confirmAbsensi(Request $request) 
    {
        $request->validate([
            'token' => 'required'
        ]);

        $data = $this->kehadiranService->confirmAbsensi($request);
        return $this->success('Berhasil konfirmasi absensi', new KehadiranResource($data));
    }

    public function getKehadiran(Request $request)
    {
        $data = $this->kehadiranService->getUserKehadiran($request);
        return $this->success('Berhasil mengambil semua data kehadiran', KehadiranResource::collection($data));
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Kehadiran::class);

        $data = $this->kehadiranService->getAllKehadiran($request);
        return $this->success('Berhasil mengambil semua data kehadiran', KehadiranResource::collection($data));
    }

    public function show($id)
    {
        Gate::authorize('view', Kehadiran::class);

        $data = $this->kehadiranService->getKehadiranById($id);
        return $this->success('Berhasil mengambil data kehadiran', new KehadiranResource($data));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('create', Kehadiran::class);

        $data = $this->kehadiranService->createKehadiran($request->all());
        return $this->success('Berhasil menambahkan data kehadiran', new KehadiranResource($data), 201);
    }

    public function update(UpdateRequest $request, $id)
    {
        Gate::authorize('update', Kehadiran::class);

        $data = $this->kehadiranService->updateKehadiranById($request->all(), $id);
        return $this->success('Berhasil mengubah data kehadiran', new KehadiranResource($data));
    }

    public function destroy($id)
    {
        Gate::authorize('delete', Kehadiran::class);

        $this->kehadiranService->deleteKehadiranById($id);
        return $this->success('Berhasil menghapus data kehadiran');
    }
}
