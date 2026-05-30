<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'member');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->withCount(['bookings', 'subscriptions', 'transactions'])
                         ->latest()
                         ->paginate(25)
                         ->withQueryString();

        $stats = [
            'total'  => User::where('role', 'member')->count(),
            'active' => User::where('role', 'member')->where('status', 'active')->count(),
        ];

        return view('superadmin.members.index', compact('members', 'stats'));
    }

    public function show(User $member)
    {
        abort_unless(strtolower($member->role) === 'member', 404);

        $member->load([
            'subscriptions.product',
            'bookings' => fn($q) => $q->with('batchClass.masterClass')->latest()->limit(10),
            'transactions' => fn($q) => $q->latest()->limit(10),
        ]);

        $activeSubscription = $member->subscriptions
            ->where('status', 'active')
            ->sortByDesc('end_date')
            ->first();

        return view('superadmin.members.show', compact('member', 'activeSubscription'));
    }

    public function edit(User $member)
    {
        abort_unless(strtolower($member->role) === 'member', 404);
        return view('superadmin.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        abort_unless(strtolower($member->role) === 'member', 404);

        $data = $request->validate([
            'name'   => 'required|string|max:120',
            'email'  => 'required|email|unique:users,email,' . $member->id,
            'phone'  => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        return redirect()->route('superadmin.members.show', $member)
            ->with('success', 'Member updated successfully.');
    }

    public function toggleStatus(User $member)
    {
        abort_unless(strtolower($member->role) === 'member', 404);

        $member->status = $member->status === 'active' ? 'inactive' : 'active';
        $member->save();

        $label = $member->status === 'active' ? 'activated' : 'deactivated';
        return back()->with('success', "Member {$label}.");
    }
}
