<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function index(): View
    {
        $sellers = User::sellers()->orderBy('name')->paginate(15);

        return view('admin.seller.index', compact('sellers'));
    }

    public function create(): View
    {
        return view('admin.seller.create', ['majors' => User::MAJORS]);
    }

    public function store(StoreSellerRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $seller = new User(collect($validated)->except('password')->all());
        $seller->password = $validated['password'];
        $seller->role = User::ROLE_SELLER;
        $seller->is_active = true;
        $seller->email_verified_at = now();
        $seller->save();

        return redirect()->route('admin.seller.index')->with('success', 'Akun seller berhasil dibuat.');
    }

    public function edit(User $seller): View
    {
        $this->ensureSeller($seller);

        return view('admin.seller.edit', [
            'seller' => $seller,
            'majors' => User::MAJORS,
        ]);
    }

    public function update(UpdateSellerRequest $request, User $seller): RedirectResponse
    {
        $this->ensureSeller($seller);
        $seller->fill($request->validated());
        $seller->save();

        return redirect()->route('admin.seller.index')->with('success', 'Data seller berhasil diperbarui.');
    }

    public function toggleActive(User $seller): RedirectResponse
    {
        $this->ensureSeller($seller);
        $seller->is_active = ! $seller->is_active;
        $seller->save();

        return back()->with('success', $seller->is_active ? 'Akun seller diaktifkan.' : 'Akun seller dinonaktifkan.');
    }

    public function resetPassword(Request $request, User $seller): RedirectResponse
    {
        $this->ensureSeller($seller);
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        $seller->password = $validated['password'];
        $seller->save();

        return back()->with('success', 'Password seller berhasil direset.');
    }

    private function ensureSeller(User $seller): void
    {
        abort_unless($seller->role === User::ROLE_SELLER, 404);
    }
}
