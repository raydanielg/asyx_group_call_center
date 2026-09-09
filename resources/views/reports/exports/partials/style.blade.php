@php
    $docTitle = $docTitle ?? 'Report';
    $docCode = $docCode ?? 'RPT';
@endphp
<style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 10.5px;
        color: #1A2332;
    }
    @page {
        margin: 80px 30px 54px 30px;
    }

    /* ============ Watermark (repeats on every page) ============ */
    .pdf-watermark {
        position: fixed;
        top: 260px;
        left: 60px;
        font-size: 64px;
        font-weight: bold;
        color: #0D3E63;
        opacity: 0.04;
        letter-spacing: 3px;
        transform: rotate(-28deg);
    }

    /* ============ Running header / footer chrome (repeats on every page) ============ */
    .pdf-chrome-top { position: fixed; top: -52px; left: 0; right: 0; font-size: 7.5px; }
    .pdf-chrome-top .brand { float: left; color: #A56035; font-weight: bold; letter-spacing: 0.6px; }
    .pdf-chrome-top .doc { float: right; color: #8B93A3; letter-spacing: 0.4px; }
    .pdf-chrome-top-rule { position: fixed; top: -28px; left: 0; right: 0; height: 0; border-top: 1.25px solid #0D3E63; }

    .pdf-chrome-bottom-rule { position: fixed; bottom: -18px; left: 0; right: 0; height: 0; border-top: 0.75px solid #D9DEE7; }
    .pdf-chrome-bottom { position: fixed; bottom: -32px; left: 0; right: 0; font-size: 7.5px; color: #8B93A3; }
    .pdf-chrome-bottom .ref { float: left; }
    .pdf-chrome-bottom .tag { float: right; font-weight: bold; color: #A56035; letter-spacing: 0.5px; }

    /* ============ Letterhead (in-flow, top of document) ============ */
    .letterhead-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .letterhead-table td { vertical-align: middle; padding: 0; }
    .lh-emblem { width: 54px; }
    .emblem {
        width: 44px; height: 44px; border-radius: 50%;
        border: 2.5px solid #A56035;
        background: #0D3E63;
        color: #fff; text-align: center; line-height: 44px;
        font-size: 12px; font-weight: bold; letter-spacing: 0.5px;
    }
    .lh-org { padding-left: 10px; }
    .org-name { font-size: 17px; font-weight: bold; color: #0D3E63; letter-spacing: 0.5px; }
    .org-sub { font-size: 8.5px; color: #6B7280; margin-top: 1px; }
    .lh-refbox { width: 190px; text-align: right; }
    .ref-label { font-size: 7px; text-transform: uppercase; letter-spacing: 1px; color: #A56035; font-weight: bold; }
    .ref-value { font-size: 9.5px; color: #1A2332; font-weight: bold; margin-top: 1px; }
    .lh-divider { border-top: 2.5px solid #0D3E63; border-bottom: 1px solid #A56035; height: 0; margin: 8px 0 12px; }

    .doc-title-block { text-align: center; margin-bottom: 10px; }
    .doc-title { font-size: 18px; font-weight: bold; color: #0D3E63; letter-spacing: 1px; margin: 0; }
    .doc-subtitle { font-size: 10px; color: #6B7280; margin: 3px 0 0; }

    .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; background: #F7F8FA; border: 1px solid #E4E7EC; }
    .meta-table td { padding: 7px 12px; font-size: 8.5px; width: 33.33%; text-align: center; border-right: 1px solid #E4E7EC; }
    .meta-table td:last-child { border-right: none; }
    .meta-label { text-transform: uppercase; letter-spacing: 0.5px; color: #8B93A3; font-size: 7.5px; }
    .meta-value { color: #1A2332; font-weight: bold; font-size: 9.5px; }
    .meta-value.classification { color: #A56035; }

    /* ============ Section headings ============ */
    h2.section-title {
        color: #0D3E63; font-size: 12px; font-weight: bold;
        border-left: 3.5px solid #A56035; padding-left: 8px;
        margin: 16px 0 8px; text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* ============ Summary cards / stats ============ */
    .summary { display: flex; gap: 10px; margin-bottom: 14px; }
    .card {
        flex: 1; border: 1px solid #E4E7EC; border-top: 3px solid #0D3E63;
        border-radius: 4px; padding: 9px; text-align: center; background: #FCFCFD;
    }
    .card .label { font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #8B93A3; }
    .card .value { font-size: 17px; font-weight: bold; margin-top: 4px; color: #0D3E63; }

    .stat { display: inline-block; border: 1px solid #E4E7EC; border-top: 3px solid #0D3E63; border-radius: 4px; padding: 8px 16px; margin: 0 8px 8px 0; }
    .stat .label { font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #8B93A3; }
    .stat .value { font-size: 15px; font-weight: bold; color: #0D3E63; }

    /* ============ Data tables ============ */
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    table.data-table th {
        background: #0D3E63; color: #fff; padding: 6px 8px; text-align: left;
        font-size: 8.5px; text-transform: uppercase; letter-spacing: 0.3px; font-weight: bold;
    }
    table.data-table td { padding: 5px 8px; font-size: 9.5px; border-bottom: 1px solid #ECEEF2; }
    table.data-table tr.row-alt td { background: #F7F8FA; }
    table.data-table tr:last-child td { border-bottom: 1px solid #D9DEE7; }

    .badge-pass { color: #16a34a; font-weight: bold; }
    .badge-info { color: #0284c7; font-weight: bold; }
    .badge-fail { color: #dc2626; font-weight: bold; }

    /* ============ Signature block ============ */
    .signoff { margin-top: 26px; page-break-inside: avoid; }
    .signoff-table { width: 100%; border-collapse: collapse; }
    .signoff-table td { width: 33.33%; padding-top: 30px; text-align: center; vertical-align: bottom; }
    .signoff-line { border-top: 1px solid #1A2332; margin: 0 16px; padding-top: 4px; font-size: 9px; font-weight: bold; color: #1A2332; }
    .signoff-role { font-size: 8px; color: #6B7280; margin-top: 2px; }
    .signoff-note {
        margin-top: 18px; font-size: 7.5px; color: #8B93A3; text-align: center;
        border-top: 1px dashed #D9DEE7; padding-top: 7px;
    }
</style>
<div class="pdf-watermark">AYS GROUP</div>
<div class="pdf-chrome-top">
    <span class="brand">AYS GROUP &nbsp;|&nbsp; CALL CENTER HRMS</span>
    <span class="doc">{{ strtoupper($docTitle) }} &nbsp;&bull;&nbsp; CONFIDENTIAL</span>
</div>
<div class="pdf-chrome-top-rule"></div>
<div class="pdf-chrome-bottom-rule"></div>
<div class="pdf-chrome-bottom">
    <span class="ref">Ref: {{ strtoupper($docCode) }}-{{ now()->format('Ymd-His') }} &nbsp;|&nbsp; Generated {{ now()->format('d M Y, H:i') }}</span>
    <span class="tag">AYS GROUP</span>
</div>
