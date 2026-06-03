<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function create()
    {
        return view('superadmin.members.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'        => 'required|string|max:150',
            'username'    => 'required|string|max:150|unique:users,username',
            'email'       => 'required|email|max:150|unique:users,email',
            'phone'       => 'nullable|string|max:50',
            'role'        => 'required|in:MEMBER,ORGANIZATION,ADMIN',
            'status'      => 'required|in:ACTIVE,SUSPENDED',
            'child_quota' => 'required|integer|min:1|max:500',
            'password'    => 'required|string|min:8|confirmed',
        ];

        if ($request->role === 'ORGANIZATION') {
            $rules['organization_name'] = 'required|string|max:255';
            $rules['organization_type'] = 'required|string|max:100';
        }

        $data = $request->validate($rules);

        User::create([
            'name'              => $data['name'],
            'full_name'         => $data['name'],
            'username'          => $data['username'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'password'          => Hash::make($data['password']),
            'role'              => $data['role'],
            'status'            => $data['status'],
            'child_quota'       => $data['child_quota'],
            'organization_name' => $data['organization_name'] ?? null,
            'organization_type' => $data['organization_type'] ?? null,
        ]);

        return redirect()->route('superadmin.members.index', ['tab' => strtolower($data['role'])])
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    public function index(Request $request)
    {
        $tab    = $request->get('tab', 'member');
        $search = $request->search;
        $status = $request->status;

        $roleMap = [
            'member'       => ['MEMBER'],
            'organization' => ['ORGANIZATION'],
            'admin'        => ['ADMIN', 'SUPERADMIN'],
        ];

        $roles = $roleMap[$tab] ?? $roleMap['member'];

        $query = User::whereIn('role', $roles);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('organization_name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', strtoupper($status));
        }

        $members = $query->withCount('children')->latest()->paginate(25)->withQueryString();

        $stats = [
            'member'       => User::whereIn('role', ['MEMBER'])->count(),
            'organization' => User::where('role', 'ORGANIZATION')->count(),
            'admin'        => User::whereIn('role', ['ADMIN', 'SUPERADMIN'])->count(),
            'active'       => User::whereIn('role', $roles)->where('status', 'ACTIVE')->count(),
        ];

        return view('superadmin.members.index', compact('members', 'stats', 'tab'));
    }

    public function show(User $member)
    {
        abort_unless(in_array(strtoupper($member->role), ['MEMBER', 'ORGANIZATION', 'ADMIN', 'SUPERADMIN']), 404);

        $children    = Child::where('user_id', $member->id)->with('assessments')->get();
        $assessments = \App\Models\Assessment::where('user_id', $member->id)
            ->with(['child', 'category'])
            ->latest()
            ->limit(10)
            ->get();

        return view('superadmin.members.show', compact('member', 'children', 'assessments'));
    }

    public function edit(User $member)
    {
        abort_unless(in_array(strtoupper($member->role), ['MEMBER', 'ORGANIZATION', 'ADMIN', 'SUPERADMIN']), 404);
        return view('superadmin.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        abort_unless(in_array(strtoupper($member->role), ['MEMBER', 'ORGANIZATION', 'ADMIN', 'SUPERADMIN']), 404);

        $rules = [
            'name'        => 'required|string|max:150',
            'username'    => 'nullable|string|max:150|unique:users,username,' . $member->id,
            'email'       => 'required|email|unique:users,email,' . $member->id,
            'phone'       => 'nullable|string|max:50',
            'status'      => 'required|in:ACTIVE,SUSPENDED',
            'child_quota' => 'required|integer|min:1|max:500',
        ];

        if (strtoupper($member->role) === 'ORGANIZATION') {
            $rules['organization_name'] = 'nullable|string|max:255';
            $rules['organization_type'] = 'nullable|string|max:100';
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        return redirect()->route('superadmin.members.show', $member)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleStatus(User $member)
    {
        abort_unless(in_array(strtoupper($member->role), ['MEMBER', 'ORGANIZATION', 'ADMIN', 'SUPERADMIN']), 404);

        $member->status = strtoupper($member->status) === 'ACTIVE' ? 'SUSPENDED' : 'ACTIVE';
        $member->save();

        $label = $member->status === 'ACTIVE' ? 'diaktifkan' : 'ditangguhkan';
        return back()->with('success', "Akun berhasil {$label}.");
    }

    // ── ORGANIZATION USER MANAGEMENT ──────────────────────────────────────

    public function organizations(Request $request)
    {
        $query = User::where('role', 'ORGANIZATION');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('organization_name', 'like', "%$s%");
            });
        }

        $organizations = $query->latest()->paginate(25)->withQueryString();

        return view('superadmin.organizations.index', compact('organizations'));
    }

    public function organizationCreate()
    {
        return view('superadmin.organizations.create');
    }

    public function organizationStore(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:150',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'nullable|string|max:50',
            'password'          => 'required|string|min:8|confirmed',
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|string|max:100',
            'child_quota'       => 'required|integer|min:1|max:500',
        ]);

        User::create([
            'name'              => $data['name'],
            'full_name'         => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'password'          => Hash::make($data['password']),
            'role'              => 'ORGANIZATION',
            'status'            => 'ACTIVE',
            'organization_name' => $data['organization_name'],
            'organization_type' => $data['organization_type'],
            'child_quota'       => $data['child_quota'],
        ]);

        return redirect()->route('superadmin.organizations.index')
            ->with('success', 'Akun organisasi berhasil dibuat.');
    }

    public function quotaEdit(User $member)
    {
        abort_unless(in_array(strtoupper($member->role), ['MEMBER', 'ORGANIZATION']), 404);
        return view('superadmin.organizations.quota', compact('member'));
    }

    public function quotaUpdate(Request $request, User $member)
    {
        abort_unless(in_array(strtoupper($member->role), ['MEMBER', 'ORGANIZATION']), 404);
        $request->validate(['child_quota' => 'required|integer|min:1|max:500']);
        $member->update(['child_quota' => $request->child_quota]);
        return back()->with('success', 'Kuota anak berhasil diperbarui.');
    }
}
