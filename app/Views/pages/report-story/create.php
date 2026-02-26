<?php if (!isset($asModal) || !$asModal): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Story</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Material+Symbols+Outlined&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #f0f2f5;
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
<?php endif; ?>

<!-- Report Story Modal -->
<div id="report-story-modal" class="rs-overlay">
    <div class="rs-card">

        <!-- Header -->
        <div class="rs-header">
            <div class="rs-header-icon">
                <span class="material-symbols-outlined">flag</span>
            </div>
            <div>
                <h3 class="rs-title">Report Story</h3>
                <p class="rs-subtitle">Help us keep the community safe</p>
            </div>
            <button type="button" class="rs-close" onclick="window.history.back()" aria-label="Close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Divider -->
        <div class="rs-divider"></div>

        <!-- Form -->
        <form id="report-story-form" method="post" action="<?= base_url('/report-story/submit') ?>" class="rs-form">
            <input type="hidden" name="story_id" id="report_story_id" value="<?= isset($story_id) ? esc($story_id) : '' ?>">

            <!-- Reason -->
            <div class="rs-field">
                <label for="report_reason" class="rs-label">
                    <span class="material-symbols-outlined rs-label-icon">category</span>
                    Reason
                </label>
                <div class="rs-select-wrap">
                    <select name="reason" id="report_reason" class="rs-select" required>
                        <option value="" disabled selected>Select a reason…</option>
                        <option value="inappropriate">🚫 Inappropriate Content</option>
                        <option value="spam">📢 Spam</option>
                        <option value="plagiarism">📋 Plagiarism</option>
                        <option value="misinformation">❌ Misinformation</option>
                        <option value="other">💬 Other</option>
                    </select>
                    <span class="material-symbols-outlined rs-chevron">expand_more</span>
                </div>
            </div>

            <!-- Details -->
            <div class="rs-field">
                <label for="report_details" class="rs-label">
                    <span class="material-symbols-outlined rs-label-icon">edit_note</span>
                    Additional Details
                    <span class="rs-optional">Optional</span>
                </label>
                <textarea
                    name="details"
                    id="report_details"
                    rows="4"
                    class="rs-textarea"
                    placeholder="Describe the issue in more detail…"
                    maxlength="500"
                    oninput="updateCharCount(this)"
                ></textarea>
                <div class="rs-char-count"><span id="rs-char-num">0</span>/500</div>
            </div>

            <!-- Actions -->
            <div class="rs-actions">
                <button type="button" class="rs-btn-cancel" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="rs-btn-submit">
                    <span class="material-symbols-outlined">send</span>
                    Submit Report
                </button>
            </div>
        </form>

    </div>
</div>

<style>
/* ── Google Font ── */
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap');

/* ── Overlay ── */
.rs-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 20, 35, 0.55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 1.25rem;
    animation: rs-fade-in 0.2s ease;
}

@keyframes rs-fade-in {
    from { opacity: 0; }
    to   { opacity: 1; }
}

/* ── Card ── */
.rs-card {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 440px;
    box-shadow:
        0 2px 4px rgba(0,0,0,0.04),
        0 8px 24px rgba(0,0,0,0.1),
        0 24px 64px rgba(0,0,0,0.08);
    overflow: hidden;
    animation: rs-slide-up 0.28s cubic-bezier(0.22, 1, 0.36, 1);
    font-family: 'DM Sans', sans-serif;
}

@keyframes rs-slide-up {
    from { opacity: 0; transform: translateY(18px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0)   scale(1); }
}

/* ── Header ── */
.rs-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 22px 24px;
    position: relative;
}

.rs-header-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #fff1f1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #e03131;
}

.rs-header-icon .material-symbols-outlined {
    font-size: 22px;
}

.rs-title {
    font-size: 17px;
    font-weight: 600;
    color: #111827;
    letter-spacing: -0.01em;
    line-height: 1.3;
}

.rs-subtitle {
    font-size: 12.5px;
    color: #9ca3af;
    margin-top: 1px;
}

.rs-close {
    position: absolute;
    right: 18px;
    top: 18px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: #f3f4f6;
    border-radius: 50%;
    cursor: pointer;
    color: #6b7280;
    transition: background 0.15s, color 0.15s;
}

.rs-close:hover {
    background: #e5e7eb;
    color: #374151;
}

.rs-close .material-symbols-outlined {
    font-size: 18px;
}

/* ── Divider ── */
.rs-divider {
    height: 1px;
    background: #f3f4f6;
}

/* ── Form ── */
.rs-form {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.rs-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* ── Label ── */
.rs-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    letter-spacing: 0.01em;
}

.rs-label-icon {
    font-size: 16px;
    color: #9ca3af;
}

.rs-optional {
    margin-left: auto;
    font-size: 11px;
    font-weight: 400;
    color: #9ca3af;
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 20px;
}

/* ── Select ── */
.rs-select-wrap {
    position: relative;
}

.rs-select {
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    background: #f9fafb;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    padding: 11px 40px 11px 14px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: #111827;
    cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
    outline: none;
}

.rs-select:focus {
    border-color: #e03131;
    box-shadow: 0 0 0 3px rgba(224, 49, 49, 0.1);
    background: #fff;
}

.rs-chevron {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: #9ca3af;
    pointer-events: none;
}

/* ── Textarea ── */
.rs-textarea {
    width: 100%;
    background: #f9fafb;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: #111827;
    resize: vertical;
    min-height: 100px;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    line-height: 1.6;
}

.rs-textarea::placeholder { color: #c1c7d0; }

.rs-textarea:focus {
    border-color: #e03131;
    box-shadow: 0 0 0 3px rgba(224, 49, 49, 0.1);
    background: #fff;
}

.rs-char-count {
    text-align: right;
    font-size: 11.5px;
    color: #9ca3af;
}

/* ── Actions ── */
.rs-actions {
    display: flex;
    gap: 10px;
    margin-top: 4px;
}

.rs-btn-cancel {
    flex: 1;
    padding: 11px 0;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    background: transparent;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    color: #6b7280;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}

.rs-btn-cancel:hover {
    background: #f3f4f6;
    color: #374151;
    border-color: #d1d5db;
}

.rs-btn-submit {
    flex: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 0;
    border: none;
    border-radius: 10px;
    background: #e03131;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
    box-shadow: 0 2px 8px rgba(224, 49, 49, 0.35);
}

.rs-btn-submit:hover {
    background: #c92a2a;
    box-shadow: 0 4px 16px rgba(224, 49, 49, 0.4);
}

.rs-btn-submit:active { transform: scale(0.98); }

.rs-btn-submit .material-symbols-outlined {
    font-size: 17px;
}

/* ── Modal Overlay ── */
.rs-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}
</style>

<script>
function updateCharCount(el) {
    document.getElementById('rs-char-num').textContent = el.value.length;
}
</script>

<?php if (!isset($asModal) || !$asModal): ?>
</body>
</html>
<?php endif; ?>