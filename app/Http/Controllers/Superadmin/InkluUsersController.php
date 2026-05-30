<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\IdentificationResult;
use App\Models\User;
use Illuminate\Http\Request;

class InkluUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'member')
            ->withCount('identificationResults')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('superadmin.inklu.users', compact('users'));
    }

    public function show(User $user)
    {
        $results = IdentificationResult::where('user_id', $user->id)
            ->orderByDesc('updated_at')->get();

        return view('superadmin.inklu.user-detail', compact('user', 'results'));
    }
}
