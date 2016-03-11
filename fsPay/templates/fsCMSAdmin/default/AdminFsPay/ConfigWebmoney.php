<p>
    <?php _T('XMLfspay_secret'); ?>:<br />
    <input class='input-100' type='text' name='webmoney_secret' value='<?php echo $tag->settings->webmoney_secret; ?>' />
</p>
<p>
    <?php _T('XMLfspay_wallet'); ?>:<br />
    <input class='input-100' type='text' name='webmoney_wallet' value='<?php echo $tag->settings->webmoney_wallet; ?>' />
</p>
<p>
<?php _T('XMLcms_text_is_active'); ?>: 
<select name='webmoney_use' >
    <option value='1' <?php echo $tag->settings->webmoney_use == '1' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_yes'); ?>
    </option>
    <option value='0' <?php echo $tag->settings->webmoney_use == '0' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_no'); ?>
    </option>
</select>
</p>
<p>
<?php _T('XMLfspay_test_mode') ?> (LMI_SIM_MODE):
<select name='webmoney_test' >
    <option value='2' <?php echo $tag->settings->webmoney_test == '2' ? 'selected' : ''; ?>>
        2
    </option>
    <option value='1' <?php echo $tag->settings->webmoney_test == '1' ? 'selected' : ''; ?>>
        1
    </option>
    <option value='0' <?php echo $tag->settings->webmoney_test == '0' ? 'selected' : ''; ?>>
       0
    </option>
</select>
</p>
<hr />
Result URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayWebmoney/Check'); ?><br />
Success URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayWebmoney/Good'); ?><br />
Fail URL: <?php echo fsHtml::Url(URL_ROOT.'Pay/Fail'); ?>