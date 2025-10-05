@extends('admin.master', ['menu' => 'AI'])
@section('title', isset($title) ? $title : 'AI Chat')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-12 col-lg-10 col-xl-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">AI Chat</h5>
                    <small id="aiChatStatus" class="text-muted">Ready</small>
                </div>

                <div class="card-body p-0">
                    <!-- Messages -->
                    <div id="aiChatScroll" class="p-3 chat-scroll bg-light" style="height: 60vh; overflow-y:auto;">
                        <!-- Example welcome -->
                        <div class="d-flex align-items-start mb-3">
                            <div class="bubble bubble-assistant">
                                <div class="bubble-meta">Assistant</div>
                                <div class="bubble-text">Hello! Ask me anything.</div>
                            </div>
                        </div>
                        <!-- Typing indicator -->
                        <div id="aiTyping" class="d-none align-items-center gap-1 text-muted ps-1">
                            <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                            <small class="ms-2">Thinking…</small>
                        </div>
                    </div>
                </div>

                <!-- Composer -->
                <div class="card-footer bg-white">
                    <form id="aiChatForm" class="w-100">
                        <div class="input-group">
                            <textarea id="aiChatInput" class="form-control" rows="1" placeholder="Type your message…"
                                style="resize:none"></textarea>
                            <button type="button" id="aiChatClear" class="btn btn-outline-secondary">Clear</button>
                            <button type="submit" id="aiChatSend" class="btn btn-primary">Send</button>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-muted">
                            <div>
                                Press <kbd>Enter</kbd> to send • <kbd>Shift</kbd>+<kbd>Enter</kbd> for newline
                            </div>
                            <div id="aiChatError" class="text-danger"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('post_styles')
    <style>
        /* Chat bubbles */
        .bubble {
            max-width: 85%;
            border-radius: 1rem;
            padding: .5rem .75rem;
            position: relative;
            word-wrap: break-word;
        }

        .bubble-user {
            background: #0d6efd;
            color: #fff;
            border-top-right-radius: .25rem;
            margin-left: auto;
        }

        .bubble-assistant {
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #212529;
            border-top-left-radius: .25rem;
        }

        .bubble-meta {
            font-size: .65rem;
            text-transform: uppercase;
            opacity: .65;
            margin-bottom: .25rem;
        }

        .bubble-text {
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Typing dots */
        #aiTyping .dot {
            width: 6px;
            height: 6px;
            background: #adb5bd;
            border-radius: 50%;
            display: inline-block;
            animation: bounce 1s infinite ease-in-out;
        }

        #aiTyping .dot:nth-child(1) {
            animation-delay: 0s
        }

        #aiTyping .dot:nth-child(2) {
            animation-delay: .15s
        }

        #aiTyping .dot:nth-child(3) {
            animation-delay: .3s
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0)
            }

            40% {
                transform: scale(1)
            }
        }

        /* RTL awareness: align bubbles */
        body.direction-rtl .bubble-user {
            margin-right: auto;
            margin-left: 0;
            border-top-left-radius: .25rem;
            border-top-right-radius: 1rem;
        }

        body.direction-rtl .bubble-assistant {
            margin-left: auto;
            margin-right: 0;
            border-top-right-radius: .25rem;
            border-top-left-radius: 1rem;
        }
    </style>
@endpush

@push('post_scripts')
<script>
(function () {
    const elScroll = document.getElementById('aiChatScroll');
    const elForm = document.getElementById('aiChatForm');
    const elInput = document.getElementById('aiChatInput');
    const elClear = document.getElementById('aiChatClear');
    const elTyping = document.getElementById('aiTyping');
    const elStatus = document.getElementById('aiChatStatus');
    const elError = document.getElementById('aiChatError');
    const CSRF = document.querySelector('meta[name=csrf-token]').content;
    const isRTL = document.body.classList.contains('direction-rtl');

    let loading = false;
    let messages = [{ role: 'assistant', content: 'Hello! Ask me anything.' }];

    function esc(html) {
        return html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }
    function md(s) {
        return esc(s)
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            .replace(/`(.+?)`/g, "<code class='bg-light px-1 rounded'>$1</code>")
            .replace(/\n/g, '<br>');
    }
    function scrollBottom() { elScroll.scrollTop = elScroll.scrollHeight; }

    function addBubble(role, content) {
        const isUser = role === 'user';
        const row = document.createElement('div');
        row.className = 'd-flex align-items-start mb-3 ' + (isUser ? (isRTL ? '' : 'justify-content-end') : '');
        const bubble = document.createElement('div');
        bubble.className = 'bubble ' + (isUser ? 'bubble-user' : 'bubble-assistant');
        bubble.innerHTML =
            `<div class="bubble-meta">${isUser ? (isRTL ? 'أنت' : 'You') : (isRTL ? 'المساعد' : 'Assistant')}</div>
             <div class="bubble-text">${md(content)}</div>`;
        row.appendChild(bubble);
        elScroll.appendChild(row);
        scrollBottom();
    }

    function showTyping(show) {
        elTyping.classList.toggle('d-none', !show);
        if (show) scrollBottom();
    }
    function setStatus(txt) { elStatus.textContent = txt; }
    function setError(txt) { elError.textContent = txt || ''; }

    function autoResize() {
        elInput.style.height = 'auto';
        elInput.style.height = Math.min(elInput.scrollHeight, 200) + 'px';
    }
    elInput.addEventListener('input', autoResize);

    // Enter to send, Shift+Enter newline
    elInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            elForm.dispatchEvent(new Event('submit'));
        }
    });

    elClear.addEventListener('click', function () {
        elScroll.innerHTML = '';
        messages = [];
        addBubble('assistant', isRTL ? 'تم مسح المحادثة. كيف أساعدك؟' : 'Chat cleared. How can I help?');
    });

    elForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (loading) return;
        const text = elInput.value.trim();
        if (!text) return;

        setError('');
        loading = true;
        setStatus(isRTL ? 'يتم الإرسال…' : 'Sending…');
        addBubble('user', text);
        messages.push({ role: 'user', content: text });
        elInput.value = '';
        autoResize();
        showTyping(true);

        const payload = { message: text, history: messages.slice(-10) };

        try {
            const res = await fetch("{{ route('admin.ai.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                throw new Error("HTTP error " + res.status);
            }

            const json = await res.json();

            if (!json.ok) {
                throw new Error(json.error || 'Unknown error');
            }

            // Add AI response bubble
            addBubble('assistant', json.ai_text || (isRTL ? 'لا توجد إجابة' : 'No reply'));

            // Save to history
            messages.push({ role: 'assistant', content: json.ai_text });

            // Optionally: show executed SQL + results
            if (json.sql) {
                addBubble('assistant', (isRTL ? 'الاستعلام المُنفّذ:' : 'Executed SQL:') + "<br><code>" + esc(json.sql) + "</code>");
            }
            if (json.db_data && json.db_data.length) {
                const preview = JSON.stringify(json.db_data.slice(0, 3), null, 2);
                addBubble('assistant', (isRTL ? 'عينة من النتائج:' : 'Sample results:') + "<pre>" + esc(preview) + "</pre>");
            }

            setStatus('Ready');
        } catch (err) {
            console.error(err);
            setError(isRTL ? 'حدث خطأ. حاول مرة أخرى.' : 'Something went wrong. Please try again.');
            setStatus('Error');
        } finally {
            loading = false;
            showTyping(false);
        }
    });
})();
</script>
@endpush