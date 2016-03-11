<p>
    <?php _T('Id магазина') ?>:
    <input type="text" value="<?php echo $tag->settings->yandexkassa_shopId; ?>" name="yandexkassa_shopId" />
</p>
<p>
    <?php _T('Пароль магазина') ?>:
    <input type="text" value="<?php echo $tag->settings->yandexkassa_password; ?>" name="yandexkassa_password" />
</p>
<p>
    <?php _T('CSID магазина') ?>:
    <input type="text" value="<?php echo $tag->settings->yandexkassa_scId; ?>" name="yandexkassa_scId" />
</p>
<p>
    <?php _T('XMLfspay_test_mode'); ?>:
    <select name='yandexkassa_demo' >
        <option value='1' <?php echo $tag->settings->yandexkassa_demo == '1' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_yes'); ?>
        </option>
        <option value='0' <?php echo $tag->settings->yandexkassa_demo == '0' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_no'); ?>
        </option>
    </select>
</p>
<p>
    <?php _T('XMLcms_text_is_active'); ?>:
    <select name='yandexkassa_use' >
        <option value='1' <?php echo $tag->settings->yandexkassa_use == '1' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_yes'); ?>
        </option>
        <option value='0' <?php echo $tag->settings->yandexkassa_use == '0' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_no'); ?>
        </option>
    </select>
</p>
<hr />
paymentAviso URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayYandexKassa/Check?isAviso=1'); ?><br />
check URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayYandexKassa/Check'); ?><br />
Success URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayYandexKassa/Success'); ?><br />
Fail URL: <?php echo fsHtml::Url(URL_ROOT.'Pay/Fail'); ?>