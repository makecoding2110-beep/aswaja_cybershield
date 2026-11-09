/**
 * cyber-guard.js — Aswaja CyberShield
 * Kuis interaktif Cyber Guard Test (Stepper Skenario)
 * Fokus: Studi kasus tautan investasi menyesatkan yang mengarah ke judi online (judol/maysir)
 * Bergantung pada: js/api.js (window.ApiService)
 */

document.addEventListener('DOMContentLoaded', () => {

  // ─── Data Soal Studi Kasus (Sinkron Penuh dengan public.case_studies di Database) ───
  const fallbackQuizData = [
    {
      scenario: "Studi Kasus 01: Penipuan Robot Trading Berkedok Syariah",
      sender: "+62 821-4455-XXXX (Admin Cuan Berkah Telegram)",
      message: "Halo Kak! Mau pasif income Rp 500rb/hari tanpa ribet? Cukup registrasi di link resmi platform trading syariah...",
      attachment: "https://cuan-syariah88.xyz/claim",
      attachmentType: "Tautan Robot Trading • Terindikasi Slot Gacor",
      icon: "smart_toy",
      question: "Tautan mengarahkan ke situs yang mewajibkan deposit awal ke rekening pribadi dan ada pilihan game roda putar berhadiah. Sikap Anda?",
      options: [
        { key: "A", text: "Mengabaikan dan memblokir nomor pengirim karena terindikasi kuat judi slot terselubung dan penipuan investasi.", correct: true },
        { key: "B", text: "Mencoba deposit nominal terkecil untuk membuktikan kebenaran sistem.", correct: false },
        { key: "C", text: "Meneruskan pesan ke grup keluarga agar mendapat komisi referral.", correct: false },
        { key: "D", text: "Meminta izin admin grup Telegram sebelum melakukan pendaftaran.", correct: false }
      ],
      explanation: "Benar (Pilihan A)! Modus ini menggabungkan pencatutan istilah syariah dengan sistem deposit judi slot (maysir). Pelaku memanfaatkan ketertarikan masyarakat terhadap keuntungan instan."
    },
    {
      scenario: "Studi Kasus 02: Skema Mining Kripto Instan",
      sender: "Broadcast Media Sosial / Iklan Bersponsor",
      message: "Revolusi Finansial 2026! Cuma modal smartphone, mining USDT hasilkan Rp 10 Juta per minggu. Daftar akun gratis hari ini!",
      attachment: "https://usdt-mining-cuan.top/spin",
      attachmentType: "Platform Mining Fiktif",
      icon: "currency_bitcoin",
      question: "Di dalam platform tersebut, Anda diberi \"saldo virtual Rp 2.000.000\", tetapi saldo tersebut baru bisa dicairkan (withdraw) jika Anda memasang taruhan tebak warna/angka sebanyak 10 putaran. Apa analisis Anda?",
      options: [
        { key: "A", text: "Ini adalah modus penipuan berkedok judi online. Saldo virtual hanya umpan psikologis untuk memaksa pengguna mentransfer uang taruhan sungguhan.", correct: true },
        { key: "B", text: "Ini adalah mekanisme promosi wajar dari perusahaan teknologi keuangan luar negeri.", correct: false },
        { key: "C", text: "Sebaiknya bermain 10 putaran dengan taruhan paling kecil agar saldo virtual bisa ditarik.", correct: false },
        { key: "D", text: "Meminjam uang ke kerabat untuk deposit taruhan agar cepat mencapai syarat batas penarikan.", correct: false }
      ],
      explanation: "Benar (Pilihan A)! Ini adalah teknik rekayasa sosial manipulasi \"sunk cost fallacy\" dan \"withdrawal lock\". Saldo awal palsu digunakan untuk menjebak korban agar terus menyetor uang ke sistem judi online."
    },
    {
      scenario: "Studi Kasus 03: Video Manipulasi Tokoh Publik",
      sender: "Akun TikTok / Reel Tidak Dikenal",
      message: "Video memperlihatkan tokoh ulama kondang seolah-olah menyatakan bahwa platform investasi berbasis aplikasi tertentu adalah halal dan menjamin hasil 100%.",
      attachment: "video_tokoh_investasi_ai.mp4",
      attachmentType: "Video Rekayasa AI Deepfake",
      icon: "face",
      question: "Gerak bibir ulama dalam video tampak tidak sinkron dengan suara (indikasi AI deepfake). Di akhir video ada ajakan klik link deposit. Sikap Anda?",
      options: [
        { key: "A", text: "Langsung percaya karena mengagumi ulama tersebut dan segera menyetor modal awal.", correct: false },
        { key: "B", text: "Menerapkan prinsip Tatsabbut: Melakukan konfirmasi ke akun resmi pesantren/tokoh, tidak menyebarkan video, dan melaporkan link tersebut sebagai phishing judol.", correct: true },
        { key: "C", text: "Membagikan video ke grup WhatsApp santri dengan tulisan \"Benarkah kabar ini?\".", correct: false },
        { key: "D", text: "Menghujat sang tokoh di kolom komentar tanpa meneliti keaslian video.", correct: false }
      ],
      explanation: "Benar (Pilihan B)! Sindikat judi online dan investasi ilegal kerap mencatut nama ulama dan tokoh masyarakat menggunakan deepfake AI untuk meruntuhkan kewaspadaan calon korban."
    },
    {
      scenario: "Studi Kasus 04: Bantuan Finansial Palsu",
      sender: "+62 813-9090-XXXX (Program Bantuan Mandiri)",
      message: "Selamat! Usaha mikro Anda terpilih menerima hibah modal Rp 15.000.000. Klik tautan verifikasi identitas dan aktifkan akun Anda dalam waktu 24 jam.",
      attachment: "https://bantuan-hibah2026.vip/verifikasi",
      attachmentType: "Phishing Bantuan & Aktivasi Judi",
      icon: "volunteer_activism",
      question: "Tautan meminta Anda mengisi data nomor rekening dan kode OTP bank, lalu menginstruksikan Anda memasang taruhan kecil di kasino daring untuk \"mengaktifkan token pencairan\". Apa tindakan Anda?",
      options: [
        { key: "A", text: "Mengikuti instruksi karena sudah membutuhkan modal usaha.", correct: false },
        { key: "B", text: "Menolak keras, tidak memberikan kode OTP apa pun, dan segera memblokir nomor tersebut karena ini adalah pencurian data dan jebakan judi online.", correct: true },
        { key: "C", text: "Memberikan nomor rekening bank tetapi tidak memasang taruhan kasino.", correct: false },
        { key: "D", text: "Meminta bukti surat keputusan resmi ke pengirim pesan via chat.", correct: false }
      ],
      explanation: "Benar (Pilihan B)! Program hibah pemerintah atau lembaga resmi TIDAK PERNAH mensyaratkan aktivitas di platform taruhan atau meminta kode OTP nasabah."
    }
  ];

  // ─── DOM References ────────────────────────────────────────────────────────
  const scenarioBadge    = document.getElementById('quiz-scenario-badge');
  const questionHeading  = document.getElementById('quiz-question-heading');
  const mockupSender     = document.getElementById('mockup-sender');
  const mockupMessage    = document.getElementById('mockup-message');
  const mockupFilename   = document.getElementById('mockup-filename');
  const mockupIcon       = document.getElementById('mockup-icon');
  const optionsGroup     = document.getElementById('quiz-options-group');
  const feedbackBox      = document.getElementById('quiz-feedback-box');
  const progressBar      = document.getElementById('quiz-progress-bar');
  const progressText     = document.getElementById('quiz-progress-text');
  const percentageText   = document.getElementById('quiz-percentage-text');
  const btnPrev          = document.getElementById('btn-quiz-prev');
  const btnNext          = document.getElementById('btn-quiz-next');
  const quizContainer    = document.getElementById('quiz-container');
  const resultCard       = document.getElementById('quiz-result-card');
  const finalScore       = document.getElementById('final-score');
  const scorePredicate   = document.getElementById('score-predicate');
  const btnRestart       = document.getElementById('btn-restart-quiz');

  if (!optionsGroup || !progressBar) return;

  // ─── State ─────────────────────────────────────────────────────────────────
  let quizData = fallbackQuizData;
  let currentIndex = 0;
  let userAnswers = {}; // { [index]: selectedKey }

  // ─── Load from ApiService if available ─────────────────────────────────────
  async function initQuiz() {
    try {
      if (window.ApiService && typeof window.ApiService.getCaseStudies === 'function') {
        const cases = await window.ApiService.getCaseStudies();
        if (cases && cases.length > 0) {
          quizData = cases.map((c, i) => ({
            scenario: c.scenario || `Studi Kasus ${String(i + 1).padStart(2, '0')}: ${c.title}`,
            sender: c.sender || c.context || 'Platform Digital Mencurigakan',
            message: c.message || c.scenario || '',
            attachment: c.attachment || (i === 0 ? 'https://cuan-syariah88.xyz/claim' : (i === 1 ? 'https://usdt-mining-cuan.top/spin' : (i === 2 ? 'video_tokoh_investasi_ai.mp4' : 'https://bantuan-hibah2026.vip/verifikasi'))),
            attachmentType: c.fiqihPrinciple ? `Kaidah: ${c.fiqihPrinciple}` : 'Tautan Terkait Judol',
            icon: c.icon || (i === 0 ? 'smart_toy' : (i === 1 ? 'currency_bitcoin' : (i === 2 ? 'face' : 'volunteer_activism'))),
            question: c.question || '',
            options: c.options || [],
            explanation: c.discussion || c.explanation || ''
          }));
        }
      }
    } catch (e) {
      console.warn('[CyberGuard] Gunakan data studi kasus fallback lokal:', e);
    }

    userAnswers = {};
    currentIndex = 0;
    renderQuestion(currentIndex);
  }

  // ─── Render Single Question ────────────────────────────────────────────────
  function renderQuestion(idx) {
    const q = quizData[idx];
    if (!q) return;

    if (scenarioBadge) scenarioBadge.textContent = q.scenario;
    if (questionHeading) questionHeading.textContent = q.question;
    if (mockupSender) mockupSender.textContent = q.sender;
    if (mockupMessage) mockupMessage.textContent = q.message;
    if (mockupFilename) mockupFilename.textContent = q.attachment || 'Tautan Analisis Terlampir';
    if (mockupIcon) mockupIcon.textContent = q.icon || 'link';

    const total = quizData.length;
    const progress = Math.round(((idx + 1) / total) * 100);
    progressBar.style.width = `${progress}%`;
    if (progressText) progressText.textContent = `Pertanyaan ${idx + 1} dari ${total}`;
    if (percentageText) percentageText.textContent = `${progress}% Selesai`;

    // Feedback box handling
    const currentAnswer = userAnswers[idx];
    if (currentAnswer) {
      showFeedback(q, currentAnswer);
    } else if (feedbackBox) {
      feedbackBox.className = 'hidden p-4 rounded-xl text-sm transition-all';
      feedbackBox.innerHTML = '';
    }

    // Render option cards
    optionsGroup.innerHTML = '';
    q.options.forEach((opt) => {
      const isSelected = currentAnswer === opt.key;
      const isAnswered = currentAnswer !== undefined;
      const isCorrectOpt = opt.correct;

      let borderClass = 'border-slate-200 hover:border-emerald-500 hover:bg-slate-50';
      let badgeClass = 'bg-slate-100 text-slate-700';

      if (isAnswered) {
        if (isCorrectOpt) {
          borderClass = 'border-emerald-500 bg-emerald-50/70 text-emerald-950';
          badgeClass = 'bg-emerald-600 text-white border-emerald-600';
        } else if (isSelected && !isCorrectOpt) {
          borderClass = 'border-red-400 bg-red-50/70 text-red-950';
          badgeClass = 'bg-red-500 text-white border-red-500';
        } else {
          borderClass = 'border-slate-200 opacity-60';
        }
      }

      const card = document.createElement('div');
      card.className = `quiz-option-card flex items-start gap-4 p-4 rounded-xl border-2 transition-all ${
        isAnswered ? 'cursor-default' : 'cursor-pointer'
      } ${borderClass}`;

      card.innerHTML = `
        <div class="w-7 h-7 rounded-lg ${badgeClass} font-bold text-xs flex items-center justify-center shrink-0 border border-slate-300">
          ${opt.key}
        </div>
        <div class="flex-1 text-sm leading-relaxed">
          ${opt.text}
        </div>
        ${isAnswered && isCorrectOpt ? '<span class="material-symbols-outlined text-emerald-600 text-[20px] shrink-0">check_circle</span>' : ''}
        ${isAnswered && isSelected && !isCorrectOpt ? '<span class="material-symbols-outlined text-red-500 text-[20px] shrink-0">cancel</span>' : ''}
      `;

      if (!isAnswered) {
        card.addEventListener('click', () => {
          userAnswers[idx] = opt.key;
          renderQuestion(idx);
        });
      }

      optionsGroup.appendChild(card);
    });

    // Update navigation buttons
    if (btnPrev) {
      btnPrev.disabled = idx === 0;
      btnPrev.className = idx === 0
        ? 'px-5 py-2.5 rounded-xl border border-slate-200 text-slate-400 font-semibold text-sm cursor-not-allowed'
        : 'px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-sm transition-all';
    }

    if (btnNext) {
      if (idx === total - 1) {
        btnNext.innerHTML = `<span>Lihat Hasil Evaluasi</span><span class="material-symbols-outlined text-[18px]">military_tech</span>`;
      } else {
        btnNext.innerHTML = `<span>Soal Selanjutnya</span><span class="material-symbols-outlined text-[18px]">arrow_forward</span>`;
      }
    }
  }

  function showFeedback(q, selectedKey) {
    if (!feedbackBox) return;
    const selectedOpt = q.options.find(o => o.key === selectedKey);
    const isCorrect = selectedOpt && selectedOpt.correct;

    feedbackBox.classList.remove('hidden');
    if (isCorrect) {
      feedbackBox.className = 'p-4 rounded-xl text-sm bg-emerald-100/90 text-emerald-950 border border-emerald-300 space-y-1';
      feedbackBox.innerHTML = `
        <div class="flex items-center gap-2 font-bold text-emerald-800">
          <span class="material-symbols-outlined text-emerald-700 text-[18px]">verified</span>
          <span>Jawaban Anda Tepat!</span>
        </div>
        <p class="leading-relaxed text-xs text-emerald-900">${q.explanation}</p>
      `;
    } else {
      feedbackBox.className = 'p-4 rounded-xl text-sm bg-red-100/90 text-red-950 border border-red-300 space-y-1';
      feedbackBox.innerHTML = `
        <div class="flex items-center gap-2 font-bold text-red-800">
          <span class="material-symbols-outlined text-red-700 text-[18px]">warning</span>
          <span>Kurang Tepat: Waspadai Pola Ini!</span>
        </div>
        <p class="leading-relaxed text-xs text-red-900">${q.explanation}</p>
      `;
    }
  }

  // ─── Scroll to Quiz Top ────────────────────────────────────────────────────
  function scrollToQuizTop() {
    if (quizContainer) {
      const navbarHeight = 90; // offset untuk navbar sticky
      const elementPosition = quizContainer.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;
      window.scrollTo({
        top: Math.max(0, offsetPosition),
        behavior: 'smooth'
      });
    } else {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  // ─── Button Handlers ───────────────────────────────────────────────────────
  if (btnPrev) {
    btnPrev.addEventListener('click', () => {
      if (currentIndex > 0) {
        currentIndex--;
        renderQuestion(currentIndex);
        scrollToQuizTop();
      }
    });
  }

  if (btnNext) {
    btnNext.addEventListener('click', () => {
      if (userAnswers[currentIndex] === undefined) {
        alert('Silakan pilih salah satu jawaban terlebih dahulu sebelum melanjutkan.');
        return;
      }

      if (currentIndex < quizData.length - 1) {
        currentIndex++;
        renderQuestion(currentIndex);
        scrollToQuizTop();
      } else {
        showResults();
      }
    });
  }

  // ─── Show Final Evaluation ─────────────────────────────────────────────────
  function showResults() {
    let correctCount = 0;
    quizData.forEach((q, idx) => {
      const ansKey = userAnswers[idx];
      const opt = q.options.find(o => o.key === ansKey);
      if (opt && opt.correct) correctCount++;
    });

    const total = quizData.length;
    const percentage = Math.round((correctCount / total) * 100);

    if (quizContainer) quizContainer.classList.add('hidden');
    if (resultCard) {
      resultCard.classList.remove('hidden');
      resultCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    if (finalScore) {
      finalScore.textContent = `${percentage} / 100 (${correctCount} dari ${total} Benar)`;
    }

    if (scorePredicate) {
      if (percentage >= 100) {
        scorePredicate.textContent = 'Istimewa! Refleks Tabayyun Anda sempurna dalam mengenali trik kamuflase investasi bodong dan tautan judi online.';
      } else if (percentage >= 75) {
        scorePredicate.textContent = 'Sangat Baik! Kewaspadaan Anda tinggi. Terus asah pemahaman dengan membaca modul literasi di menu Etika.';
      } else if (percentage >= 50) {
        scorePredicate.textContent = 'Cukup Baik. Anda masih memiliki celah kerentanan terhadap iming-iming cuan instan. Pelajari ciri-ciri tautan judol lebih mendalam.';
      } else {
        scorePredicate.textContent = 'Perlu Waspada Ekstra! Anda rentan terjebak modus robot trading dan arisan ponzi. Segera pelajari panduan 4T di halaman Etika.';
      }
    }
  }

  // ─── Restart ───────────────────────────────────────────────────────────────
  if (btnRestart) {
    btnRestart.addEventListener('click', () => {
      userAnswers = {};
      currentIndex = 0;
      if (resultCard) resultCard.classList.add('hidden');
      if (quizContainer) quizContainer.classList.remove('hidden');
      renderQuestion(0);
      scrollToQuizTop();
    });
  }

  // Init
  initQuiz();

});
