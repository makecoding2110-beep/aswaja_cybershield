<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Kata Kunci Perjudian Online & Pola Defacement (Threat Intelligence)
    |--------------------------------------------------------------------------
    | Digunakan oleh UrlThreatAnalyzer untuk mendeteksi Web Defacement,
    | Subdomain Hijacking, dan Open Redirect bertarget judol.
    */

    // Indikator Kuat (Eksplisit Judi Online)
    'strong_judol' => [
        'slot', 'gacor', 'togel', 'toto', 'maxwin', 'jackpot', 'scatter', 'sbobet',
        'casino', 'kasino', 'parlay', 'mix-parlay', 'joker123', 'pragmatic', 'pgsoft', 'habanero',
        'depo-slot', 'depo-dana', 'deposit-slot', 'deposit-pulsa', 'deposit', 'judol', 'taruhan', 'roulette', 'baccarat', 'sicbo',
        'colok', 'free-spin', 'freespin', 'rtp-live', 'rtp-slot', 'pola-gacor',
        'agen-slot', 'bandar-togel', 'slot88', 'slot777', 'gacor88', 'gacor777',
        'depo-pulsa', 'depo-10k', 'bonus-new-member', 'sabung-ayam', 'mauslot',
        'startogel', 'semuahoki', 'kpktoto', 'araitoto', 'dewacukong', 'cukong88',
        'hokibet', 'hokibet88', 'hoki88', 'dewaslot88', 'bet88', 'anti-rungkad',
        'ibiza168', 'ibiza', 'slotmaxwin', 'rtp-winrate', 'link-resmi-gacor', 'mahjong-ways'
    ],

    // Frasa Konten Halaman Live (Mendeteksi situs judol yang bersembunyi di balik nama domain normal)
    'live_content_judol_phrases' => [
        'slot gacor', 'situs slot', 'link situs slot', 'gampang jackpot', 'gampang menang',
        'slot paling gacor', 'situs togel', 'togel online', 'startogel', 'mauslot', 'mau slot',
        'semuahoki', 'semua hoki', 'kpktoto', 'araitoto', 'dewacukong', 'dewa slot88',
        'sultan mania', 'zeus petir', 'daftar akun gacor', 'rtp 98%', 'bonus new member',
        'sabung ayam', 'free chip', 'pecahan selayar', 'next depo', 'slot & casino',
        'provider games terlengkap', 'situs hoki pembawa rezeki', 'maxwin hari ini',
        'hokibet88', 'hoki88', 'hoki slot', 'dewaslot88', 'dewa slot gacor',
        'anti rungkad', 'rtp & pola slot', 'pola slot', 'rtp game', 'demo slot', 'situs gacor',
        'ibiza168', 'slot88', 'slot88 maxwin', 'link resmi gacor', 'rtp winrate', 'min deposit 20k',
        'mahjong ways', 'gampang maxwin'
    ],

    // Indikator DNS Sinkhole Pemblokiran Pemerintah (Komdigi TrustPositif RI)
    'trustpositif_sinkhole_hosts' => [
        'lamanlabuh.aduankonten.id', 'aduankonten.id', 'trustpositif.kominfo.go.id', 'internetpositif.id'
    ],
    'trustpositif_sinkhole_ips' => [
        '182.23.79.195', '139.255.196.196', '139.255.196.197', '36.86.63.185'
    ],

    // Platform Pembuat Web Gratis / Link-in-Bio yang Sering Disalahgunakan Bandar Judol
    'abused_builder_hosts' => [
        'squarespace.com', 'mssg.me', 'heylink.me', 'linktr.ee', 'bio.site',
        'mez.ink', 'taplink.cc', 'carrd.co', 'beacons.ai', 'campsite.bio'
    ],

    // Parameter Iklan Berbayar (Malvertising Google Ads untuk Judol)
    'malvertising_params' => [
        'gad_source', 'gad_campaignid', 'gclid', 'gbraid', 'wbraid'
    ],

    // Indikator Kontekstual (Tema Mitologi / Permainan Populer Judol)
    'contextual_judol' => [
        'zeus', 'dewa', 'thor', 'dragon', 'olympus', 'mahjong', 'bonanza',
        'starlight', 'sweet-bonanza', 'gates-of-olympus', 'spaceman', 'aztec'
    ],

    // Angka Khas Sindikat Perjudian
    'suspicious_numbers' => [
        '88', '138', '777', '888', '999', '303', '168', '4d', '3d', '2d'
    ],

    // Jalur Unggahan Rentan Web Defacement (WordPress, Joomla, OJS)
    'vulnerable_upload_paths' => [
        'wp-content/uploads',
        'wp-includes',
        'uploads',
        'journal',
        'ojs',
        'files',
        'assets/user',
        'images/stories',
        'cache'
    ],

    // Parameter Query Terindikasi Open Redirect
    'open_redirect_params' => [
        'url', 'redirect', 'redirect_uri', 'to', 'link', 'dest',
        'next', 'goto', 'return', 'target', 'r', 'u', 'forward',
        'out', 'view', 'continue', 'redir'
    ],

    // Layanan Masking URL, Domain Rebouncer & Ekosistem Berbagi (Google Share, Shortener, Proxy Link)
    'masking_and_rebouncer_domains' => [
        'share.google', 'app.goo.gl', 'goo.gl', 'bit.ly', 'tinyurl.com', 't.co',
        'cutt.ly', 'is.gd', 'rb.gy', 's.id', 'shorturl.at', 'linktr.ee', 'dld.bz',
        'wa.me', 'msft.it', 'aka.ms', 'fb.me', 'tribun.id'
    ],

    // Domain & Entitas Platform Trading Ilegal / Binary Options / Investasi Menyesatkan Tanpa Izin Bappebti & OJK
    'illegal_investment_domains' => [
        'olymptrade.com', 'olymptrade', 'binomo.com', 'binomo', 'quotex.io', 'quotex.com', 'quotex',
        'iqoption.com', 'iqoption', 'pocketoption.com', 'pocketoption', 'octafx.com', 'octafx',
        'fbs.com', 'dna-pro', 'net89', 'autotrade-gold', 'fin888', 'titip-dana', 'arisan-online'
    ]
];

