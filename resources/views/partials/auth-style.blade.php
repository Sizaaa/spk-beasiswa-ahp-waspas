
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap');

/*
|--------------------------------------------------------------------------
| BEASISWA CERDAS — AUTH STYLE
|--------------------------------------------------------------------------
| Berlaku hanya pada Login dan Lupa Kata Sandi.
| Tidak mengubah halaman Admin, User, atau Beranda.
*/

body > footer {
    display: none !important;
}

.bc-auth-topbar,
.bc-auth-page {
    font-family: "DM Sans", "Segoe UI", Arial, sans-serif;
}

.bc-auth-topbar {
    background: #fff;
    border-bottom: 1px solid #e1e8e3;
}

.bc-auth-topbar-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 17px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.bc-auth-brand {
    color: #103f36;
    font-size: 19px;
    font-weight: 800;
    letter-spacing: -0.035em;
    text-decoration: none;
    white-space: nowrap;
}

.bc-auth-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 9px 13px;
    border: 1px solid #cbded0;
    border-radius: 8px;
    color: #116759;
    background: #fff;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: background-color .15s ease;
}

.bc-auth-back:hover {
    background: #f1f6f2;
}

/* Area utama */

.bc-auth-page {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 32px 20px 44px;
    background: #f6f7f5;
    color: #26342d;
}

.bc-auth-card {
    display: grid;
    grid-template-columns: minmax(0, .86fr) minmax(0, 1.14fr);
    width: 100%;
    max-width: 980px;
    min-width: 0;
    overflow: hidden;
    border: 1px solid #dce5de;
    border-radius: 13px;
    background: #fff;
    box-shadow: 0 3px 12px rgba(16, 63, 54, .045);
}

/* Panel kiri */

.bc-auth-side {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 26px;
    min-width: 0;
    padding: 35px 34px;
    background: #103f36;
    color: #fff;
}

.bc-auth-kicker {
    color: #d8eae0;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .07em;
    text-transform: uppercase;
}

.bc-auth-side-title {
    max-width: 320px;
    margin-top: 21px;
    color: #fff;
    font-size: clamp(26px, 2.6vw, 31px);
    font-weight: 800;
    line-height: 1.25;
    letter-spacing: -.045em;
}

.bc-auth-side-desc {
    max-width: 340px;
    margin-top: 15px;
    color: #d7e8de;
    font-size: 12.5px;
    line-height: 1.8;
}

.bc-auth-side-list {
    display: grid;
    gap: 0;
}

.bc-auth-side-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 13px 0;
    border-bottom: 1px solid rgba(255, 255, 255, .13);
}

.bc-auth-side-item:last-child {
    border-bottom: 0;
}

.bc-auth-side-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    border: 1px solid rgba(255, 255, 255, .24);
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
}

.bc-auth-side-item-title {
    color: #fff;
    font-size: 12px;
    font-weight: 700;
}

.bc-auth-side-item-desc {
    margin-top: 4px;
    color: #d1e3d7;
    font-size: 11.5px;
    line-height: 1.65;
}

.bc-auth-side-footer {
    padding-top: 8px;
    color: #c7dfd0;
    font-size: 10.5px;
    line-height: 1.7;
}

/* Panel formulir */

.bc-auth-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
    padding: 36px 42px;
    background: #fff;
}

.bc-auth-logo {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border-radius: 10px;
    background: #e9f3ee;
    color: #103f36;
    font-size: 16px;
    font-weight: 800;
}

.bc-auth-eyebrow {
    margin-top: 21px;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #116759;
}

.bc-auth-title {
    margin-top: 15px;
    color: #103f36;
    font-size: clamp(25px, 2.5vw, 29px);
    font-weight: 800;
    line-height: 1.3;
    letter-spacing: -.045em;
}

.bc-auth-eyebrow + .bc-auth-title {
    margin-top: 7px;
}

.bc-auth-subtitle {
    margin-top: 9px;
    color: #64736e;
    font-size: 12.5px;
    line-height: 1.75;
}

/* Form */

.bc-auth-form {
    margin-top: 24px;
}

.bc-auth-field + .bc-auth-field {
    margin-top: 18px;
}

.bc-auth-label {
    display: block;
    margin-bottom: 7px;
    color: #293b34;
    font-size: 12px;
    font-weight: 700;
}

.bc-auth-input {
    display: block;
    width: 100%;
    min-height: 45px;
    padding: 10px 13px;
    border: 1px solid #cedbd2;
    border-radius: 8px;
    background: #fff;
    color: #25392f;
    outline: none;
    box-shadow: none;
    font-family: inherit;
    font-size: 12.5px;
    transition:
        border-color .15s ease,
        box-shadow .15s ease;
}

.bc-auth-input:focus {
    border-color: #116759;
    box-shadow: 0 0 0 3px rgba(17, 103, 89, .09);
}

.bc-auth-input::placeholder {
    color: #91a096;
}

.bc-auth-input[aria-invalid="true"] {
    border-color: #d77e7e;
}

