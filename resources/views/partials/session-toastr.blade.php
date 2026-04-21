@php
    $qSuccess = request()->query('sifec_inline');
    $qError = request()->query('sifec_err_inline');
    $qNotice = request()->query('sifec_notice');
    $noticeMsgs = config('sifec_notices.messages', []);
    $hasQueryFlash = (is_string($qSuccess) && $qSuccess !== '')
        || (is_string($qNotice) && $qNotice !== '' && isset($noticeMsgs[$qNotice]))
        || (is_string($qError) && $qError !== '');
    $tfFlash = \App\Support\SifecTransientFlashCookie::read(request());

    $normalizeFlash = static function ($value): ?string {
        if ($value === null) {
            return null;
        }
        if ($value instanceof \Illuminate\Contracts\Support\MessageBag) {
            $value = $value->first();
        }
        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }
        if (is_array($value)) {
            $value = implode(' ', array_map(static fn ($v) => is_scalar($v) ? (string) $v : '', $value));
        }
        if (is_scalar($value) && ! is_string($value)) {
            $value = (string) $value;
        }
        if (is_object($value) && method_exists($value, '__toString')) {
            $value = (string) $value;
        }
        if (! is_string($value) || $value === '') {
            return null;
        }

        return $value;
    };

    $sSuccess = $normalizeFlash(session('success'));
    $sError = $normalizeFlash(session('error'));
    $sWarning = $normalizeFlash(session('warning'));
    $sInfo = $normalizeFlash(session('info'));
    if (! $hasQueryFlash && $tfFlash !== null) {
        if ($tfFlash['type'] === 'success' && $sSuccess === null) {
            $sSuccess = $tfFlash['message'];
        }
        if ($tfFlash['type'] === 'error' && $sError === null) {
            $sError = $tfFlash['message'];
        }
    }
@endphp

@if ($hasQueryFlash || $sSuccess || $sError || $sWarning || $sInfo)
    <script>
        (function () {
            var $jq = window.jQuery || window.$;

            var payload = {
                query: @json($hasQueryFlash),
                qSuccess: @json(is_string($qSuccess) && $qSuccess !== '' ? $qSuccess : null),
                qNoticeText: @json(
                    is_string($qNotice) && $qNotice !== '' && isset($noticeMsgs[$qNotice])
                        ? $noticeMsgs[$qNotice]
                        : null
                ),
                qError: @json(is_string($qError) && $qError !== '' ? $qError : null),
                sSuccess: @json($sSuccess),
                sError: @json($sError),
                sWarning: @json($sWarning),
                sInfo: @json($sInfo)
            };

            function fallbackBanner(text, variant) {
                if (!text) {
                    return;
                }
                var wrap = document.createElement('div');
                wrap.setAttribute('role', 'status');
                wrap.style.cssText = 'position:fixed;top:1rem;left:50%;transform:translateX(-50%);z-index:2147483646;max-width:min(520px,94vw);padding:12px 16px;border-radius:10px;font:600 14px/1.4 system-ui,sans-serif;box-shadow:0 8px 32px rgba(0,0,0,.2);';
                wrap.style.background = variant === 'error' ? '#b42318' : '#0f5132';
                wrap.style.color = '#fff';
                wrap.textContent = text;
                document.body.appendChild(wrap);
                window.setTimeout(function () {
                    if (wrap.parentNode) {
                        wrap.parentNode.removeChild(wrap);
                    }
                }, 9000);
            }

            function tryShow() {
                if (typeof window.toastr === 'undefined' || !window.toastr.success) {
                    return false;
                }
                window.toastr.options.closeButton = true;
                window.toastr.options.progressBar = true;
                window.toastr.options.timeOut = 8000;

                var useQuery =
                    payload.query &&
                    (payload.qSuccess || payload.qNoticeText || payload.qError);
                if (useQuery) {
                    if (payload.qSuccess) {
                        window.toastr.success(payload.qSuccess);
                    } else if (payload.qNoticeText) {
                        window.toastr.success(payload.qNoticeText);
                    } else if (payload.qError) {
                        window.toastr.error(payload.qError);
                    }
                } else {
                    if (payload.sSuccess) {
                        window.toastr.success(payload.sSuccess);
                    }
                    if (payload.sError) {
                        window.toastr.error(payload.sError);
                    }
                    if (payload.sWarning) {
                        window.toastr.warning(payload.sWarning);
                    }
                    if (payload.sInfo) {
                        window.toastr.info(payload.sInfo);
                    }
                }
                return true;
            }

            var sifecToastrFlashStarted = false;
            function startPolling() {
                if (sifecToastrFlashStarted) {
                    return;
                }
                sifecToastrFlashStarted = true;
                window.setTimeout(function () {
                    if (tryShow()) {
                        return;
                    }
                    var attempts = 0;
                    var id = window.setInterval(function () {
                        attempts += 1;
                        var shown = tryShow();
                        if (shown || attempts > 80) {
                            window.clearInterval(id);
                            if (! shown && attempts > 80) {
                                if (payload.query) {
                                    if (payload.qSuccess) {
                                        fallbackBanner(payload.qSuccess, 'success');
                                    } else if (payload.qNoticeText) {
                                        fallbackBanner(payload.qNoticeText, 'success');
                                    } else if (payload.qError) {
                                        fallbackBanner(payload.qError, 'error');
                                    }
                                } else {
                                    if (payload.sSuccess) {
                                        fallbackBanner(payload.sSuccess, 'success');
                                    }
                                    if (payload.sError) {
                                        fallbackBanner(payload.sError, 'error');
                                    }
                                    if (payload.sWarning) {
                                        fallbackBanner(payload.sWarning, 'success');
                                    }
                                    if (payload.sInfo) {
                                        fallbackBanner(payload.sInfo, 'success');
                                    }
                                }
                            }
                        }
                    }, 50);
                }, 0);
            }

            if ($jq && $jq.fn) {
                $jq(startPolling);
            } else {
                window.addEventListener('DOMContentLoaded', startPolling);
                window.addEventListener('load', startPolling);
            }
        })();
    </script>
@endif
