<tr style="display: table-row-group; vertical-align: top;">
    <td style="padding-top: 12px; padding-bottom: 12px; width: 254px">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr style="vertical-align: top">
                <td style="width: 40px; padding-right: 12px">
                    <img width="40" height="40" src="<?=url(asset('img/email/icons/icon-smile.png', true))?>" alt="Icon Smile" />
                </td>
                <td>
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr>
                            <td style="padding-bottom: 4px;">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 16px;
                        font-style: normal;
                        font-weight: 600;
                        line-height: 150%;
                        color: #0d0d0d;
                      ">Покупатель:
                                                        </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      "><?=$order->user->last_name?> <?=$order->user->name?> <?=$order->user->patronym?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      "><?=$order->user->phone?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      "><?=$order->email?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                      "><?=$order->comment?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!--<tr class="divider">-->
<!--    <td style="width: 100%; height: 2px; background-color: #f2f2f2"></td>-->
<!--</tr>-->
<!--<tr style="display: table-row-group; vertical-align: top;">-->
<!--    <td style="padding-top: 12px; padding-bottom: 12px; width: 254px">-->
<!--        <table cellpadding="0" cellspacing="0" border="0" width="100%">-->
<!--            <tr style="vertical-align: top">-->
<!--                <td style="width: 40px; padding-right: 12px">-->
<!--                    <img width="40" height="40" src="--><?php //=url(asset('img/email/icons/icon-smile.png', true))?><!--" alt="Icon Smile" />-->
<!--                </td>-->
<!--                <td>-->
<!--                    <table cellpadding="0" cellspacing="0" border="0" width="100%">-->
<!--                        <tr>-->
<!--                            <td style="padding-bottom: 4px;">-->
<!--                                                        <span style="-->
<!--                        font-family: 'Arial', 'Noto Sans', sans-serif;-->
<!--                        font-size: 16px;-->
<!--                        font-style: normal;-->
<!--                        font-weight: 600;-->
<!--                        line-height: 150%;-->
<!--                        color: #0d0d0d;-->
<!--                      ">Получатель:-->
<!--                                                        </span>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>-->
<!--                                                        <span style="-->
<!--                        font-family: 'Arial', 'Noto Sans', sans-serif;-->
<!--                        font-size: 14px;-->
<!--                        font-style: normal;-->
<!--                        font-weight: 400;-->
<!--                        line-height: 150%;-->
<!--                        color: #404040;-->
<!--                      ">--><?php //=$order->last_name?><!-- --><?php //=$order->name?><!-- --><?php //=$order->patronym?><!--</span>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>-->
<!--                                                                                        <span style="-->
<!--                        font-family: 'Arial', 'Noto Sans', sans-serif;-->
<!--                        font-size: 14px;-->
<!--                        font-style: normal;-->
<!--                        font-weight: 400;-->
<!--                        line-height: 150%;-->
<!--                        color: #404040;-->
<!--                      ">--><?php //=$order->phone ?><!--</span>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </td>-->
<!--</tr>-->