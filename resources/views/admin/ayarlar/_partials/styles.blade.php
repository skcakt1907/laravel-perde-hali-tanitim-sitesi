{{--
═══════════════════════════════════════════════════════════
AYARLAR ORTAK CSS — her ayarlar sayfasında @push('head') ile gelir
═══════════════════════════════════════════════════════════
--}}
<style>
    /* ─── Yerleşim: sol alt-menü + içerik ─── */
    .ayarlar-layout {
        display: grid;
        grid-template-columns: 244px 1fr;
        gap: 20px;
        align-items: start;
    }

    .ayarlar-content { min-width: 0; }

    .ayarlar-subnav {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 12px;
        position: sticky;
        top: calc(var(--header-h) + 20px);
        max-height: calc(100vh - var(--header-h) - 40px);
        overflow-y: auto;
    }

    .ayarlar-group { margin-bottom: 14px; }
    .ayarlar-group:last-child { margin-bottom: 0; }

    .ayarlar-group-title {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        padding: 6px 12px;
        margin-bottom: 2px;
    }

    .ayarlar-sub-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: var(--radius-md);
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text-secondary);
        transition: all 0.15s;
        margin-bottom: 2px;
    }

    .ayarlar-sub-link svg { width: 16px; height: 16px; flex-shrink: 0; }
    .ayarlar-sub-link:hover { background: var(--bg-subtle); color: var(--text); }

    .ayarlar-sub-link.active {
        background: linear-gradient(135deg, var(--brand-soft), rgba(37, 99, 235, 0.03));
        color: var(--brand-dark);
        border-left: 3px solid var(--brand);
        padding-left: 9px;
        font-weight: 600;
    }

    /* Telefon açılır menü düğmesi — masaüstünde gizli */
    .ayarlar-nav-toggle { display: none; }

    /* ─── Yapışkan kaydet çubuğu ─── */
    .sticky-save {
        position: sticky;
        bottom: 16px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        box-shadow: var(--shadow-lg);
        z-index: 10;
    }

    .sticky-save .hint { font-size: 12.5px; color: var(--text-muted); }

    /* ─── SEO karakter sayacı ─── */
    .char-counter { font-size: 11px; color: var(--text-muted); text-align: right; margin-top: 4px; }
    .char-counter.warn { color: var(--warning); }
    .char-counter.over { color: var(--danger); }

    /* ─── Bilgi kartı ─── */
    .info-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        background: linear-gradient(135deg, var(--info-soft), transparent);
        border: 1px solid rgba(37, 99, 235, 0.2);
        border-left: 4px solid var(--brand);
        border-radius: var(--radius-md);
        margin-bottom: 16px;
    }

    .info-card .ic { flex-shrink: 0; color: var(--brand); margin-top: 1px; }
    .info-card .body { font-size: 13px; color: var(--text-secondary); line-height: 1.55; }
    .info-card.is-warning { background: linear-gradient(135deg, var(--warning-soft), transparent); border-color: rgba(217, 131, 36, 0.25); border-left-color: var(--warning); }
    .info-card.is-warning .ic { color: var(--warning); }

    /* ─── Görsel yükleme / önizleme alanı ─── */
    .image-field { display: grid; grid-template-columns: 180px 1fr; gap: 16px; align-items: start; }
    .image-field .thumb-box {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--bg-subtle);
        aspect-ratio: 4/3;
    }
    .image-field .thumb-box img { width: 100%; height: 100%; object-fit: cover; }
    .image-field .thumb-box.empty {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 12px;
        text-align: center;
        padding: 10px;
    }

    /* ─── Dil sekmeleri (DE / TR) ─── */
    .lang-tabs { display: flex; gap: 6px; margin-bottom: 14px; border-bottom: 1px solid var(--border); }

    .lang-tab {
        padding: 8px 14px;
        border-radius: var(--radius-sm) var(--radius-sm) 0 0;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
        border: 1px solid transparent;
        border-bottom: none;
        margin-bottom: -1px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .lang-tab:hover { color: var(--text); background: var(--bg-subtle); }

    .lang-tab.active {
        background: var(--surface);
        border-color: var(--border);
        color: var(--brand-dark);
        box-shadow: inset 0 2px 0 var(--brand);
    }

    .lang-tab .flag {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.06em;
        padding: 2px 6px;
        border-radius: 4px;
        background: var(--bg-subtle);
        color: var(--text-secondary);
    }

    .lang-tab.active .flag { background: var(--brand); color: var(--brand-contrast); }
    .lang-panel[hidden] { display: none; }

    @media (max-width: 900px) {
        .ayarlar-layout { grid-template-columns: 1fr; }

        .ayarlar-nav-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            padding: 12px 16px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 12px;
        }

        .ayarlar-nav-toggle .nt-left { display: flex; align-items: center; gap: 8px; }
        .ayarlar-nav-toggle .nt-chevron { transition: transform 0.2s; flex-shrink: 0; }
        .ayarlar-nav-toggle.open .nt-chevron { transform: rotate(180deg); }

        /* Alt menü telefonda varsayılan KAPALI ve sticky değil */
        .ayarlar-subnav { position: static; max-height: none; overflow: visible; display: none; margin-bottom: 16px; }
        .ayarlar-subnav.open { display: block; }

        /* Menü açıkken form gizlenir — üst üste binmesin */
        body.ayarlar-nav-acik .ayarlar-content { display: none; }

        /* Kaydet çubuğu telefonda havada kalmasın */
        .sticky-save { position: static; box-shadow: none; }

        .image-field { grid-template-columns: 1fr; }
    }

    @media (max-width: 560px) {
        .ayarlar-content .form-grid { grid-template-columns: 1fr !important; }
        .sticky-save { flex-direction: column; align-items: stretch; }
        .sticky-save .btn { width: 100%; }
    }
</style>

<script>
/* SEO karakter sayacı */
function updateCharCounter(el, counterId, max) {
    var c = document.getElementById(counterId);
    if (!c) return;
    var n = el.value.length;
    c.textContent = n + ' / ' + max;
    c.classList.toggle('warn', n > max * 0.9 && n <= max);
    c.classList.toggle('over', n > max);
}

/* DE / TR sekmeleri */
function langTab(group, locale) {
    document.querySelectorAll('[data-lang-tab="' + group + '"]').forEach(function (b) {
        b.classList.toggle('active', b.dataset.locale === locale);
    });
    document.querySelectorAll('[data-lang-panel="' + group + '"]').forEach(function (p) {
        p.hidden = p.dataset.locale !== locale;
    });
    if (window.lucide) window.lucide.createIcons();
}

window.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('textarea[data-counter], input[data-counter]').forEach(function (el) {
        updateCharCounter(el, el.dataset.counter, parseInt(el.dataset.counterMax || '160', 10));
    });
});
</script>
