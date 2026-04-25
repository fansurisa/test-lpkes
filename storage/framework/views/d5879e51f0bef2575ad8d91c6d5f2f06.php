<?php $__env->startSection('title', 'Lengkapi Profil'); ?>

<?php $__env->startSection('content'); ?>
    <h2 class="text-2xl font-bold text-gray-900 mb-1">Lengkapi Profil Anda</h2>
    <p class="text-sm text-gray-500 mb-6">Informasi ini diperlukan untuk mengakses pelatihan</p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert-error mb-4">
            <ul class="list-disc list-inside space-y-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('profile.complete.store')); ?>" class="space-y-4"
          x-data="{ userType: '<?php echo e(old('user_type', '')); ?>' }">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="name" class="label">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="input"
                   value="<?php echo e(old('name', auth()->user()->name)); ?>" required>
        </div>

        <div class="form-group">
            <label for="phone" class="label">Nomor HP / WhatsApp</label>
            <input type="tel" id="phone" name="phone" class="input"
                   value="<?php echo e(old('phone')); ?>" placeholder="08xx-xxxx-xxxx" required>
        </div>

        <div class="form-group">
            <label class="label">Saya adalah</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="user_type" value="nakes" x-model="userType"
                           class="sr-only" <?php echo e(old('user_type') === 'nakes' ? 'checked' : ''); ?>>
                    <div :class="userType === 'nakes' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                         class="border-2 rounded-lg p-3 text-center transition-colors">
                        <div class="font-semibold text-sm">Tenaga Kesehatan</div>
                        <div class="text-xs mt-0.5">(Nakes)</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="user_type" value="non_nakes" x-model="userType"
                           class="sr-only" <?php echo e(old('user_type') === 'non_nakes' ? 'checked' : ''); ?>>
                    <div :class="userType === 'non_nakes' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                         class="border-2 rounded-lg p-3 text-center transition-colors">
                        <div class="font-semibold text-sm">Masyarakat Umum</div>
                        <div class="text-xs mt-0.5">(Non-Nakes)</div>
                    </div>
                </label>
            </div>
        </div>

        
        <div x-show="userType === 'nakes'" x-transition class="form-group">
            <label for="str_number" class="label">Nomor STR <span class="text-red-500">*</span></label>
            <input type="text" id="str_number" name="str_number" class="input"
                   value="<?php echo e(old('str_number')); ?>" placeholder="Nomor Surat Tanda Registrasi">
            <p class="text-xs text-gray-500 mt-1">Wajib diisi untuk Tenaga Kesehatan</p>
        </div>

        <div class="form-group">
            <label for="profession" class="label">Profesi</label>
            <select id="profession" name="profession" class="input" required>
                <option value="" disabled selected>Pilih profesi</option>
                <option value="dokter"       <?php echo e(old('profession') === 'dokter' ? 'selected' : ''); ?>>Dokter</option>
                <option value="perawat"      <?php echo e(old('profession') === 'perawat' ? 'selected' : ''); ?>>Perawat</option>
                <option value="bidan"        <?php echo e(old('profession') === 'bidan' ? 'selected' : ''); ?>>Bidan</option>
                <option value="apoteker"     <?php echo e(old('profession') === 'apoteker' ? 'selected' : ''); ?>>Apoteker</option>
                <option value="analis"       <?php echo e(old('profession') === 'analis' ? 'selected' : ''); ?>>Analis Kesehatan</option>
                <option value="radiografer"  <?php echo e(old('profession') === 'radiografer' ? 'selected' : ''); ?>>Radiografer</option>
                <option value="fisioterapis" <?php echo e(old('profession') === 'fisioterapis' ? 'selected' : ''); ?>>Fisioterapis</option>
                <option value="gizi"         <?php echo e(old('profession') === 'gizi' ? 'selected' : ''); ?>>Ahli Gizi</option>
                <option value="rekam_medis"  <?php echo e(old('profession') === 'rekam_medis' ? 'selected' : ''); ?>>Rekam Medis</option>
                <option value="lainnya"      <?php echo e(old('profession') === 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
            </select>
        </div>

        <button type="submit" class="btn-primary w-full mt-2">
            Simpan & Lanjutkan
        </button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/fansuriarrumi/Documents/lpkesolutions/resources/views/auth/complete-profile.blade.php ENDPATH**/ ?>