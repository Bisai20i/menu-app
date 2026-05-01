{{--
QR Print Modal — Reusable, fully self-contained component.

Include this component on any page that needs print-formatted QR support.
Do NOT pass any Blade props — all content is injected at runtime via JavaScript.

Usage from any page / Livewire component:
─────────────────────────────────────────
openQrPrintModal({
sourceId : 'my-qr-element-id', // ID of element holding the QR SVG/img
title : 'My Restaurant', // Name shown on the poster
wifiQrUrl : 'https://...', // (optional) URL of WiFi QR image
});
--}}
<div class="modal fade" id="qrPrintModal" tabindex="-1" aria-labelledby="qrPrintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrPrintModalLabel">Print Ready QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light d-flex justify-content-center py-5">

                <style>
                    /* ── Preview card (shown in modal) ───────────────────────────── */
                    .qr-print-card {
                        width: 105mm;
                        background: #ffffff;
                        border-radius: 16px;
                        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
                        padding: 18px;
                        text-align: center;
                        display: flex;
                        flex-direction: column;
                        border-top: 6px solid #000;
                        border-bottom: 6px solid #000;
                        color: #000;
                        font-family: Arial, sans-serif;
                        margin: 0 auto;
                    }

                    .qr-print-card .logo-text {
                        font-size: 14px;
                        font-weight: bold;
                        margin-bottom: 4px;
                        text-transform: lowercase;
                    }

                    .qr-print-card .header-text {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 12px;
                    }

                    .qr-print-card .qr-box {
                        background: #fafafa;
                        padding: 14px;
                        border-radius: 12px;
                        display: inline-block;
                        margin: 0 auto 10px auto;
                    }

                    .qr-print-card .qr-box svg,
                    .qr-print-card .qr-box img {
                        width: 220px !important;
                        height: 220px !important;
                        display: block;
                    }

                    .qr-print-card .cta {
                        margin-top: 10px;
                        font-size: 14px;
                        font-weight: 600;
                    }

                    .qr-print-card .subtext {
                        font-size: 12px;
                        color: #555;
                        margin-top: 4px;
                    }

                    .qr-print-card .divider {
                        height: 1px;
                        background: #eee;
                        margin: 15px 0 20px 0;
                    }

                    .qr-print-card .wifi-section {
                        display: grid;
                        grid-template-columns: 1.2fr 0.8fr;
                        padding: 8px;
                        border-radius: 12px;
                        border: 1px solid #dedede;
                        overflow: hidden;
                        background: #fafafa;
                        text-align: left;
                    }

                    .qr-print-card .wifi-left {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 10px;
                        text-align: center;
                        border-right: 1px solid #eee;
                    }

                    .qr-print-card .wifi-left span {
                        font-size: 14px;
                        font-weight: 500;
                        line-height: 1.2;
                        margin-bottom: 5px;
                    }

                    .qr-print-card .wifi-left strong {
                        font-size: 20px;
                        font-weight: 700;
                        line-height: 1;
                        color: #fff;
                        background: #000;
                        padding: 6px 12px;
                        border-radius: 8px;
                        display: inline-block;
                    }

                    .qr-print-card .wifi-right {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 10px;
                    }

                    .qr-print-card .wifi-right img {
                        width: 80px !important;
                        height: 80px !important;
                    }

                    .qr-print-card .wifi-caption {
                        font-size: 10px;
                        margin-top: 6px;
                        color: #444;
                        text-align: center;
                    }
                </style>

                <!-- PRINT AREA START -->
                <div id="printArea" class="qr-print-card">

                    <!-- HEADER -->
                    <div class="mb-3">
                        <div class="logo-text">saral menu</div>
                        <div class="header-text text-uppercase" id="qrModalTitle">—</div>

                        <div class="qr-box" id="modal-main-qr-container">
                            <!-- QR code cloned here via JS -->
                        </div>

                        <div class="cta">Scan to View Menu &amp; Order</div>
                        <div class="subtext">No app needed • Works on any phone</div>
                    </div>

                    <!-- FOOTER — shown when a WiFi QR URL is provided -->
                    <div id="qrModalWifiSection" style="display:none;">
                        <div class="divider"></div>
                        <div class="wifi-section">
                            <div class="wifi-left">
                                <span>Available</span>
                                <strong>FREE WIFI</strong>
                            </div>
                            <div class="wifi-right">
                                <img id="qrModalWifiImg" src="" alt="WiFi QR">
                                <div class="wifi-caption">Scan for WiFi access</div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER — shown when no WiFi QR is provided -->
                    <div id="qrModalFooter" class="mt-2 pt-2 border-top">
                        <small class="text-muted">saralmenu.com</small>
                    </div>

                </div>
                <!-- PRINT AREA END -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printQrPoster()">
                    <i class="bx bx-printer me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {

        /** CSS injected into the dedicated print window — keeps it isolated from the main page. */
        var PRINT_STYLES = `
        * { box-sizing: border-box; margin: 0; padding: 0; }
        @page { size: auto; margin: 8mm; }
        body {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            min-height: 100vh;
            background: #fff;
            font-family: Arial, sans-serif;
        }
        .qr-print-card {
            width: 105mm;
            background: #ffffff;
            border-radius: 16px;
            padding: 18px;
            text-align: center;
            display: flex;
            flex-direction: column;
            border-top: 6px solid #000;
            border-bottom: 6px solid #000;
            color: #000;
            margin: 0 auto;
        }
        .logo-text { font-size: 14px; font-weight: bold; margin-bottom: 4px; text-transform: lowercase; }
        .header-text { font-size: 24px; font-weight: bold; margin-bottom: 12px; }
        .qr-box {
            background: #fafafa;
            padding: 14px;
            border-radius: 12px;
            display: inline-block;
            margin: 0 auto 10px auto;
        }
        .qr-box svg, .qr-box img { width: 220px !important; height: 220px !important; display: block; }
        .cta { margin-top: 10px; font-size: 14px; font-weight: 600; }
        .subtext { font-size: 12px; color: #555; margin-top: 4px; }
        .divider { height: 1px; background: #eee; margin: 15px 0 20px 0; }
        .wifi-section {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            padding: 8px;
            border-radius: 12px;
            border: 1px solid #dedede;
            overflow: hidden;
            background: #fafafa;
            text-align: left;
        }
        .wifi-left {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 10px; text-align: center;
            border-right: 1px solid #eee;
        }
        .wifi-left span { font-size: 14px; font-weight: 500; line-height: 1.2; margin-bottom: 5px; }
        .wifi-left strong {
            font-size: 20px; font-weight: 700; color: #fff; background: #000;
            padding: 6px 12px; border-radius: 8px; display: inline-block;
        }
        .wifi-right {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 10px;
        }
        .wifi-right img { width: 80px !important; height: 80px !important; }
        .wifi-caption { font-size: 10px; margin-top: 6px; color: #444; text-align: center; }
        .border-top { border-top: 1px solid #dee2e6 !important; }
        .mt-2 { margin-top: 0.5rem !important; }
        .pt-2 { padding-top: 0.5rem !important; }
        .text-muted { color: #6c757d !important; }
        small { font-size: 0.875em; }
    `;

        /**
         * Open the QR Print Modal, populating it with data from the caller.
         *
         * @param {object} opts
         * @param {string}  opts.sourceId   - ID of the element containing the QR SVG/img to clone
         * @param {string}  opts.title      - Name displayed on the poster (e.g. restaurant / table name)
         * @param {string}  [opts.wifiQrUrl]- URL of the WiFi QR image (omit to hide the section)
         */
        window.openQrPrintModal = function (opts) {
            var sourceEl = document.getElementById(opts.sourceId);
            var targetEl = document.getElementById('modal-main-qr-container');
            var titleEl = document.getElementById('qrModalTitle');
            var wifiSec = document.getElementById('qrModalWifiSection');
            var footerEl = document.getElementById('qrModalFooter');
            var wifiImg = document.getElementById('qrModalWifiImg');

            if (!sourceEl || !targetEl) {
                console.error('[QR Modal] Source element #' + opts.sourceId + ' not found.');
                return;
            }

            // 1. Clone the QR SVG into the modal preview
            targetEl.innerHTML = sourceEl.innerHTML;

            // Normalise SVG dimensions for the 220px slot
            var svg = targetEl.querySelector('svg');
            if (svg) {
                svg.setAttribute('width', '220');
                svg.setAttribute('height', '220');
                svg.style.width = '220px';
                svg.style.height = '220px';
            }

            // 2. Set the title
            if (titleEl) titleEl.textContent = opts.title || '';

            // 3. Toggle WiFi section
            if (opts.wifiQrUrl) {
                if (wifiImg) wifiImg.src = opts.wifiQrUrl;
                if (wifiSec) wifiSec.style.display = '';
                if (footerEl) footerEl.style.display = 'none';
            } else {
                if (wifiSec) wifiSec.style.display = 'none';
                if (footerEl) footerEl.style.display = '';
            }

            // 4. Open the Bootstrap modal
            bootstrap.Modal.getOrCreateInstance(document.getElementById('qrPrintModal')).show();
        };

        /**
         * Opens a minimal dedicated print window containing ONLY the poster HTML + styles.
         * This avoids the "blank print" problem caused by the modal being nested deep in the layout.
         */
        window.printQrPoster = function () {
            var printArea = document.getElementById('printArea');
            if (!printArea) return;

            // Serialize the current poster HTML (with live QR + title already injected)
            var html = printArea.outerHTML;

            var win = window.open('', '_blank', 'width=800,height=700,toolbar=0,menubar=0,scrollbars=0');
            win.document.write(
                '<!DOCTYPE html>' +
                '<html><head>' +
                '<meta charset="utf-8">' +
                '<title>Print QR Poster</title>' +
                '<style>' + PRINT_STYLES + '</style>' +
                '</head><body>' +
                html +
                '<script>' +
                'window.onload = function(){ window.print(); window.close(); };' +
                '<\/script>' +
                '</body></html>'
            );
            win.document.close();
        };

    })();
</script>