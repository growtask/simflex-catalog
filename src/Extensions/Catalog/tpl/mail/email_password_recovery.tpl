<?php include 'header.tpl'; ?>
    <!-- Message Registration Complete  -->
    <tr>
        <td style="padding: 40px; border-radius: 12px;
background-color: #ffffff;">
            <table cellpadding="0" cellspacing="0" border="0" style="
border-radius: 12px;
background-color: #ffffff;
width: 100%;
    width: 100%;
">
                <tr>
                    <td>
                    <span style="font-variant-numeric: lining-nums proportional-nums;
                    font-family: 'Arial', 'Noto Sans', sans-serif;
                    font-size: 20px;
                    font-style: normal;
                    font-weight: 700;
                    line-height: 100%;
                    color: #404040;
                    ">Здравствуйте, <?= $user->name ?></span>
                    </td>
                </tr>
                <tr style="height: 12px;"></tr>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" border="0" style="font-family: 'Arial', 'Noto Sans', sans-serif;
                        font-size: 14px;
                        font-style: normal;
                        font-weight: 400;
                        line-height: 150%;
                        color: #404040;
                        width: 400px;
                        margin: 0;
                ">
                            <tr>
                                <td>
                                    Вы оставили заявку на восстановление пароля. Ваш новый пароль:
                                    <strong><?= $password ?></strong>
                                    <br>
                                    <br>
                                    Изменить его вы можете в личном кабинете
                                </td>
                            </tr>

                        </table>
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
                <tr style="height: 20px;">
                </tr>
            </table>
        </td>
    </tr>
    <!-- End Message Registration Complete -->
<?php include 'footer.tpl'; ?>