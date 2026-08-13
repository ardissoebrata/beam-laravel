<?php

return [
    'required' => ':attribute wajib diisi.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi saat ini salah.',
    'min' => [
        'string' => ':attribute minimal harus terdiri dari :min karakter.',
    ],
    'max' => [
        'string' => ':attribute maksimal harus terdiri dari :max karakter.',
    ],
    'unique' => ':attribute sudah digunakan.',
    'same' => ':attribute dan :other harus cocok.',
    'string' => ':attribute harus berupa teks.',
    'integer' => ':attribute harus berupa angka.',
    'in' => ':attribute yang dipilih tidak valid.',
    'attributes' => [
        'name' => 'nama',
        'email' => 'email',
        'password' => 'kata sandi',
        'password_confirmation' => 'konfirmasi kata sandi',
        'current_password' => 'kata sandi saat ini',
        'sort_field' => 'kolom pengurutan',
        'sort_order' => 'urutan pengurutan',
        'per_page' => 'jumlah per halaman',
        'search' => 'pencarian',
    ],
];