.bc-auth-hint {
    margin-top: 7px;
    color: #718078;
    font-size: 11px;
    line-height: 1.65;
}

.bc-auth-password-wrap {
    position: relative;
}

.bc-auth-password-wrap .bc-auth-input {
    padding-right: 52px;
}

.bc-auth-eye {
    position: absolute;
    top: 50%;
    right: 8px;
    transform: translateY(-50%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    padding: 0;
    border: 0;
    border-radius: 7px;
    background: transparent;
    color: #697b70;
    cursor: pointer;
}

.bc-auth-eye:hover {
    color: #116759;
    background: #edf5ef;
}

.bc-auth-eye svg {
    width: 19px;
    height: 19px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.bc-auth-eye .bc-auth-eye-hidden {
    display: none;
}

.bc-auth-forgot {
    margin-top: 9px;
    text-align: right;
}

.bc-auth-forgot a {
    color: #116759;
    font-size: 11.5px;
    font-weight: 700;
    text-decoration: none;
}

.bc-auth-forgot a:hover {
    text-decoration: underline;
}

/* Tombol */

.bc-auth-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 45px;
    margin-top: 21px;
    padding: 10px 15px;
    border: 1px solid #116759;
    border-radius: 8px;
    background: #116759;
    color: #fff;
    font-family: inherit;
    font-size: 12.5px;
    font-weight: 750;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    box-shadow: none;
    transition: background-color .15s ease;
}

.bc-auth-primary:hover {
    background: #0c5649;
}

.bc-auth-secondary {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 42px;
    margin-top: 11px;
    padding: 9px 14px;
    border: 1px solid #bad2c2;
    border-radius: 8px;
    background: #fff;
    color: #116759;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
}

.bc-auth-secondary:hover {
    background: #f1f6f2;
}

/* Pesan dan informasi */

.bc-auth-alert {
    margin-top: 18px;
    padding: 12px 13px;
    border-radius: 8px;
    font-size: 12px;
    line-height: 1.7;
}

.bc-auth-alert-success {
    border: 1px solid #c7dfcf;
    color: #165b42;
    background: #eff7f1;
}

.bc-auth-alert-error {
    border: 1px solid #e9c4c4;
    color: #9c3838;
    background: #fff4f4;
}

.bc-auth-email {
    margin-top: 20px;
    padding: 12px 14px;
    border: 1px solid #e0e8e1;
    border-radius: 8px;
    background: #f7f9f7;
}

.bc-auth-email small {
    display: block;
    color: #64736e;
    font-size: 11px;
}

.bc-auth-email strong {
    display: block;
    margin-top: 5px;
    color: #103f36;
    font-size: 12.5px;
    overflow-wrap: anywhere;
}

.bc-auth-code {
    text-align: center;
    letter-spacing: .22em;
    font-size: 20px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
}

.bc-auth-note {
    margin-top: 18px;
    padding: 11px 13px;
    border: 1px solid #e0e8e1;
    border-radius: 8px;
    background: #f7f9f7;
    color: #65756a;
    font-size: 11.5px;
    line-height: 1.7;
}

.bc-auth-resend {
    margin-top: 22px;
    padding-top: 18px;
    border-top: 1px solid #e8eee9;
}

.bc-auth-bottom {
    margin-top: 23px;
    padding-top: 17px;
    border-top: 1px solid #e8eee9;
    text-align: center;
    color: #64736e;
    font-size: 12px;
}

.bc-auth-bottom a {
    color: #116759;
    font-weight: 700;
    text-decoration: none;
}

.bc-auth-bottom a:hover {
    text-decoration: underline;
}

.bc-auth-link-block {
    margin-top: 11px;
}

/* Aksesibilitas */

.bc-auth-page :is(a, button):focus-visible,
.bc-auth-topbar :is(a, button):focus-visible {
    outline: 2px solid #116759;
    outline-offset: 3px;
}

@media (max-width: 820px) {
    .bc-auth-card {
        grid-template-columns: 1fr;
        max-width: 600px;
    }

    .bc-auth-side {
        padding: 26px 28px;
        gap: 17px;
    }

    .bc-auth-side-title {
        margin-top: 12px;
        font-size: 26px;
    }

    .bc-auth-side-desc {
        margin-top: 9px;
    }

    .bc-auth-side-list {
        display: none;
    }

    .bc-auth-content {
        padding: 30px 28px;
    }
}

@media (max-width: 480px) {
    .bc-auth-topbar-inner {
        padding: 15px 16px;
    }

    .bc-auth-brand {
        font-size: 16px;
    }

    .bc-auth-back {
        padding: 8px 9px;
        font-size: 11px;
    }

    .bc-auth-page {
        padding: 18px 12px 28px;
    }

    .bc-auth-side,
    .bc-auth-content {
        padding: 24px 21px;
    }

    .bc-auth-title {
        font-size: 25px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .bc-auth-page *,
    .bc-auth-topbar * {
        transition: none !important;
    }
}
</style>
