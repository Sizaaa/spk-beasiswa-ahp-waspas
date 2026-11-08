
{{-- 
    BEASISWA CERDAS
    KONFIRMASI GLOBAL FINAL

    Digunakan pada layout:
    - layouts.admin
    - layouts.utama

    Tanpa dialog confirm() bawaan browser
    untuk pola onclick dan onsubmit.
--}}

<style>
    /* ===================================
       VISIBILITAS
    =================================== */

    #bcc-overlay[hidden],
    #bcc-progress[hidden],
    .bcc-error[hidden] {
        display: none !important;
    }

    /* ===================================
       OVERLAY SELURUH LAYAR
    =================================== */

    #bcc-overlay {
        position: fixed !important;
        inset: 0 !important;

        width: 100vw !important;
        height: 100vh !important;
        height: 100dvh !important;

        z-index: 2147483000 !important;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 18px;
        box-sizing: border-box;

        background: rgba(8, 30, 24, .53);

        -webkit-backdrop-filter: blur(7px);
        backdrop-filter: blur(7px);

        overflow-y: auto;
        overscroll-behavior: contain;
    }

    /* ===================================
       KARTU KONFIRMASI
    =================================== */

    .bcc-dialog {
        width: min(100%, 470px);
        max-height: calc(100dvh - 36px);

        overflow-y: auto;

        padding: 32px 35px;

        background: #fff;

        border: 1px solid #dfebe5;
        border-radius: 24px;

        box-shadow:
            0 24px 70px rgba(10, 44, 33, .25);

        animation: bcc-appear .2s ease-out;
    }

    .bcc-accent {
        width: 57px;
        height: 4px;

        margin-bottom: 21px;

        border-radius: 8px;

        background: linear-gradient(
            90deg,
            #07986b,
            #68c8a2
        );
    }

    .bcc-dialog h2 {
        margin: 0;

        color: #0b493a;

        font-size: 25px;
        font-weight: 800;
        line-height: 1.28;
        letter-spacing: -.03em;
    }

    .bcc-dialog p {
        margin: 15px 0 0;

        color: #56646a;

        font-size: 15px;
        line-height: 1.75;

        white-space: pre-line;
    }

    /* ===================================
       TOMBOL
    =================================== */

    .bcc-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap-reverse;

        gap: 12px;
        margin-top: 28px;
    }

    .bcc-btn {
        min-height: 45px;

        padding: 10px 20px;

        border: 1px solid transparent;
        border-radius: 12px;

        cursor: pointer;

        font: inherit;
        font-size: 13px;
        font-weight: 750;

        transition:
            transform .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .bcc-btn:hover:not(:disabled) {
        transform: translateY(-2px);
    }

    .bcc-btn:active:not(:disabled) {
        transform: translateY(0) scale(.985);
    }

    .bcc-btn:focus-visible {
        outline: 3px solid #9ad9c0;
        outline-offset: 2px;
    }

    .bcc-cancel {
        color: #2e4144;
        background: #edf2f3;

        border-color: #dce6e8;
    }

    .bcc-cancel:hover {
        background: #e4edee;
    }

    .bcc-confirm {
        color: #fff;

        background: linear-gradient(
            115deg,
            #0b5c47,
            #119468
        );
    }

    .bcc-confirm:hover {
        box-shadow:
            0 9px 22px rgba(7, 107, 73, .22);
    }

    .bcc-confirm.is-warning {
        background: linear-gradient(
            115deg,
            #80540f,
            #aa7824
        );
    }

    .bcc-confirm.is-danger {
        background: linear-gradient(
            115deg,
            #9d3034,
            #c7474d
        );
    }

    /* ===================================
       VALIDASI
    =================================== */

    .bcc-error {
        display: block;
        margin-top: 7px;

        color: #b42b38;

        font-size: 13px;
        line-height: 1.6;
    }

    .bcc-invalid {
        outline: 2px solid
            rgba(213, 56, 70, .32) !important;

        outline-offset: 2px;
    }

    /* ===================================
       SKELETON SHIMMER
    =================================== */

    #bcc-progress {
        position: fixed !important;

        bottom: 18px !important;
        left: 50% !important;

        transform: translateX(-50%) !important;

        z-index: 2147483001 !important;

        width: min(
            980px,
            calc(100% - 36px)
        );

        padding: 20px 24px;

        border: 1px solid #dce9e4;
        border-radius: 17px;

        background: #fff;

        box-shadow:
            0 14px 40px rgba(9, 46, 36, .15);

        display: flex;
        align-items: center;

        gap: 22px;

        box-sizing: border-box;
    }

    .bcc-progress-label {
        white-space: nowrap;

        font-size: 14px;
        font-weight: 700;

        color: #173b31;
    }

    .bcc-lines {
        flex: 1;

        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 16px;
    }

    .bcc-line {
        height: 15px;

        border-radius: 20px;

        background: linear-gradient(
            100deg,
            #e1efe9 20%,
            #f9fffc 45%,
            #d9ece3 65%
        );

        background-size: 220% 100%;

        animation:
            bcc-shimmer
            1.3s
            ease-in-out
            infinite;
    }

    /* ===================================
       ANIMASI
    =================================== */

    @keyframes bcc-appear {
        from {
            opacity: 0;

            transform:
                translateY(12px)
                scale(.985);
        }

        to {
            opacity: 1;

            transform:
                translateY(0)
                scale(1);
        }
    }

    @keyframes bcc-shimmer {
        from {
            background-position: 110% 0;
        }

        to {
            background-position: -110% 0;
        }
    }

    /* ===================================
       RESPONSIVE
    =================================== */

    @media (max-width: 600px) {
        .bcc-dialog {
            padding: 26px 22px;
        }

        .bcc-dialog h2 {
            font-size: 23px;
        }

        #bcc-progress {
            display: block;
        }

        .bcc-lines {
            margin-top: 14px;
            gap: 8px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bcc-dialog,
        .bcc-line {
            animation: none;
        }

        .bcc-btn {
            transition: none;
        }

        .bcc-btn:hover {
            transform: none;
        }
    }
