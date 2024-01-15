<tr>
    <td style="padding-top: 12px; <?= $isBlockOnTop ? 'padding-bottom: 40px;' : 'padding-bottom: 52px;'?>  width: 254px">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr style="vertical-align: top">
                <td style="width: 40px; padding-right: 12px">
                    <img width="40" height="40" src="<?=url(asset('img/email/icons/icon-location.png', true))?>"
                         alt="Icon location" />
                </td>
                <td>
                    <table cellpadding="0" cellspacing="0" border="0"
                           width="100%">
                        <tr>
                            <td style="padding-bottom: 4px;">
                                                        <span style="
                        font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 16px;
                        font-style: normal;
                        font-weight: 600;
                        line-height: 150%;
                        color: #0d0d0d;
                      ">Получение:
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
                      "><?=$order->last_name?> <?=$order->name?> <?=$order->patronym?></span>
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
                      "><?=$order->phone?></span>
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
                      "><?=$order->city?></span>
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
                      "><?=$order->transcomp?></span>
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
                      "><?=$order->address?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>