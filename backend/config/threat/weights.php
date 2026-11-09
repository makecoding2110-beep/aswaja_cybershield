<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bobot Skor & Aturan Penguatan Kombinasi Ancaman (Threat Scoring)
    |--------------------------------------------------------------------------
    | Skor kontribusi (0 - 100) per indikator dan matriks kombinasi.
    */

    'indicator_weights' => [
        // Web Defacement / Subdomain Hijacking
        'defacement_path' => 70,
        'subdomain_asing' => 65,
        'gov_edu_keyword_judol' => 75,

        // Open Redirect
        'open_redirect_param' => 35,
        'dua_host_dalam_url' => 40,
        'url_encoding_berlapis' => 30,
        'open_redirect_judol_dest' => 80,
        'open_redirect_cheap_tld' => 65,

        // Typosquatting / URL Spoofing
        'typosquatting_mirip_instansi' => 70,
        'tld_bukan_go_id' => 50,
        'domain_homoglif' => 65,
        'embel_embel_penipuan' => 45,

        // RDAP Domain Baru (< 30 Hari)
        'domain_baru' => 35,

        // Penyusupan Konten Judol Terselubung (Cloaking / Hijacked Commercial Domain / Malvertising)
        'cloaked_live_judol_title' => 95,
        'defaced_judol_subpath' => 95,
        'cloaked_live_judol_content' => 90,
        'cloaked_outbound_judol_link' => 92,
        'hijacked_path_judol' => 85,
        'kamuflase_investasi_judol' => 88,
        'direct_judol_path' => 85,
        'malvertising_cloaked_ad' => 75,
        'abused_landing_platform' => 70,
        'amp_cloaking_path' => 65,

        // Pemblokiran DNS Komdigi TrustPositif & Pembelajaran Mandiri Basis Data (Self-Learning Intelligence)
        'komdigi_trustpositif_block' => 95,
        'learned_from_reports_db' => 92,
        'learned_from_scan_history' => 90,
        'learned_pattern_intelligence' => 82
    ],

    // Nilai Ambang Minimal jika terjadi Kombinasi Berbahaya
    'combination_thresholds' => [
        // Open redirect ke tujuan kata kunci judol atau TLD mencurigakan -> Minimal 80 (Berbahaya)
        'open_redirect_with_malicious_dest' => 80,

        // Domain lembaga pemerintah / pendidikan (.go.id/.ac.id/.desa.id) + path judol -> Minimal 85 (Berbahaya)
        'defacement_gov_with_judol' => 85,

        // Typosquatting nama instansi resmi di TLD non-resmi (.com/.vip/dll) + embel-embel -> Minimal 80 (Berbahaya)
        'typosquatting_with_deceptive_suffix' => 80,
    ]
];

