<div class="content">
    <div class="content__inner">
        <div class="content__head">
            <div class="content__head-wrap">
                <div class="content__head-bg"></div>
                <div class="content__head-left">
                    <h4 class="content__title">Загрузка в VK</h4>
                </div>
            </div>
        </div>
        <div class="content__body">
            <?php \Simflex\Admin\Plugins\Alert\Alert::warning('Пожалуйста, не закрывайте вкладку до окончания загрузки!'); ?>
            <?php \Simflex\Admin\Plugins\Alert\Alert::output() ?>

            <div class="data-point">
                <h2>Загружено: <?=$fin?> / <?=$tot?></h2>
            </div>

            <br/>

            <div class="data-point">
                <p>Загружается:</p>
                <ul>
                    <?php foreach ($next as $n): ?>
                    <li><?=$n->name?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php //if ($ret == \App\Extensions\Catalog\VkUpload::STATUS_NEXT): ?>
<script>
    setTimeout(function () {
        window.location.reload();
    }, 1000);
</script>
<?php //endif; ?>
