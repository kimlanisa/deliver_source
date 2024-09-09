<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    // Required
    'name_required'       => 'Nama wajib diisi.',
    'phone_required'       => 'No. Handphone wajib diisi.',
    'email_required'      => 'Email wajib diisi.',
    'status_required'     => 'Status wajib diisi.',
    'role_required'       => 'Role wajib diisi.',
    'password_required'   => 'Password wajib diisi.',
    //roles
    'role_name_required'   => 'Nama Role wajib diisi.',
    // admin
    'admin_user_id_required' => 'Nama user wajib diisi.',

    'accepted'             => 'Isian :attribute harus diterima.',
    'active_url'           => 'Isian :attribute bukan URL yang sah.',
    'after'                => 'Isian :attribute harus tanggal setelah :date.',
    'after_or_equal'       => 'Isian :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha'                => 'Isian :attribute hanya boleh berisi huruf.',
    'alpha_dash'           => 'Isian :attribute hanya boleh berisi huruf, angka, dan strip.',
    'alpha_num'            => 'Isian :attribute hanya boleh berisi huruf dan angka.',
    'array'                => 'Isian :attribute harus berupa sebuah array.',
    'before'               => 'Isian :attribute harus tanggal sebelum :date.',
    'before_or_equal'      => 'Isian :attribute harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => 'Isian :attribute harus antara :min dan :max.',
        'file'    => 'Isian :attribute harus antara :min dan :max kilobytes.',
        'string'  => 'Isian :attribute harus antara :min dan :max karakter.',
        'array'   => 'Isian :attribute harus antara :min dan :max item.',
    ],
    'boolean'              => 'Isian :attribute harus berupa true atau false',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => 'Isian :attribute bukan tanggal yang valid.',
    'date_equals'          => 'Isian :attribute harus tanggal yang sama dengan :date.',
    'date_format'          => 'Isian :attribute tidak cocok dengan format :format.',
    'different'            => 'Isian :attribute dan :other harus berbeda.',
    'digits'               => 'Isian :attribute harus berupa angka :digits.',
    'digits_between'       => 'Isian :attribute harus antara angka :min dan :max.',
    'dimensions'           => 'Isian :attribute harus merupakan dimensi gambar yang sah.',
    'distinct'             => 'Isian :attribute memiliki nilai yang duplikat.',
    'email'                => 'Isian :attribute harus berupa alamat surel yang valid.',
    'ends_with'            => 'Isian :attribute harus diakhiri dengan: :values.',
    'exists'               => 'Isian :attribute yang dipilih tidak valid.',
    'file'                 => 'Isian :attribute harus berupa file.',
    'filled'               => 'Isian :attribute wajib diisi.',
    'gt' => [
        'numeric'   => 'Isian :attribute harus lebih besar dari :value.',
        'file'      => 'Isian :attribute harus lebih besar dari :value kilobytes.',
        'string'    => 'Isian :attribute harus lebih besar dari :value karakter.',
        'array'     => 'Isian :attribute harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric'   => 'Isian :attribute harus lebih besar dari atau sama dengan :value.',
        'file'      => 'Isian :attribute harus lebih besar dari atau sama dengan :value kilobytes.',
        'string'    => 'Isian :attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array'     => 'Isian :attribute harus memiliki :value item atau lebih.',
    ],
    'image'                => 'Isian :attribute harus berupa gambar.',
    'in'                   => 'Isian :attribute yang dipilih tidak valid.',
    'in_array'             => 'Isian :attribute tidak terdapat dalam :other.',
    'integer'              => 'Isian :attribute harus merupakan bilangan bulat.',
    'ip'                   => 'Isian :attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => 'Isian :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => 'Isian :attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => 'Isian :attribute harus berupa JSON string yang valid.',
    'lt' => [
        'numeric'   => 'Isian :attribute harus kurang dari :value.',
        'file'      => 'Isian :attribute harus kurang dari :value kilobytes.',
        'string'    => 'Isian :attribute harus kurang dari :value karakter.',
        'array'     => 'Isian :attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric'   => 'Isian :attribute harus kurang dari atau sama dengan :value.',
        'file'      => 'Isian :attribute harus kurang dari atau sama dengan :value kilobytes.',
        'string'    => 'Isian :attribute harus kurang dari atau sama dengan :value karakter.',
        'array'     => 'Isian :attribute tidak boleh lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => 'Isian :attribute seharusnya tidak lebih dari :max.',
        'file'    => 'Isian :attribute seharusnya tidak lebih dari :max kilobytes.',
        'string'  => 'Isian :attribute seharusnya tidak lebih dari :max karakter.',
        'array'   => 'Isian :attribute seharusnya tidak lebih dari :max item.',
    ],
    'mimes'                => 'Isian :attribute harus dokumen berjenis : :values.',
    'mimetypes'            => 'Isian :attribute harus berupa file bertipe: :values.',
    'min'                  => [
        'numeric' => 'Isian :attribute harus minimal :min.',
        'file'    => 'Isian :attribute harus minimal :min kilobytes.',
        'string'  => 'Isian :attribute harus minimal :min karakter.',
        'array'   => 'Isian :attribute harus minimal :min item.',
    ],
    'not_in'               => 'Isian :attribute yang dipilih tidak valid.',
    'not_regex'            => 'Isian :attribute format tidak valid.',
    'numeric'              => 'Isian :attribute harus berupa angka.',
    'password'             => 'Kata sandi tidak benar',
    'present'              => 'Isian :attribute wajib ada.',
    'regex'                => 'Format isian :attribute tidak valid.',
    'required'             => 'Isian :attribute wajib diisi.',
    'required_if'          => 'Isian :attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => 'Isian :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'        => 'Isian :attribute wajib diisi bila terdapat :values.',
    'required_with_all'    => 'Isian :attribute wajib diisi bila terdapat :values.',
    'required_without'     => 'Isian :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Isian :attribute wajib diisi bila tidak terdapat ada :values.',
    'same'                 => 'Isian :attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => 'Isian :attribute harus berukuran :size.',
        'file'    => 'Isian :attribute harus berukuran :size kilobyte.',
        'string'  => 'Isian :attribute harus berukuran :size karakter.',
        'array'   => 'Isian :attribute harus mengandung :size item.',
    ],
    'starts_with'          => 'Isian :attribute harus dimulai dengan: :values.',
    'string'               => 'Isian :attribute harus berupa string.',
    'timezone'             => 'Isian :attribute harus berupa zona waktu yang valid.',
    'unique'               => 'Isian :attribute sudah ada sebelumnya.',
    'uploaded'             => 'Isian :attribute gagal mengunggah.',
    'url'                  => 'Format isian :attribute tidak valid.',
    'uuid'                 => 'Isian :attribute harus berupa UUID yang valid.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message'
        ],
        'attendance_date' => [
            'required' => 'Isian Tanggal Absen wajib diisi.'
        ],
        'attendance_time_in' => [
            'required' => 'Isian Jam Masuk wajib diisi.'
        ],
        'attendance_time_out' => [
            'required' => 'Isian Jam Keluar wajib diisi.'
        ],
        'attendance_notes' => [
            'required' => 'Isian Keterangan Absen wajib diisi.'
        ],
        'leave_kind_id' => [
            'required' => 'Isian Jenis Cuti wajib diisi.'
        ],
        'leave_type_id' => [
            'required' => 'Isian Tipe Cuti wajib diisi.'
        ],
        'date_start' => [
            'required' => 'Isian Tanggal Mulai wajib diisi.'
        ],
        'date_end' => [
            'required' => 'Isian Tanggal Berakhir wajib diisi.'
        ],
        'contact_emergency' => [
            'required' => 'Isian Kontak Darurat wajib diisi.'
        ],
        'description' => [
            'required' => 'Isian Keterangan wajib diisi.'
        ],
        'permit_type_id' => [
            'required' => 'Isian Tipe Izin wajib diisi.'
        ],
        'date_overtime' => [
            'required' => 'Isian Tanggal Lembur wajib diisi.'
        ],
        'overtime_type_id' => [
            'required' => 'Isian Tipe Lembur wajib diisi.'
        ],
        'start_overtime' => [
            'required' => 'Isian Tanggal Mulai-Selesai Lembur wajib diisi.'
        ],
        'dinas_purpose_id' => [
            'required' => 'Isian Tujuan Dinas wajib diisi.'
        ],
        'dinas_scope_id' => [
            'required' => 'Isian Cakupan Dinas wajib diisi.'
        ],
        'utilities' => [
            'required' => 'Isian Keperluan wajib diisi.'
        ],
        'cost_pocket_money' => [
            'required' => 'Isian Biaya Uang Saku wajib diisi.'
        ],
        'cost_meal' => [
            'required' => 'Isian Biaya Uang Makan wajib diisi.'
        ],
        'cost_other' => [
            'required' => 'Isian Biaya Lainnya wajib diisi.'
        ],
        'employee_id' => [
            'required' => 'Isian Pegawai wajib diisi.'
        ],
        'warning_letter_type_id' => [
            'required' => 'Isian Tipe Surat Peringatan wajib diisi.'
        ],
        'warning_letter_kind_id' => [
            'required' => 'Isian Jenis Surat Peringatan wajib diisi.'
        ],
        'effective_date' => [
            'required' => 'Isian Tanggal Efektif wajib diisi.'
        ],
        'submission_type_id' => [
            'required' => 'Isian Tipe Perubahan wajib diisi.'
        ],
        'reason' => [
            'required' => 'Isian Alasan wajib diisi.'
        ],
        'date_submit_resign_letter' => [
            'required' => 'Isian Tgl. Serah Surat Pengunduran Diri wajib diisi.'
        ],
        'date_last_work' => [
            'required' => 'Isian Tgl. Terakhir Bekerja wajib diisi.'
        ],
        'letters_reference' => [
            'required' => 'Isian Surat Referensi Bekerja wajib diisi.'
        ],
        'claim_category_id' => [
            'required' => 'Isian Kategori Klaim  wajib diisi.'
        ],
        'receipt_date' => [
            'required' => 'Isian Tanggal Kwitansi wajib diisi.'
        ],
        'file_claim' => [
            'required' => 'Isian Bukti Kwitansi wajib diisi.'
        ],
        'question' => [
            'required' => 'Isian Pertanyaan wajib diisi.'
        ],
        'kpi_type' => [
            'required' => 'Isian Tipe KPI wajib diisi.'
        ],
        'kpi_category_id' => [
            'required' => 'Isian Kategori KPI wajib diisi.'
        ],
        'status' => [
            'required' => 'Isian Status wajib diisi.'
        ],
        'answer' => [
            'required' => 'Isian Jawaban wajib diisi.'
        ],
        'score' => [
            'required' => 'Isian Skor wajib diisi.'
        ],
        'visit_date' => [
            'required' => 'Isian Tanggal Kunjungan wajib diisi.'
        ],
        'visit_start' => [
            'required' => 'Isian Mulai Kunjungan wajib diisi.'
        ],
        'visit_end' => [
            'required' => 'Isian Berakhir Kunjungan wajib diisi.'
        ],
        'visit_location_id' => [
            'required' => 'Isian Lokasi Kunjungan wajib diisi.'
        ],
        'area_id' => [
            'required' => 'Isian Area wajib diisi.'
        ],
        'value_receipt' => [
            'required' => 'Isian Nilai Pada Kwitansi wajib diisi.'
        ],
        'value_approved' => [
            'required' => 'Isian Nilai Di Setujui wajib diisi.'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
