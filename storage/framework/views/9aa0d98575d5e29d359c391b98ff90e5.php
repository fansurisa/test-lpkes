<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola informasi pribadi Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="card p-6 text-center">
            <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>"
                 class="w-24 h-24 rounded-full object-cover mx-auto mb-3 ring-4 ring-primary-100">
            <h3 class="font-semibold text-gray-900"><?php echo e($user->name); ?></h3>
            <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
            <div class="mt-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isNakes()): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                        Tenaga Kesehatan
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        Masyarakat Umum
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isNakes()): ?>
                <div class="mt-4 p-3 bg-gray-50 rounded-lg text-left">
                    <p class="text-xs text-gray-500">Nomor STR</p>
                    <p class="font-mono text-sm font-medium text-gray-900"><?php echo e($user->str_number ?: '-'); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="mt-3 p-3 bg-gray-50 rounded-lg text-left">
                <p class="text-xs text-gray-500">Total SKP</p>
                <p class="font-bold text-lg text-purple-600"><?php echo e($user->totalSkp()); ?></p>
            </div>
        </div>

        
        <div class="lg:col-span-2 card p-6">
            <h2 class="font-semibold text-gray-900 mb-6">Edit Informasi</h2>

            <form method="POST" action="<?php echo e(route('dashboard.profile.update')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="alert-error">
                        <ul class="list-disc list-inside space-y-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="name" class="label">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="input"
                               value="<?php echo e(old('name', $user->name)); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="label">Nomor HP</label>
                        <input type="tel" id="phone" name="phone" class="input"
                               value="<?php echo e(old('phone', $user->phone)); ?>" placeholder="08xx-xxxx-xxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="label">Email</label>
                    <input type="email" class="input bg-gray-50 cursor-not-allowed"
                           value="<?php echo e($user->email); ?>" disabled>
                    <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah</p>
                </div>

                <div class="form-group">
                    <label for="profession" class="label">Profesi</label>
                    <select id="profession" name="profession" class="input" required>
                        <option value="" disabled>Pilih profesi</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            'dokter' => 'Dokter',
                            'perawat' => 'Perawat',
                            'bidan' => 'Bidan',
                            'apoteker' => 'Apoteker',
                            'analis' => 'Analis Kesehatan',
                            'radiografer' => 'Radiografer',
                            'fisioterapis' => 'Fisioterapis',
                            'gizi' => 'Ahli Gizi',
                            'rekam_medis' => 'Rekam Medis',
                            'lainnya' => 'Lainnya',
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php echo e(old('profession', $user->profession) === $val ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isNakes()): ?>
                    <div class="form-group">
                        <label for="str_number" class="label">Nomor STR</label>
                        <input type="text" id="str_number" name="str_number" class="input"
                               value="<?php echo e(old('str_number', $user->str_number)); ?>"
                               placeholder="Nomor Surat Tanda Registrasi">
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/fansuriarrumi/Documents/lpkesolutions/resources/views/dashboard/profile.blade.php ENDPATH**/ ?>