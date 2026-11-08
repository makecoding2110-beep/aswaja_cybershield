<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Basis Data Instansi Resmi Pemerintah & Lembaga Finansial Indonesia
    |--------------------------------------------------------------------------
    | Digunakan untuk mendeteksi Typosquatting, Homoglyph Attack, dan Impersonasi.
    */

    'brands' => [
        'ojk' => [
            'name' => 'Otoritas Jasa Keuangan (OJK)',
            'official_domains' => ['ojk.go.id'],
            'canonical_label' => 'ojk'
        ],
        'bappebti' => [
            'name' => 'Bappebti Kementerian Perdagangan',
            'official_domains' => ['bappebti.go.id'],
            'canonical_label' => 'bappebti'
        ],
        'bi' => [
            'name' => 'Bank Indonesia (BI)',
            'official_domains' => ['bi.go.id'],
            'canonical_label' => 'bi'
        ],
        'kominfo' => [
            'name' => 'Kementerian Komunikasi dan Digital (Komdigi/Kominfo)',
            'official_domains' => ['kominfo.go.id', 'komdigi.go.id'],
            'canonical_label' => 'kominfo'
        ],
        'kemkes' => [
            'name' => 'Kementerian Kesehatan RI',
            'official_domains' => ['kemkes.go.id'],
            'canonical_label' => 'kemkes'
        ],
        'kemensos' => [
            'name' => 'Kementerian Sosial RI',
            'official_domains' => ['kemensos.go.id'],
            'canonical_label' => 'kemensos'
        ],
        'pajak' => [
            'name' => 'Direktorat Jenderal Pajak (DJP)',
            'official_domains' => ['pajak.go.id'],
            'canonical_label' => 'pajak'
        ],
        'bpjs' => [
            'name' => 'BPJS Kesehatan & Ketenagakerjaan',
            'official_domains' => ['bpjs-kesehatan.go.id', 'bpjsketenagakerjaan.go.id'],
            'canonical_label' => 'bpjs'
        ],
        'polri' => [
            'name' => 'Kepolisian Negara Republik Indonesia (Polri)',
            'official_domains' => ['polri.go.id'],
            'canonical_label' => 'polri'
        ],
        'bca' => [
            'name' => 'Bank Central Asia (BCA)',
            'official_domains' => ['bca.co.id', 'klikbca.co.id'],
            'canonical_label' => 'bca'
        ],
        'mandiri' => [
            'name' => 'Bank Mandiri',
            'official_domains' => ['bankmandiri.co.id', 'livinbymandiri.co.id'],
            'canonical_label' => 'mandiri'
        ],
        'bri' => [
            'name' => 'Bank Rakyat Indonesia (BRI)',
            'official_domains' => ['bri.co.id', 'brimo.bri.co.id'],
            'canonical_label' => 'bri'
        ],
        'bni' => [
            'name' => 'Bank Negara Indonesia (BNI)',
            'official_domains' => ['bni.co.id', 'wondr.bni.co.id'],
            'canonical_label' => 'bni'
        ],
        'bareksa' => [
            'name' => 'Bareksa Marketplace Investasi',
            'official_domains' => ['bareksa.com'],
            'canonical_label' => 'bareksa'
        ]
    ],

    // Embel-embel manipulasi / social engineering penipuan
    'deceptive_suffixes' => [
        '-resmi', '-info', '-login', '-daftar', '-bansos', '-bantuan',
        '-klaim', '-hadiah', '-layanan', '-portal', '-cek', '-online',
        '-verifikasi', '-dana', '-pemberitahuan', '-pusat', '-update'
    ],

    // Kamus Substitusi Homoglif Karakter Mirip (Homoglyph Normalization)
    'homoglyph_map' => [
        '0' => 'o',
        '1' => 'l',
        'rn' => 'm',
        'vv' => 'w',
        '3' => 'e',
        '5' => 's',
        '8' => 'b',
        'cl' => 'd'
    ]
];

