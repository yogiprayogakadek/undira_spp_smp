<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    private function roles()
    {
        return [
            'admin',
            'kepala sekolah',
            'bendahara'
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->userService->getAll(['id', 'email', 'role', 'is_active']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return ucwords($row->role);
                })
                ->addColumn('is_active', function ($row) {
                    return $row->is_active == true
                        ? '<span class="badge bg-primary">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    if (auth()->user()->role === 'kepala sekolah') {
                        return '<span class="text-muted small">Read Only</span>';
                    }
                    return '
                    <a href="' . route('user.show', $row->id) . '">
                        <button type="button"
                            class="justify-content-center w-80 btn mb-1 bg-primary-subtle text-primary">
                            <i class="ti ti-pencil fs-4 me-2"></i>
                            Edit
                        </button>
                    </a>
                    ';
                })
                ->rawColumns(['actions', 'is_active', 'role'])
                ->make(true);
        };

        return view('main.user.index');
    }

    public function create()
    {
        return view('main.user.create')->with(['roles' => $this->roles()]);
    }

    public function store(UserStoreRequest $request)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $data = [
            'email' => $request->email,
            'password' => Hash::make('password'),
            'role' => $request->role,
        ];

        $this->userService->create($data);

        return redirect()->route('user.index')->with('success', 'User saved successfully');
    }

    public function show($id)
    {
        $user = $this->userService->findById(['id', 'email', 'role', 'is_active'], $id);
        return view('main.user.update')->with([
            'user' => $user,
            'roles' => $this->roles()
        ]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        try {
            $this->userService->update($id, $request->validated());

            return redirect()
                ->route('user.index')
                ->with('success', 'User dan profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $this->userService->delete($id);

        return redirect()->route('user.index')->with('success', 'User deleted successfully');
    }
}
