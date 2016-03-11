<p>
    LMI_MERCHANT_ID:  
    <input type="text" value="<?php echo $tag->settings->paymaster_id; ?>" name="paymaster_id" />
</p>
<p>
    Secret:  
    <input type="text" value="<?php echo $tag->settings->paymaster_secret; ?>" name="paymaster_secret" />
</p>
<p>
    <?php _T('XMLcms_text_is_active'); ?>: 
    <select name='paymaster_use' >
        <option value='1' <?php echo $tag->settings->paymaster_use == '1' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_yes'); ?>
        </option>
        <option value='0' <?php echo $tag->settings->paymaster_use == '0' ? 'selected' : ''; ?>>
            <?php _T('XMLcms_no'); ?>
        </option>
    </select>
</p>
<hr />
Result URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayPaymaster/Check'); ?><br />
Success URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayPaymaster/Success'); ?><br />
Fail URL: <?php echo fsHtml::Url(URL_ROOT.'Pay/Fail'); ?>