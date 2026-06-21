<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LandingFact;
use App\Models\LandingTeamMember;
use App\Models\LandingHki;
use App\Models\LandingPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingContentController extends Controller
{
    // ── OVERVIEW ──────────────────────────────────────────────────────────
    public function index()
    {
        $factCount    = LandingFact::count();
        $teamCount    = LandingTeamMember::count();
        $hkiCount     = LandingHki::count();
        $partnerCount = LandingPartner::count();
        return view('superadmin.landing.index', compact('factCount', 'teamCount', 'hkiCount', 'partnerCount'));
    }

    // ── FAKTA UNIK ────────────────────────────────────────────────────────
    public function facts()          { return view('superadmin.landing.facts.index', ['facts' => LandingFact::orderBy('sort_order')->get()]); }
    public function factsCreate()    { return view('superadmin.landing.facts.form', ['fact' => null]); }
    public function factsStore(Request $r)
    {
        $r->validate(['title' => 'required|string|max:255', 'body' => 'nullable|string', 'icon' => 'nullable|string|max:100', 'sort_order' => 'integer']);
        LandingFact::create($r->only('title', 'body', 'icon', 'sort_order') + ['is_active' => $r->boolean('is_active', true)]);
        return redirect()->route('superadmin.landing.facts')->with('success', 'Fakta berhasil ditambahkan.');
    }
    public function factsEdit(LandingFact $fact)  { return view('superadmin.landing.facts.form', compact('fact')); }
    public function factsUpdate(Request $r, LandingFact $fact)
    {
        $r->validate(['title' => 'required|string|max:255', 'body' => 'nullable|string', 'icon' => 'nullable|string|max:100', 'sort_order' => 'integer']);
        $fact->update($r->only('title', 'body', 'icon', 'sort_order') + ['is_active' => $r->boolean('is_active')]);
        return redirect()->route('superadmin.landing.facts')->with('success', 'Fakta berhasil diperbarui.');
    }
    public function factsDestroy(LandingFact $fact) { $fact->delete(); return back()->with('success', 'Fakta dihapus.'); }

    // ── TIM PENGEMBANG ────────────────────────────────────────────────────
    public function team()         { return view('superadmin.landing.team.index', ['members' => LandingTeamMember::orderBy('sort_order')->get()]); }
    public function teamCreate()   { return view('superadmin.landing.team.form', ['member' => null]); }
    public function teamStore(Request $r)
    {
        $r->validate(['name' => 'required|string|max:255', 'role_label' => 'nullable|string|max:150', 'affiliation' => 'nullable|string|max:255', 'group' => 'required|in:dosen,mahasiswa,eksternal', 'photo' => 'nullable|image|max:5120', 'sort_order' => 'integer']);
        $data = $r->only('name', 'role_label', 'affiliation', 'group', 'sort_order') + ['is_active' => $r->boolean('is_active', true)];
        if ($r->hasFile('photo')) $data['photo'] = $r->file('photo')->store('team', 'public');
        LandingTeamMember::create($data);
        return redirect()->route('superadmin.landing.team')->with('success', 'Anggota tim berhasil ditambahkan.');
    }
    public function teamEdit(LandingTeamMember $member) { return view('superadmin.landing.team.form', compact('member')); }
    public function teamUpdate(Request $r, LandingTeamMember $member)
    {
        $r->validate(['name' => 'required|string|max:255', 'role_label' => 'nullable|string|max:150', 'affiliation' => 'nullable|string|max:255', 'group' => 'required|in:dosen,mahasiswa,eksternal', 'photo' => 'nullable|image|max:5120', 'sort_order' => 'integer']);
        $data = $r->only('name', 'role_label', 'affiliation', 'group', 'sort_order') + ['is_active' => $r->boolean('is_active')];

        if ($r->hasFile('photo')) {
            if ($member->photo) Storage::disk('public')->delete($member->photo);
            $data['photo'] = $r->file('photo')->store('team', 'public');
        } elseif ($r->input('remove_photo') === '1' && $member->photo) {
            Storage::disk('public')->delete($member->photo);
            $data['photo'] = null;
        }

        $member->update($data);
        return redirect()->route('superadmin.landing.team')->with('success', 'Anggota tim berhasil diperbarui.');
    }
    public function teamDestroy(LandingTeamMember $member)
    {
        if ($member->photo) Storage::disk('public')->delete($member->photo);
        $member->delete();
        return back()->with('success', 'Anggota tim dihapus.');
    }

    // ── HKI PATEN ─────────────────────────────────────────────────────────
    public function hki()         { return view('superadmin.landing.hki.index', ['hkis' => LandingHki::latest()->get()]); }
    public function hkiCreate()   { return view('superadmin.landing.hki.form', ['hki' => null]); }
    public function hkiStore(Request $r)
    {
        $r->validate(['title' => 'required|string|max:255', 'description' => 'nullable|string', 'image' => 'nullable|image|max:5120', 'certificate_number' => 'nullable|string|max:150', 'issued_date' => 'nullable|date']);
        $data = $r->only('title', 'description', 'certificate_number', 'issued_date') + ['is_active' => $r->boolean('is_active', true)];
        if ($r->hasFile('image')) $data['image'] = $r->file('image')->store('hki', 'public');
        LandingHki::create($data);
        return redirect()->route('superadmin.landing.hki')->with('success', 'HKI berhasil ditambahkan.');
    }
    public function hkiEdit(LandingHki $hki) { return view('superadmin.landing.hki.form', compact('hki')); }
    public function hkiUpdate(Request $r, LandingHki $hki)
    {
        $r->validate(['title' => 'required|string|max:255', 'description' => 'nullable|string', 'image' => 'nullable|image|max:5120', 'certificate_number' => 'nullable|string|max:150', 'issued_date' => 'nullable|date']);
        $data = $r->only('title', 'description', 'certificate_number', 'issued_date') + ['is_active' => $r->boolean('is_active')];
        if ($r->hasFile('image')) {
            if ($hki->image) Storage::disk('public')->delete($hki->image);
            $data['image'] = $r->file('image')->store('hki', 'public');
        }
        $hki->update($data);
        return redirect()->route('superadmin.landing.hki')->with('success', 'HKI berhasil diperbarui.');
    }
    public function hkiDestroy(LandingHki $hki)
    {
        if ($hki->image) Storage::disk('public')->delete($hki->image);
        $hki->delete();
        return back()->with('success', 'HKI dihapus.');
    }

    // ── PARTNER ───────────────────────────────────────────────────────────
    public function partners()         { return view('superadmin.landing.partners.index', ['partners' => LandingPartner::orderBy('sort_order')->get()]); }
    public function partnersCreate()   { return view('superadmin.landing.partners.form', ['partner' => null]); }
    public function partnersStore(Request $r)
    {
        $r->validate(['name' => 'required|string|max:255', 'logo' => 'nullable|image|max:5120', 'website_url' => 'nullable|url|max:500', 'description' => 'nullable|string', 'sort_order' => 'integer']);
        $data = $r->only('name', 'website_url', 'description', 'sort_order') + ['is_active' => $r->boolean('is_active', true)];
        if ($r->hasFile('logo')) $data['logo'] = $r->file('logo')->store('partners', 'public');
        LandingPartner::create($data);
        return redirect()->route('superadmin.landing.partners')->with('success', 'Partner berhasil ditambahkan.');
    }
    public function partnersEdit(LandingPartner $partner) { return view('superadmin.landing.partners.form', compact('partner')); }
    public function partnersUpdate(Request $r, LandingPartner $partner)
    {
        $r->validate(['name' => 'required|string|max:255', 'logo' => 'nullable|image|max:5120', 'website_url' => 'nullable|url|max:500', 'description' => 'nullable|string', 'sort_order' => 'integer']);
        $data = $r->only('name', 'website_url', 'description', 'sort_order') + ['is_active' => $r->boolean('is_active')];
        if ($r->hasFile('logo')) {
            if ($partner->logo) Storage::disk('public')->delete($partner->logo);
            $data['logo'] = $r->file('logo')->store('partners', 'public');
        }
        $partner->update($data);
        return redirect()->route('superadmin.landing.partners')->with('success', 'Partner berhasil diperbarui.');
    }
    public function partnersDestroy(LandingPartner $partner)
    {
        if ($partner->logo) Storage::disk('public')->delete($partner->logo);
        $partner->delete();
        return back()->with('success', 'Partner dihapus.');
    }
}

