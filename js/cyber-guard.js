/**
 * cyber-guard.js — Aswaja CyberShield
 * Kuis interaktif Cyber Guard Test (Stepper Skenario)
 * Fokus: Studi kasus tautan investasi menyesatkan yang mengarah ke judi online (judol/maysir)
 * Bergantung pada: js/api.js (window.ApiService)
 */

document.addEventListener('DOMContentLoaded', () => {

  // ─── Data Soal Studi Kasus (Sinkron dengan public.case_studies di Database) ───
  const fallbackQuizData = [
    {
      scenario: "Studi Kasus 01: Jebakan Grup VIP \"Titip Dana\" & Robot Slot Otomatis",
      sender: "Admin VIP Arbitrase Syariah (+62 812-XXXX-9981)",
      message: "Assalamualaikum kak, slot titip dana batch ke-14 tinggal 2 kuota lagi! Modal Rp 500rb cair Rp 2.500.000 sore ini via algoritma AI Auto-Spin. Daftar di: https://vip-arbitrase-berkah88.site/join",
      attachment: "Konteks: Anda dimasukkan secara tiba-tiba ke dalam grup Telegram 'Komunitas Cuan Berkah AI'.",
      attachmentType: "Tautan Terkait Judol",
      icon: "trending_up",
      question: "Berdasarkan kaidah Fiqih Informasi (Prinsip 4T) dan literasi keamanan siber, langkah manakah yang paling tepat?",
      options: [
        { key: "A", text: "Mencoba transfer nominal terkecil terlebih dahulu untuk menguji kebenarannya.", correct: false },
        { key: "B", text: "Menahan diri (Tawaqquf), tidak mengeklik tautan karena klaim profit pasti dan kata kunci spin/slot adalah ciri mutlak judi online & penipuan.", correct: true },
        { key: "C", text: "Membagikan tautan tersebut ke grup keluarga agar ikut mendapat keuntungan.", correct: false },
        { key: "D", text: "Membalas pesan admin dengan mengirimkan foto KTP untuk verifikasi anggota.", correct: false }
      ],
      explanation: "Dalam Fiqih Muamalah, janji keuntungan tetap tanpa risiko pada skema spekulatif adalah haram (mengandung unsur Gharar & Maysir/perjudian). Dari sisi keamanan siber, domain .site dengan iming-iming pelipatgandaan uang instan adalah modus investasi bodong berkedok slot."
    },
    {
      scenario: "Studi Kasus 02: Situs Pinjol Kilat Tanpa Verifikasi KTP Berujung Judi",
      sender: "DanaCepat-SyariahOfficial",
      message: "Butuh dana darurat mendesak? Cair Rp 10 Juta dalam 3 menit tanpa BI Checking & tanpa bunga! Cukup daftar akun member dan klaim saldo awal di: https://danacepat-cair88.xyz/klaim-bonus",
      attachment: "Konteks: Sebuah iklan di media sosial menawarkan pinjaman modal usaha tanpa syarat dokumen apa pun.",
      attachmentType: "Tautan Terkait Judol",
      icon: "account_balance_wallet",
      question: "Mengapa tautan di atas sangat berbahaya dan bagaimana cara melakukan Tatsabbut (konfirmasi otoritas)?",
      options: [
        { key: "A", text: "Aman dicoba selama tidak meminta PIN ATM.", correct: false },
        { key: "B", text: "Berbahaya karena mengarahkan pengguna membuat akun deposit judi online berkedok pencairan pinjaman; wajib dicek di daftar resmi OJK.", correct: true },
        { key: "C", text: "Langsung mendaftar menggunakan email utama dan nomor telepon aktif.", correct: false },
        { key: "D", text: "Mengunduh aplikasi yang disarankan di dalam halaman web tersebut.", correct: false }
      ],
      explanation: "Banyak situs judi online menyamar sebagai layanan pinjaman kilat untuk menjebak warga yang sedang kesulitan ekonomi agar melakukan deposit awal ('biaya admin pencairan' yang ternyata top-up saldo taruhan). Selalu lakukan Tatsabbut melalui kanal resmi OJK (Kontak 157)."
    },
    {
      scenario: "Studi Kasus 03: Undangan Pernikahan Digital Berformat .APK di WhatsApp",
      sender: "+62 857-XXXX-3310 (Tidak Dikenal)",
      message: "Kami mengharap kehadiran Bapak/Ibu pada acara resepsi pernikahan putra-putri kami. Silakan buka file undangan terlampir untuk melihat lokasi dan jadwal acara. [File: Surat-Undangan-VIP.apk - 6.4 MB]",
      attachment: "Konteks: Anda menerima pesan WhatsApp dari nomor tidak dikenal yang mengirimkan file bernama 'Surat-Undangan-VIP.apk'.",
      attachmentType: "Malware Pencuri OTP (.APK)",
      icon: "phishing",
      question: "Tindakan mitigasi apa yang wajib dilakukan saat menerima file dengan ekstensi .apk melalui pesan singkat?",
      options: [
        { key: "A", text: "Membuka dan menginstal file tersebut karena penasaran siapa yang menikah.", correct: false },
        { key: "B", text: "Menolak mengunduh/menginstal file .apk, segera menghapus pesan, dan memblokir pengirim karena file .apk adalah aplikasi berbahaya pencuri SMS OTP.", correct: true },
        { key: "C", text: "Meneruskan file .apk tersebut ke teman lain untuk menanyakan isinya.", correct: false },
        { key: "D", text: "Mengizinkan akses baca SMS ketika aplikasi meminta izin instalasi.", correct: false }
      ],
      explanation: "Undangan digital resmi selalu berupa tautan web atau dokumen gambar/PDF, bukan file instalasi aplikasi (.apk). Menginstal .apk dari luar toko aplikasi resmi berisiko menyusupkan malware pencuri SMS OTP yang dapat menguras rekening mobile banking."
    },
    {
      scenario: "Studi Kasus 04: Giveaway Saldo DANA Kaget Palsu Mengarah ke Situs Phishing",
      sender: "Berbagi Berkah Ramadhan Official",
      message: "Alhamdulillah rezeki hari Jumat! Ambil saldo DANA Kaget Rp 750.000 gratis khusus 50 orang tercepat. Masukkan nomor HP dan PIN DANA Anda di: https://link-dana-kaget-berkah.cc/klaim",
      attachment: "Konteks: Beredar siaran langsung di media sosial yang menampilkan tangkapan layar bagi-bagi saldo dompet digital.",
      attachmentType: "Phishing Kredensial Dompet Digital",
      icon: "redeem",
      question: "Indikator utama apa yang membuktikan bahwa tautan tersebut adalah jebakan Phishing?",
      options: [
        { key: "A", text: "Karena nominal hadiahnya kurang besar.", correct: false },
        { key: "B", text: "Domain bukan milik resmi dana.id (link.dana.id) dan meminta input PIN serta kode OTP secara langsung di halaman web luar aplikasi.", correct: true },
        { key: "C", text: "Karena dibagikan pada hari Jumat.", correct: false },
        { key: "D", text: "Tautan menggunakan protokol HTTPS sehingga pasti aman 100%.", correct: false }
      ],
      explanation: "Fitur DANA Kaget yang asli hanya dapat diklaim langsung di dalam aplikasi resmi DANA melalui domain link.dana.id dan tidak pernah meminta pengguna mengetikkan PIN atau kode OTP pada situs web eksternal. Protokol HTTPS tidak menjamin sebuah situs bebas dari penipuan phishing."
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
            sender: c.sender || c.context || 'Platform Digital Mencurigakan',
            message: c.message || c.scenario || '',
            attachment: c.attachment || (c.scenario ? `Konteks: ${c.scenario}` : 'Tautan / Berkas Analisis'),
            attachmentType: c.fiqihPrinciple ? `Kaidah: ${c.fiqihPrinciple}` : 'Tautan Terkait Judol',
            icon: c.icon || 'quiz',
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
