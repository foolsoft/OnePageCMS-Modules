<?php _T('XMLcms_text_is_active'); ?>: 
<select name='handpay_use' >
    <option value='1' <?php echo $tag->settings->handpay_use == '1' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_yes'); ?>
    </option>
    <option value='0' <?php echo $tag->settings->handpay_use == '0' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_no'); ?>
    </option>
</select>
