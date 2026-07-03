<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reverb Broadcast Tester</title>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-slate-100 text-slate-900 antialiased dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 dark:text-slate-100">
    <div class="mx-auto flex min-h-screen w-full max-w-2xl flex-col gap-6 px-4 py-10 sm:py-16">

        <header class="text-center">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">Reverb Broadcast Tester</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Gửi request đến <code class="rounded bg-slate-200/70 px-1.5 py-0.5 text-slate-700 dark:bg-slate-800 dark:text-slate-300">POST /api/broadcast-test</code>
                và lắng nghe sự kiện qua WebSocket theo thời gian thực.
            </p>
        </header>

        <section class="rounded-2xl border border-slate-200/80 bg-white/70 p-5 shadow-sm backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/60">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Trạng thái kết nối</span>
                <span id="status-badge" class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                    <span id="status-dot" class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    <span id="status-text">Đang kết nối…</span>
                </span>
            </div>
            <dl class="mt-4 grid grid-cols-2 gap-3 text-xs text-slate-500 dark:text-slate-400">
                <div class="rounded-lg bg-slate-100/80 px-3 py-2 dark:bg-slate-800/60">
                    <dt class="font-medium text-slate-400 dark:text-slate-500">Channel</dt>
                    <dd class="mt-0.5 font-mono text-slate-700 dark:text-slate-200">test-channel</dd>
                </div>
                <div class="rounded-lg bg-slate-100/80 px-3 py-2 dark:bg-slate-800/60">
                    <dt class="font-medium text-slate-400 dark:text-slate-500">Event</dt>
                    <dd class="mt-0.5 font-mono text-slate-700 dark:text-slate-200">test.event</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-2xl border border-slate-200/80 bg-white/70 p-5 shadow-sm backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/60">
            <form id="send-form" class="flex flex-col gap-3 sm:flex-row">
                <input
                    id="message-input"
                    type="text"
                    placeholder="Nhập nội dung cần broadcast…"
                    class="flex-1 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500/20"
                >
                <button
                    id="send-button"
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <svg id="send-spinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span>Gửi sự kiện</span>
                </button>
            </form>
            <p id="send-error" class="mt-2 hidden text-xs text-red-500"></p>
        </section>

        <section class="flex flex-1 flex-col rounded-2xl border border-slate-200/80 bg-white/70 p-5 shadow-sm backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/60">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-medium text-slate-600 dark:text-slate-300">Nhật ký sự kiện</h2>
                <button id="clear-log" type="button" class="text-xs text-slate-400 transition hover:text-slate-600 dark:hover:text-slate-200">Xoá log</button>
            </div>
            <ul id="log-list" class="mt-3 flex-1 space-y-2 overflow-y-auto text-sm" style="max-height: 22rem;">
                <li id="log-empty" class="rounded-lg border border-dashed border-slate-200 px-3 py-6 text-center text-xs text-slate-400 dark:border-slate-700 dark:text-slate-500">
                    Chưa có sự kiện nào. Gửi thử ở trên để xem log real-time.
                </li>
            </ul>
        </section>
    </div>

    <script type="module">
        const statusDot = document.getElementById('status-dot');
        const statusText = document.getElementById('status-text');
        const statusBadge = document.getElementById('status-badge');
        const form = document.getElementById('send-form');
        const input = document.getElementById('message-input');
        const button = document.getElementById('send-button');
        const spinner = document.getElementById('send-spinner');
        const errorEl = document.getElementById('send-error');
        const logList = document.getElementById('log-list');
        const logEmpty = document.getElementById('log-empty');
        const clearLogBtn = document.getElementById('clear-log');

        const STATUS_STYLES = {
            connecting: ['bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400', 'bg-amber-500', 'Đang kết nối…'],
            connected: ['bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400', 'bg-emerald-500', 'Đã kết nối'],
            disconnected: ['bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400', 'bg-red-500', 'Mất kết nối'],
        };

        function setStatus(state) {
            const [badgeClass, dotClass, label] = STATUS_STYLES[state];
            statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium ' + badgeClass;
            statusDot.className = 'h-1.5 w-1.5 rounded-full ' + dotClass;
            statusText.textContent = label;
        }

        function addLogEntry({ direction, message }) {
            logEmpty.remove();

            const time = new Date().toLocaleTimeString('vi-VN', { hour12: false });
            const isReceived = direction === 'received';

            const li = document.createElement('li');
            li.className = 'flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50/80 px-3 py-2 dark:border-slate-800 dark:bg-slate-800/40';
            li.innerHTML = `
                <span class="mt-0.5 inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide ${isReceived ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400' : 'bg-slate-200 text-slate-500 dark:bg-slate-700 dark:text-slate-300'}">
                    ${isReceived ? 'Nhận' : 'Gửi'}
                </span>
                <span class="flex-1 break-words text-slate-700 dark:text-slate-200"></span>
                <span class="shrink-0 font-mono text-[11px] text-slate-400 dark:text-slate-500">${time}</span>
            `;
            li.querySelector('span.flex-1').textContent = message;

            logList.prepend(li);
        }

        clearLogBtn.addEventListener('click', () => {
            logList.innerHTML = '';
            logList.appendChild(logEmpty);
        });

        function bindEcho() {
            if (!window.Echo) {
                setTimeout(bindEcho, 100);
                return;
            }

            const pusher = window.Echo.connector.pusher;
            setStatus(pusher.connection.state === 'connected' ? 'connected' : 'connecting');

            pusher.connection.bind('state_change', ({ current }) => {
                if (current === 'connected') setStatus('connected');
                else if (current === 'connecting') setStatus('connecting');
                else setStatus('disconnected');
            });

            window.Echo.channel('test-channel').listen('.test.event', (payload) => {
                addLogEntry({ direction: 'received', message: payload.message ?? JSON.stringify(payload) });
            });
        }

        bindEcho();

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            errorEl.classList.add('hidden');

            const message = input.value.trim() || undefined;

            button.disabled = true;
            spinner.classList.remove('hidden');

            try {
                const response = await fetch('/api/broadcast-test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Gửi sự kiện thất bại.');
                }

                addLogEntry({ direction: 'sent', message: data.message });
                input.value = '';
            } catch (error) {
                errorEl.textContent = error.message;
                errorEl.classList.remove('hidden');
            } finally {
                button.disabled = false;
                spinner.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
