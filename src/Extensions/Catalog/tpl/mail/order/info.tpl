<tr align="center">
    <td style=" <?= $isBlockOnTop ? 'padding-bottom: 40px' : 'padding-top: 40px'?>">
        <table cellpadding="0" cellspacing="0" border="0" width="320px">
            <tr>
                <td style="border-radius: 28px; padding: 20px; border: 2px solid #f2f2f2;">
                    <table style="width: 100%">
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0"
                                       width="100%">
                                    <tr>
                                        <td style="padding: 0 0 8px 0;">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      "><?=\Simflex\Core\Helpers\Str::pluralize($order->getTotalProductCount(), 'товар')?></span>
                                        </td>
                                        <td style="padding: 0 0 8px 0;" align="right">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 600;
                        line-height: 150%;
                        color: #404040;
                      "><?=$order->sum_total?> ₽</span>
                                        </td>
                                    </tr>
                                    <?php if ($order->__sum_actual < $order->__sum_total): ?>
                                        <tr class="divider">
                                            <td style="height: 2px; background: #f2f2f2;">
                                            </td>
                                            <td style="height: 2px; background: #f2f2f2;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0;">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      ">Стоимость со скидкой</span>
                                            </td>
                                            <td style="padding: 8px 0;" align="right">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 600;
                        line-height: 150%;
                        color: #5B13BF;
                      "><?=$order->sum_actual?> ₽</span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($order->discount): ?>
                                        <tr class="divider">
                                            <td style="height: 2px; background: #f2f2f2;">
                                            </td>
                                            <td style="height: 2px; background: #f2f2f2;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0;">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      ">Персональная скидка</span>
                                            </td>
                                            <td style="padding: 8px 0;" align="right">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 600;
                        line-height: 150%;
                        color: #cc2929;
                      "><?=$order->discount?>%</span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr class="divider">
                                        <td style="height: 2px; background: #f2f2f2;">
                                        </td>
                                        <td style="height: 2px; background: #f2f2f2;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0;">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      ">Итоговая скидка</span>
                                        </td>
                                        <td style="padding: 8px 0;" align="right">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 600;
                        line-height: 150%;
                        color: #cc2929;
                      ">– <?=$order->getDiscount()?> ₽</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 12px;"></td>
                                        <td style="height: 12px;"></td>
                                    </tr>
                                    <tr>
                                        <td
                                                style="padding: 9.5px 12px; border-top-left-radius: 12px; border-bottom-left-radius: 12px; background: #f2f2f2;">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 600;
                        line-height: 150%;
                        color: #404040;
                      ">Итого:</span>
                                        </td>
                                        <td style="padding: 9.5px 12px; border-top-right-radius: 12px; border-bottom-right-radius: 12px; background: #f2f2f2;"
                                            align="right">
                                                        <span style="
                        font-variant-numeric: lining-nums proportional-nums;
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 20px;
                        font-style: normal;
                        font-weight: 700;
                        line-height: 100%;
                        color: #5b13bf;
                      "><?=$order->getTotal()?> ₽</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td
                                    style="width: 100%; height: 20px; background-color: transparent">
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="
                cursor: pointer;
                 border-radius: 120px;
                background: linear-gradient(90deg, #7318F2 0%, #30b2f2 100%);

              ">
                                <a href="<?=url('/user/orders/' . $order->order_id . '/')?>" style="
                  text-decoration: none;
                  font-family: 'Arial', 'Noto Sans', sans-serif;
                  font-style: normal;
                  background: url(<?=url(asset('img/email/background-gradient.png', true))?>);
                  font-weight: 600;
                  font-size: 16px;
                  line-height: 150%;
                  font-feature-settings: 'pnum' on, 'lnum' on, 'ss09' on;
                  color: #ffffff;
                  display: block;
                  padding: 12px 20px;
                  border-radius: 120px;
                ">Подробнее о заказе</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>