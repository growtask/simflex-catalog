<!--<a class="btn btn-xs btn-primary" href="?action=form&--><?php //echo $this->pk->name, '=', $row[$this->pk->name] ?><!--" title="Редактировать">-->
<!--    <i class="icon-note"></i>-->
<!--</a>-->

<?php
$subs = \Simflex\Core\DB::assoc('select distinct sub_num, (select count(*) from catalog_order_sub_dl dl where dl.order_id = os.order_id and dl.sub_num = os.sub_num) as is_dl from catalog_order_product_sub os 
                        where order_id = ?', false, false, [$row['order_id']]);
?>

<div class="table__body-item-btns">
    <div class="tooltip tooltip-row1-edit" data-tip="Редактировать">
        <a href="?action=form&<?php echo $this->pk->name, '=', $row[$this->pk->name] ?>" class="BtnIconPrimaryXs">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M11 3.99998H6.8C5.11984 3.99998 4.27976 3.99998 3.63803 4.32696C3.07354 4.61458 2.6146 5.07353 2.32698 5.63801C2 6.27975 2 7.11983 2 8.79998V17.2C2 18.8801 2 19.7202 2.32698 20.362C2.6146 20.9264 3.07354 21.3854 3.63803 21.673C4.27976 22 5.11984 22 6.8 22H15.2C16.8802 22 17.7202 22 18.362 21.673C18.9265 21.3854 19.3854 20.9264 19.673 20.362C20 19.7202 20 18.8801 20 17.2V13M7.99997 16H9.67452C10.1637 16 10.4083 16 10.6385 15.9447C10.8425 15.8957 11.0376 15.8149 11.2166 15.7053C11.4184 15.5816 11.5914 15.4086 11.9373 15.0627L21.5 5.49998C22.3284 4.67156 22.3284 3.32841 21.5 2.49998C20.6716 1.67156 19.3284 1.67155 18.5 2.49998L8.93723 12.0627C8.59133 12.4086 8.41838 12.5816 8.29469 12.7834C8.18504 12.9624 8.10423 13.1574 8.05523 13.3615C7.99997 13.5917 7.99997 13.8363 7.99997 14.3255V16Z"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
            </svg>

        </a>
    </div>
    <div class="tooltip tooltip-row1-delete" data-tip="Удалить" onclick="TableEditor.onRowDeleteRequest(<?= $row[$this->pk->name] ?>, 'content_table'); return false">
        <button href="?action=delete&rows[]=<?=$row[$this->pk->name]?>" class="BtnIconSecondaryXs">
            <svg width="17" height="16" viewBox="0 0 17 16" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M6.5 2H10.5M2.5 4H14.5M13.1667 4L12.6991 11.0129C12.629 12.065 12.5939 12.5911 12.3667 12.99C12.1666 13.3412 11.8648 13.6235 11.5011 13.7998C11.088 14 10.5607 14 9.50623 14H7.49377C6.43927 14 5.91202 14 5.49889 13.7998C5.13517 13.6235 4.83339 13.3412 4.63332 12.99C4.40607 12.5911 4.371 12.065 4.30086 11.0129L3.83333 4"
                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
            </svg>
        </button>
    </div>
    <div class="tooltip tooltip-row1-info" data-tip="Информация">
        <button class="BtnIconSecondaryMonoXs" onclick="showInfoModal('<?=$row[$this->pk->name]?>', '<?=$this->pk->name?>')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    <div class="tooltip tooltip-row1-dl" data-tip="Распечатать заказ">
        <a target="_blank" href="./?action=renderDoc&order_id=<?=$row['order_id']?>&tpl=zakaz&print=1" class="BtnIconPrimaryXs">
            <svg viewBox="0 0 16 16" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M9.335 7.333h-4M6.668 10H5.335m5.333-5.333H5.335m8-.134v6.934c0 1.12 0 1.68-.218 2.108a2 2 0 01-.874.874c-.428.218-.988.218-2.108.218H5.868c-1.12 0-1.68 0-2.108-.218a2 2 0 01-.874-.874c-.218-.428-.218-.988-.218-2.108V4.533c0-1.12 0-1.68.218-2.108a2 2 0 01.874-.874c.428-.218.988-.218 2.108-.218h4.267c1.12 0 1.68 0 2.108.218a2 2 0 01.874.874c.218.428.218.988.218 2.108z"
                        stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
    <?php foreach ($subs as $sub): ?>
        <div class="tooltip tooltip-row1-dl" data-tip="Распечатать дозаказ №<?=$sub['sub_num']?>">
            <a <?=$sub['is_dl']?'disabled':''?> <?php if ($sub['is_dl']): ?> href="#" <?php else: ?> target="_blank" href="./?action=renderDoc&order_id=<?=$row['order_id']?>&tpl=zakaz&print=1&sub=<?=$sub['sub_num']?>"<?php endif; ?> class="BtnIconSecondaryMonoXs <?=$sub['is_dl']?'disabled':''?> ">
                <svg viewBox="0 0 16 16" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M9.335 7.333h-4M6.668 10H5.335m5.333-5.333H5.335m8-.134v6.934c0 1.12 0 1.68-.218 2.108a2 2 0 01-.874.874c-.428.218-.988.218-2.108.218H5.868c-1.12 0-1.68 0-2.108-.218a2 2 0 01-.874-.874c-.218-.428-.218-.988-.218-2.108V4.533c0-1.12 0-1.68.218-2.108a2 2 0 01.874-.874c.428-.218.988-.218 2.108-.218h4.267c1.12 0 1.68 0 2.108.218a2 2 0 01.874.874c.218.428.218.988.218 2.108z"
                            stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<div class="table__body-item-settings">
    <div class="table__body-item-settings-toggle">
        <button type="button" class="BtnIconPrimarySm">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                <path
                        d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                <path
                        d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
            </svg>
        </button>
    </div>
    <div class="table__body-item-settings-btns">
        <button type="button"
                class="table__body-item-settings-btn table__body-item-settings-close BtnIconPrimarySm">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"
                      fill="#ffffff" />
            </svg>

        </button>
        <a
                href="?action=form&<?php echo $this->pk->name, '=', $row[$this->pk->name] ?>"
                class="table__body-item-settings-btn BtnIconPrimarySm">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M11 3.99998H6.8C5.11984 3.99998 4.27976 3.99998 3.63803 4.32696C3.07354 4.61458 2.6146 5.07353 2.32698 5.63801C2 6.27975 2 7.11983 2 8.79998V17.2C2 18.8801 2 19.7202 2.32698 20.362C2.6146 20.9264 3.07354 21.3854 3.63803 21.673C4.27976 22 5.11984 22 6.8 22H15.2C16.8802 22 17.7202 22 18.362 21.673C18.9265 21.3854 19.3854 20.9264 19.673 20.362C20 19.7202 20 18.8801 20 17.2V13M7.99997 16H9.67452C10.1637 16 10.4083 16 10.6385 15.9447C10.8425 15.8957 11.0376 15.8149 11.2166 15.7053C11.4184 15.5816 11.5914 15.4086 11.9373 15.0627L21.5 5.49998C22.3284 4.67156 22.3284 3.32841 21.5 2.49998C20.6716 1.67156 19.3284 1.67155 18.5 2.49998L8.93723 12.0627C8.59133 12.4086 8.41838 12.5816 8.29469 12.7834C8.18504 12.9624 8.10423 13.1574 8.05523 13.3615C7.99997 13.5917 7.99997 13.8363 7.99997 14.3255V16Z"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
            </svg>
        </a>
        <a
                href="?action=form&<?php echo $this->pk->name, '=', $row[$this->pk->name] ?>"
                class="table__body-item-settings-btn BtnIconSecondarySm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        <button
                onclick="showInfoModal('<?=$row[$this->pk->name]?>', '<?=$this->pk->name?>')"
                type="button"
                class="table__body-item-settings-btn BtnIconSecondaryMonoSm">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
            </svg>

        </button>
        <a
                target="_blank" href="./?action=renderDoc&order_id=<?=$row['order_id']?>&tpl=zakaz&print=1"
                class="table__body-item-settings-btn BtnIconPrimarySm">
            <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15v1.2c0 1.68 0 2.52-.327 3.162a3 3 0 01-1.311 1.311C18.72 21 17.88 21 16.2 21H7.8c-1.68 0-2.52 0-3.162-.327a3 3 0 01-1.311-1.311C3 18.72 3 17.88 3 16.2V15m14-5l-5 5m0 0l-5-5m5 5V3"
                      stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>

        </a>
        <?php foreach ($subs as $sub): ?>
        <a
            <?=$sub['is_dl'] ? 'disabled' : ''?>
                target="_blank"
            <?php if ($sub['is_dl']): ?> href="#" <?php else: ?> href="./?action=renderDoc&order_id=<?=$row['order_id']?>&tpl=zakaz&print=1&sub=<?=$sub['sub_num']?>" <?php endif; ?>
                class="table__body-item-settings-btn BtnIconSecondaryMonoSm <?= $sub['is_dl'] ? 'disabled' : ''?>" data-tip="Распечатать дозаказ №<?=$sub['sub_num']?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M14 11H8M10 15H8M16 7H8M20 6.8V17.2C20 18.8802 20 19.7202 19.673 20.362C19.3854 20.9265 18.9265 21.3854 18.362 21.673C17.7202 22 16.8802 22 15.2 22H8.8C7.11984 22 6.27976 22 5.63803 21.673C5.07354 21.3854 4.6146 20.9265 4.32698 20.362C4 19.7202 4 18.8802 4 17.2V6.8C4 5.11984 4 4.27976 4.32698 3.63803C4.6146 3.07354 5.07354 2.6146 5.63803 2.32698C6.27976 2 7.11984 2 8.8 2H15.2C16.8802 2 17.7202 2 18.362 2.32698C18.9265 2.6146 19.3854 3.07354 19.673 3.63803C20 4.27976 20 5.11984 20 6.8Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

        </a>
        <?php endforeach; ?>
    </div>
</div>