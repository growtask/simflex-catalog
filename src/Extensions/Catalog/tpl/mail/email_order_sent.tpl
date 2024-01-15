<?php include 'header.tpl'; ?>
    <!-- Message Order Sent Complete  -->

    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="
                    background-color: #ffffff;
                    border-radius: 20px;
                    margin-bottom: 20px;
                    position: relative;
                    width: 100%;
                  ">
                <tr>
                    <td style="padding: 40px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <h4 style="
                                margin: 0;
                                padding: 0;
                                margin-bottom: 12px;
                                font-family: 'Arial', 'Noto Sans', sans-serif;
                                font-style: normal;
                                font-weight: 700;
                                font-size: 20px;
                                line-height: 30px;
                                font-feature-settings: 'pnum' on, 'lnum' on, 'ss09' on;
                              ">
                                        Заказ №<?= $order->order_id ?> отправлен
                                    </h4>
                                </td>
                            </tr>
                            <tr style="display: table-cell">
                                <td style="padding-right: 12px">
                          <span style="
                                font-family: 'Arial', 'Noto Sans', sans-serif;
                                font-style: normal;
                                font-weight: 600;
                                font-size: 14px;
                                line-height: 150%;
                                font-feature-settings: 'pnum' on, 'lnum' on, 'ss09' on;
                                color: #404040;
                              ">Статус заказа:</span>
                                </td>
                                <td style="padding: 9.5px 16px; background-color: #7318f2; border-radius: 12px">
                          <span style="
                                font-family: 'Arial', 'Noto Sans', sans-serif;
                                font-style: normal;
                                font-weight: 600;
                                font-size: 14px;
                                line-height: 150%;
                                font-feature-settings: 'pnum' on, 'lnum' on, 'ss09' on;
                                color: #ffffff;
                              ">Отправлен</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0;">
                                    <table style="width: 400px;" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="font-family: 'Arial', 'Noto Sans', sans-serif;
                                font-size: 14px;
                                font-style: normal;
                                font-weight: 400;
                                line-height: 150%;
                                color: #404040;
                                ">
                                                Здравствуйте, <?= $user->name ?>
                                                <br>
                                                <br>
                                                Ваш заказ отправлен транспортной компанией.
                                                Трек-номер для отслеживания:
                                                <br>
                                                <strong><?= $order->tracking ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="
                          padding-bottom: 20px;
                          ">
                                    <a style="
                          padding: 9.5px 16px;
                          border-radius: 120px;
                          /*background: linear-gradient(90deg, #7318F2 0%, #30B2F2 100%);*/
                                            background: url(<?=url(asset('img/email/background-gradient.png', true))?>);
                          font-family: 'Arial', 'Noto Sans', sans-serif;
                          font-size: 14px;
                          font-style: normal;
                          font-weight: 600;
                          line-height: 150%;
                          color: #ffffff;
                          text-decoration: none;
                          display: inline-block;
                          -webkit-text-size-adjust:none; 
                          width: fit-content;
                          " href="<?= $order->tracking ?>">Отслеживать заказ</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:9.5px 12px;border-radius:12px;background:#f2f2f2">
                                    <p style="font-family: 'Arial', 'Noto Sans', sans-serif;
                    font-size: 14px;
                    font-style: normal;
                    font-weight: 400;
                    line-height: 150%;
                    color: #404040;
                    margin: 0;">
                                        Просьба не отвечать на это сообщение, если вы хотите задать вопрос отправьте сообщение на <?=\Simflex\Core\Core::siteParam('email')?>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height: 12px;">
                                <td></td>
                            </tr>
                            <tr class="divider">
                                <td style="width: 100%; height: 2px; background-color: #f2f2f2"></td>
                            </tr>
                            <?php include 'order/receiver.tpl'; ?>
                            <tr class="divider">
                                <td style="width: 100%; height: 2px; background-color: #f2f2f2"></td>
                            </tr>
                            <?php include 'order/place.tpl'; ?>

                            <!-- Cart items -->
                            <?php include 'order/cart.tpl'; ?>

                            <!-- End Cart items -->


                            <!-- Order Info -->
                            <?php include 'order/info.tpl'; ?>
                            <!-- End Order Info  -->


                        </table>
                    </td>
                </tr>
                <tr></tr>
            </table>
        </td>
    </tr>

    <!-- End Message Order Sent Complete -->
<?php include 'footer.tpl'; ?>