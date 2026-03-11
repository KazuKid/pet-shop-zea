<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET /api/admin/users
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')->latest();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name',  'ilike', "%{$term}%")
                  ->orWhere('email', 'ilike', "%{$term}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        return response()->json($query->paginate(20));
    }

    /**
     * GET /api/admin/users/{id}
     */
    public function show(int $id)
    {
        $user = User::withCount('orders')->findOrFail($id);
        return response()->json($user);
    }

    /**
     * PUT /api/admin/users/{id}
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'sometimes|string|max:100',
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'role'     => 'sometimes|in:admin,customer',
            'password' => 'nullable|string|min:8',
        ]);

        // Only update password if explicitly provided
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json($user->fresh()->loadCount('orders'));
    }

    /**
     * DELETE /api/admin/users/{id}
     */
    public function destroy(int $id, Request $request)
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Tidak dapat menghapus akun yang sedang aktif.',
            ], 422);
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'message' => 'Tidak dapat menghapus admin terakhir.',
            ], 422);
        }

        $user->tokens()->each(fn ($t) => $t->delete());
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
