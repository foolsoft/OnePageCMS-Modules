<p>
    <?php _T('Paypal Email') ?>:
    <input type="text" value="<?php echo $tag->settings->paypal_email; ?>" name="paypal_email" />
</p>
<p>
    <?php _T('XMLfspay_test_mode'); ?>:
    <select name='paypal_demo' >
        <option value='1' <?php echo $tag->settings->paypal_demo == '1' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_yes'); ?>
        </option>
        <option value='0' <?php echo $tag->settings->paypal_demo == '0' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_no'); ?>
        </option>
    </select>
</p>
<p>
    <?php _T('XMLcms_text_is_active'); ?>:
    <select name='paypal_use' >
        <option value='1' <?php echo $tag->settings->paypal_use == '1' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_yes'); ?>
        </option>
        <option value='0' <?php echo $tag->settings->paypal_use == '0' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_no'); ?>
        </option>
    </select>
</p>
<hr />
IPN Check URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayPaypal/Check'); ?><br />
Success URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayPaypal/Success'); ?><br />
Fail URL: <?php echo fsHtml::Url(URL_ROOT.'Pay/Fail'); ?>