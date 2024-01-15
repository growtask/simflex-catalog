<tr style="height: 20px;">

</tr>

<tr style="margin-top: 40px">
    <td>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 20px">
            <tr>
                <th style="width: 184px"></th>
                <th style="width: 184px"></th>
                <th style="width: 184px"></th>
            </tr>
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0" border="0" style="

                          background-color: #ffffff;
                          width: 184px;
                          border-radius: 12px;
                          margin-right: 24px;
                        ">
                        <tr style="display: table-cell" align="center">
                            <td style="padding: 16px 9px;">
                                <a href="<?=url('/user/')?>" style="
                                font-size: 12px;
                                text-decoration: none;
                                font-weight: 600;
                                font-family: 'Arial', Arial;
                                color: #7318F2;
                                display: table-cell;
                              ">
                                    <img width="24" src="<?=url(asset('img/email/icons/User.png', true))?>" alt="User Profile"
                                         style="margin-right: 8px; vertical-align: middle;" />
                                    <span style="vertical-align: middle;">Личный кабинет</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table cellpadding="0" cellspacing="0" border="0" style="
                          background-color: #ffffff;
                          width: 184px;
                          border-radius: 12px;
                          margin-right: 24px;
                        ">
                        <tr style="display: table-cell" align="center">
                            <td style="padding: 16px 9px;">
                                <a href="<?=url('/user/orders/')?>" style="
                                font-size: 12px;
                                text-decoration: none;
                                font-weight: 600;
                                font-family: 'Arial', Arial;
                                color: #7318F2;
                                display: table-cell;
                              ">
                                    <img width="24" src="<?=url(asset('img/email/icons/Bag.png', true))?>" alt="User Profile"
                                         style="margin-right: 8px; vertical-align: middle;" />
                                    <span style="vertical-align: middle;">Мои заказы</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table cellpadding="0" cellspacing="0" border="0"
                           style=" background-color: #ffffff; width: 184px; border-radius: 12px;">
                        <tr style="display: table-cell; padding: 16px 9px;" align="center">
                            <td>
                                <a href="#" style="
                                font-size: 12px;
                                text-decoration: none;
                                font-weight: 600;
                                font-family: 'Arial', Arial;
                                color: #7318F2;
                                display: table-cell;
                              ">
                                    <img width="24" src="<?=url(asset('img/email/icons/Heart.png', true))?>" alt="User Profile"
                                         style="margin-right: 8px; vertical-align: middle;" />
                                    <span style="vertical-align: middle;">Избранное</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="background-color: #ffffff"></tr>
            <tr style="background-color: #ffffff"></tr>
        </table>
    </td>
</tr>

