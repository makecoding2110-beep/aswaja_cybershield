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
        'learned_pattern_intelligence' => 82,

        // URL Masking & Domain Rebouncer (Penyembunyian Tautan & Proxy Link)
        'url_masking_detected' => 85,
        'domain_rebouncer_detected' => 80,
        'binary_options_illegal_investment' => 85,
        'unmasked_threat_inherited' => 85,

        // SEO Cloaking (Penyusupan Domain Resmi Pemerintah / Akademik yang Dimanipulasi untuk Mesin Pencari)
        'seo_cloaking_detected' => 95,
        'seo_cloaking_snippet_judol' => 92
    ],

    // Nilai Ambang Minimal jika terjadi Kombinasi Berbahaya
    'combination_thresholds' => [
        // Open redirect ke tujuan kata kunci judol atau TLD mencurigakan -> Minimal 80 (Berbahaya)
        'open_redirect_with_malicious_dest' => 80,

        // URL Masking / Domain Rebouncer yang menyembunyikan situs judol atau investasi menyesatkan -> Minimal 80 (Berbahaya)
        'url_masking_with_threat_dest' => 80,

        // SEO Cloaking pada domain resmi pemerintah/akademik -> Minimal 90 (Kritis / Sangat Berbahaya)
        'seo_cloaking_gov_edu' => 90,

        // Domain lembaga pemerintah / pendidikan (.go.id/.ac.id/.desa.id) + path judol -> Minimal 85 (Berbahaya)
        'defacement_gov_with_judol' => 85,

        // Typosquatting nama instansi resmi di TLD non-resmi (.com/.vip/dll) + embel-embel -> Minimal 80 (Berbahaya)
        'typosquatting_with_deceptive_suffix' => 80,
    ]
];

