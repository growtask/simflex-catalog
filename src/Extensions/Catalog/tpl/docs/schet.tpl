

<div id="result-generate-shop-document">


    <title>Заказ № <?= $o->order_id ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        table{ border-collapse: collapse; }
        table.acc td{ border: 1pt solid #000000; padding: 0pt 3pt; line-height: 21pt; }
        table.it td{ border: 1pt solid #000000; padding: 0pt 3pt; }
        table.sign td{ font-weight: bold; vertical-align: bottom; }
        table.header td{ padding: 0pt; vertical-align: top; }
    </style>




    <div style="margin: 0pt; padding: 42.51968503937pt 42.51968503937pt 42.51968503937pt 56.692913385827pt; width: 496.0674015748pt; background: #ffffff">

        <table class="header">
            <tbody><tr>
                <td>
                    <b></b><br></td>
            </tr>
            </tbody></table>

        <table class="acc" width="100%">
            <tbody><tr>
                <td>
                    <b>ПОСТАВЩИК:</b><br>
                    ИП ПУЧКИНА ИРИНА ВЛАДИМИРОВНА<br>
                    ИНН 541011248124 <br>
                    г.Новосибирск ул. Татьяны Снежиной 49/3, 1 <br>
                    тел 8(383)248-65-65,<br>
                    ОГРН 318547600187636.<br>

                    Номер р/счета 40802810144050042857<br>
                    БИК 045004641<br>
                    Корр. Счет 30101810500000000641<br>
                    ИНН банка 7707083893, КПП 540645005, ОРГН 1027700132195<br>
                    Наименование Банка: Новосибирское отделение №8047 ПАО СБЕРБАНК
                </td>
            </tr>
            <tr>
                <td>
                    <b>ЗАКАЗЧИК:</b><br>
                    <?php if ($o->org_active): ?>
                        <?=$o->org_name?>, ИНН <?=$o->org_inn?>
                    <?php else: ?>
                        <?= $o->user_last_name ?> <?= $o->user_name ?> <?= $o->user_patronym ?><br>
                    <?php endif; ?><br>
                    Телефон:  <?= $o->user_phone ?><br>
                    E-mail: <?= $o->user->email ?><br>
                    Город: <?= $o->user->city ?> <?= $o->user->address ?><br>
                </td>
            </tr>
            </tbody></table>
        <br>
        <br>


        <table width="100%">
            <colgroup>
                <col width="50%">
                <col width="0">
                <col width="50%">
            </colgroup>
            <tbody><tr>
                <td></td>
                <td style="font-size: 2em; font-weight: bold; text-align: center">
                    <nobr>СЧЕТ на заказ № <?= $o->order_id ?><br>от                     <?php
                        $d = getdate();
                        $date = $d['mday'] . '.' . $d['mon']  . '.'  . $d['year']
                        ?>
                        <?= $date ?></nobr>
                </td>
                <td></td>
            </tr>
            </tbody></table>
        <br><br><br>
        <table class="it" width="100%" cellpadding="10" cellspacing="10">
            <tbody><tr>
                <td align="center">№</td>
                <td align="center">Наименование товара</td>
                <td align="center">Кол-во</td>
                <td align="center">Ед.</td>
                <td align="center">Цена, руб.</td>
                <td align="center">Скидка, %</td>
                <td align="center">Цена, руб. с учетом скидки</td>
                <td align="center">Сумма, руб.</td>
            </tr>

            <?php $i = 1;
            foreach ($o->getProducts('', '', 'COALESCE(is_deleted, 0) = 0') as $pi): ?>
                <?php $p = $pi['product']; ?>
            <tr valign="top">
                <td align="center"><?= $i++ ?></td>
                <td align="left" style="word-break: break-word;">
                    <?= $p->name ?><br>
                    <small>Размер: <?= $pi['size'] ?></small>
                </td>
                <td align="center"><?= $pi['qty'] ?></td>
                <td align="center"> шт</td>
                <td align="center"><nobr><?=number_format($pi['price'], 2, '.',' ') ?></nobr></td>
                <td align="center"><?= $o->discount ?? 0 ?></td>
                <td align="center">
                    <nobr>
                        <?php if ($o->discount ?? 0): ?>
                            <?= number_format($pi['price'] - ($pi['price'] * ($o->discount * 0.01)), 2, '.', ' ') ?>
                        <?php else: ?>
                            <?= number_format($pi['price'], 2, '.',' ') ?>
                        <?php endif; ?>							</nobr>
                </td>
                <td align="center">
                    <nobr>
                        <?php if ($o->discount ?? 0): ?>
                            <?= number_format(($pi['price'] - ($pi['price'] * ($o->discount * 0.01))) * $pi['qty'], 2, '.', ' ') ?>
                        <?php else: ?>
                            <?= number_format($pi['price'] * $pi['qty'], 2, '.', ' ') ?>
                        <?php endif; ?>
                    </nobr>
                </td>
            </tr>
            <?php endforeach; ?>

            <tr valign="top">
                <td align="right" style="border-width: 0pt 1pt 0pt 0pt" colspan="7"><nobr>НДС:</nobr></td>
                <td align="center"><nobr>Без НДС</nobr></td>
            </tr>
            <tr valign="top">
                <td align="right" style="border-width: 0pt 1pt 0pt 0pt" colspan="7"><nobr>Без скидки:</nobr></td>
                <td align="center"><nobr><?= number_format($o->__sum_actual, 2, '.', ' ') ?></nobr></td>
            </tr>
            <tr valign="top">
                <td align="right" style="border-width: 0pt 1pt 0pt 0pt" colspan="7"><nobr>Итого:</nobr></td>
                <td align="center"><nobr>
                        <?php if ($o->discount ?? 0): ?>
                            <?= $o->getTotal2() ?>
                        <?php else: ?>
                            <?= $o->getTotal3() ?>
                        <?php endif; ?>														</nobr></td>
            </tr>
            </tbody></table>
        <br>

        <p style="text-transform:uppercase;"><b>Скидка от суммы <?= $o->discount ?? 0 ?>%, Итого к оплате <?= $o->getTotal2() ?> руб.</b></p>

        <b>Всего наименований <?= $o->getProductCount('COALESCE(is_deleted, 0) = 0') ?>, на сумму
            <?= $o->getTotal2() ?></b>
        <br>
        <br>

        <div style="position: relative; "></div>

        <div style="position: relative">
            <table class="sign">
                <tbody><tr>
                    <td style="width: 150pt; text-align: right;padding-top: 40px">ИП</td>
                    <td style="width: 100pt; border-bottom: 1pt solid #000000; border-width: 0pt 0pt 1pt 0pt; text-align: center;"></td>
                    <td>ПУЧКИНА И.В.</td>
                </tr>
                </tbody></table>
        </div>
    </div>



    <?php if ($print): ?>
        <script>window.print();</script>
    <?php endif; ?>
</div>