<!-- End Layout Cards -->
<tr>
    <!-- Footer -->
    <td align="center" valign="top" style="background: #490F99; border-radius: 20px">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="footer"
               style="width: 100% !important; min-width: 100%; max-width: 100%; padding: 32px 20px; display: block;">
            <tbody style="vertical-align: top">
                <tr>
                    <td>
                        <table style="width: 237px;">
                            <tr>
                                <td style="height: 40px; padding: 0;">
                                    <a href="tel:<?=\Simflex\Core\Core::siteParam('phone')?>" style="
                                  color: #ffffff;
                                  font-size: 20px;
                                  line-height: 24px;
                                  font-weight: 700;
                                  font-family: 'Arial', Arial, Tahoma, Geneva, sans-serif;
                                  text-decoration: none;
                                  font-feature-settings: 'pnum' on, 'lnum' on, 'ss09' on, 'liga' off;
                                "><?=\Simflex\Core\Core::siteParam('phone')?></a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 40px; padding: 0;">
                                    <a href="mailto:<?=\Simflex\Core\Core::siteParam('email')?>" style="
                                  color: #ffffff;
                                  font-size: 16px;
                                  line-height: 24px;
                                  font-weight: 600;
                                  font-family: 'Arial', Arial, Tahoma;
                                  text-decoration: none;
                                "><?=\Simflex\Core\Core::siteParam('email')?></a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                    <table cellpadding="0" cellspacing="0" border="0"
                                           style="margin-top: 12px">
                                        <tr>
                                            <?php if (\Simflex\Core\Core::siteParam('vk')): ?>
                                            <td style="padding-right: 8px">
                                                <a href="<?=\Simflex\Core\Core::siteParam('vk')?>" style="cursor: point;">
                                                    <img src="<?=url(asset('img/email/socials/Social.png', true))?>" width="40px"
                                                         alt="Vk Logo" />
                                                </a>
                                            </td>
                                            <?php endif; ?>
                                            <?php if (\Simflex\Core\Core::siteParam('tg')): ?>
                                            <td style="padding-right: 8px">
                                                <a href="<?=\Simflex\Core\Core::siteParam('tg')?>" style="cursor: point;">
                                                    <img src="<?=url(asset('img/email/socials/Social-1.png', true))?>" width="40px"
                                                         alt="Telegram Logo" />
                                                </a>
                                            </td>
                                            <?php endif; ?>
                                            <?php if (\Simflex\Core\Core::siteParam('whats_app')): ?>
                                            <td style="padding-right: 8px">
                                                <a href="<?=\Simflex\Core\Core::siteParam('whats_app')?>" style="cursor: point;">
                                                    <img src="<?=url(asset('img/email/socials/Social-2.png', true))?>" width="40px"
                                                         alt="Whats app Logo" />
                                                </a>
                                            </td>
                                            <?php endif; ?>
                                            <?php if (\Simflex\Core\Core::siteParam('inst')): ?>
                                            <td>
                                                <a href="<?=\Simflex\Core\Core::siteParam('inst')?>" style="cursor: point;">
                                                    <img src="<?=url(asset('img/email/socials/Social-3.png', true))?>" width="40px"
                                                         alt="Instagram Logo" />
                                                </a>
                                            </td><?php endif; ?>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table cellpadding="0" cellspacing="0" border="0" width="100%"
                               style="width: 151px; margin-right: 20px;">
                            <tr>
                                <td style="height: 40px;">
                                    <a target="_blank" href="<?=url('/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">Главная</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 40px;">
                                    <a target="_blank" href="<?=url('/about/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">О нас</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 40px;">
                                    <a target="_blank" href="<?=url('/reviews/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">Отзывы</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 40px;">
                                    <a target="_blank" href="<?=url('/blog/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">Полезные статьи</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 40px;">
                                    <a target="_blank" href="<?=url('/contacts/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">Контакты</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table cellpadding="0" cellspacing="0" border="0" width="100%"
                               style="width: 151px">
                            <tr>
                                <td style="height: 40px; padding: 0;">
                                    <a target="_blank" href="<?=url('/delivery/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">Доставка и оплата</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 40px; padding: 0;">
                                    <a target="_blank" href="<?=url('/terms/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">Условия работы</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 40px; padding: 0;">
                                    <a target="_blank" href="<?=url('/faq/')?>" style="
                                  font-family: Arial;
                                  font-size: 14px;
                                  font-style: normal;
                                  font-weight: 400;
                                  line-height: 150%;
                                  color: #f2f2f2;
                                  cursor: pointer;
                                ">Частые вопросы</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
    <!-- End Footer -->

    <!-- Copyrights -->
</tr>
<tr>
    <td style="height: 20px; background-color: transparent"></td>
</tr>
<tr>
    <td>
        <table>
            <tr>
                <td valign="center" style="background-color: transparent">
                                        <span style="
                          font-family: 'Arial', Arial, Tahoma, Geneva, sans-serif;
                          font-style: normal;
                          color: #999999;
                          font-size: 14px;
                          line-height: 21px;
                          font-weight: 600;
                          font-feature-settings: 'pnum' on, 'lnum' on, 'ss09' on, 'liga' off;
                        ">
                                            © <?=date('Y')?> Непоседа, Все права защищены, <?=\Simflex\Core\Core::siteParam('company')?>
                                        </span>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!-- End Copyrights -->
<tr>
    <td style="height: 20px; background-color: transparent"></td>
</tr>
</table>
</td>
</tr>
</table>
</body>

</html>