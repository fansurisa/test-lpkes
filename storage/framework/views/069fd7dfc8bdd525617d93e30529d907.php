<article class="card overflow-hidden hover:shadow-md transition-shadow group">
    <a href="<?php echo e(route('catalog.show', $training->slug)); ?>" class="block">
        
        <div class="relative h-44 bg-gray-100 overflow-hidden">
            <img src="<?php echo e($training->thumbnail_url); ?>"
                 alt="<?php echo e($training->title); ?>"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            
            <div class="absolute top-3 left-3 flex gap-1.5">
                <span class="<?php echo e($training->type === 'event' ? 'badge-event' : 'badge-ecourse'); ?>">
                    <?php echo e($training->type_label); ?>

                </span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($training->hasSkp()): ?>
                    <span class="badge-skp">
                        <?php echo e($training->skp_value); ?> SKP
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($training->is_free): ?>
                <div class="absolute top-3 right-3">
                    <span class="badge-free">GRATIS</span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="p-4">
            
            <div class="text-xs font-medium text-primary-600 mb-1.5">
                <?php echo e($training->category->name); ?>

            </div>

            
            <h3 class="font-semibold text-gray-900 text-sm leading-snug mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                <?php echo e($training->title); ?>

            </h3>

            
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                <span class="<?php echo e($training->is_free ? 'text-green-600 font-bold' : 'text-gray-900 font-bold'); ?> text-sm">
                    <?php echo e($training->formatted_price); ?>

                </span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($training->enrollments_count > 0): ?>
                    <span class="text-xs text-gray-400">
                        <?php echo e(number_format($training->enrollments_count)); ?> peserta
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </a>
</article>
<?php /**PATH /Users/fansuriarrumi/Documents/lpkesolutions/resources/views/components/training-card.blade.php ENDPATH**/ ?>