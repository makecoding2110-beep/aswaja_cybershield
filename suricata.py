"""
=============================================================================
SURICATA IDS ENGINE EMULATOR
=============================================================================
Implementasi Signature-Based IDS untuk mendeteksi (investasi menyesatkan),
(tautan judol), (maysir modern), (investasi bodong).

Kompatibel dengan format rule resmi Suricata IDS (Snort/Suricata Specification).
Dapat digunakan sebagai:
1. Engine deteksi layer pertama sebelum AI Analysis (Gemini/DeepSeek).
2. CLI analyzer mandiri dengan format output JSON.
=============================================================================
"""

import sys
import os
import re
import json
import argparse
from typing import List, Dict, Any, Optional
from datetime import datetime

# =============================================================================
# 1. ATURAN SIGNATURE SURICATA RESMI (SURICATA RULES MATRIX)
# =============================================================================
DEFAULT_SURICATA_RULES = [
    # Whitelist / Passthrough (Regulator Resmi)
    'pass http any any -> any any (msg:"ASWAJA-IDS: Domain Resmi Pengawas Pemerintah (OJK/Bappebti/Kominfo)"; pcre:"/\\.(ojk|bappebti|bi|kominfo|go)\\.id/i"; classtype:not-suspicious; priority:4; sid:1000000; rev:1;)',

    # Tingkat 1 (Kuat): Indikator Kuat Eksplisit Judi Online
    'alert http any any -> any any (msg:"ASWAJA-IDS [TINGKAT KUAT]: Indikator Kuat Judi Online (Slot Gacor / Situs Togel / Casino Online)"; pcre:"/(slot[-_]?gacor|situs[-_]?togel|casino[-_]?online|kasino[-_]?online|judi[-_]?slot|judi[-_]?online|bandar[-_]?togel|agen[-_]?slot|judi[-_]?bola|sbobet|togel4d|slot88|slot777|gacor777|gacor88|deposit[-_]?slot|depo[-_]?slot)/i"; classtype:policy-violation; priority:1; sid:1000001; rev:2; metadata:fiqih_aswaja maysir_sharih tier_strong;)',
    
    # Tingkat 2 (Menengah): Istilah Taruhan / Fitur Slot (Perlu Indikator Pendukung)
    'alert http any any -> any any (msg:"ASWAJA-IDS [TINGKAT MENENGAH]: Istilah Taruhan / Fitur Slot (Maxwin / Jackpot / Scatter / Free Spin)"; pcre:"/(maxwin|jackpot|scatter|free[-_]?spin|rtp[-_]?live|rtp[-_]?tinggi|pragmatic|pgsoft|habanero|pola[-_]?gacor|baccarat|roulette|sicbo|colok[-_]?bebas|(?<![a-z])(slot|gacor|togel|casino|kasino)(?![a-z]))/i"; classtype:policy-violation; priority:2; sid:1000002; rev:2; metadata:fiqih_aswaja maysir_sharih tier_medium;)',
    
    # Tingkat 3 (Kontekstual): Nama Tema / Mitologi (Perlu Pemeriksaan Tambahan)
    'alert http any any -> any any (msg:"ASWAJA-IDS [TINGKAT KONTEKSTUAL]: Kata Kontekstual (Zeus / Dewa / Thor / Dragon) - Perlu Pemeriksaan Tambahan"; pcre:"/(?<![a-z])(zeus|dewa|thor|dragon|olympus|mahjong|bonanza)(?![a-z])/i"; classtype:misc-activity; priority:3; sid:1000003; rev:2; metadata:fiqih_aswaja contextual_check tier_contextual;)',

    # Deteksi Manipulasi Deposit & Pencucian Dana E-Wallet
    'alert http any any -> any any (msg:"ASWAJA-IDS: Parameter Deposit Cepat / Rekening Penampung Judol"; pcre:"/(deposit-(dana|gopay|qris|pulsa)|depo-pulsa|depo-10k|bonus-new-member-100)/i"; classtype:suspicious-traffic; priority:1; sid:1000004; rev:1; metadata:fiqih_aswaja gharar_fasid;)',

    # Deteksi TLD Berisiko Tinggi (Fast-Flux / Disposable Domains Jaringan Judol)
    'alert http any any -> any any (msg:"ASWAJA-IDS: TLD Reputasi Buruk Kerap Dipakai Sindikat Judol"; pcre:"/\\.(vip|cc|xyz|top|fun|click|site|live|club|icu|monster)(\\/|$|\\?)/i"; classtype:suspicious-filename; priority:2; sid:1000005; rev:1; metadata:fiqih_aswaja syubhat_dom;)',

    # Deteksi Skema Piramida / Robot Trading / Investasi Bodong
    'alert http any any -> any any (msg:"ASWAJA-IDS: Janji Keuntungan Pasif / Robot Trading Tak Berizin"; pcre:"/(robot-trading|profit-pasti|cuan-harian|arisan-online-syariah|investasi-titip-dana)/i"; classtype:bad-unknown; priority:1; sid:1000006; rev:1; metadata:fiqih_aswaja aklu_amwal_bil_bathil;)',

    # Deteksi Unduhan APK Berbahaya / Malware Penyusup
    'alert http any any -> any any (msg:"ASWAJA-IDS: Upaya Pengunduhan APK Liar / Malware Siber"; pcre:"/\\.(apk|exe|scr|bat|vbs)(\\?|$)/i"; classtype:trojan-activity; priority:1; sid:1000007; rev:1; metadata:fiqih_aswaja dharar_amwal;)',

    # Deteksi Kode Afiliasi Sindikat Perjudian
    'alert http any any -> any any (msg:"ASWAJA-IDS: Tautan Referal / Afiliasi Perekrutan Pemain Judi"; pcre:"/(\\?|&)(ref|aff|affiliate|agent|referral)=[a-zA-Z0-9_-]+/i"; classtype:suspicious-traffic; priority:3; sid:1000008; rev:1; metadata:fiqih_aswaja taawun_alal_itsmi;)'
]

