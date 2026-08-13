<?php

test('the application shares Indonesian locale and frontend translations', function () {
    expect(app()->getLocale())->toBe('id');

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->where('locale', 'id')
            ->where('translations.auth.login', 'Masuk')
            ->where('translations.navigation.dashboard', 'Dasbor'));
});

test('application messages are translated into Indonesian', function () {
    expect(__('User created.'))->toBe('Pengguna berhasil dibuat.')
        ->and(__('You cannot delete your own account here.'))
        ->toBe('Anda tidak dapat menghapus akun Anda sendiri di sini.');
});
