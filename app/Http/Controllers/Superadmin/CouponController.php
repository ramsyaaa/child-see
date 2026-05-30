<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                      });
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<=', now());
            }
        }

        $coupons = $query->latest()->paginate(20)->withQueryString();

        return view('superadmin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('superadmin.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'         => 'required|string|max:50|unique:coupons,code',
            'description'  => 'nullable|string|max:255',
            'type'         => 'required|in:percentage,nominal',
            'value'        => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses'     => 'nullable|integer|min:1',
            'expires_at'   => 'nullable|date|after:today',
            'is_active'    => 'nullable|boolean',
        ]);

        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage discount cannot exceed 100%.'])->withInput();
        }

        $data['code']      = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', true);

        Coupon::create($data);

        return redirect()->route('superadmin.coupons.index')
            ->with('success', 'Coupon "' . $data['code'] . '" created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('superadmin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'         => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description'  => 'nullable|string|max:255',
            'type'         => 'required|in:percentage,nominal',
            'value'        => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses'     => 'nullable|integer|min:1',
            'expires_at'   => 'nullable|date',
            'is_active'    => 'nullable|boolean',
        ]);

        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage discount cannot exceed 100%.'])->withInput();
        }

        $data['code']      = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', false);

        $coupon->update($data);

        return redirect()->route('superadmin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->used_count > 0) {
            return back()->with('error', 'Cannot delete a coupon that has been used. Deactivate it instead.');
        }
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }
}