# =============================================================================
# 2. PARSER ATURAN SURICATA (SURICATA RULE OBJECT)
# =============================================================================
class SuricataRule:
    """Representasi satu signature rule Suricata IDS."""

    def __init__(self, raw_rule: str):
        self.raw = raw_rule.strip()
        self.action = "alert"
        self.proto = "http"
        self.src_ip = "any"
        self.src_port = "any"
        self.direction = "->"
        self.dst_ip = "any"
        self.dst_port = "any"
        
        self.msg = ""
        self.pcre: Optional[re.Pattern] = None
        self.content: List[str] = []
        self.classtype = "unknown"
        self.priority = 3
        self.sid = 0
        self.rev = 1
        self.metadata = ""

        self._parse()

    def _parse(self):
        # Format dasar: ACTION PROTO SRC SRC_PORT DIR DST DST_PORT (OPTIONS)
        match = re.match(r'^(\w+)\s+(\w+)\s+(\S+)\s+(\S+)\s+(->|<>)\s+(\S+)\s+(\S+)\s+\((.*)\);?$', self.raw)
        if not match:
            return

        self.action = match.group(1).lower()
        self.proto = match.group(2).lower()
        self.src_ip = match.group(3)
        self.src_port = match.group(4)
        self.direction = match.group(5)
        self.dst_ip = match.group(6)
        self.dst_port = match.group(7)
        opts_str = match.group(8)

        # Parse options di dalam kurung: key:"val"; atau key:val;
        tokens = re.findall(r'(\w+)(?::(?:"([^"]*)"|([^;]+)))?;', opts_str)
        for key, val_quoted, val_unquoted in tokens:
            val = val_quoted if val_quoted != '' else val_unquoted
            val = val.strip() if val else ''

            if key == 'msg':
                self.msg = val
            elif key == 'sid':
                try:
                    self.sid = int(val)
                except ValueError:
                    self.sid = 0
            elif key == 'rev':
                try:
                    self.rev = int(val)
                except ValueError:
                    self.rev = 1
            elif key == 'priority':
                try:
                    self.priority = int(val)
                except ValueError:
                    self.priority = 3
            elif key == 'classtype':
                self.classtype = val
            elif key == 'metadata':
                self.metadata = val
            elif key == 'content':
                self.content.append(val)
            elif key == 'pcre':
                # Format regex pcre: "/pattern/modifiers"
                pcre_match = re.match(r'^/(.*)/([a-zA-Z]*)$', val)
                if pcre_match:
                    pattern_str, flags_str = pcre_match.groups()
                    flags = 0
                    if 'i' in flags_str:
                        flags |= re.IGNORECASE
                    try:
                        self.pcre = re.compile(pattern_str, flags)
                    except re.error:
                        self.pcre = None

    def match(self, payload: str) -> bool:
        """Memeriksa apakah payload URL cocok dengan rule Suricata ini."""
        # 1. Periksa exact content matches jika ada
        for c in self.content:
            if c.lower() not in payload.lower():
                return False

        # 2. Periksa PCRE regex jika ada
        if self.pcre:
            if not self.pcre.search(payload):
                return False

        return True


