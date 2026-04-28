<div
    x-cloak
    x-data="{
        open: false,
        form: null,
        title: 'Keluar dari sesi Anda?',
        message: 'Apakah Anda yakin ingin keluar dari akun ini?',
        confirm() {
            if (!this.form) return;
            this.form.dataset.logoutConfirmed = 'true';
            this.form.submit();
            this.close();
        },
        close() {
            this.open = false;
            this.form = null;
        }
    }"
    x-on:open-logout-confirm.window="
        form = $event.detail.form;
        title = $event.detail.title || 'Keluar dari sesi Anda?';
        message = $event.detail.message || 'Apakah Anda yakin ingin keluar?';
        open = true;
    "
    x-on:keydown.escape.window="close()"
    x-show="open"
    class="fixed inset-0 z-[120] flex items-center justify-center p-4"
>
    <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm" @click="close()"></div>

    <div
        x-show="open"
        x-transition.opacity.scale.90
        class="relative w-full max-w-md overflow-hidden rounded-[2rem] border border-white/70 bg-white/95 p-6 shadow-[0px_30px_60px_rgba(15,23,42,0.22)] backdrop-blur-xl sm:p-7"
    >
        <div class="flex items-start gap-4">

            <div class="min-w-0 flex-1">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-rose-700">Konfirmasi Logout</p>
                <h3 class="mt-2 text-xl font-black tracking-tight text-slate-900" x-text="title"></h3>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <button
                type="button"
                @click="close()"
                class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                Batal
            </button>
            <button
                type="button"
                @click="confirm()"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 transition hover:bg-rose-700"
            >
                Ya, keluar
            </button>
        </div>
    </div>
</div>

<script>
    if (!window.__logoutConfirmInitialized) {
        window.__logoutConfirmInitialized = true;

        document.addEventListener('submit', function (event) {
            const form = event.target;

            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (!form.matches('[data-logout-confirm]') || form.dataset.logoutConfirmed === 'true') {
                return;
            }

            event.preventDefault();

            window.dispatchEvent(new CustomEvent('open-logout-confirm', {
                detail: {
                    form,
                    title: form.dataset.confirmTitle || 'Keluar dari sesi Anda?',
                    message: form.dataset.confirmMessage || 'Apakah Anda yakin ingin keluar?'
                }
            }));
        });
    }
</script>
