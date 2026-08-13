<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'search' => ['nullable', 'string', 'max:100'],
            'sort_field' => ['nullable', Rule::in(['name', 'email', 'created_at'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        $sortField = $validated['sort_field'] ?? 'created_at';
        $sortOrder = $validated['sort_order'] ?? 'desc';

        $users = User::query()
            ->select(['id', 'name', 'email', 'created_at'])
            ->when($validated['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortField, $sortOrder)
            ->paginate($validated['per_page'] ?? 10)
            ->withQueryString();

        return Inertia::render('Users', [
            'users' => $users,
            'filters' => [
                'search' => $validated['search'] ?? '',
            ],
        ]);
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        User::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User created.')]);

        return to_route('users.index');
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if (! filled($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User updated.')]);

        return to_route('users.index');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 403, __('You cannot delete your own account here.'));

        $user->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User deleted.')]);

        return to_route('users.index');
    }
}