# =============================================================================
# 3. SURICATA ENGINE (INTRUSION DETECTION SYSTEM)
# =============================================================================
class SuricataEngine:
    """Mesin penilai inspeksi paket/URL berbasis Signature Suricata."""

    def __init__(self, rules_list: Optional[List[str]] = None):
        self.rules: List[SuricataRule] = []
        raw_list = rules_list if rules_list is not None else DEFAULT_SURICATA_RULES
        for r in raw_list:
            if r.strip() and not r.strip().startswith('#'):
                self.rules.append(SuricataRule(r))

    def inspect_url(self, url: str) -> Dict[str, Any]:
        """
        Inspeksi komprehensif URL terhadap database rules Suricata.
        Mengembalikan struktur penilaian IDS yang kompatibel dengan AI Tabayyun.
        """
        target = url.strip()
        alerts = []
        is_passed_by_whitelist = False

        for rule in self.rules:
            if rule.match(target):
                if rule.action == 'pass':
                    is_passed_by_whitelist = True
                    alerts.append({
                        'sid': rule.sid,
                        'rev': rule.rev,
                        'action': 'PASS',
                        'priority': rule.priority,
                        'classtype': rule.classtype,
                        'message': rule.msg,
                        'metadata': rule.metadata
                    })
                    break  # Whitelist langsung mengizinkan lalu lintas
                else:
                    alerts.append({
                        'sid': rule.sid,
                        'rev': rule.rev,
                        'action': rule.action.upper(),
                        'priority': rule.priority,
                        'classtype': rule.classtype,
                        'message': rule.msg,
                        'metadata': rule.metadata
                    })

        # Hitung kalkulasi skor dan kesimpulan IDS
        if is_passed_by_whitelist:
            score = 98
            category = "belum_ditemukan_indikasi"
            status_alert = "Status Aman Terverifikasi (Suricata PASS)"
            alert_class = "emerald"
            verdict = "PASS"
            title = "Aman: Terverifikasi oleh Suricata IDS Whitelist"
        elif len(alerts) > 0:
            # Ambil prioritas tertinggi (1 = paling bahaya)
            highest_priority = min(a['priority'] for a in alerts)
            if highest_priority == 1:
                score = 15
                category = "terindikasi_judol"
                status_alert = "Peringatan Bahaya Tinggi (Suricata ALERT)"
                alert_class = "red"
                verdict = "DROP / BLOKIR"
                title = "Waspada: Terdeteksi Pelanggaran Signature Suricata IDS"
            else:
                score = 45
                category = "perlu_diwaspadai"
                status_alert = "Perlu Diwaspadai (Suricata SUSPICIOUS)"
                alert_class = "amber"
                verdict = "ALERT"
                title = "Perhatian: Parameter Tautan Mencurigakan pada Suricata IDS"
        else:
            score = 85
            category = "belum_ditemukan_indikasi"
            status_alert = "Belum Terindikasi (Suricata CLEAN)"
            alert_class = "emerald"
            verdict = "ALLOW"
            title = "Bersih: Tidak Ditemukan Pola Signature Berbahaya"

        # Format findings untuk tampilan AI Tabayyun
        findings = []
        if is_passed_by_whitelist:
            findings.append({
                'type': 'success',
                'label': 'Suricata Whitelist:',
                'detail': 'Domain terdaftar dalam signature institusi resmi negara (OJK/Bappebti/Kominfo).',
                'desc': 'Domain lolos sensor IDS tanpa anomali.'
            })
        elif alerts:
            for a in alerts:
                severity_type = 'error' if a['priority'] == 1 else 'warning'
                findings.append({
                    'type': severity_type,
                    'label': f"Suricata SID [{a['sid']}]:",
                    'detail': f"{a['message']} (Classtype: {a['classtype']})",
                    'desc': a['message']
                })
        else:
            findings.append({
                'type': 'success',
                'label': 'Suricata Signature Scan:',
                'detail': 'Lolos inspeksi 8 signature aktif deteksi perjudian online & malware.',
                'desc': 'Tidak ditemukan string pattern berbahaya.'
            })

        return {
            'success': True,
            'url': target,
            'ids_engine': 'Suricata IDS v7.0.x (Aswaja CyberShield Ruleset)',
            'timestamp': datetime.now().isoformat(),
            'verdict': verdict,
            'score': score,
            'category': category,
            'statusAlert': status_alert,
            'alertClass': alert_class,
            'title': title,
            'alerts_count': len(alerts),
            'alerts': alerts,
            'findings': findings,
            'source': 'suricata_ids'
        }

    def export_rules_file(self, output_path: str):
        """Mengekspor berkas rules Suricata murni (.rules) untuk sensor Linux."""
        content = "# ===================================================================\n"
        content += "# Aswaja CyberShield - Suricata IDS Ruleset for Judol Detection\n"
        content += f"# Generated at: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n"
        content += "# ===================================================================\n\n"
        for r in self.rules:
            content += f"{r.raw}\n"

        with open(output_path, 'w', encoding='utf-8') as f:
            f.write(content)


