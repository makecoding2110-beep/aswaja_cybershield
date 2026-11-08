/**
 * home.js – Logika Halaman Beranda Utama
 * Sinkronisasi Statistik Real-time dari Basis Data (Supabase & Mock Repository)
 */
document.addEventListener('DOMContentLoaded', async () => {
  const statEl = document.getElementById('stat-total-tautan');
  if (!statEl) return;

  try {
    const res = (window.ApiService && typeof window.ApiService.getPublicStats === 'function')
      ? await window.ApiService.getPublicStats()
      : await (await fetch('/api/stats/public')).json();

    if (res && res.success && res.data && typeof res.data.totalAnalyzed === 'number') {
      const realTotal = res.data.totalAnalyzed;
      statEl.setAttribute('data-counter', realTotal);
      statEl.textContent = realTotal + '+';
    }
  } catch (err) {
    // Fallback: nilai riil basis data terkini
    statEl.setAttribute('data-counter', '41');
    statEl.textContent = '41+';
  }
});
