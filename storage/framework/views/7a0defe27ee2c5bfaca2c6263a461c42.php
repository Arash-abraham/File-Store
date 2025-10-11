<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?php echo $__env->yieldContent('title','عنوان صفحه'); ?>
</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
<?php echo $__env->yieldContent('styles'); ?>


<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    'sans': ['Vazirmatn', 'system-ui', 'sans-serif'],
                }
            }
        }
    }
</script><?php /**PATH /opt/lampp/htdocs/File-Store/resources/views/app/layouts/head-tag.blade.php ENDPATH**/ ?>