# =============================================================================
# 4. CLI INTERFACE & SELF-TEST
# =============================================================================
def run_self_test():
    """Menguji efektivitas engine terhadap berbagai skenario tautan nyata."""
    engine = SuricataEngine()
    test_cases = [
        ("https://cuan-trading-vip88.vip/slot-gacor/deposit", "DROP / BLOKIR", "Judol Slot VIP"),
        ("https://singapore4d-toto-jackpot.cc/register?ref=agen123", "DROP / BLOKIR", "Togel Online + Referal"),
        ("https://robot-trading-syariah-cuan.online/member", "DROP / BLOKIR", "Investasi Bodong"),
        ("https://ojk.go.id/id/berita-dan-kegiatan/siaran-pers/default.aspx", "PASS", "Regulator Resmi OJK"),
        ("https://unukaltim.ac.id/portal-akademik", "ALLOW", "Portal Kampus Bersih")
    ]

    print("\n" + "=" * 70)
    print("🧪 SURICATA IDS ENGINE - ASWAJA CYBERSHIELD SELF-TEST")
    print("=" * 70)

    all_passed = True
    for url, expected_verdict, label in test_cases:
        res = engine.inspect_url(url)
        passed = (res['verdict'] == expected_verdict)
        if not passed:
            all_passed = False
        badge = "✅ PASS" if passed else "❌ FAIL"
        print(f"[{badge}] {label}")
        print(f"       Target   : {url}")
        print(f"       Hasil    : {res['verdict']} (Skor: {res['score']}) | Alert: {res['alerts_count']} rule")
        if res['alerts']:
            for a in res['alerts']:
                print(f"       -> [SID: {a['sid']}] {a['message']}")
        print("-" * 70)

    print(f"Kesimpulan: {'SEMUA PENGUJIAN LULUS (100%)' if all_passed else 'ADA PENGUJIAN GAGAL'}\n")
    return all_passed


def main():
    parser = argparse.ArgumentParser(
        description="Suricata IDS Engine Emulator untuk Deteksi Tautan Judi Online Aswaja CyberShield"
    )
    parser.add_argument('--url', type=str, help='URL target yang akan diinspeksi oleh Suricata')
    parser.add_argument('--json', action='store_true', help='Keluarkan hasil audit murni dalam format JSON')
    parser.add_argument('--test', action='store_true', help='Jalankan rangkaian pengujian mandiri (self-test)')
    parser.add_argument('--export', type=str, metavar='FILEPATH', help='Ekspor berkas rule Suricata (.rules)')

    args = parser.parse_args()

    engine = SuricataEngine()

    if args.export:
        engine.export_rules_file(args.export)
        print(f"✅ Berkas rules Suricata berhasil diekspor ke: {args.export}")
        return

    if args.test:
        success = run_self_test()
        sys.exit(0 if success else 1)

    if args.url:
        result = engine.inspect_url(args.url)
        if args.json:
            print(json.dumps(result, indent=2, ensure_ascii=False))
        else:
            print("\n" + "=" * 60)
            print("🛡️ HASIL INSPEKSI SURICATA IDS ENGINE")
            print("=" * 60)
            print(f"Target URL   : {result['url']}")
            print(f"Verdict      : {result['verdict']}")
            print(f"Skor Keamanan: {result['score']}/100")
            print(f"Status       : {result['statusAlert']}")
            print(f"Alert Rules  : {result['alerts_count']} pelanggaran signature")
            print("-" * 60)
            for f in result['findings']:
                print(f"• [{f['label']}] {f['detail']}")
            print("=" * 60 + "\n")
        return

    # Jika dipanggil tanpa argumen, tampilkan bantuan ringkas
    parser.print_help()


if __name__ == '__main__':
    main()

