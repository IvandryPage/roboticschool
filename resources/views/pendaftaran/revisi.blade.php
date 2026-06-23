{{-- Upload Ulang Dokumen - RoboNesia styled --}}

<style>
    /* ===== RoboNesia Design Tokens ===== */
    :root {
        --dark-bg:      #0f1923;
        --dark-surface: #162130;
        --cyan:         #38bdf8;
        --cyan-light:   #e0f4fd;
        --text-main:    #0f1923;
        --text-muted:   #6b7280;
        --text-light:   #9ca3af;
        --border:       #e5e7eb;
        --white:        #ffffff;
        --page-bg:      #f3f4f6;
        --radius-card:  1rem;
        --radius-input: 0.5rem;
        --shadow-card:  0 4px 24px rgba(0,0,0,0.08);
    }

    /* ===== Page Layout ===== */
    .revisi-page {
        min-height: 100vh;
        background-color: var(--page-bg);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* ===== Dark Hero Header (same as screenshot) ===== */
    .revisi-hero {
        background-color: var(--dark-bg);
        padding: 2.5rem 1.5rem 4rem;
        text-align: center;
    }

    .revisi-hero__badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 999px;
        padding: 0.3rem 0.9rem;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.7);
        margin-bottom: 1.25rem;
    }

    .revisi-hero__badge::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        border: 1.5px solid rgba(255,255,255,0.5);
    }

    .revisi-hero__title {
        color: var(--white);
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0 0 0.75rem;
    }

    .revisi-hero__sub {
        color: rgba(255,255,255,0.55);
        font-size: 0.95rem;
        line-height: 1.6;
        max-width: 420px;
        margin: 0 auto;
    }

    /* ===== Floating Card (same white card style) ===== */
    .revisi-card-wrap {
        max-width: 640px;
        margin: -2rem auto 2rem;
        padding: 0 1rem;
    }

    .revisi-card {
        background: var(--white);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        padding: 2rem;
    }

    /* ===== Status Banner (REVISI badge — matches screenshot) ===== */
    .revisi-banner {
        display: flex;
        align-items: center;
        gap: 1rem;
        background-color: var(--cyan-light);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1.75rem;
    }

    .revisi-banner__icon {
        width: 44px;
        height: 44px;
        background: var(--cyan);
        border-radius: 0.5rem;
        flex-shrink: 0;
    }

    .revisi-banner__label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--cyan);
        margin-bottom: 0.15rem;
    }

    .revisi-banner__title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.1rem;
    }

    .revisi-banner__desc {
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    /* ===== Form Elements ===== */
    .revisi-form__label {
        display: block;
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .revisi-form__dropzone {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        width: 100%;
        min-height: 130px;
        border: 1.5px dashed var(--border);
        border-radius: var(--radius-input);
        background: #fafafa;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        position: relative;
        box-sizing: border-box;
        padding: 1.5rem;
        text-align: center;
    }

    .revisi-form__dropzone:hover {
        border-color: var(--cyan);
        background: var(--cyan-light);
    }

    .revisi-form__dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .revisi-form__dropzone-icon {
        font-size: 1.5rem;
        line-height: 1;
        color: var(--text-light);
    }

    .revisi-form__dropzone-text {
        font-size: 0.88rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .revisi-form__dropzone-hint {
        font-size: 0.75rem;
        color: var(--text-light);
    }

    .revisi-form__filename {
        display: none;
        font-size: 0.8rem;
        color: var(--cyan);
        font-weight: 500;
        margin-top: 0.5rem;
    }

    /* ===== Submit Button (matches "Cek status sekarang" style) ===== */
    .revisi-form__submit {
        display: block;
        width: 100%;
        margin-top: 1.25rem;
        padding: 0.85rem;
        background: var(--white);
        color: var(--text-main);
        font-size: 0.95rem;
        font-weight: 500;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-input);
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
        letter-spacing: 0.01em;
    }

    .revisi-form__submit:hover {
        background: var(--dark-bg);
        border-color: var(--dark-bg);
        color: var(--white);
    }

    /* ===== Validation Error ===== */
    @if ($errors->has('dokumen'))
    .revisi-form__error {
        display: block;
    }
    @endif

    .revisi-form__error {
        font-size: 0.8rem;
        color: #ef4444;
        margin-top: 0.4rem;
    }
</style>

<div class="revisi-page">

    {{-- Dark hero header --}}
    <div class="revisi-hero">
        <div class="revisi-hero__badge">Portal peserta</div>
        <h1 class="revisi-hero__title">Upload Ulang Dokumen</h1>
        <p class="revisi-hero__sub">
            Silakan unggah ulang dokumen sesuai catatan admin untuk melanjutkan proses pendaftaranmu.
        </p>
    </div>

    {{-- Floating white card --}}
    <div class="revisi-card-wrap">
        <div class="revisi-card">

            {{-- REVISI status banner --}}
            <div class="revisi-banner">
                <div class="revisi-banner__icon"></div>
                <div>
                    <div class="revisi-banner__label">Revisi</div>
                    <div class="revisi-banner__title">{{ $pendaftaran->nama ?? 'Peserta' }}</div>
                    <div class="revisi-banner__desc">Tim kami sedang meninjau pendaftaranmu</div>
                </div>
            </div>

            {{-- Upload form --}}
            <form
                action="{{ route('pendaftaran.revisi.store', $pendaftaran->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div>
                    <label class="revisi-form__label">Dokumen terbaru</label>

                    <div class="revisi-form__dropzone" id="dropzone">
                        <input
                            type="file"
                            name="dokumen"
                            id="dokumenInput"
                            required
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            onchange="showFilename(this)"
                        >
                        <span class="revisi-form__dropzone-icon">📄</span>
                        <span class="revisi-form__dropzone-text">Klik untuk pilih file</span>
                        <span class="revisi-form__dropzone-hint">PDF, JPG, PNG, atau DOC — maks. 5 MB</span>
                    </div>

                    <span class="revisi-form__filename" id="filenameDisplay"></span>

                    @error('dokumen')
                        <span class="revisi-form__error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="revisi-form__submit">
                    Kirim Revisi
                </button>

            </form>

        </div>
    </div>

</div>

<script>
    function showFilename(input) {
        const display = document.getElementById('filenameDisplay');
        if (input.files && input.files[0]) {
            display.textContent = '✓ ' + input.files[0].name;
            display.style.display = 'block';
        }
    }
</script>