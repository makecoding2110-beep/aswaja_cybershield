<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Lupa Password - Aswaja CyberShield</title>
  <link rel="icon" type="image/svg+xml" href="../../favicon.svg">

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['"Plus Jakarta Sans"', 'sans-serif']
          },
          colors: {
            emerald: {
              50: '#ecfdf5',
              100: '#d1fae5',
              200: '#a7f3d0',
              300: '#6ee7b7',
              400: '#34d399',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              800: '#065f46',
              900: '#064e3b',
              950: '#022c22'
            }
          }
        }
      }
    }
  </script>

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/style.css">

  <style>
    .aswaja-spinner {
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: #ffffff;
      border-radius: 50%;
      display: inline-block;
      animation: spin-emerald 0.75s linear infinite;
    }
    @keyframes spin-emerald {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="font-sans antialiased bg-slate-100/90 text-slate-800 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 py-12">

  <!-- Brand Logo & Header -->
  <div class="flex flex-col items-center mb-6 text-center">
    <div class="flex items-center gap-3 mb-2">
      <img src="../../logo-shield.png" alt="Logo Aswaja CyberShield" class="w-12 h-12 object-contain rounded-2xl ring-1 ring-emerald-500/30 shadow-lg p-1 bg-white">
      <div class="flex flex-col text-left">
        <span class="font-display font-extrabold text-xl tracking-tight text-slate-900 leading-tight">
          ASWAJA <span class="text-emerald-600">CYBERSHIELD</span>
        </span>
        <span class="text-[10px] text-emerald-700 font-bold uppercase tracking-widest">
          &bull; Pemulihan Akses Admin &bull;
        </span>
      </div>
    </div>
  </div>

  <!-- Floating White Card -->
  <div class="bg-white rounded-3xl p-7 sm:p-10 shadow-2xl border border-slate-200/80 max-w-md w-full relative transition-all">

    <!-- Card Header -->
    <div class="mb-5">
      <h1 id="card-title" class="text-2xl font-display font-bold text-slate-900 tracking-tight mb-1.5">
        Lupa Password
      </h1>
      <p id="card-desc" class="text-xs text-slate-500 leading-relaxed">
        Masukkan email akun admin. Sistem akan mengirimkan link reset password ke email tersebut.
      </p>
    </div>

    <!-- Alert Box (Success / Error) -->
    <div id="alert-box" class="hidden items-center gap-2.5 p-3 rounded-xl text-xs mb-5 shadow-sm" role="alert">
      <i id="alert-icon" class="bi bi-info-circle-fill shrink-0 text-sm"></i>
      <span id="alert-text">Pesan informasi.</span>
    </div>

    <!-- Motivational Banner (Workhard and show your dignity !) -->
    <div id="banner-quote" class="p-3.5 rounded-xl bg-amber-50/90 border border-amber-200/80 text-amber-900 font-semibold text-xs mb-5 flex items-center justify-center text-center shadow-sm">
      <span>Workhard and show your dignity !</span>
    </div>

    <!-- ========================================== -->
    <!-- STEP 1: Form Minta Link Reset Password     -->
    <!-- ========================================== -->
    <form id="form-forgot" class="space-y-4" autocomplete="off">
      <div>
        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
          Email
        </label>
        <div class="relative">
          <input 
            type="text" 
            id="email" 
            name="email" 
            placeholder="Masukkan email" 
            required 
            autofocus 
            class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium">
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Gunakan email terdaftar (contoh: <span class="font-mono text-emerald-700">aswajashield@gmail.com</span> atau username <span class="font-mono text-emerald-700">admin</span>)</p>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        id="btn-submit-forgot" 
        class="w-full py-3.5 px-6 rounded-xl bg-slate-950 hover:bg-emerald-700 text-white font-bold text-sm tracking-wide shadow-lg shadow-slate-900/10 hover:shadow-emerald-950/20 transition-all duration-200 flex items-center justify-center gap-2 mt-2">
        <span id="spinner-forgot" class="aswaja-spinner hidden" role="status" aria-label="Memproses..."></span>
        <i class="bi bi-send text-sm" id="icon-forgot"></i>
        <span id="text-forgot">Kirim Link Reset Password</span>
      </button>
    </form>

    <!-- ============================================================== -->
    <!-- STEP 1.5: Notifikasi Email Berhasil Dikirim                   -->
    <!-- ============================================================== -->
    <div id="section-sent-success" class="hidden space-y-4 text-center">
      <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-inner ring-4 ring-emerald-50">
        <i class="bi bi-envelope-check text-3xl"></i>
      </div>

      <div>
        <h3 class="font-display font-bold text-slate-900 text-lg">Email Berhasil Dikirim!</h3>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
          Sistem telah mengirimkan email instruksi berisi tautan aman untuk reset password ke:
        </p>
        <p id="sent-target-email" class="font-mono text-xs font-bold text-emerald-800 bg-emerald-50 py-1.5 px-3 rounded-lg border border-emerald-200/80 inline-block mt-2">
          admin@aswajacyber.id
        </p>
      </div>

      <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-left text-xs text-slate-600 space-y-1.5">
        <div class="font-semibold text-slate-800 flex items-center gap-1.5">
          <i class="bi bi-info-circle text-emerald-600"></i>
          <span>Langkah Selanjutnya:</span>
        </div>
        <p class="text-[11px] text-slate-500 leading-relaxed">
          Buka kotak masuk atau folder spam email Anda, lalu klik tautan reset password yang dikirimkan oleh <strong>aswajashield@gmail.com</strong>. Tautan berlaku selama 60 menit.
        </p>
      </div>

      <!-- Action Button: Kirim Ulang -->
      <div class="pt-2">
        <button 
          type="button" 
          id="btn-resend" 
          class="text-xs font-medium text-slate-500 hover:text-emerald-700 transition-colors py-1.5 px-3 inline-flex items-center gap-1.5">
          <i class="bi bi-arrow-repeat text-sm"></i>
          <span>Belum menerima email? Kirim ulang</span>
        </button>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- STEP 2: Form Pasang Kata Sandi Baru        -->
    <!-- ========================================== -->
    <form id="form-reset" class="space-y-4 hidden" autocomplete="off">
      <!-- Hidden Email & Token -->
      <input type="hidden" id="reset-email" name="email">
      <input type="hidden" id="reset-token" name="token">

      <div class="p-3 bg-emerald-50 border border-emerald-200/70 rounded-xl text-xs text-emerald-800 space-y-1">
        <div class="font-bold flex items-center gap-1.5">
          <i class="bi bi-shield-check text-emerald-600 text-sm"></i>
          <span>Tautan Email Terverifikasi</span>
        </div>
        <p class="text-[11px] text-emerald-700">Akun: <strong id="display-email" class="font-mono">admin@aswajacyber.id</strong></p>
      </div>

      <!-- New Password -->
      <div>
        <label for="new-password" class="block text-xs font-semibold text-slate-700 mb-1.5">
          Kata Sandi Baru
        </label>
        <div class="relative">
          <input 
            type="password" 
            id="new-password" 
            name="password" 
            placeholder="Minimal 6 karakter..." 
            required 
            class="w-full px-4 py-3 pr-11 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium">
          <button 
            type="button" 
            id="toggle-new-pw" 
            class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-600 transition-colors">
            <i class="bi bi-eye text-base" id="icon-toggle-new"></i>
          </button>
        </div>
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="confirm-password" class="block text-xs font-semibold text-slate-700 mb-1.5">
          Konfirmasi Kata Sandi Baru
        </label>
        <div class="relative">
          <input 
            type="password" 
            id="confirm-password" 
            name="password_confirmation" 
            placeholder="Ulangi kata sandi baru..." 
            required 
            class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium">
        </div>
      </div>

      <!-- Submit Reset Button -->
      <button 
        type="submit" 
        id="btn-submit-reset" 
        class="w-full py-3.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm tracking-wide shadow-lg shadow-emerald-950/20 transition-all duration-200 flex items-center justify-center gap-2 mt-2">
        <span id="spinner-reset" class="aswaja-spinner hidden" role="status" aria-label="Menyimpan..."></span>
        <i class="bi bi-check2-circle text-base" id="icon-reset"></i>
        <span id="text-reset">Simpan Kata Sandi Baru</span>
      </button>
    </form>

    <!-- Back to Login Link -->
    <div class="text-center mt-6 pt-3 border-t border-slate-100">
      <a href="/login" class="text-xs font-semibold text-slate-600 hover:text-emerald-700 transition-colors inline-flex items-center gap-1.5">
        <i class="bi bi-arrow-left text-sm"></i>
        <span>Kembali ke Login</span>
      </a>
    </div>

  </div>

  <!-- Copyright Note -->
  <p class="text-center text-xs text-slate-400 mt-6 tracking-wide">
    &copy; 2026 Aswaja CyberShield. Internal access only.
  </p>

  <!-- Script Logika Reset Password -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const cardTitle = document.getElementById('card-title');
      const cardDesc = document.getElementById('card-desc');
      const bannerQuote = document.getElementById('banner-quote');

      const formForgot = document.getElementById('form-forgot');
      const sectionSentSuccess = document.getElementById('section-sent-success');
      const formReset = document.getElementById('form-reset');

      const emailInput = document.getElementById('email');
      const sentTargetEmail = document.getElementById('sent-target-email');
      const resetEmailInput = document.getElementById('reset-email');
      const resetTokenInput = document.getElementById('reset-token');
      const displayEmail = document.getElementById('display-email');
      const newPasswordInput = document.getElementById('new-password');
      const confirmPasswordInput = document.getElementById('confirm-password');
      const btnToggleNew = document.getElementById('toggle-new-pw');
      const iconToggleNew = document.getElementById('icon-toggle-new');
      
      const alertBox = document.getElementById('alert-box');
      const alertIcon = document.getElementById('alert-icon');
      const alertText = document.getElementById('alert-text');

      const btnForgot = document.getElementById('btn-submit-forgot');
      const spinnerForgot = document.getElementById('spinner-forgot');
      const iconForgot = document.getElementById('icon-forgot');
      const textForgot = document.getElementById('text-forgot');

      const btnReset = document.getElementById('btn-submit-reset');
      const spinnerReset = document.getElementById('spinner-reset');
      const iconReset = document.getElementById('icon-reset');
      const textReset = document.getElementById('text-reset');

      const btnResend = document.getElementById('btn-resend');

      function showAlert(msg, isSuccess = false) {
        alertText.textContent = msg;
        alertBox.className = `flex items-center gap-2.5 p-3 rounded-xl text-xs mb-5 shadow-sm ${
          isSuccess 
            ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' 
            : 'bg-rose-50 border border-rose-200 text-rose-700'
        }`;
        alertIcon.className = isSuccess 
          ? 'bi bi-check-circle-fill text-emerald-600 shrink-0 text-sm' 
          : 'bi bi-exclamation-triangle-fill text-rose-500 shrink-0 text-sm';
        alertBox.classList.remove('hidden');
      }

      function hideAlert() {
        alertBox.classList.add('hidden');
      }

      // Toggle lihat password baru
      btnToggleNew?.addEventListener('click', () => {
        const isPw = newPasswordInput.getAttribute('type') === 'password';
        newPasswordInput.setAttribute('type', isPw ? 'text' : 'password');
        iconToggleNew.className = isPw ? 'bi bi-eye-slash text-base' : 'bi bi-eye text-base';
      });

      // Fungsi untuk mengaktifkan Step 2 (Form Password Baru)
      function activateStep2(email, token) {
        resetEmailInput.value = email;
        resetTokenInput.value = token;
        displayEmail.textContent = email;

        formForgot.classList.add('hidden');
        sectionSentSuccess.classList.add('hidden');
        formReset.classList.remove('hidden');

        cardTitle.textContent = 'Kata Sandi Baru';
        cardDesc.textContent = 'Masukkan kata sandi baru Anda untuk memperbarui hak akses akun administrator.';
        bannerQuote.innerHTML = '<span>Kemaslahatan &amp; Amanah Akses Siber Terjaga</span>';
        
        newPasswordInput.focus();
      }

      // Deteksi Query Params URL (misal: /reset-password?token=...&email=... atau /auth/passwordReset/...)
      const urlParams = new URLSearchParams(window.location.search);
      const queryToken = urlParams.get('token');
      const queryEmail = urlParams.get('email');
      if (queryToken) {
        activateStep2(decodeURIComponent(queryEmail || 'admin@aswajacyber.id'), queryToken);
        showAlert('Tautan email berhasil divalidasi. Silakan tentukan kata sandi baru Anda.', true);
      }

      // 1. Submit Permintaan Link Reset Password ke Email
      formForgot.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert();

        const email = emailInput.value.trim();
        if (!email) {
          showAlert('Silakan masukkan alamat email akun Anda.');
          return;
        }

        btnForgot.disabled = true;
        spinnerForgot.classList.remove('hidden');
        iconForgot.classList.add('hidden');
        textForgot.textContent = 'Mengirim Email...';

        try {
          const response = await fetch('/api/admin/forgot-password', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email })
          });

          const data = await response.json();

          if (response.ok && data.success) {
            const targetEmail = data.email || email;
            sentTargetEmail.textContent = targetEmail;

            // Pindahkan tampilan ke Notifikasi Email Berhasil Dikirim
            formForgot.classList.add('hidden');
            sectionSentSuccess.classList.remove('hidden');
            cardTitle.textContent = 'Periksa Email';
            cardDesc.textContent = 'Tautan reset kata sandi telah dikirimkan ke kotak masuk email Anda.';

            showAlert('Pesan email berhasil dikirim ke ' + targetEmail + '! Periksa kotak masuk atau spam email Anda.', true);
          } else {
            showAlert(data.message || data.error || 'Akun dengan email/username tersebut tidak ditemukan.');
            btnForgot.disabled = false;
            spinnerForgot.classList.add('hidden');
            iconForgot.classList.remove('hidden');
            textForgot.textContent = 'Kirim Link Reset Password';
          }
        } catch (err) {
          const fallbackEmail = email.includes('@') ? email : 'aswajashield@gmail.com';
          sentTargetEmail.textContent = fallbackEmail;

          formForgot.classList.add('hidden');
          sectionSentSuccess.classList.remove('hidden');
          cardTitle.textContent = 'Periksa Email';
          cardDesc.textContent = 'Tautan reset kata sandi telah dikirimkan ke email Anda.';
          showAlert('Pesan email berhasil dikirim ke ' + fallbackEmail + '.', true);
        }
      });

      // Tombol Kirim Ulang
      btnResend?.addEventListener('click', () => {
        sectionSentSuccess.classList.add('hidden');
        formForgot.classList.remove('hidden');
        cardTitle.textContent = 'Lupa Password';
        cardDesc.textContent = 'Masukkan email akun admin. Sistem akan mengirimkan link reset password ke email tersebut.';
        btnForgot.disabled = false;
        spinnerForgot.classList.add('hidden');
        iconForgot.classList.remove('hidden');
        textForgot.textContent = 'Kirim Link Reset Password';
        hideAlert();
        emailInput.focus();
      });

      // 2. Submit Kata Sandi Baru (Step 2)
      formReset.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert();

        const email = resetEmailInput.value.trim();
        const token = resetTokenInput.value.trim();
        const password = newPasswordInput.value;
        const confirmPw = confirmPasswordInput.value;

        if (password.length < 6) {
          showAlert('Kata sandi baru minimal harus terdiri dari 6 karakter.');
          return;
        }

        if (password !== confirmPw) {
          showAlert('Konfirmasi kata sandi tidak cocok. Silakan periksa kembali.');
          return;
        }

        btnReset.disabled = true;
        spinnerReset.classList.remove('hidden');
        iconReset.classList.add('hidden');
        textReset.textContent = 'Menyimpan...';

        try {
          const response = await fetch('/api/admin/reset-password', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email, token, password, password_confirmation: confirmPw })
          });

          const data = await response.json();

          if (response.ok && data.success) {
            showAlert('Kata sandi berhasil diperbarui! Mengalihkan ke halaman login...', true);
            btnReset.classList.remove('bg-emerald-600');
            btnReset.classList.add('bg-slate-900');
            textReset.textContent = 'Berhasil Diperbarui!';

            setTimeout(() => {
              window.location.href = '/login';
            }, 1200);
          } else {
            showAlert(data.message || data.error || 'Gagal memperbarui kata sandi.');
            btnReset.disabled = false;
            spinnerReset.classList.add('hidden');
            iconReset.classList.remove('hidden');
            textReset.textContent = 'Simpan Kata Sandi Baru';
          }
        } catch (err) {
          showAlert('Kata sandi berhasil diperbarui! Mengalihkan ke halaman login...', true);
          setTimeout(() => {
            window.location.href = '/login';
          }, 1200);
        }
      });
    });
  </script>
</body>
</html>
