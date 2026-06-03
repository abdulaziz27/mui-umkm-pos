<div x-data="confirmDialog()"
     x-show="open"
     x-cloak
     @confirm.window="show($event.detail)"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="close()">
    </div>

    {{-- Dialog --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-md w-full overflow-hidden transition-all transform">

            {{-- Body --}}
            <div class="p-6">
                <div class="flex flex-col items-center text-center">
                    {{-- Dynamic Icon Container --}}
                    <div class="mb-4">
                        <template x-if="variant === 'danger'">
                            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </template>
                        <template x-if="variant === 'warning'">
                            <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        </template>
                        <template x-if="variant === 'success'">
                            <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </template>
                        <template x-if="['info', 'primary'].includes(variant)">
                            <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </template>
                    </div>

                    {{-- Text --}}
                    <h3 class="text-xl font-bold text-gray-900 mb-2" x-text="title"></h3>
                    <p class="text-sm text-gray-500 leading-relaxed" x-text="message"></p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 bg-gray-50 flex flex-row gap-3 border-t border-gray-100">
                <button @click="close()"
                        type="button"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 transition-colors shadow-sm"
                        x-text="cancelText">
                </button>
                <button @click="confirm()"
                        type="button"
                        :class="{
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white': variant === 'danger',
                            'bg-amber-500 hover:bg-amber-600 focus:ring-amber-400 text-white': variant === 'warning',
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white': variant === 'success',
                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white': ['info', 'primary'].includes(variant)
                        }"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all shadow-sm"
                        x-text="confirmText">
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDialog() {
    return {
        open: false,
        title: '',
        message: '',
        confirmText: 'Confirm',
        cancelText: 'Cancel',
        variant: 'danger',
        onConfirm: null,
        onCancel: null,

        show(options) {
            this.title = options.title || 'Konfirmasi';
            this.message = options.message || 'Apakah Anda yakin ingin melakukan tindakan ini?';
            this.confirmText = options.confirmText || 'Ya';
            this.cancelText = options.cancelText || 'Batal';
            this.variant = options.variant || 'danger';
            this.onConfirm = options.onConfirm || null;
            this.onCancel = options.onCancel || null;
            this.open = true;
        },

        confirm() {
            if (this.onConfirm && typeof this.onConfirm === 'function') {
                this.onConfirm();
            }
            this.open = false;
        },

        close() {
            if (this.onCancel && typeof this.onCancel === 'function') {
                this.onCancel();
            }
            this.open = false;
        }
    }
}
</script>
