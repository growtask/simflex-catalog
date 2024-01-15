<?php include 'header.tpl'; ?>
<!-- Message Registration Complete  -->
<tr>
    <td style="padding: 40px; background: #FFFFFF; border-radius: 12px">
        <table cellpadding="0" cellspacing="0" border="0" style="
border-radius: 12px;
background-color: #ffffff;
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
                    ">Здравствуйте, <?=$user->name?></span>
                </td>
            </tr>
            <tr style="height: 12px;"></tr>
            <tr>
                <td>
                    <p style="font-family: 'Arial', 'Noto Sans', sans-serif;
                    font-size: 14px;
                    font-style: normal;
                    font-weight: 400;
                    line-height: 150%;
                    color: #404040;
                    width: 400px;
                    margin: 0;
                    ">
                        Благодарим вас за регистрацию в нашем интернет-магазине! Мы будем рады, если
                        использование нашего сайта доставит вам удовольствие!
                        <br>
                        <br>
                        Пожалуйста, <strong>подтвердите свой e-mail</strong>
                    </p>
                </td>
            </tr>
            <tr style="height: 20px;">
            </tr>
            <tr>
                <td style="padding-bottom: 20px">
                    <a style="
                    padding: 9.5px 16px;
                    justify-content: center;
                    align-items: center;
                    gap: 8px;
                    border-radius: 120px;
                    font-family: 'Arial', 'Noto Sans', sans-serif;
                    font-size: 14px;
                    background: linear-gradient(90deg, #7318f2 0%, #30B2f2 100%);
                    font-style: normal;
                    font-weight: 600;
                    line-height: 150%;
                    text-decoration: none;
                    color: #ffffff;
                    display: inline-block;
                    " href="<?=url('/auth/', ['action' =>'complete', 'u' => $user->user_id, 'c' => $user->code])?>">Подтвердить е-mail</a>
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
        </table>
    </td>
</tr>
<!-- End Message Registration Complete -->
<?php include 'footer.tpl'; ?>