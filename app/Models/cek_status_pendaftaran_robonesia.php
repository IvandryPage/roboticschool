
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

  .robonesia-wrap { font-family: 'Plus Jakarta Sans', sans-serif; padding: 0; }

  .rbn-header {
    background: #0F172A;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
  }
  .rbn-logo { color: #fff; font-weight: 700; font-size: 17px; letter-spacing: -0.3px; }
  .rbn-logo span { color: #00BCD4; }
  .rbn-nav-link { font-size: 13px; color: #94A3B8; cursor: pointer; }
  .rbn-nav-link:hover { color: #00BCD4; }

  .rbn-hero {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    padding: 40px 32px 32px;
    text-align: center;
  }
  .rbn-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(0,188,212,0.12);
    color: #00BCD4;
    font-size: 12px; font-weight: 500;
    padding: 4px 12px; border-radius: 20px;
    margin-bottom: 14px;
    border: 0.5px solid rgba(0,188,212,0.3);
  }
  .rbn-hero h1 {
    font-size: 26px; font-weight: 700; color: #fff;
    margin: 0 0 8px; letter-spacing: -0.5px;
  }
  .rbn-hero p { font-size: 14px; color: #94A3B8; margin: 0; }

  .rbn-search-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-lg);
    padding: 24px;
    margin: 24px 32px;
  }
  .rbn-tabs {
    display: flex; gap: 4px;
    background: var(--color-background-secondary);
    border-radius: var(--border-radius-md);
    padding: 4px; margin-bottom: 20px;
  }
  .rbn-tab {
    flex: 1; padding: 8px 16px; border-radius: 6px;
    font-size: 13px; font-weight: 500; cursor: pointer;
    border: none; background: transparent;
    color: var(--color-text-secondary);
    transition: background 0.15s, color 0.15s;
  }
  .rbn-tab.active {
    background: #00BCD4;
    color: #fff;
  }
  .rbn-field { margin-bottom: 16px; }
  .rbn-label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); margin-bottom: 6px; display: block; }
  .rbn-input-wrap { position: relative; }
  .rbn-input {
    width: 100%; box-sizing: border-box;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    padding: 10px 14px 10px 38px;
    font-size: 14px; font-family: inherit;
    background: var(--color-background-primary);
    color: var(--color-text-primary);
    outline: none;
  }
  .rbn-input:focus { border-color: #00BCD4; box-shadow: 0 0 0 3px rgba(0,188,212,0.12); }
  .rbn-input-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--color-text-tertiary); font-size: 16px; pointer-events: none;
  }
  .rbn-btn-primary {
    width: 100%; padding: 11px; border-radius: var(--border-radius-md);
    background: #00BCD4; color: #fff;
    border: none; font-size: 14px; font-weight: 600;
    font-family: inherit; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
  }
  .rbn-btn-primary:hover { background: #00ACC1; }
  .rbn-btn-primary:disabled { background: #94A3B8; cursor: not-allowed; }

  .rbn-result { margin: 0 32px 24px; display: none; }

  .rbn-status-banner {
    border-radius: var(--border-radius-lg);
    padding: 16px 20px;
    margin-bottom: 16px;
    display: flex; align-items: center; gap: 14px;
  }
  .rbn-status-banner.menunggu { background: #FFF8E1; border: 0.5px solid #FFD54F; }
  .rbn-status-banner.diproses { background: #E0F7FA; border: 0.5px solid #80DEEA; }
  .rbn-status-banner.diterima { background: #E8F5E9; border: 0.5px solid #A5D6A7; }
  .rbn-status-banner.ditolak  { background: #FFEBEE; border: 0.5px solid #EF9A9A; }

  .rbn-status-icon {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .menunggu .rbn-status-icon { background: #FFE082; color: #F57F17; }
  .diproses .rbn-status-icon { background: #80DEEA; color: #00697A; }
  .diterima .rbn-status-icon { background: #A5D6A7; color: #1B5E20; }
  .ditolak  .rbn-status-icon { background: #EF9A9A; color: #B71C1C; }

  .rbn-status-label { font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px; }
  .menunggu .rbn-status-label { color: #F9A825; }
  .diproses .rbn-status-label { color: #0097A7; }
  .diterima .rbn-status-label { color: #388E3C; }
  .ditolak  .rbn-status-label { color: #C62828; }

  .rbn-status-name { font-size: 16px; font-weight: 700; color: var(--color-text-primary); }
  .rbn-status-sub  { font-size: 12px; color: var(--color-text-secondary); margin-top: 1px; }

  .rbn-detail-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;
  }
  .rbn-detail-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-md);
    padding: 12px 14px;
  }
  .rbn-detail-card .lbl { font-size: 11px; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 4px; }
  .rbn-detail-card .val { font-size: 13px; font-weight: 600; color: var(--color-text-primary); }

  .rbn-timeline { margin-bottom: 16px; }
  .rbn-timeline-title { font-size: 13px; font-weight: 600; color: var(--color-text-primary); margin-bottom: 12px; }
  .rbn-timeline-items { position: relative; padding-left: 20px; }
  .rbn-timeline-items::before {
    content: ''; position: absolute; left: 7px; top: 6px; bottom: 6px;
    width: 1.5px; background: var(--color-border-tertiary);
  }
  .rbn-tl-item { position: relative; margin-bottom: 14px; padding-left: 16px; }
  .rbn-tl-dot {
    position: absolute; left: -13px; top: 3px;
    width: 10px; height: 10px; border-radius: 50%;
    background: #00BCD4; border: 2px solid var(--color-background-primary);
    box-shadow: 0 0 0 1.5px #00BCD4;
  }
  .rbn-tl-dot.pending { background: #E2E8F0; box-shadow: 0 0 0 1.5px #CBD5E1; }
  .rbn-tl-item-title { font-size: 13px; font-weight: 600; color: var(--color-text-primary); }
  .rbn-tl-item-time  { font-size: 11px; color: var(--color-text-secondary); margin-top: 1px; }

  .rbn-admin-note {
    background: #E0F7FA;
    border: 0.5px solid #80DEEA;
    border-left: 3px solid #00BCD4;
    border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0;
    padding: 14px 16px;
    margin-bottom: 16px;
  }
  .rbn-admin-note .note-header { display: flex; align-items: center; gap: 6px; margin-bottom: 6px; }
  .rbn-admin-note .note-label { font-size: 11px; font-weight: 600; color: #0097A7; text-transform: uppercase; letter-spacing: 0.5px; }
  .rbn-admin-note .note-text { font-size: 13px; color: #004D52; line-height: 1.6; }

  .rbn-action-row { display: flex; gap: 10px; }
  .rbn-btn-sec {
    flex: 1; padding: 10px; border-radius: var(--border-radius-md);
    background: transparent; color: var(--color-text-secondary);
    border: 0.5px solid var(--color-border-secondary);
    font-size: 13px; font-weight: 500;
    font-family: inherit; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 6px;
  }
  .rbn-btn-sec:hover { background: var(--color-background-secondary); }

  .rbn-empty {
    text-align: center; padding: 32px 16px;
    display: none;
  }
  .rbn-empty-icon { font-size: 40px; color: var(--color-text-tertiary); margin-bottom: 10px; }
  .rbn-empty-title { font-size: 15px; font-weight: 600; color: var(--color-text-primary); margin-bottom: 6px; }
  .rbn-empty-sub { font-size: 13px; color: var(--color-text-secondary); }

  .spinner { display: none; width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }

  .rbn-footer {
    border-top: 0.5px solid var(--color-border-tertiary);
    padding: 12px 32px;
    display: flex; align-items: center; justify-content: space-between;
    border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
  }
  .rbn-footer-text { font-size: 12px; color: var(--color-text-secondary); }
  .rbn-footer-link { font-size: 12px; color: #00BCD4; cursor: pointer; font-weight: 500; }
</style>

<div class="robonesia-wrap">
  <h2 class="sr-only">Halaman cek status pendaftaran RoboNesia — masukkan nomor referensi atau email untuk melihat status pendaftaran kursus Anda.</h2>

  <div class="rbn-header">
    <div class="rbn-logo">Robo<span>Nesia</span></div>
    <span class="rbn-nav-link"><i class="ti ti-arrow-left" aria-hidden="true"></i> Kembali ke beranda</span>
  </div>

  <div class="rbn-hero">
    <div class="rbn-hero-eyebrow"><i class="ti ti-clipboard-check" aria-hidden="true"></i> Portal peserta</div>
    <h1>Cek status pendaftaran</h1>
    <p>Masukkan nomor referensi atau email terdaftar untuk melihat perkembangan pendaftaran kamu.</p>
  </div>

  <div class="rbn-search-card">
    <div class="rbn-tabs">
      <button class="rbn-tab active" id="tab-ref" onclick="switchTab('ref')"><i class="ti ti-ticket" aria-hidden="true"></i> Nomor referensi</button>
      <button class="rbn-tab" id="tab-email" onclick="switchTab('email')"><i class="ti ti-mail" aria-hidden="true"></i> Email</button>
    </div>

    <div id="panel-ref">
      <div class="rbn-field">
        <label class="rbn-label" for="input-ref">Nomor referensi</label>
        <div class="rbn-input-wrap">
          <i class="ti ti-ticket rbn-input-icon" aria-hidden="true"></i>
          <input class="rbn-input" id="input-ref" type="text" placeholder="Contoh: RBN-2026-00421" />
        </div>
      </div>
    </div>

    <div id="panel-email" style="display:none">
      <div class="rbn-field">
        <label class="rbn-label" for="input-email">Alamat email</label>
        <div class="rbn-input-wrap">
          <i class="ti ti-mail rbn-input-icon" aria-hidden="true"></i>
          <input class="rbn-input" id="input-email" type="email" placeholder="Contoh: budi@email.com" />
        </div>
      </div>
    </div>

    <button class="rbn-btn-primary" id="btn-cek" onclick="cekStatus()">
      <span id="btn-label"><i class="ti ti-search" aria-hidden="true"></i> Cek status sekarang</span>
      <div class="spinner" id="spinner"></div>
    </button>
  </div>

  <div class="rbn-result" id="result-area"></div>
  <div class="rbn-empty" id="empty-area">
    <div class="rbn-empty-icon"><i class="ti ti-file-search" aria-hidden="true"></i></div>
    <div class="rbn-empty-title">Data tidak ditemukan</div>
    <div class="rbn-empty-sub">Periksa kembali nomor referensi atau email yang kamu masukkan,<br>lalu coba lagi.</div>
  </div>

  <div class="rbn-footer">
    <span class="rbn-footer-text"><i class="ti ti-lock" aria-hidden="true" style="vertical-align:-2px; margin-right:4px"></i>Data kamu aman dan terenkripsi</span>
    <span class="rbn-footer-link" onclick="sendPrompt('Saya butuh bantuan mendaftar program kursus RoboNesia')">Butuh bantuan? ↗</span>
  </div>
</div>

<script>
const mockData = {
  'RBN-2026-00421': {
    nama: 'Budi Santoso',
    program: 'Arduino Basic',
    level: 'Pemula',
    biaya: 'Rp 450.000',
    tanggal_daftar: '10 Juni 2026',
    status: 'diproses',
    statusLabel: 'Sedang diproses',
    statusSub: 'Tim kami sedang meninjau pendaftaranmu',
    timeline: [
      { title: 'Pendaftaran diterima', time: '10 Jun 2026, 09.14', done: true },
      { title: 'Dokumen diverifikasi', time: '11 Jun 2026, 14.32', done: true },
      { title: 'Menunggu konfirmasi pembayaran', time: '12 Jun 2026, 10.05', done: true },
      { title: 'Pendaftaran dikonfirmasi', time: 'Dalam proses', done: false },
    ],
    catatan: 'Halo Budi! Pembayaran kamu sudah kami terima. Tinggal menunggu konfirmasi dari tim pelatih. Kamu akan mendapat notifikasi via email dalam 1–2 hari kerja.'
  },
  'RBN-2026-00389': {
    nama: 'Siti Rahayu',
    program: 'IoT Development',
    level: 'Menengah',
    biaya: 'Rp 2.450.000',
    tanggal_daftar: '2 Juni 2026',
    status: 'diterima',
    statusLabel: 'Pendaftaran diterima',
    statusSub: 'Selamat! Kamu resmi menjadi peserta',
    timeline: [
      { title: 'Pendaftaran diterima', time: '2 Jun 2026, 08.50', done: true },
      { title: 'Dokumen diverifikasi', time: '3 Jun 2026, 11.20', done: true },
      { title: 'Pembayaran dikonfirmasi', time: '4 Jun 2026, 09.15', done: true },
      { title: 'Pendaftaran dikonfirmasi', time: '5 Jun 2026, 13.00', done: true },
    ],
    catatan: 'Selamat datang, Siti! Kelas IoT Development akan dimulai pada 1 Juli 2026 pukul 09.00 WIB via Zoom. Link meeting akan dikirim ke email kamu H-1 sebelum kelas dimulai.'
  },
  'RBN-2026-00355': {
    nama: 'Ahmad Fauzi',
    program: 'Advanced Robotics',
    level: 'Lanjutan',
    biaya: 'Rp 5.000.000',
    tanggal_daftar: '28 Mei 2026',
    status: 'ditolak',
    statusLabel: 'Tidak dapat diproses',
    statusSub: 'Terdapat kendala pada pendaftaranmu',
    timeline: [
      { title: 'Pendaftaran diterima', time: '28 Mei 2026, 15.30', done: true },
      { title: 'Dokumen diverifikasi', time: '29 Mei 2026, 10.45', done: true },
      { title: 'Ditolak – persyaratan tidak terpenuhi', time: '30 Mei 2026, 09.00', done: true },
    ],
    catatan: 'Halo Ahmad, sayang sekali kami belum dapat memproses pendaftaranmu untuk kelas Advanced Robotics karena prasyarat level Menengah belum terpenuhi. Kami menyarankan kamu mendaftar dulu ke kelas Robotics for Kids atau Arduino Basic. Hubungi kami jika butuh panduan lebih lanjut.'
  },
  'budi@email.com': { ref: 'RBN-2026-00421' },
  'siti@email.com': { ref: 'RBN-2026-00389' },
  'ahmad@email.com': { ref: 'RBN-2026-00355' },
};

const statusIcons = {
  menunggu: 'ti-clock',
  diproses: 'ti-loader-2',
  diterima: 'ti-circle-check',
  ditolak:  'ti-circle-x',
};

let activeTab = 'ref';

function switchTab(tab) {
  activeTab = tab;
  document.getElementById('tab-ref').classList.toggle('active', tab === 'ref');
  document.getElementById('tab-email').classList.toggle('active', tab === 'email');
  document.getElementById('panel-ref').style.display = tab === 'ref' ? '' : 'none';
  document.getElementById('panel-email').style.display = tab === 'email' ? '' : 'none';
  document.getElementById('result-area').style.display = 'none';
  document.getElementById('empty-area').style.display = 'none';
}

function cekStatus() {
  const btn = document.getElementById('btn-cek');
  const label = document.getElementById('btn-label');
  const spin = document.getElementById('spinner');
  label.style.display = 'none'; spin.style.display = 'block'; btn.disabled = true;

  setTimeout(() => {
    label.style.display = ''; spin.style.display = 'none'; btn.disabled = false;
    const key = activeTab === 'ref'
      ? (document.getElementById('input-ref').value || '').trim().toUpperCase()
      : (document.getElementById('input-email').value || '').trim().toLowerCase();

    let d = mockData[key];
    if (d && d.ref) d = mockData[d.ref];

    document.getElementById('empty-area').style.display = 'none';
    document.getElementById('result-area').style.display = 'none';

    if (!d || !d.nama) {
      document.getElementById('empty-area').style.display = 'block';
      return;
    }
    renderResult(d);
  }, 900);
}

function renderResult(d) {
  const tlHtml = d.timeline.map(t => `
    <div class="rbn-tl-item">
      <div class="rbn-tl-dot ${t.done ? '' : 'pending'}"></div>
      <div class="rbn-tl-item-title" style="color:${t.done ? 'var(--color-text-primary)' : 'var(--color-text-secondary)'}">${t.title}</div>
      <div class="rbn-tl-item-time">${t.time}</div>
    </div>
  `).join('');

  const area = document.getElementById('result-area');
  area.innerHTML = `
    <div class="rbn-status-banner ${d.status}">
      <div class="rbn-status-icon"><i class="ti ${statusIcons[d.status]}" aria-hidden="true"></i></div>
      <div>
        <div class="rbn-status-label">${d.statusLabel}</div>
        <div class="rbn-status-name">${d.nama}</div>
        <div class="rbn-status-sub">${d.statusSub}</div>
      </div>
    </div>

    <div class="rbn-detail-grid">
      <div class="rbn-detail-card"><div class="lbl"><i class="ti ti-book" aria-hidden="true"></i> Program</div><div class="val">${d.program}</div></div>
      <div class="rbn-detail-card"><div class="lbl"><i class="ti ti-chart-bar" aria-hidden="true"></i> Level</div><div class="val">${d.level}</div></div>
      <div class="rbn-detail-card"><div class="lbl"><i class="ti ti-wallet" aria-hidden="true"></i> Biaya</div><div class="val">${d.biaya}</div></div>
      <div class="rbn-detail-card"><div class="lbl"><i class="ti ti-calendar" aria-hidden="true"></i> Tanggal daftar</div><div class="val">${d.tanggal_daftar}</div></div>
    </div>

    <div class="rbn-admin-note">
      <div class="note-header"><i class="ti ti-message-circle" aria-hidden="true" style="color:#0097A7;font-size:15px"></i><span class="note-label">Catatan dari admin</span></div>
      <div class="note-text">${d.catatan}</div>
    </div>

    <div class="rbn-timeline">
      <div class="rbn-timeline-title"><i class="ti ti-timeline" aria-hidden="true"></i> Riwayat proses</div>
      <div class="rbn-timeline-items">${tlHtml}</div>
    </div>

    <div class="rbn-action-row">
      <button class="rbn-btn-sec" onclick="sendPrompt('Saya ingin bertanya tentang status pendaftaran saya untuk program ${d.program}')"><i class="ti ti-message-question" aria-hidden="true"></i> Tanya admin ↗</button>
      <button class="rbn-btn-sec" onclick="sendPrompt('Bantu saya mendaftar ulang atau pilih program kursus lain di RoboNesia')"><i class="ti ti-refresh" aria-hidden="true"></i> Daftar program lain ↗</button>
    </div>
  `;
  area.style.display = 'block';
}
</script>