</style>

<!-- ===================================
     MODAL KONFIRMASI
=================================== -->

<div
    id="bcc-overlay"
    hidden

    role="dialog"
    aria-modal="true"

    aria-labelledby="bcc-title"
    aria-describedby="bcc-message"
>

    <div
        class="bcc-dialog"
        tabindex="-1"
    >

        <div class="bcc-accent"></div>

        <h2 id="bcc-title">
            Konfirmasi Tindakan
        </h2>

        <p id="bcc-message"></p>

        <div class="bcc-actions">

            <button
                id="bcc-batal"
                class="bcc-btn bcc-cancel"
                type="button"
            >
                Batal
            </button>

            <button
                id="bcc-ya"
                class="bcc-btn bcc-confirm"
                type="button"
            >
                Ya, Lanjutkan
            </button>

        </div>

    </div>

</div>

<!-- ===================================
     SHIMMER SAAT SUBMIT
=================================== -->

<div
    id="bcc-progress"
    hidden

    role="status"
    aria-live="polite"
    aria-busy="true"
>

    <span class="bcc-progress-label">
        Memproses permintaan...
    </span>

    <div
        class="bcc-lines"
        aria-hidden="true"
    >

        <div class="bcc-line"></div>
        <div class="bcc-line"></div>
        <div class="bcc-line"></div>

    </div>

</div>

<!-- ===================================
     JAVASCRIPT KONFIRMASI GLOBAL
=================================== -->

