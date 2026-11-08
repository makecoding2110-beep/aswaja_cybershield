/**
 * ui-kit.js — Aswaja CyberShield
 * Komponen UI reusable: Modal (dialog/alertdialog), Toast, alur Konfirmasi,
 * Konfirmasi Hapus, smooth scroll, validasi form, editor teks kaya, dan
 * pelacak perubahan form (dirty tracking).
 *
 * Dipakai lewat objek global `AswajaUI`. Tidak bergantung pada pustaka lain.
 * Semua warna berasal dari token CSS di css/ui-kit.css (otomatis mengikuti
 * tema terang/gelap, termasuk saat pop up sedang terbuka).
 */
(function (global) {
  'use strict';
  if (global.AswajaUI) return;

  var doc = global.document;

  /* ── Utilitas dasar ─────────────────────────────────────────────────────── */

  var uid = 0;

  /** Ikon SVG statis (tepercaya) agar tidak bergantung pada font ikon halaman. */
  var SVG_OPEN = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
  var ICONS = {
    success: SVG_OPEN + '<circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.8"/></svg>',
    error: SVG_OPEN + '<circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.5"/><path d="M12 16.5h.01"/></svg>',
    warning: SVG_OPEN + '<path d="M10.3 3.9 2.4 17.5A2 2 0 0 0 4.1 20.5h15.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9.5v4"/><path d="M12 17h.01"/></svg>',
    danger: SVG_OPEN + '<path d="M4 7h16"/><path d="M9 7V4.5h6V7"/><path d="M6.5 7l.8 12.2a1.5 1.5 0 0 0 1.5 1.3h6.4a1.5 1.5 0 0 0 1.5-1.3L17.5 7"/><path d="M10 11v5.5M14 11v5.5"/></svg>',
    info: SVG_OPEN + '<circle cx="12" cy="12" r="9"/><path d="M12 11v5.5"/><path d="M12 7.7h.01"/></svg>',
    close: SVG_OPEN + '<path d="M6 6l12 12M18 6 6 18"/></svg>',
    loading: '<span class="ui-spinner" style="width:1.25rem;height:1.25rem" aria-hidden="true"></span>'
  };

  function reducedMotion() {
    return !!(global.matchMedia && global.matchMedia('(prefers-reduced-motion: reduce)').matches);
  }

  /** Pembuat elemen DOM aman (memakai textContent, bukan innerHTML). */
  function el(tag, props, children) {
    var node = doc.createElement(tag);
    props = props || {};
    Object.keys(props).forEach(function (key) {
      var val = props[key];
      if (val === null || val === undefined || val === false) return;
      if (key === 'class') node.className = val;
      else if (key === 'text') node.textContent = val;
      else if (key === 'html') node.innerHTML = val; // hanya untuk ikon statis internal
      else node.setAttribute(key, val === true ? '' : val);
    });
    (children || []).forEach(function (child) {
      if (child === null || child === undefined || child === false) return;
      node.appendChild(typeof child === 'string' ? doc.createTextNode(child) : child);
    });
    return node;
  }

  function icon(name, cls) {
    return el('span', { class: cls || '', html: ICONS[name] || ICONS.info, 'aria-hidden': 'true' });
  }

  var FOCUSABLE = 'a[href],button:not([disabled]),input:not([disabled]):not([type="hidden"]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])';

  function focusablesIn(root) {
    return Array.prototype.filter.call(root.querySelectorAll(FOCUSABLE), function (n) {
      return n.offsetWidth > 0 || n.offsetHeight > 0 || n === doc.activeElement;
    });
  }

  function safeFocus(node) {
    if (!node || typeof node.focus !== 'function') return;
    try { node.focus({ preventScroll: true }); } catch (e) { node.focus(); }
  }

  /* ── Manajemen tumpukan modal ───────────────────────────────────────────── */

  var stack = [];
  var lockCount = 0;
  var inertMarked = [];
  var keyBound = false;

  function lockScroll() {
    if (lockCount++ === 0) {
      var sbw = global.innerWidth - doc.documentElement.clientWidth;
      doc.documentElement.classList.add('ui-scroll-lock');
      doc.body.classList.add('ui-scroll-lock');
      if (sbw > 0) doc.body.style.paddingRight = sbw + 'px';
    }
  }
  function unlockScroll() {
    if (--lockCount <= 0) {
      lockCount = 0;
      doc.documentElement.classList.remove('ui-scroll-lock');
      doc.body.classList.remove('ui-scroll-lock');
      doc.body.style.paddingRight = '';
    }
  }

  /** Hanya modal teratas yang interaktif; elemen lain dibuat inert. */
  function refreshInert() {
    inertMarked.forEach(function (n) { n.removeAttribute('inert'); n.removeAttribute('aria-hidden'); });
    inertMarked = [];
    var top = stack[stack.length - 1];
    if (!top) return;
    Array.prototype.forEach.call(doc.body.children, function (child) {
      if (child === top.overlay || child.classList.contains('ui-toast-region')) return;
      if (child.tagName === 'SCRIPT' || child.tagName === 'STYLE' || child.tagName === 'LINK') return;
      if (child.hasAttribute('inert')) return;
      child.setAttribute('inert', '');
      child.setAttribute('aria-hidden', 'true');
      inertMarked.push(child);
    });
  }

  function onKeydown(e) {
    var top = stack[stack.length - 1];
    if (!top) return;
    if (e.key === 'Escape' || e.key === 'Esc') {
      if (top.st.dismissible && !top.busy) {
        e.preventDefault();
        e.stopPropagation();
        top.requestClose('esc');
      } else {
        e.preventDefault();
      }
      return;
    }
    if (e.key === 'Tab') trapFocus(e, top.el);
  }

  function trapFocus(e, root) {
    var items = focusablesIn(root);
    if (!items.length) { e.preventDefault(); safeFocus(root); return; }
    var first = items[0];
    var last = items[items.length - 1];
    var active = doc.activeElement;
    if (e.shiftKey && (active === first || !root.contains(active))) {
      e.preventDefault(); safeFocus(last);
    } else if (!e.shiftKey && (active === last || !root.contains(active))) {
      e.preventDefault(); safeFocus(first);
    }
  }

  function onFocusIn(e) {
    var top = stack[stack.length - 1];
    if (!top) return;
    if (top.el.contains(e.target)) return;
    if (e.target.closest && e.target.closest('.ui-toast-region')) return;
    safeFocus(focusablesIn(top.el)[0] || top.el);
  }

  function bindKeys() {
    if (keyBound) return;
    keyBound = true;
    doc.addEventListener('keydown', onKeydown, true);
    doc.addEventListener('focusin', onFocusIn, true);
  }

  /* ── Modal inti ─────────────────────────────────────────────────────────── */

  /**
   * Membuka modal.
   * opts: { role, title, description, content(Node), icon, size, dismissible,
   *         actions:[{id,label,variant,onClick,closes,autofocus,disabled}],
   *         initialFocus, beforeClose(reason), onClose(reason), onOpen(inst) }
   */
  function openModal(options) {
    bindKeys();
    var id = ++uid;
    var previouslyFocused = doc.activeElement;
    var resolveClosed;
    var closedPromise = new Promise(function (r) { resolveClosed = r; });

    var st = {
      role: options.role || 'dialog',
      size: options.size || 'md',
      icon: options.icon === undefined ? null : options.icon,
      title: options.title || '',
      description: options.description || '',
      content: options.content || null,
      actions: options.actions || [],
      dismissible: options.dismissible !== false,
      initialFocus: options.initialFocus || null
    };

    var overlay = el('div', { class: 'ui-overlay' });
    var dialog = el('div', {
      class: 'ui-modal',
      role: st.role,
      'aria-modal': 'true',
      tabindex: '-1'
    });
    overlay.appendChild(dialog);

    var inst = {
      el: dialog,
      overlay: overlay,
      st: st,
      busy: false,
      closed: false,
      lastAction: null,
      result: closedPromise,
      buttons: {}
    };

    function render() {
      dialog.className = 'ui-modal' + (st.size === 'lg' ? ' ui-modal--lg' : '');
      dialog.setAttribute('role', st.role);
      while (dialog.firstChild) dialog.removeChild(dialog.firstChild);
      inst.buttons = {};

      var titleId = 'ui-modal-title-' + id;
      var bodyId = 'ui-modal-body-' + id;

      var head = el('div', { class: 'ui-modal__head' });
      if (st.icon) {
        head.appendChild(icon(st.icon, 'ui-modal__icon ui-modal__icon--' + st.icon));
      }
      head.appendChild(el('div', { class: 'ui-modal__titles' }, [
        el('h2', { class: 'ui-modal__title', id: titleId, text: st.title })
      ]));
      if (st.dismissible && !inst.busy && !st.hideCloseButton) {
        var closeBtn = el('button', { type: 'button', class: 'ui-modal__close', 'aria-label': 'Tutup' });
        closeBtn.innerHTML = ICONS.close;
        closeBtn.addEventListener('click', function () { inst.requestClose('close-button'); });
        head.appendChild(closeBtn);
      }
      dialog.appendChild(head);

      var body = el('div', { class: 'ui-modal__body', id: bodyId, 'aria-live': 'polite' });
      if (st.description) {
        var desc = el('div', { class: 'ui-modal__desc' });
        if (typeof st.description === 'string') desc.textContent = st.description;
        else desc.appendChild(st.description);
        body.appendChild(desc);
      }
      if (st.content) body.appendChild(st.content);
      dialog.appendChild(body);

      dialog.setAttribute('aria-labelledby', titleId);
      dialog.setAttribute('aria-describedby', bodyId);
      if (inst.busy) dialog.setAttribute('aria-busy', 'true'); else dialog.removeAttribute('aria-busy');

      var foot = el('div', { class: 'ui-modal__foot' });
      st.actions.forEach(function (a, idx) {
        var btn = el('button', {
          type: 'button',
          class: 'ui-btn ui-btn--' + (a.variant || 'outline'),
          'data-action': a.id || String(idx)
        });
        if (a.busy) btn.appendChild(el('span', { class: 'ui-spinner', 'aria-hidden': 'true' }));
        btn.appendChild(doc.createTextNode(a.label));
        if (a.disabled || inst.busy) btn.disabled = true;
        btn.addEventListener('click', function () { runAction(a, btn); });
        foot.appendChild(btn);
        inst.buttons[a.id || String(idx)] = btn;
      });
      if (st.actions.length) dialog.appendChild(foot);
    }

    function runAction(a, btn) {
      if (btn.disabled || inst.busy) return;
      inst.lastAction = a.id || null;
      var out;
      try { out = a.onClick ? a.onClick(inst, a) : undefined; } catch (err) { console.error(err); return; }
      if (out && typeof out.then === 'function') {
        out.then(function (r) { if (a.closes !== false && r !== false) inst.close(a.id || 'action'); });
      } else if (a.closes !== false && out !== false) {
        inst.close(a.id || 'action');
      }
    }

    function applyFocus() {
      var target = null;
      if (st.initialFocus) {
        target = typeof st.initialFocus === 'string' ? dialog.querySelector(st.initialFocus) : st.initialFocus;
      }
      if (!target) {
        var auto = st.actions.filter(function (a) { return a.autofocus; })[0];
        if (auto) target = inst.buttons[auto.id || String(st.actions.indexOf(auto))];
      }
      if (!target || target.disabled) target = dialog;
      safeFocus(target);
    }

    inst.update = function (patch) {
      Object.keys(patch || {}).forEach(function (k) { st[k] = patch[k]; });
      render();
      if (!patch || patch.keepFocus !== true) applyFocus();
      return inst;
    };

    inst.setBusy = function (flag) {
      inst.busy = !!flag;
      render();
      return inst;
    };

    inst.setActionDisabled = function (actionId, flag) {
      if (inst.buttons[actionId]) inst.buttons[actionId].disabled = !!flag;
    };

    inst.requestClose = function (reason) {
      if (inst.closed) return;
      if (typeof options.beforeClose === 'function' && options.beforeClose(reason, inst) === false) return;
      inst.close(reason);
    };

    inst.close = function (reason) {
      if (inst.closed) return;
      inst.closed = true;
      var idx = stack.indexOf(inst);
      if (idx > -1) stack.splice(idx, 1);
      overlay.classList.remove('is-open');
      var finish = function () {
        if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
        refreshInert();
        unlockScroll();
        if (previouslyFocused && doc.contains(previouslyFocused)) safeFocus(previouslyFocused);
        if (typeof options.onClose === 'function') options.onClose(reason, inst);
        resolveClosed(reason);
      };
      if (reducedMotion()) finish(); else setTimeout(finish, 200);
      // Aktifkan kembali modal di bawahnya segera (agar fokus tidak tertahan)
      refreshInert();
    };

    // Klik overlay (hanya jika mousedown & click sama-sama di overlay)
    var downOnOverlay = false;
    overlay.addEventListener('mousedown', function (e) { downOnOverlay = e.target === overlay; });
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay && downOnOverlay && st.dismissible && !inst.busy) inst.requestClose('overlay');
      downOnOverlay = false;
    });

    render();
    doc.body.appendChild(overlay);
    stack.push(inst);
    lockScroll();
    refreshInert();
    // Paksa reflow lalu aktifkan transisi masuk
    void overlay.offsetWidth;
    overlay.classList.add('is-open');
    applyFocus();
    if (typeof options.onOpen === 'function') options.onOpen(inst);
    return inst;
  }

  /* ── Alur konfirmasi: konfirmasi → loading → sukses / error ─────────────── */

  function summaryNode(items) {
    var dl = el('dl', { class: 'ui-summary' });
    items.forEach(function (it) {
      if (it.value === undefined || it.value === null || it.value === '') return;
      dl.appendChild(el('dt', { text: it.label }));
      dl.appendChild(el('dd', { text: String(it.value) }));
    });
    return dl;
  }

  /**
   * opts: { title, message, summary:[{label,value}], content(Node), icon, role,
   *         variant:'primary'|'danger', confirmText, cancelText, loadingTitle,
   *         loadingText, retryText, closeText, confirmDisabled,
   *         onConfirm: async()=>result,
   *         success: fn(result)=>{title,message,content,actions}|false|undefined,
   *         successToast: string, errorTitle, onOpen }
   * Mengembalikan Promise<{status:'cancelled'|'success'|'error', result?, error?, action?}>
   */
  function confirmFlow(opts) {
    var o = Object.assign({
      title: 'Konfirmasi',
      message: '',
      icon: 'info',
      role: 'dialog',
      variant: 'primary',
      confirmText: 'Ya, Lanjutkan',
      cancelText: 'Batal',
      loadingTitle: 'Memproses…',
      loadingText: 'Mohon tunggu, jangan menutup halaman ini.',
      retryText: 'Coba Lagi',
      closeText: 'Tutup',
      errorTitle: 'Terjadi kesalahan'
    }, opts || {});

    return new Promise(function (resolve) {
      var outcome = { status: 'cancelled' };
      var content = o.content || (o.summary ? summaryNode(o.summary) : null);

      function confirmActions() {
        return [
          { id: 'cancel', label: o.cancelText, variant: 'outline', autofocus: o.variant === 'danger' },
          { id: 'confirm', label: o.confirmText, variant: o.variant, autofocus: o.variant !== 'danger', closes: false, disabled: !!o.confirmDisabled, onClick: run }
        ];
      }

      var inst = openModal({
        role: o.role,
        icon: o.icon,
        title: o.title,
        description: o.message,
        content: content,
        actions: confirmActions(),
        onOpen: o.onOpen,
        onClose: function () {
          outcome.action = inst.lastAction;
          resolve(outcome);
        }
      });

      function run() {
        if (!o.onConfirm) {
          outcome = { status: 'success' };
          inst.close('confirm');
          return;
        }
        inst.busy = true;
        inst.update({ icon: 'loading', title: o.loadingTitle, description: o.loadingText, content: null, actions: [], dismissible: false });
        Promise.resolve()
          .then(function () { return o.onConfirm(inst); })
          .then(function (result) {
            inst.busy = false;
            outcome = { status: 'success', result: result };
            var s = typeof o.success === 'function' ? o.success(result) : o.success;
            if (s === false) {
              if (o.successToast) toast(o.successToast, { type: 'success' });
              inst.close('success');
              return;
            }
            s = s || {};
            inst.update({
              icon: 'success',
              title: s.title || 'Berhasil',
              description: s.message || '',
              content: s.content || null,
              hideCloseButton: s.hideCloseButton !== undefined ? !!s.hideCloseButton : false,
              dismissible: true,
              actions: s.actions || [{ id: 'close', label: o.closeText, variant: 'primary', autofocus: true }]
            });
          })
          .catch(function (err) {
            console.error('[AswajaUI] Aksi gagal:', err);
            inst.busy = false;
            outcome = { status: 'error', error: err };
            var details = null;
            if (err && err.errors && typeof err.errors === 'object') {
              details = el('ul', { style: 'margin:.5rem 0 0;padding-left:1.1rem;list-style:disc;font-size:.8125rem' });
              Object.keys(err.errors).forEach(function (k) {
                [].concat(err.errors[k]).forEach(function (m) { details.appendChild(el('li', { text: String(m) })); });
              });
            }
            inst.update({
              icon: 'error',
              title: o.errorTitle,
              description: (err && err.message) || 'Terjadi kesalahan. Silakan coba lagi.',
              content: details,
              dismissible: true,
              actions: [
                { id: 'close', label: o.closeText, variant: 'outline' },
                { id: 'retry', label: o.retryText, variant: o.variant, autofocus: true, closes: false, onClick: run }
              ]
            });
          });
      }
    });
  }

  /** Dialog satu tombol (informasi / peringatan / error). */
  function notice(opts) {
    var o = Object.assign({ icon: 'info', title: '', message: '', buttonText: 'Mengerti', role: 'alertdialog' }, opts || {});
    return new Promise(function (resolve) {
      openModal({
        role: o.role,
        icon: o.icon,
        title: o.title,
        description: o.message,
        content: o.content || null,
        actions: [{ id: 'ok', label: o.buttonText, variant: 'primary', autofocus: true }],
        onClose: function () { resolve(true); }
      });
    });
  }

  /* ── Konfirmasi hapus (satu komponen untuk semua jenis data) ────────────── */

  /**
   * opts: { type:'artikel', name:'Judul', names:[...], count:1,
   *         requireText:'HAPUS'|username, blockedReason:'...', blockedTitle,
   *         onDelete: async()=>..., successMessage }
   * Mengembalikan Promise<{status:'deleted'|'cancelled'|'blocked'|'error', result?}>
   */
  function confirmDelete(opts) {
    var o = Object.assign({
      type: 'data',
      name: '',
      names: null,
      count: 1,
      requireText: null,
      blockedReason: null,
      blockedTitle: 'Penghapusan tidak diizinkan',
      successMessage: 'Data berhasil dihapus.'
    }, opts || {});

    if (o.blockedReason) {
      return notice({ icon: 'warning', title: o.blockedTitle, message: o.blockedReason }).then(function () {
        return { status: 'blocked' };
      });
    }

    var bulk = o.count > 1;
    var title = bulk ? 'Hapus ' + o.count + ' ' + o.type + ' terpilih?' : 'Hapus ' + o.type + '?';

    // Rincian data yang akan dihapus
    var box = el('div');
    if (bulk && o.names && o.names.length) {
      var ul = el('ul', { class: 'ui-summary', style: 'display:block;list-style:disc;padding-left:1.5rem;margin-top:.75rem' });
      o.names.slice(0, 5).forEach(function (n) { ul.appendChild(el('li', { text: String(n), style: 'color:var(--text-heading)' })); });
      if (o.names.length > 5) ul.appendChild(el('li', { text: 'dan ' + (o.names.length - 5) + ' lainnya', style: 'color:var(--text-muted)' }));
      box.appendChild(ul);
    } else if (!bulk && o.name) {
      box.appendChild(summaryNode([{ label: o.type.charAt(0).toUpperCase() + o.type.slice(1), value: o.name }]));
    }
    box.appendChild(el('div', {
      style: 'margin-top:.75rem;font-weight:600;color:var(--color-danger-text)',
      text: 'Tindakan ini tidak dapat dibatalkan.'
    }));

    var typed = null;
    if (o.requireText) {
      var fid = 'ui-confirm-input-' + (++uid);
      typed = el('input', { type: 'text', id: fid, class: 'ui-input', autocomplete: 'off', spellcheck: 'false', 'aria-describedby': fid + '-hint' });
      var codeEl = el('code', { text: o.requireText });
      var label = el('label', { for: fid }, ['Ketik ', codeEl, ' untuk melanjutkan.']);
      box.appendChild(el('div', { class: 'ui-confirm-field' }, [label, typed, el('div', { class: 'ui-field-hint', id: fid + '-hint', text: 'Huruf besar/kecil harus sama persis.' })]));
    }

    return confirmFlow({
      role: 'alertdialog',
      icon: 'danger',
      variant: 'danger',
      title: title,
      message: bulk ? 'Data berikut akan dihapus:' : 'Data berikut akan dihapus:',
      content: box,
      confirmText: 'Ya, Hapus',
      cancelText: 'Batal',
      confirmDisabled: !!o.requireText,
      loadingTitle: 'Menghapus…',
      loadingText: 'Sedang menghapus data. Mohon tunggu.',
      errorTitle: 'Gagal menghapus',
      onOpen: function (inst) {
        if (!typed) return;
        typed.addEventListener('input', function () {
          inst.setActionDisabled('confirm', typed.value.trim() !== String(o.requireText));
        });
        typed.addEventListener('keydown', function (e) {
          if (e.key === 'Enter' && typed.value.trim() === String(o.requireText)) {
            e.preventDefault();
            if (inst.buttons.confirm) inst.buttons.confirm.click();
          }
        });
      },
      onConfirm: o.onDelete,
      success: false,
      successToast: o.successMessage
    }).then(function (r) {
      if (r.status === 'success') return { status: 'deleted', result: r.result };
      return r;
    });
  }

  /** Peringatan perubahan belum disimpan → Promise<boolean> (true = buang). */
  function confirmDiscard(opts) {
    var o = Object.assign({
      title: 'Perubahan belum disimpan',
      message: 'Jika Anda menutup form ini, perubahan yang sudah dibuat akan hilang.',
      stayText: 'Lanjutkan Mengedit',
      discardText: 'Buang Perubahan'
    }, opts || {});
    return new Promise(function (resolve) {
      var discard = false;
      openModal({
        role: 'alertdialog',
        icon: 'warning',
        title: o.title,
        description: o.message,
        actions: [
          { id: 'discard', label: o.discardText, variant: 'outline', onClick: function () { discard = true; } },
          { id: 'stay', label: o.stayText, variant: 'primary', autofocus: true }
        ],
        onClose: function () { resolve(discard); }
      });
    });
  }

  /* ── Toast ──────────────────────────────────────────────────────────────── */

  function toast(message, options) {
    var o = typeof options === 'string' ? { type: options } : (options || {});
    var type = o.type || 'info';
    var duration = o.duration !== undefined ? o.duration : (type === 'error' ? 7000 : 4500);

    var region = doc.querySelector('.ui-toast-region');
    if (!region) {
      region = el('div', { class: 'ui-toast-region' });
      doc.body.appendChild(region);
    }
    while (region.children.length >= 4) region.removeChild(region.firstChild);

    var closeBtn = el('button', { type: 'button', class: 'ui-toast__close', 'aria-label': 'Tutup notifikasi' });
    closeBtn.innerHTML = ICONS.close;
    var msg = el('div', { class: 'ui-toast__msg' });
    if (o.title) msg.appendChild(el('span', { class: 'ui-toast__title', text: o.title }));
    msg.appendChild(doc.createTextNode(String(message)));

    var node = el('div', { class: 'ui-toast ui-toast--' + type, role: type === 'error' ? 'alert' : 'status' }, [
      icon(type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info', 'ui-toast__icon'),
      msg,
      closeBtn
    ]);
    region.appendChild(node);
    void node.offsetWidth;
    node.classList.add('is-open');

    var timer = null;
    function dismiss() {
      clearTimeout(timer);
      node.classList.remove('is-open');
      var rm = function () { if (node.parentNode) node.parentNode.removeChild(node); };
      if (reducedMotion()) rm(); else setTimeout(rm, 220);
    }
    function arm(ms) { if (duration > 0) { clearTimeout(timer); timer = setTimeout(dismiss, ms); } }
    closeBtn.addEventListener('click', dismiss);
    node.addEventListener('mouseenter', function () { clearTimeout(timer); });
    node.addEventListener('mouseleave', function () { arm(1500); });
    arm(duration);
    return { close: dismiss };
  }

  /* ── Smooth scroll ──────────────────────────────────────────────────────── */

  function navOffset() {
    var header = doc.querySelector('header.sticky, header.fixed');
    return header ? Math.round(header.getBoundingClientRect().height) + 16 : 96;
  }

  function animateScrollTo(y) {
    // Fallback untuk browser lama tanpa CSS scroll-behavior
    var startY = global.pageYOffset;
    var delta = y - startY;
    var duration = 500;
    var start = null;
    function ease(t) { return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; }
    function step(ts) {
      if (start === null) start = ts;
      var p = Math.min((ts - start) / duration, 1);
      global.scrollTo(0, startY + delta * ease(p));
      if (p < 1) global.requestAnimationFrame(step);
    }
    global.requestAnimationFrame(step);
  }

  function waitForScrollEnd(maxMs) {
    return new Promise(function (resolve) {
      var last = -1;
      var stable = 0;
      var began = Date.now();
      function tick() {
        var y = global.pageYOffset;
        stable = y === last ? stable + 1 : 0;
        last = y;
        if (stable >= 3 || Date.now() - began > (maxMs || 1500)) return resolve();
        setTimeout(tick, 50);
      }
      setTimeout(tick, 60);
    });
  }

  /**
   * Scroll halus ke elemen, lalu (opsional) fokus.
   * opts: { focus: selector|Element, focusTarget: bool, offset: px }
   */
  function scrollToTarget(target, opts) {
    opts = opts || {};
    var node = typeof target === 'string' ? doc.querySelector(target) : target;
    if (!node) return Promise.resolve(false);

    var offset = opts.offset !== undefined ? opts.offset : navOffset();
    var block = opts.block || 'start';
    // Pastikan judul tidak tertutup navbar sticky walau CSS belum termuat
    if (!parseFloat(global.getComputedStyle(node).scrollMarginTop)) {
      node.style.scrollMarginTop = offset + 'px';
    }

    var smoothSupported = 'scrollBehavior' in doc.documentElement.style;
    if (reducedMotion()) {
      try { node.scrollIntoView({ behavior: 'instant', block: block }); }
      catch (e) { node.scrollIntoView(true); }
    } else if (smoothSupported) {
      node.scrollIntoView({ behavior: 'smooth', block: block });
    } else {
      animateScrollTo(node.getBoundingClientRect().top + global.pageYOffset - offset);
    }

    return waitForScrollEnd().then(function () {
      var focusEl = null;
      if (opts.focus) focusEl = typeof opts.focus === 'string' ? doc.querySelector(opts.focus) : opts.focus;
      if (!focusEl && opts.focusTarget) {
        if (!node.hasAttribute('tabindex')) node.setAttribute('tabindex', '-1');
        focusEl = node;
      }
      if (focusEl) safeFocus(focusEl);
      return true;
    });
  }

  /**
   * Menangani (1) tautan [data-scroll-to] dan (2) hash URL saat halaman dibuka.
   * Elemen target dapat memuat data-scroll-focus="#selector-input" untuk
   * memfokuskan field setelah scroll selesai.
   */
  function initHashScroll() {
    if (initHashScroll.done) return;
    initHashScroll.done = true;

    function runHash(fromLoad) {
      var id = '';
      try { id = decodeURIComponent((global.location.hash || '').slice(1)); } catch (e) { return; }
      if (!id) return;
      var node = doc.getElementById(id);
      if (!node) return;
      var go = function () {
        scrollToTarget(node, { focus: node.getAttribute('data-scroll-focus') });
      };
      if (fromLoad && !reducedMotion()) {
        // Mulai dari atas agar perpindahan terlihat halus setelah tiba dari halaman lain
        try { global.scrollTo({ top: 0, behavior: 'instant' }); } catch (e) { global.scrollTo(0, 0); }
        global.requestAnimationFrame(go);
      } else {
        go();
      }
    }

    if (doc.readyState === 'complete') setTimeout(function () { runHash(true); }, 150);
    else global.addEventListener('load', function () { setTimeout(function () { runHash(true); }, 250); });
    global.addEventListener('hashchange', function () { runHash(false); });

    doc.addEventListener('click', function (e) {
      var trigger = e.target.closest ? e.target.closest('[data-scroll-to]') : null;
      if (!trigger) return;
      var sel = trigger.getAttribute('data-scroll-to');
      var node = sel ? doc.querySelector(sel) : null;
      if (!node) return; // biarkan navigasi normal jika target tidak ada di halaman ini
      e.preventDefault();
      scrollToTarget(node, { focus: trigger.getAttribute('data-scroll-focus') || node.getAttribute('data-scroll-focus') });
      try { global.history.replaceState(null, '', sel); } catch (err) { /* abaikan */ }
    });
  }

  /* ── Validasi & bantuan form ────────────────────────────────────────────── */

  function clearFieldError(input) {
    if (!input) return;
    input.removeAttribute('aria-invalid');
    var errId = input.getAttribute('data-error-id');
    if (errId) {
      var node = doc.getElementById(errId);
      if (node) node.parentNode.removeChild(node);
      input.removeAttribute('data-error-id');
      input.removeAttribute('aria-describedby');
    }
  }

  function setFieldError(input, message) {
    if (!input) return;
    clearFieldError(input);
    if (!input.id) input.id = 'ui-field-' + (++uid);
    var errId = input.id + '-error';
    var node = el('div', { class: 'ui-field-error', id: errId, role: 'alert' }, [
      icon('error', ''),
      el('span', { text: message })
    ]);
    node.firstChild.style.cssText = 'display:inline-flex;width:.95rem;height:.95rem;flex-shrink:0;margin-top:.05rem';
    input.setAttribute('aria-invalid', 'true');
    input.setAttribute('data-error-id', errId);
    input.setAttribute('aria-describedby', errId);
    input.insertAdjacentElement('afterend', node);
  }

  function clearFormErrors(form) {
    Array.prototype.forEach.call(form.querySelectorAll('[aria-invalid="true"]'), clearFieldError);
    Array.prototype.forEach.call(form.querySelectorAll('.ui-field-error'), function (n) { n.parentNode.removeChild(n); });
  }

  /**
   * Menampilkan error validasi: tanda di tiap field + pop up ringkasan.
   * errors: [{ input, label, message }]
   */
  function showValidationErrors(form, errors, opts) {
    opts = opts || {};
    if (form) clearFormErrors(form);
    errors.forEach(function (e) { setFieldError(e.input, e.message); });
    var list = el('ul', { style: 'margin:.5rem 0 0;padding-left:1.1rem;list-style:disc;font-size:.8125rem' });
    errors.forEach(function (e) {
      list.appendChild(el('li', null, [el('strong', { text: e.label + ': ' }), e.message]));
    });
    var first = errors[0] && errors[0].input;
    return notice({
      icon: 'error',
      title: opts.title || 'Data belum lengkap',
      message: opts.message || 'Periksa kembali isian berikut:',
      content: list,
      buttonText: 'Perbaiki'
    }).then(function () { if (first) safeFocus(first); });
  }

  /** Validasi berkas: mengembalikan pesan error atau null jika lolos. */
  function validateFile(file, rules) {
    if (!file) return null;
    var maxBytes = rules.maxBytes || 5 * 1024 * 1024;
    var ext = (file.name.split('.').pop() || '').toLowerCase();
    var okExt = !rules.exts || rules.exts.indexOf(ext) > -1;
    var okMime = !rules.types || !file.type || rules.types.indexOf(file.type) > -1;
    if (!okExt || !okMime) return rules.typeMessage || 'Format berkas tidak didukung.';
    if (file.size > maxBytes) return rules.sizeMessage || 'Ukuran berkas terlalu besar.';
    return null;
  }

  /** Pelacak perubahan form untuk peringatan "belum disimpan". */
  function trackDirty(form, extraState) {
    function snapshot() {
      var pairs = [];
      new FormData(form).forEach(function (v, k) { pairs.push([k, typeof v === 'string' ? v : (v && v.name) || '']); });
      return JSON.stringify(pairs) + (extraState ? extraState() : '');
    }
    var base = snapshot();
    return {
      isDirty: function () { return snapshot() !== base; },
      reset: function () { base = snapshot(); }
    };
  }

  /**
   * Memberi perilaku dialog (ARIA, focus trap, Esc, klik overlay) pada modal
   * form lama yang memakai kelas `hidden` untuk buka/tutup.
   */
  function enhanceModal(root, opts) {
    if (!root || root.__uiEnhanced) return;
    root.__uiEnhanced = true;
    opts = opts || {};
    var card = opts.card || root.querySelector('div > div') || root;
    card.setAttribute('role', 'dialog');
    card.setAttribute('aria-modal', 'true');
    card.setAttribute('tabindex', '-1');
    if (opts.labelledBy) card.setAttribute('aria-labelledby', opts.labelledBy);

    var active = false;
    var prevFocus = null;

    function handleKey(e) {
      if (stack.length) return; // modal AswajaUI di atasnya yang menangani
      if (e.key === 'Escape') { e.preventDefault(); if (opts.onRequestClose) opts.onRequestClose('esc'); }
      else if (e.key === 'Tab') trapFocus(e, card);
    }
    function activate() {
      active = true;
      prevFocus = doc.activeElement;
      lockScroll();
      doc.addEventListener('keydown', handleKey, true);
      setTimeout(function () {
        var target = opts.initialFocus ? doc.querySelector(opts.initialFocus) : focusablesIn(card).filter(function (n) { return n.tagName !== 'BUTTON'; })[0];
        safeFocus(target || card);
      }, 30);
    }
    function deactivate() {
      active = false;
      unlockScroll();
      doc.removeEventListener('keydown', handleKey, true);
      if (prevFocus && doc.contains(prevFocus)) safeFocus(prevFocus);
    }
    function sync() {
      var open = !root.classList.contains('hidden');
      if (open && !active) activate();
      else if (!open && active) deactivate();
    }
    new MutationObserver(sync).observe(root, { attributes: true, attributeFilter: ['class'] });
    root.addEventListener('mousedown', function (e) {
      root.__downOnBackdrop = !card.contains(e.target);
    });
    root.addEventListener('click', function (e) {
      if (root.__downOnBackdrop && !card.contains(e.target) && opts.onRequestClose) opts.onRequestClose('overlay');
      root.__downOnBackdrop = false;
    });
    sync();
  }

  /* ── Editor teks kaya (aman) ────────────────────────────────────────────── */

  var RTE_ALLOWED = { P: 1, BR: 1, STRONG: 1, B: 1, EM: 1, I: 1, U: 1, H2: 1, H3: 1, UL: 1, OL: 1, LI: 1, BLOCKQUOTE: 1, A: 1, CODE: 1 };
  var RTE_DROP = { SCRIPT: 1, STYLE: 1, IFRAME: 1, OBJECT: 1, EMBED: 1, NOSCRIPT: 1, TEMPLATE: 1, LINK: 1, META: 1, SVG: 1, MATH: 1, FORM: 1, INPUT: 1, BUTTON: 1, TEXTAREA: 1, SELECT: 1 };

  /** Sanitasi HTML dengan daftar putih tag; semua atribut dibuang kecuali href aman. */
  function sanitizeHtml(html) {
    var parsed = new DOMParser().parseFromString('<body>' + String(html || '') + '</body>', 'text/html');
    var out = doc.createElement('div');
    (function walk(src, dest) {
      Array.prototype.forEach.call(src.childNodes, function (ch) {
        if (ch.nodeType === 3) { dest.appendChild(doc.createTextNode(ch.nodeValue)); return; }
        if (ch.nodeType !== 1) return;
        var tag = ch.tagName;
        if (RTE_DROP[tag]) return;
        var outTag = tag === 'DIV' ? 'P' : tag;
        if (!RTE_ALLOWED[outTag]) { walk(ch, dest); return; }
        var n = doc.createElement(outTag.toLowerCase());
        if (outTag === 'A') {
          var href = (ch.getAttribute('href') || '').trim();
          if (/^(https?:|mailto:)/i.test(href)) {
            n.setAttribute('href', href);
            n.setAttribute('rel', 'noopener noreferrer nofollow');
            n.setAttribute('target', '_blank');
          }
        }
        walk(ch, n);
        dest.appendChild(n);
      });
    })(parsed.body, out);
    return out.innerHTML;
  }

  function textToHtml(text) {
    var esc = String(text || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return esc.split(/\n{2,}/).filter(function (p) { return p.trim(); }).map(function (p) {
      return '<p>' + p.replace(/\n/g, '<br>') + '</p>';
    }).join('');
  }

  function richEditor(textarea, opts) {
    opts = opts || {};
    var area = el('div', {
      class: 'ui-rte__area',
      contenteditable: 'true',
      role: 'textbox',
      'aria-multiline': 'true',
      'aria-label': opts.label || 'Isi artikel',
      'data-placeholder': opts.placeholder || textarea.getAttribute('placeholder') || ''
    });
    var bar = el('div', { class: 'ui-rte__bar', role: 'toolbar', 'aria-label': 'Format teks' });
    var cmds = [
      { label: 'B', title: 'Tebal', run: function () { doc.execCommand('bold'); } },
      { label: 'I', title: 'Miring', run: function () { doc.execCommand('italic'); } },
      { label: 'H2', title: 'Judul bagian', run: function () { doc.execCommand('formatBlock', false, 'h2'); } },
      { label: 'H3', title: 'Sub judul', run: function () { doc.execCommand('formatBlock', false, 'h3'); } },
      { label: '• Daftar', title: 'Daftar poin', run: function () { doc.execCommand('insertUnorderedList'); } },
      { label: '1. Daftar', title: 'Daftar angka', run: function () { doc.execCommand('insertOrderedList'); } },
      { label: '“ Kutipan', title: 'Kutipan', run: function () { doc.execCommand('formatBlock', false, 'blockquote'); } },
      { label: 'Paragraf', title: 'Kembali ke paragraf biasa', run: function () { doc.execCommand('formatBlock', false, 'p'); } }
    ];
    cmds.forEach(function (c) {
      var b = el('button', { type: 'button', class: 'ui-rte__btn', title: c.title, 'aria-label': c.title, text: c.label });
      b.addEventListener('mousedown', function (e) { e.preventDefault(); }); // jaga seleksi teks
      b.addEventListener('click', function () { area.focus(); c.run(); sync(); });
      bar.appendChild(b);
    });
    var wrap = el('div', { class: 'ui-rte' }, [bar, area]);

    textarea.setAttribute('hidden', '');
    textarea.setAttribute('aria-hidden', 'true');
    textarea.tabIndex = -1;
    textarea.insertAdjacentElement('afterend', wrap);

    try { doc.execCommand('defaultParagraphSeparator', false, 'p'); } catch (e) { /* abaikan */ }

    function sync() {
      textarea.value = sanitizeHtml(area.innerHTML);
      textarea.dispatchEvent(new Event('input', { bubbles: true }));
    }
    area.addEventListener('input', sync);
    area.addEventListener('paste', function (e) {
      e.preventDefault();
      var text = (e.clipboardData || global.clipboardData).getData('text/plain');
      doc.execCommand('insertText', false, text);
    });

    var api = {
      root: area,
      setHTML: function (value) {
        var v = String(value || '');
        area.innerHTML = /<\/?[a-z][\s\S]*>/i.test(v) ? sanitizeHtml(v) : textToHtml(v);
        textarea.value = sanitizeHtml(area.innerHTML);
      },
      getHTML: function () { return sanitizeHtml(area.innerHTML); },
      getText: function () { return (area.textContent || '').trim(); },
      focus: function () { safeFocus(area); }
    };
    api.setHTML(textarea.value);
    return api;
  }

  /* ── Ekspor ─────────────────────────────────────────────────────────────── */

  global.AswajaUI = {
    version: '1.0.0',
    modal: { open: openModal },
    confirm: confirmFlow,
    notice: notice,
    alert: notice,
    success: function (title, message) { return notice({ icon: 'success', title: title, message: message, role: 'dialog' }); },
    error: function (title, message) { return notice({ icon: 'error', title: title, message: message }); },
    confirmDelete: confirmDelete,
    confirmDiscard: confirmDiscard,
    toast: toast,
    scrollTo: scrollToTarget,
    initHashScroll: initHashScroll,
    form: {
      setError: setFieldError,
      clearError: clearFieldError,
      clearErrors: clearFormErrors,
      showErrors: showValidationErrors,
      validateFile: validateFile,
      trackDirty: trackDirty
    },
    enhanceModal: enhanceModal,
    richEditor: richEditor,
    sanitizeHtml: sanitizeHtml,
    summary: summaryNode
  };

  // Aktifkan smooth scroll untuk [data-scroll-to] dan hash URL di semua halaman yang memuat berkas ini.
  initHashScroll();
})(window);
