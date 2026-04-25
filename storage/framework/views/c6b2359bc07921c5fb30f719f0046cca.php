<?php $__env->startSection('title', 'Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="card p-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran</h1>
            <p class="text-gray-500 text-sm mb-2"><?php echo e($training->title); ?></p>
            <p class="text-2xl font-extrabold text-primary-600 mb-6"><?php echo e($training->formatted_price); ?></p>

            <button id="pay-button" class="btn-primary w-full text-base py-3">
                Buka Halaman Pembayaran
            </button>
            <p class="text-xs text-gray-400 mt-3">
                Anda akan diarahkan ke halaman pembayaran Midtrans yang aman.
            </p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!app()->isProduction()): ?>
                <div class="mt-6 pt-6 border-t border-dashed border-gray-300">
                    <p class="text-xs text-gray-400 mb-2">⚙️ Development mode</p>
                    <form method="POST" action="<?php echo e(route('enrollments.skip', $training)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                class="w-full px-5 py-2.5 rounded-lg bg-amber-100 text-amber-800 font-semibold text-sm hover:bg-amber-200 border border-amber-300 transition-colors">
                            ⚡ Skip Pembayaran (Dev)
                        </button>
                    </form>
                    <p class="text-xs text-gray-400 mt-2">
                        Tombol ini akan hilang otomatis di production.
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            if (!'<?php echo e($order->snap_token); ?>') {
                alert('Snap token tidak tersedia. Pastikan MIDTRANS_SERVER_KEY sudah diisi di .env');
                return;
            }
            snap.pay('<?php echo e($order->snap_token); ?>', {
                onSuccess: function() { window.location.href = '<?php echo e(route('enrollments.success', $training->slug)); ?>'; },
                onPending: function() { window.location.href = '<?php echo e(route('enrollments.success', $training->slug)); ?>'; },
                onError:   function() { alert('Pembayaran gagal. Silakan coba lagi.'); },
                onClose:   function() { console.log('Popup ditutup'); }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/fansuriarrumi/Documents/lpkesolutions/resources/views/enrollments/payment.blade.php ENDPATH**/ ?>