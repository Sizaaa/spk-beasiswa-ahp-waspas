{{--
|--------------------------------------------------------------------------
| BEASISWA CERDAS — MODERN PREMIUM SAAS DESIGN SYSTEM
|--------------------------------------------------------------------------
| Desain: Modern Premium Education SaaS Dashboard
| - Font: Plus Jakarta Sans (Google Fonts)
| - Palet: Deep Emerald (#103F36), Forest (#116759), Mint (#20A67A), Surface (#FFFFFF)
| - Background: #F5F8F6 dengan depth & subtle layering
| - Komponen: Buttons, Inputs, Cards, Panels, Badges, Steppers, Tables
|
| Mempertahankan seluruh route, controller, data, dan interaksi backend.
--}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap');

/* =========================================
   1. DESIGN TOKENS & SYSTEM VARIABLES
========================================= */
:root {
    /* Brand Colors */
    --bc-green: #103F36;
    --bc-green-dark: #0A2923;
    --bc-green-deep: #071F1A;
    --bc-accent: #116759;
    --bc-accent-hover: #0D5448;
    --bc-accent-light: #E6F5F0;
    --bc-brand: #20A67A;
    --bc-brand-hover: #198B66;
    --bc-brand-glow: rgba(32, 166, 122, 0.25);

    /* Backgrounds & Surfaces */
    --bc-background: #F5F8F6;
    --bc-surface: #FFFFFF;
    --bc-surface-muted: #F8FAF9;
    --bc-surface-subtle: #F0F4F2;
    --bc-surface-card: #FFFFFF;

    /* Text System */
    --bc-text: #172B25;
    --bc-text-secondary: #52665F;
    --bc-text-muted: #7E928B;
    --bc-text-light: #A3B5AE;

    /* Borders */
    --bc-border: #E3EBE6;
    --bc-border-subtle: #EDF3F0;
    --bc-border-strong: #CDDCD4;
    --bc-border-focus: #20A67A;

    /* Functional Colors */
    --bc-success-bg: #E8F8F2;
    --bc-success-text: #0E7051;
    --bc-success-border: #B8E8D5;

    --bc-warning-bg: #FEF6E7;
    --bc-warning-text: #9A5B00;
    --bc-warning-border: #FADDAA;

    --bc-danger-bg: #FDEEEE;
    --bc-danger-text: #B32626;
    --bc-danger-border: #F8C3C3;

    --bc-info-bg: #EBF3FE;
    --bc-info-text: #1E5AB9;
    --bc-info-border: #C4DCFD;

    /* Radii */
    --bc-radius-xs: 6px;
    --bc-radius-sm: 8px;
    --bc-radius-md: 12px;
    --bc-radius: 16px;
    --bc-radius-lg: 20px;
    --bc-radius-xl: 24px;
    --bc-radius-pill: 9999px;

    /* Shadows */
    --bc-shadow-xs: 0 1px 2px 0 rgba(16, 63, 54, 0.03);
    --bc-shadow-sm: 0 2px 6px 0 rgba(16, 63, 54, 0.04);
    --bc-shadow: 0 4px 16px -2px rgba(16, 63, 54, 0.05), 0 2px 6px -2px rgba(16, 63, 54, 0.03);
    --bc-shadow-md: 0 10px 25px -4px rgba(16, 63, 54, 0.07), 0 4px 10px -2px rgba(16, 63, 54, 0.03);
    --bc-shadow-lg: 0 20px 35px -6px rgba(16, 63, 54, 0.09), 0 8px 16px -4px rgba(16, 63, 54, 0.04);
    --bc-shadow-glow: 0 0 24px -2px rgba(32, 166, 122, 0.28);

    /* Typography */
    --bc-font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --bc-transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

/* =========================================
   2. GLOBAL TYPOGRAPHY & RESET
========================================= */
body {
    font-family: var(--bc-font) !important;
    color: var(--bc-text);
    background-color: var(--bc-background) !important;
    font-synthesis: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
    line-height: 1.6;
}

button, input, select, textarea {
    font-family: inherit !important;
}

body :is(h1, h2, h3, h4, h5, h6) {
    letter-spacing: -0.025em;
    font-feature-settings: "cv02", "cv03", "cv04", "cv11";
}

/* =========================================
   3. MODERN BUTTON SYSTEM (.bc-btn)
========================================= */
.bc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 650;
    line-height: 1.35;
    padding: 9px 18px;
    border-radius: var(--bc-radius-md) !important;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: var(--bc-transition);
    user-select: none;
    white-space: nowrap;
}

.bc-btn:hover {
    transform: translateY(-1.5px);
}

.bc-btn:active {
    transform: translateY(0) scale(0.985);
}

.bc-btn:focus-visible {
    outline: none;
    box-shadow: 0 0 0 3px rgba(32, 166, 122, 0.35);
}

/* Primary Button (Gradient Deep Emerald) */
.bc-btn--primary {
    background: linear-gradient(135deg, #116759 0%, #103F36 100%) !important;
    color: #FFFFFF !important;
    border-color: rgba(255, 255, 255, 0.12) !important;
    box-shadow: 0 2px 8px rgba(16, 63, 54, 0.25);
}

.bc-btn--primary:hover {
    background: linear-gradient(135deg, #157B6B 0%, #0D342C 100%) !important;
    box-shadow: 0 4px 16px rgba(16, 63, 54, 0.35);
    color: #FFFFFF !important;
}

/* Accent Button (Vibrant Mint) */
.bc-btn--accent {
    background: linear-gradient(135deg, #20A67A 0%, #116759 100%) !important;
    color: #FFFFFF !important;
    box-shadow: 0 2px 10px rgba(32, 166, 122, 0.3);
}

.bc-btn--accent:hover {
    background: linear-gradient(135deg, #28BA8A 0%, #0E5A4D 100%) !important;
    box-shadow: 0 4px 18px rgba(32, 166, 122, 0.45);
    color: #FFFFFF !important;
}

/* Outline Button (Clean Crisp Border) */
.bc-btn--outline {
    background: #FFFFFF !important;
    border-color: var(--bc-border) !important;
    color: var(--bc-accent) !important;
    box-shadow: var(--bc-shadow-xs);
}

.bc-btn--outline:hover {
    border-color: var(--bc-brand) !important;
    background: #F3FAF7 !important;
    color: var(--bc-green-dark) !important;
    box-shadow: var(--bc-shadow-sm);
}

/* Danger / Red Button */
.bc-btn--red {
    background: #FFF1F1 !important;
    border-color: #F8C3C3 !important;
    color: #B32626 !important;
}

.bc-btn--red:hover {
    background: #FFE4E4 !important;
    border-color: #E68A8A !important;
    color: #8C1C1C !important;
}

/* Amber / Warning Button */
.bc-btn--amber {
    background: #FEF7EA !important;
    border-color: #F8DCB1 !important;
    color: #9A5B00 !important;
}

.bc-btn--amber:hover {
    background: #FDF0D6 !important;
    border-color: #E8BE78 !important;
}

/* =========================================
   4. PANEL & CARD SYSTEM (.bc-panel, .bc-card)
========================================= */
.bc-panel,
.bc-card {
    background: var(--bc-surface);
    border: 1px solid var(--bc-border);
    border-radius: var(--bc-radius) !important;
    box-shadow: var(--bc-shadow);
    transition: var(--bc-transition);
    overflow: hidden;
}

.bc-panel:hover,
.bc-card:hover {
    box-shadow: var(--bc-shadow-md);
    border-color: var(--bc-border-strong);
}

.bc-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding: 20px 24px;
    background: #FFFFFF;
    border-bottom: 1px solid var(--bc-border-subtle);
}

.bc-panel-title {
    font-size: 16px;
    font-weight: 750;
    color: var(--bc-green);
    letter-spacing: -0.015em;
    line-height: 1.35;
}

.bc-panel-desc {
    margin-top: 4px;
    color: var(--bc-text-secondary);
    font-size: 12.5px;
    line-height: 1.6;
}

.bc-panel-body {
    padding: 24px;
}

/* =========================================
   5. STATISTICS CARDS (.bc-stat)
========================================= */
.bc-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}

@media (max-width: 1024px) {
    .bc-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px) {
    .bc-stats {
        grid-template-columns: 1fr;
    }
}

.bc-stat {
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 146px;
    padding: 20px 22px;
    background: #FFFFFF;
    border: 1px solid var(--bc-border);
    border-radius: var(--bc-radius) !important;
    box-shadow: var(--bc-shadow-sm);
    transition: var(--bc-transition);
    overflow: hidden;
}

.bc-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    opacity: 0;
    transition: var(--bc-transition);
}

.bc-stat:hover {
    transform: translateY(-2px);
    box-shadow: var(--bc-shadow-md);
    border-color: var(--bc-border-strong);
}

.bc-stat:hover::before {
    opacity: 1;
}

.bc-stat-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.bc-stat-label {
    font-size: 12.5px;
    font-weight: 650;
    color: var(--bc-text-secondary);
    line-height: 1.4;
}

.bc-stat-icon {
    display: flex;
    width: 38px;
    height: 38px;
    align-items: center;
    justify-content: center;
    border-radius: var(--bc-radius-md);
    font-size: 17px;
    flex-shrink: 0;
    transition: var(--bc-transition);
}

.bc-stat:hover .bc-stat-icon {
    transform: scale(1.08);
}

.bc-stat-value {
    margin-top: 14px;
    font-size: 32px;
    font-weight: 800;
    color: var(--bc-green);
    line-height: 1.15;
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.02em;
}

.bc-stat-foot {
    margin-top: auto;
    padding-top: 10px;
    font-size: 11.5px;
    color: var(--bc-text-muted);
    line-height: 1.4;
}

/* Stat Card Color Variants */
.bc-stat--teal::before { background: linear-gradient(90deg, #116759, #20A67A); }
.bc-stat--teal .bc-stat-icon { color: #0E6857; background: #E6F7F1; }

.bc-stat--blue::before { background: linear-gradient(90deg, #2563EB, #60A5FA); }
.bc-stat--blue .bc-stat-icon { color: #1E5AB9; background: #EBF3FE; }

.bc-stat--green::before { background: linear-gradient(90deg, #16A34A, #4ADE80); }
.bc-stat--green .bc-stat-icon { color: #0F764F; background: #E8F8F2; }

.bc-stat--amber::before { background: linear-gradient(90deg, #D97706, #FBBF24); }
.bc-stat--amber .bc-stat-icon { color: #9A5B00; background: #FEF6E7; }

/* =========================================
   6. TABLE SYSTEM (.bc-table)
========================================= */
.bc-table-scroll {
    width: 100%;
    overflow-x: auto;
    border-radius: var(--bc-radius-md);
    border: 1px solid var(--bc-border-subtle);
}

.bc-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13.5px;
    text-align: left;
}

.bc-table thead th {
    background: #F8FAF9;
    color: var(--bc-text-secondary);
    font-size: 11px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 13px 18px;
    border-bottom: 1px solid var(--bc-border);
    white-space: nowrap;
}

.bc-table tbody td {
    padding: 16px 18px;
    border-bottom: 1px solid var(--bc-border-subtle);
    color: var(--bc-text);
    vertical-align: middle;
    transition: background 0.12s ease;
}

.bc-table tbody tr:last-child td {
    border-bottom: none;
}

.bc-table tbody tr:hover td {
    background: #F4FAF7;
}

.bc-cell-title {
    font-weight: 700;
    color: var(--bc-green);
    font-size: 13.5px;
}

.bc-cell-caption {
    font-size: 11px;
    color: var(--bc-text-muted);
    margin-top: 3px;
}

/* =========================================
   7. STATUS BADGES (.bc-status, .bc-badge)
========================================= */
.bc-status,
.bc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: var(--bc-radius-pill);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.3;
    white-space: nowrap;
    border: 1px solid transparent;
}

.bc-status::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.85;
}

.bc-status--terverifikasi,
.bc-status--diterima {
    background: var(--bc-success-bg);
    color: var(--bc-success-text);
    border-color: var(--bc-success-border);
}

.bc-status--diajukan,
.bc-status--menunggu {
    background: var(--bc-info-bg);
    color: var(--bc-info-text);
    border-color: var(--bc-info-border);
}

.bc-status--perlu_perbaikan,
.bc-status--cadangan {
    background: var(--bc-warning-bg);
    color: var(--bc-warning-text);
    border-color: var(--bc-warning-border);
}

.bc-status--ditolak,
.bc-status--tidak_diterima {
    background: var(--bc-danger-bg);
    color: var(--bc-danger-text);
    border-color: var(--bc-danger-border);
}

.bc-status--draf,
.bc-status--belum_mengisi {
    background: #F1F5F3;
    color: #5C706A;
    border-color: #DDE5E1;
}

/* =========================================
   8. FORM CONTROLS (.bc-form-control)
========================================= */
.bc-form-control,
input[type="text"],
input[type="email"],
input[type="password"],
input[type="number"],
select,
textarea {
    width: 100%;
    min-height: 42px;
    padding: 9px 14px;
    border-radius: var(--bc-radius-md) !important;
    border: 1px solid var(--bc-border-strong);
    background: #FFFFFF;
    color: var(--bc-text);
    font-size: 13.5px;
    transition: var(--bc-transition);
}

.bc-form-control:focus,
input:focus,
select:focus,
textarea:focus {
    border-color: var(--bc-brand) !important;
    box-shadow: 0 0 0 3px rgba(32, 166, 122, 0.22) !important;
    outline: none;
}

/* =========================================
   9. NOTICES & ALERTS (.bc-notice)
========================================= */
.bc-notice {
    padding: 16px 20px;
    border-radius: var(--bc-radius-md);
    background: #F4FAF7;
    border: 1px solid #D1EADE;
    color: var(--bc-green);
    font-size: 13px;
    line-height: 1.6;
}

/* =========================================
   10. STEPPER / TIMELINE SYSTEM
========================================= */
.bc-stepper {
    display: flex;
    justify-content: space-between;
    position: relative;
    padding: 10px 0;
}

.bc-step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.bc-step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 750;
    font-size: 14px;
    transition: var(--bc-transition);
}

.bc-step-icon--active {
    background: linear-gradient(135deg, #20A67A, #116759);
    color: white;
    box-shadow: 0 0 0 4px rgba(32, 166, 122, 0.25);
}

.bc-step-icon--done {
    background: #E8F8F2;
    color: #0E7051;
    border: 1.5px solid #20A67A;
}

.bc-step-icon--pending {
    background: #F0F4F2;
    color: #8B9E97;
    border: 1.5px solid #D6E2DD;
}

/* =========================================
   11. SCROLLBAR
========================================= */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #CBDCD4;
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: #9DBBB0;
}
</style>
