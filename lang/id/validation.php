<?php

return [

    /*
    |---------------------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi
    |---------------------------------------------------------------------------------------
    |
    | Baris bahasa berikut ini berisi standar pesan kesalahan yang digunakan oleh
    | kelas validasi. Beberapa aturan mempunyai banyak versi seperti aturan 'size'.
    | Jangan ragu untuk mengoptimalkan setiap pesan yang ada di sini.
    |
    */

    'accepted'        => ':attribute harus diterima.',
    'accepted_if'     => ':attribute harus diterima ketika :other adalah :value.',
    'active_url'      => ':attribute bukan URL yang valid.',
    'after'           => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'  => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'           => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'      => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'       => ':attribute hanya boleh berisi huruf dan angka.',
    'array'           => ':attribute harus berisi sebuah array.',
    'ascii'           => ':attribute hanya boleh berisi karakter dan simbol alphanumerik.',
    'before'          => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'         => [
        'array'   => ':attribute harus memiliki :min sampai :max anggota.',
        'file'    => ':attribute harus berukuran antara :min sampai :max kilobyte.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string'  => ':attribute harus berisi antara :min sampai :max karakter.',
    ],
    'boolean'        => ':attribute harus bernilai true atau false',
    'confirmed'      => 'Konfirmasi :attribute tidak cocok.',
    'current_passowrd' => 'Password yang dimasukkan salah.',
    'date'           => ':attribute bukan tanggal yang valid.',
    'date_equals'    => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'    => ':attribute tidak cocok dengan format :format.',
    'decimal'        => ':attribute harus berupa angka desimal.',
    'declined'       => ':attribute harus ditolak.',
    'declined_if'    => ':attribute harus ditolak ketika :other adalah :value.',
    'different'      => ':attribute dan :other harus berbeda.',
    'digits'         => ':attribute harus terdiri dari :digits angka.',
    'digits_between' => ':attribute harus terdiri dari :min sampai :max angka.',
    'dimensions'     => ':attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'       => ':attribute memiliki nilai yang duplikat.',
    'doesnt_end_with' => ':attribute tidak boleh diakhiri salah satu dari berikut: :values',
    'doesnt_start_with' => ':attribute tidak boleh dimulai salah satu dari berikut: :values',
    'email'          => ':attribute harus berupa alamat surel yang valid.',
    'ends_with'      => ':attribute harus diakhiri salah satu dari berikut: :values',
    'enum'          => ':attribute yang dipilih tidak valid.',
    'exists'         => ':attribute yang dipilih tidak valid.',
    'file'           => ':attribute harus berupa sebuah berkas.',
    'filled'         => ':attribute harus memiliki nilai.',
    'gt'             => [
        'array'   => ':attribute harus memiliki lebih dari :value anggota.',
        'file'    => ':attribute harus berukuran lebih besar dari :value kilobyte.',
        'numeric' => ':attribute harus bernilai lebih besar dari :value.',
        'string'  => ':attribute harus berisi lebih besar dari :value karakter.',
    ],
    'gte' => [
        'array'   => ':attribute harus terdiri dari :value anggota atau lebih.',
        'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'string'  => ':attribute harus berisi lebih besar dari atau sama dengan :value karakter.',
    ],
    'image'    => ':attribute harus berupa gambar.',
    'in'       => ':attribute yang dipilih tidak valid.',
    'in_array' => ':attribute tidak ada di dalam :other.',
    'integer'  => ':attribute harus berupa bilangan bulat.',
    'ip'       => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'     => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'     => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'     => ':attribute harus berupa JSON string yang valid.',
    'lowercase' => ':attribute harus berupa string huruf kecil.',
    'lt'       => [
        'array'   => ':attribute harus memiliki kurang dari :value anggota.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobyte.',
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'string'  => ':attribute harus berisi kurang dari :value karakter.',
    ],
    'lte' => [
        'array'   => ':attribute harus tidak lebih dari :value anggota.',
        'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus bernilai kurang dari atau sama dengan :value.',
        'string'  => ':attribute harus berisi kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => ':attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array'   => ':attribute maksimal terdiri dari :max anggota.',
        'file'    => ':attribute maksimal berukuran :max kilobyte.',
        'numeric' => ':attribute maskimal bernilai :max.',
        'string'  => ':attribute maskimal berisi :max karakter.',
    ],
    'max_digits' => ':attribute tidak boleh bernilai lebih dari :max digit.',
    'mimes'     => ':attribute harus berupa berkas berjenis: :values.',
    'mimetypes' => ':attribute harus berupa berkas berjenis: :values.',
    'min'       => [
        'array'   => ':attribute minimal terdiri dari :min anggota.',
        'file'    => ':attribute minimal berukuran :min kilobyte.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string'  => ':attribute minimal berisi :min karakter.',
    ],
    'min_digits' => ':attribute tidak boleh bernilai kurang dari :min digit.',
    'missing'    => ':attribute harus dihilangkan.',
    'missing_if' => ':attribute harus dihilangkan ketika :other adalah :value.',
    'missing_unless' => ':attribute harus dihilangkan kecuali :other memiliki nilai :values.',
    'missing_with' => ':attribute harus dihilangkan ketika terdapat :values.',
    'missing_with_all' => ':attribute harus dihilangkan ketika terdapat :values.',
    'multiple_of' => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters' => ':attribute harus berisi paling sedikit satu huruf.',
        'mixed'   => ':attribute harus berisi paling sedikit satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute harus berisi paling sedikit satu angka.',
        'symbols' => ':attribute harus berisi paling sedikit satu simbol.',
        'uncompromised' => ':attribute telah terdaftar dalam daftar data yang bocor. Silakan gunakan kata sandi yang lebih aman.',
    ],
    'present'              => ':attribute wajib ada.',
    'present_if'           => ':attribute wajib ada jika :other adalah :value.',
    'present_unless'       => ':attribute wajib ada kecuali jika :other adalah :value.',
    'present_with'         => ':attribute wajib ada jika :values ada.',
    'present_with_all'     => ':attribute wajib ada jika :values ada.',
    'prohibited'           => ':attribute dilarang.',
    'prohibited_if'        => ':attribute dilarang bila :other adalah :value.',
    'prohibited_unless'    => ':attribute dilarang kecuali :other memiliki nilai :values.',
    'prohibits'            => ':attribute dilarang bila :other ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute harus diisi.',
    'required_array_keys'  => ':attribute wajib memiliki nilai untuk :keys.',
    'required_if'          => ':attribute harus diisi bila :other adalah :value.',
    'required_if_accepted' => ':attribute harus diisi bila :other diterima.',
    'required_unless'      => ':attribute harus diisi kecuali :other memiliki nilai :values.',
    'required_with'        => ':attribute harus diisi bila terdapat :values.',
    'required_with_all'    => ':attribute harus diisi bila terdapat :values.',
    'required_without'     => ':attribute harus diisi bila tidak terdapat :values.',
    'required_without_all' => ':attribute harus diisi bila sama sekali tidak terdapat :values.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'array'   => ':attribute harus mengandung :size anggota.',
        'file'    => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus berukuran :size.',
        'string'  => ':attribute harus berukuran :size karakter.',
    ],
    'starts_with' => ':attribute harus diawali salah satu dari berikut: :values',
    'string'      => ':attribute harus berupa string.',
    'timezone'    => ':attribute harus berisi zona waktu yang valid.',
    'unique'      => ':attribute sudah ada sebelumnya.',
    'uploaded'    => ':attribute gagal diunggah.',
    'uppercase'   => ':attribute harus berupa huruf besar.',
    'url'         => ':attribute harus berupa URL yang valid.',
    'ulid'        => ':attribute harus merupakan ULID yang valid.',
    'uuid'        => ':attribute harus merupakan UUID yang valid.',

    /*
    |---------------------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi Kustom
    |---------------------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi untuk atribut sesuai keinginan dengan menggunakan
    | konvensi "attribute.rule" dalam penamaan barisnya. Hal ini mempercepat dalam menentukan
    | baris bahasa kustom yang spesifik untuk aturan atribut yang diberikan.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |---------------------------------------------------------------------------------------
    | Kustom Validasi Atribut
    |---------------------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar 'placeholder' atribut dengan sesuatu yang
    | lebih mudah dimengerti oleh pembaca seperti "Alamat Surel" daripada "surel" saja.
    | Hal ini membantu kita dalam membuat pesan menjadi lebih ekspresif.
    |
    */

    'attributes' => [
        'name' => 'Nama',
        'email' => 'Alamat Email',
        'current_password' => 'Password Lama',
    ],
];
