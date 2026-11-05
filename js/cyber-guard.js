/**
 * cyber-guard.js — Aswaja CyberShield
 * Kuis interaktif Cyber Guard Test (Stepper Skenario)
 * Fokus: Studi kasus tautan investasi menyesatkan yang mengarah ke judi online (judol/maysir)
 * Bergantung pada: js/api.js (window.ApiService)
 */

document.addEventListener('DOMContentLoaded', () => {

  // ─── Data Soal Judol Lokal (Fallback jika API belum siap) ───────────────────
  const fallbackQuizData = [
    {
      scenario: "Studi Kasus 01: Investasi Robot Trading Pasif",
      sender: "Grup WhatsApp: 💰 CUAN AUTOPILOT VIP 88",
      message: "Daftar sekarang! ROBOT TRADING otomatis kerja 24 jam. Profit PASTI 3–5% per hari tanpa risiko. Modal awal Rp 500.000 LANGSUNG CAIR. Link pendaftaran: https://robot-trading-cuan88.vip/register",
      attachment: "Tautan: robot-trading-cuan88.vip/register",
      attachmentType: "Tautan URL Mencurigakan",
      icon: "trending_up",
      question: "Anda menerima undangan grup berisi tawaran 'robot trading' dengan jaminan profit harian tanpa risiko melalui tautan ber-domain .vip. Apa tindakan Tabayyun yang paling tepat?",
      options: [
        { key: "A", text: "Langsung mendaftar dan mentransfer deposit awal karena nominalnya terjangkau dan keuntungannya menggiurkan.", correct: false },
        { key: "B", text: "Meneruskan tautan ke grup keluarga agar anggota lain bisa ikut berinvestasi bersama.", correct: false },
        { key: "C", text: "Menerapkan Tawaqquf: Menolak membuka tautan, memeriksa legalitas di cekfintech.ojk.go.id, dan keluar dari grup.", correct: true },
        { key: "D", text: "Mencoba menyetor sejumlah kecil uang terlebih dahulu untuk membuktikan klaim profit.", correct: false }
      ],
      explanation: "Tepat sekali (Pilihan C)! Tidak ada instrumen investasi legal yang mampu menjamin profit pasti harian tanpa risiko fluktuasi. Skema ini lazim digunakan sindikat penipuan trading bodong yang berujung pada penguncian dana dan pengalihan ke platform perjudian daring. Domain .vip tidak berizin OJK/Bappebti."
    },
    {
      scenario: "Studi Kasus 02: Aplikasi Dompet Digital Berkedok Slot",
      sender: "Iklan Media Sosial: Dompet Cerdas Finansial",
      message: "Kelola keuangan bulanan Anda dengan mudah! Dapatkan bonus saldo Rp 50.000 untuk pendaftar hari ini. Akses tautan:",
      attachment: "Tautan: smart-wallet-finance.xyz/bonus-slot",
      attachmentType: "Parameter /bonus-slot Terdeteksi",
      icon: "account_balance_wallet",
      question: "Sebuah iklan menawarkan dompet digital finansial, namun struktur tautannya berakhiran '/bonus-slot' dengan domain .xyz. Bagaimana sikap Anda?",
      options: [
        { key: "A", text: "Mengunduh dan mendaftar demi mengklaim bonus saldo Rp 50.000 cuma-cuma.", correct: false },
        { key: "B", text: "Mengenali parameter '/bonus-slot' sebagai sinyal bahaya: situs ini berkamuflase sebagai aplikasi keuangan untuk menjerat ke situs judi.", correct: true },
        { key: "C", text: "Mencoba login dengan akun media sosial utama untuk kemudahan verifikasi.", correct: false },
        { key: "D", text: "Mengabaikan tautan tetapi membagikan tangkapan layarnya ke forum publik tanpa peringatan.", correct: false }
      ],
      explanation: "Benar (Pilihan B)! Penyusupan kata kunci perjudian seperti '/bonus-slot' atau '/spin' di balik nama berbau finansial adalah taktik kamuflase maysir modern untuk menghindari pemblokiran Kominfo dan mengelabui calon korban."
    },
    {
      scenario: "Studi Kasus 03: Undangan Arisan Kilat Berantai",
      sender: "Kontak Teman Lama: 'Arisan Berkah'",
      message: "Assalamualaikum ukhti/akhi, yuk gabung arisan kilat modal Rp 200.000 dalam 7 hari dapat Rp 800.000. Putaran cepat, slot terbatas! Klik:",
      attachment: "Tautan: arisan-berkah-putaran.cc/join?ref=KL7",
      attachmentType: "Skema Perputaran Dana Ponzi",
      icon: "groups",
      question: "Teman Anda mengirimkan ajakan 'arisan online' dengan pengembalian 4x lipat dalam sepekan melalui tautan tidak resmi. Bagaimana tanggapan Anda?",
      options: [
        { key: "A", text: "Ikut bergabung karena yang mengajak adalah teman lama yang Anda percayai.", correct: false },
        { key: "B", text: "Meminta bukti mutasi bank teman tersebut sebelum memutuskan transfer dana.", correct: false },
        { key: "C", text: "Menolak tegas dan menasihati teman tersebut bahwa skema perputaran uang kilat ini mengandung unsur gharar dan ponzi judol.", correct: true },
        { key: "D", text: "Membayar slot arisan menggunakan kartu kredit atau pinjaman online.", correct: false }
      ],
      explanation: "Tepat (Pilihan C)! Arisan online dengan penggandaan uang tidak wajar dalam hitungan hari merupakan modus Ponzi yang kerap terafiliasi dengan perputaran dana judi online. Dalam Islam, transaksi ini haram karena mengandung riba, maysir (spekulasi), dan memakan harta sesama secara batil."
    },
    {
      scenario: "Studi Kasus 04: Penawaran Partner Afiliasi Cuan Kilat",
      sender: "Email: partner@maxwin-affiliasi-vip.online",
      message: "SELAMAT! Profil Anda terpilih menjadi mitra passive income. Dapatkan komisi 40% dari setiap pengguna yang mendaftar melalui link referral Anda:",
      attachment: "Tautan: maxwin-affiliasi-vip.online/partner",
      attachmentType: "Afiliasi Jaringan Maysir",
      icon: "diversity_3",
      question: "Anda menerima surel penawaran komisi referral 40% dari situs dengan nama domain berunsur 'maxwin'. Apa tindakan yang mencerminkan etika Tabayyun?",
      options: [
        { key: "A", text: "Menerima tawaran kemitraan karena tidak memerlukan modal awal dan menghasilkan uang pasif.", correct: false },
        { key: "B", text: "Mengenali istilah 'maxwin' sebagai terminologi judi slot online, menolak menjadi agen promosi judol, dan melaporkannya.", correct: true },
        { key: "C", text: "Membagikan tautan referral tersebut ke kenalan yang sedang membutuhkan pekerjaan.", correct: false },
        { key: "D", text: "Mengklik tautan untuk mempelajari skema pembagian komisinya terlebih dahulu.", correct: false }
      ],
      explanation: "Benar (Pilihan B)! Membantu menyebarkan tautan afiliasi judi daring adalah bentuk tolong-menolong dalam keburukan (ta'awun 'alal itsmi wal 'udwan) yang dilarang agama dan diancam pidana UU ITE di Indonesia."
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
            scenario: `Studi Kasus ${String(i + 1).padStart(2, '0')}: ${c.title}`,
            sender: c.context || 'Platform Digital Mencurigakan',
            message: c.scenario || '',
            attachment: c.attachment || 'Tautan / Berkas Analisis',
            attachmentType: 'Tautan Terkait Judol',
            icon: c.icon || 'quiz',
            question: c.question || '',
            options: c.options || [],
            explanation: c.explanation || ''
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
