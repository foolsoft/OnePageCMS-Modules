<p>
    ID:<br />
    <input class='input-100' type='text' name='qiwi_id' value='<?php echo $tag->settings->qiwi_id; ?>' />
</p>
<p>
    <?php _T('XMLcms_text_password'); ?>:<br />
    <input class='input-100' type='text' name='qiwi_secret' value='<?php echo $tag->settings->qiwi_secret; ?>' />
</p>
<p>
    <?php _T('XMLfspay_payment_lifetime'); ?>:<br />
    <input class='input-100' type='text' name='qiwi_lifetime' value='<?php echo $tag->settings->qiwi_lifetime; ?>' />
</p>
<p>
    <?php _T('XMLfspay_check_registr'); ?> (check_agt):
    <select name='qiwi_agt' >
        <option value='true' <?php echo $tag->settings->qiwi_agt == 'true' ? 'selected' : '' ?>>
            true
        </option>
        <option value='false' <?php echo $tag->settings->qiwi_agt == 'false' ? 'selected' : '' ?>>
            false
        </option>
    </select>
</p>
<p>
<?php _T('XMLcms_text_is_active'); ?>: 
<select name='qiwi_use' >
    <option value='1' <?php echo $tag->settings->qiwi_use == '1' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_yes'); ?>
    </option>
    <option value='0' <?php echo $tag->settings->qiwi_use == '0' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_no'); ?>
    </option>
</select>
</p>
<p>
<hr />
SOAP URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayQiwi/Check'); ?><br />
Success URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayQiwi/Good'); ?><br />
Fail URL: <?php echo fsHtml::Url(URL_ROOT.'Pay/Fail'); ?>