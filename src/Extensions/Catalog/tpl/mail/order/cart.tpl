<tr>
    <td>
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%">
            <!-- Cart Item -->
            <?php foreach ($order->getProducts() as $pi): ?>
                <?php $p = $pi['product']; ?>
                <tr>
                    <td style="padding: 4px 0; border-top: 2px solid #f2f2f2;">
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 89px">
                            <tr>
                                <td>
                                    <?php if ($pi['is_changed']): ?>
                                        <span style="
                  font-family: 'Arial', 'Noto Sans', sans-serif;
                  font-size: 10px;
                  font-style: normal;
                  font-weight: 600;
                  line-height: 150%;
                  color: #ffffff;
                  -webkit-text-size-adjust:none;
                  display: block;
                  text-align: center;
                  padding: 2px 4px;
                  border-radius: 8px;
                  background: #cc2929;
                  margin-right: 4px;
                  ">Изменён</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($pi['is_deleted']): ?>
                                        <span style="
                  font-family: 'Arial', 'Noto Sans', sans-serif;
                  font-size: 10px;
                  font-style: normal;
                  font-weight: 600;
                  line-height: 150%;
                  color: #ffffff;
                  -webkit-text-size-adjust:none;
                  display: block;
                  text-align: center;
                  padding: 2px 4px;
                  border-radius: 8px;
                  margin-right: 4px;
                  background: #000000;
                  ">Удалён</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($pi['is_added']): ?>
                                        <span style="
                  font-family: 'Arial', 'Noto Sans', sans-serif;
                  font-size: 10px;
                  font-style: normal;
                  font-weight: 600;
                  line-height: 150%;
                  color: #ffffff;
                  -webkit-text-size-adjust:none;
                  display: block;
                  text-align: center;
                  padding: 2px 4px;
                  border-radius: 8px;
                  margin-right: 4px;
                  background: linear-gradient(90deg, #7318F2 0%, #30B2F2 100%);
                  ">Добавлен</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td
                                        style="position: relative; padding-right: 20px;">


                                    <img style="object-fit: cover; border-radius: 20px;"
                                         width="64px" height="64px"
                                         src="<?= url($p->getPreviewImage()) ?>" alt="">
                                </td>
                                <td style="padding-right: 20px; max-width: 211px; width: 100%">
                                    <a href="<?= url('/' . $p->path) ?>" style="
                  text-decoration: none;
                  font-family: 'Arial', 'Noto Sans', sans-serif;
                  font-size: 14px;
                  font-style: normal;
                  font-weight: 600;
                  line-height: 150%;
                  -webkit-text-size-adjust:none;
                  display: block;
                      white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                  color: #262626;"><?= $p->name ?></a>
                                    <table cellpadding="0" cellspacing="0"
                                           border="0">
                                        <tr style="
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
">
                                            <td
                                                    style="padding-top: 4px; padding-right: 8px;">
                                                                    <span style="font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 12px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                        -webkit-text-size-adjust:none;
                  display: block;">Размер: <strong><?= $pi['size'] ?></strong></span>
                                            </td>
                                            <td style="padding-top: 4px;">
                                                                    <span style="font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 12px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                        -webkit-text-size-adjust:none;
                        display: block;
                        text-overflow: ellipsis;
                        width: 97px;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        ">Цвет: <strong><?= $p->color ?></strong></span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="padding-right: 4px;">
                                    <?php if (!$pi['is_deleted']): ?>
                                    <table cellpadding="0" cellspacing="0"
                                           border="0">
                                        <tr>
                                            <td style="height: 48px; border-radius: 12px;
                          background: #f2f2f2;">
                                                <table cellpadding="0"
                                                       cellspacing="0" border="0"
                                                       style="

                          padding: 7px 16px;
                          display: block;
                          ">
                                                    <tr>
                                                        <td>
                                                            <table
                                                                    cellpadding="0"
                                                                    cellspacing="0"
                                                                    border="0">
                                                                <tr>
                                                                    <td
                                                                            style="padding-right: 4px; white-space: nowrap">
                                                                                            <span
                                                                                                    style="
                                    -webkit-text-size-adjust:none;
                                    font-size: 16px;
                                    font-style: normal;
                                    font-weight: 700;
                                    line-height: 100%;
                                    font-family: 'Arial', 'Noto Sans', sans-serif;
                                    color: #cc2929;
                                    "><?= number_format($pi['price'] * $pi['qty'], 0, '', ' ') ?> ₽</span>
                                                                    </td>
                                                                    <?php if ($p->price_old && $p->price_old != $p->price): ?>
                                                                        <td style="white-space: nowrap">
                                                                                            <span
                                                                                                    style="
                                    font-family: 'Arial', 'Noto Sans', sans-serif;
                                    font-size: 11px;
                                    font-style: normal;
                                    font-weight: 600;
                                    line-height: 150%;
                                    color: #999999;
                                    text-decoration: line-through;
                                    -webkit-text-size-adjust:none;
                              "><?= number_format($pi['price_old'] * $pi['qty'], 0, '', ' ') ?> ₽</span>
                                                                        </td>
                                                                    <?php endif; ?>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td
                                                                style="padding-top: 4px;">
                                                                                <span style="
                                text-align: center;
                                font-variant-numeric: lining-nums proportional-nums;
                                font-family: 'Arial', 'Noto Sans', sans-serif;
                                font-size: 10px;
                                font-style: normal;
                                font-weight: 600;
                                line-height: 100%;
                                color: #404040;
                                -webkit-text-size-adjust:none;
                                display: block;
                                "><?= $pi['price'] ?> ₽/шт.</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <table cellpadding="0" cellspacing="0"
                                           border="0"
                                           style="height: 48px; background: #f2f2f2; border-radius: 12px;">
                                        <tr>
                                            <td style="padding: 13.5px 12px 13.5px 12px; white-space: nowrap; <?php if ($pi['is_changed']): ?> font-weight: bold; color:#5B13BF <?php endif; ?>">
                                                                    <span style="
                      color: #404040;
                      font-family: 'Arial', 'Noto Sans', sans-serif;
                      font-size: 12px;
                      font-style: normal;
                      font-weight: 600;
                      line-height: 150%"><?= $pi['qty'] ?> шт.</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <?php endforeach; ?>
            <!-- End Cart Item -->
        </table>
    </td>
</tr>