<script>
(() => {
    'use strict';

    // Cegah pemasangan script dua kali.

    if (window.__beasiswaConfirmFinalInstalled) {
        return;
    }

    window.__beasiswaConfirmFinalInstalled = true;

    // ===================================
    // ELEMEN UTAMA
    // ===================================

    const overlay =
        document.getElementById('bcc-overlay');

    const progress =
        document.getElementById('bcc-progress');

    const heading =
        document.getElementById('bcc-title');

    const messageBox =
        document.getElementById('bcc-message');

    const cancel =
        document.getElementById('bcc-batal');

    const agree =
        document.getElementById('bcc-ya');

    if (
        !overlay ||
        !progress ||
        !heading ||
        !messageBox ||
        !cancel ||
        !agree
    ) {
        return;
    }

    // PENTING:
    // Pindahkan modal ke body.
    //
    // Tujuannya agar modal tidak terpotong
    // oleh transform/overflow pada kartu.

    document.body.appendChild(overlay);

    document.body.appendChild(progress);

    let pending = null;
    let replaying = false;
    let submitting = false;

    let lastFocus = null;
    let oldOverflow = '';

    // ===================================
    // DETEKSI confirm() LAMA
    // ===================================

    function extractConfirm(source) {

        if (typeof source !== 'string') {
            return null;
        }

        const match = source.match(
            /(?:window\.)?confirm\s*\(\s*(['"`])((?:\\.|(?!\1)[\s\S])*)\1\s*\)/i
        );

        if (!match) {
            return null;
        }

        return match[2].replace(
            /\\(['"`\\])/g,
            '$1'
        );
    }

    // ===================================
    // JUDUL DAN PESAN MODAL
    // ===================================

    function describe(text, submitter) {

        const lower = text.toLowerCase();

        const action =
            submitter?.name === 'keputusan'
                ? submitter.value
                : '';

        // VERIFIKASI

        if (action === 'terverifikasi') {

            return [
                'Konfirmasi Verifikasi',

                'Pastikan data dan dokumen sudah diperiksa serta sesuai. Pengajuan ini akan berstatus Terverifikasi.',

                'Ya, Verifikasi',

                ''
            ];
        }

        // MINTA PERBAIKAN

        if (action === 'perlu_perbaikan') {

            return [
                'Konfirmasi Perbaikan',

                'Catatan untuk User akan disampaikan agar pengajuan dapat diperbaiki.',

                'Ya, Minta Perbaikan',

                'is-warning'
            ];
        }

        // TOLAK

        if (action === 'ditolak') {

            return [
                'Konfirmasi Penolakan',

                'Pastikan alasan penolakan sudah benar sebelum menyimpan keputusan.',

                'Ya, Tolak',

                'is-danger'
            ];
        }

        // MASUKKAN KE SELEKSI

        if (
            /masukkan.*seleksi|alternatif waspas/.test(
                lower
            )
        ) {

            return [
                'Konfirmasi Masuk Seleksi',

                'Masukkan pengajuan terverifikasi ini ke data alternatif WASPAS? Data yang sudah masuk tidak akan dibuat dua kali.',

                'Ya, Masukkan',

                ''
            ];
        }

        // HAPUS

        if (
            /hapus|menghapus|delete/.test(lower)
        ) {

            return [
                'Konfirmasi Penghapusan',
                text,
                'Ya, Hapus',
                'is-danger'
            ];
        }

        // RESET

        if (/reset/.test(lower)) {

            return [
                'Konfirmasi Reset',
                text,
                'Ya, Reset',
                'is-warning'
            ];
        }

        // SIMPAN RIWAYAT

        if (
            /riwayat|simpan.*perhitungan|simpan.*salinan/.test(
                lower
            )
        ) {

            return [
                'Konfirmasi Penyimpanan',
                text,
                'Ya, Simpan',
                ''
            ];
        }

        // KIRIM PENDAFTARAN

        if (
            /kirim.*(pendaftaran|pengajuan)/.test(
                lower
            )
        ) {

            return [
                'Konfirmasi Pengajuan',
                text,
                'Ya, Kirim Pengajuan',
                ''
            ];
        }

        // TINDAKAN UMUM

        return [
            'Konfirmasi Tindakan',
            text,
            'Ya, Lanjutkan',
            ''
        ];
    }

    // ===================================
    // VALIDASI DI DALAM HALAMAN
    // ===================================

    function inlineError(field, text) {

        if (!field) {
            return true;
        }

        field.classList.add('bcc-invalid');

        field.setAttribute(
            'aria-invalid',
            'true'
        );

        const id = 'bcc-error-' + (
            field.name ||
            field.id ||
            'input'
        );

        let note =
            document.getElementById(id);

        if (!note) {

            note = document.createElement('p');

            note.id = id;
            note.className = 'bcc-error';

            note.setAttribute(
                'role',
                'alert'
            );

            const container =
                field.closest('label') || field;

            container.insertAdjacentElement(
                'afterend',
                note
            );

            field.addEventListener(
                field.type === 'checkbox'
                    ? 'change'
                    : 'input',

                () => {

                    field.classList.remove(
                        'bcc-invalid'
                    );

                    field.removeAttribute(
                        'aria-invalid'
                    );

                    note.hidden = true;
                }
            );
        }

        note.textContent = text;
        note.hidden = false;

        field.focus();

        field.scrollIntoView({
            block: 'center',
            behavior: 'smooth'
        });

        return false;
    }

    // ===================================
    // VALIDASI KEPUTUSAN ADMIN
    // ===================================

    function checkDecision(submitter) {

        if (
            !submitter ||
            submitter.name !== 'keputusan' ||
            !submitter.form
        ) {
            return true;
        }

        const form = submitter.form;

        const approval = form.querySelector(
            '[name="sudah_periksa"]'
        );

        const notes = form.querySelector(
            '[name="catatan_admin"]'
        );

        if (
            submitter.value === 'terverifikasi' &&
            approval &&
            !approval.checked
        ) {

            return inlineError(
                approval,
                'Centang pernyataan pemeriksaan terlebih dahulu.'
            );
        }

        if (
            [
                'perlu_perbaikan',
                'ditolak'
            ].includes(submitter.value) &&
            notes &&
            notes.value.trim().length < 10
        ) {

            return inlineError(
                notes,
                'Tulis alasan minimal 10 karakter.'
            );
        }

        return true;
    }

    // ===================================
    // MEMBUKA MODAL
    // ===================================

    function open(job, text, submitter) {

        if (pending || submitting) {
            return;
        }

        if (!checkDecision(submitter)) {
            return;
        }

        pending = job;

        lastFocus =
            document.activeElement;

        oldOverflow =
            document.body.style.overflow;

        const [
            title,
            description,
            button,
            tone
        ] = describe(text, submitter);

        heading.textContent = title;

        messageBox.textContent = description;

        agree.textContent = button;

        agree.className =
            'bcc-btn bcc-confirm' +
            (tone ? ' ' + tone : '');

        document.body.style.overflow =
            'hidden';

        overlay.hidden = false;

        cancel.focus();
    }

    // ===================================
    // MENUTUP MODAL
    // ===================================

    function close() {

        overlay.hidden = true;

        document.body.style.overflow =
            oldOverflow;

        pending = null;

        lastFocus?.focus();
    }

    // ===================================
    // TANGKAP onclick confirm()
    // ===================================

    document.addEventListener(
        'click',

        event => {

            if (
                replaying ||
                pending ||
                submitting
            ) {
                return;
            }

            const target =
                event.target instanceof Element

                    ? event.target.closest(
                        'button, input[type="submit"], input[type="button"], a'
                    )

                    : null;

            if (
                !target ||
                overlay.contains(target)
            ) {
                return;
            }

            const text =
                target.getAttribute(
                    'data-bc-confirm'
                ) ||

                extractConfirm(
                    target.getAttribute(
                        'onclick'
                    )
                );

            if (!text) {
                return;
            }

            const form = target.form || null;

            // Pertahankan validasi HTML
            // jika formulir memilikinya.

            if (
                form &&
                !form.checkValidity()
            ) {

                form.reportValidity();

                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            open(
                {
                    kind: 'click',
                    target,
                    form
                },

                text,

                target
            );
        },

        true
    );

    // ===================================
    // TANGKAP onsubmit confirm()
    // ===================================

    document.addEventListener(
        'submit',

        event => {

            if (
                replaying ||
                pending ||
                submitting
            ) {
                return;
            }

            const form = event.target;

            const text =
                form.getAttribute(
                    'data-bc-confirm'
                ) ||

                extractConfirm(
                    form.getAttribute(
                        'onsubmit'
                    )
                );

            if (!text) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            open(
                {
                    kind: 'submit',
                    form,
                    submitter: event.submitter
                },

                text,

                event.submitter
            );
        },

        true
    );

    // ===================================
    // TOMBOL BATAL
    // ===================================

    cancel.addEventListener(
        'click',
        close
    );

    // Klik area gelap.

    overlay.addEventListener(
        'click',

        event => {

            if (event.target === overlay) {
                close();
            }
        }
    );

    // ===================================
    // KEYBOARD
    // ===================================

    document.addEventListener(
        'keydown',

        event => {

            if (overlay.hidden) {
                return;
            }

            if (event.key === 'Escape') {

                event.preventDefault();

                close();

                return;
            }

            if (event.key !== 'Tab') {
                return;
            }

            if (
                event.shiftKey &&
                document.activeElement === cancel
            ) {

                event.preventDefault();

                agree.focus();

            } else if (
                !event.shiftKey &&
                document.activeElement === agree
            ) {

                event.preventDefault();

                cancel.focus();
            }
        }
    );

    // ===================================
    // SETUJU DAN LANJUTKAN TINDAKAN
    // ===================================

    agree.addEventListener(
        'click',

        () => {

            if (!pending || submitting) {
                return;
            }

            const job = pending;

            close();

            // Jalankan kembali tindakan asli.
            //
            // Untuk mencegah confirm() browser
            // muncul kedua kali, confirm()
            // dikembalikan true hanya selama
            // pengulangan sinkron ini.

            const originalConfirm =
                window.confirm;

            let observedSubmit = null;

            const observedForm = job.form;

            const observe = e => {

                if (
                    e.target === observedForm
                ) {
                    observedSubmit = e;
                }
            };

            document.addEventListener(
                'submit',
                observe,
                true
            );

            try {

                replaying = true;

                window.confirm = () => true;

                if (job.kind === 'click') {

                    // Klik tombol yang sama.
                    // Atribut name=value terjaga.

                    job.target.click();

                } else if (
                    job.submitter &&
                    job.submitter.form === job.form
                ) {

                    // Submit melalui tombol asli.

                    job.form.requestSubmit(
                        job.submitter
                    );

                } else {

                    job.form.requestSubmit();
                }

            } finally {

                window.confirm =
                    originalConfirm;

                replaying = false;

                document.removeEventListener(
                    'submit',
                    observe,
                    true
                );
            }

            // Shimmer hanya muncul saat
            // submit tidak dibatalkan
            // oleh validasi lain.

            if (observedSubmit) {

                queueMicrotask(() => {

                    if (
                        !observedSubmit.defaultPrevented
                    ) {

                        submitting = true;

                        progress.hidden = false;
                    }
                });
            }
        }
    );

    // ===================================
    // MEMULIHKAN HALAMAN DARI CACHE
    // ===================================

    window.addEventListener(
        'pageshow',

        event => {

            if (!event.persisted) {
                return;
            }

            submitting = false;
            pending = null;

            overlay.hidden = true;
            progress.hidden = true;

            document.body.style.overflow =
                oldOverflow;
        }
    );

})();
</script>
