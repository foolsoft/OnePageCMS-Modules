<p>
    <?php _T('XMLcms_text_login'); ?>:<br />
    <input class='input-100' type='text' name='robokassa_login' value='<?php echo $tag->settings->robokassa_login; ?>' />
</p>
<p>
    <?php _T('XMLcms_text_password'); ?> 1:<br />
    <input class='input-100' type='text' name='robokassa_public' value='<?php echo $tag->settings->robokassa_public; ?>' />
</p>
<p>
    <?php _T('XMLcms_text_password'); ?> 2:<br />
    <input class='input-100' type='text' name='robokassa_private' value='<?php echo $tag->settings->robokassa_private; ?>' />
</p>
<p>
<?php _T('XMLcms_text_is_active'); ?>: 
<select name='robokassa_use' >
    <option value='1' <?php echo $tag->settings->robokassa_use == '1' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_yes'); ?>
    </option>
    <option value='0' <?php echo $tag->settings->robokassa_use == '0' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_no'); ?>
    </option>
</select>
</p>
<p>
<?php _T('XMLfspay_test_mode') ?>:
<select name='robokassa_test' >
    <option value='1' <?php echo $tag->settings->robokassa_test == '1' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_yes'); ?>
    </option>
    <option value='0' <?php echo $tag->settings->robokassa_test == '0' ? 'selected' : ''; ?>>
        <?php _T('XMLcms_no'); ?>
    </option>
</select>
</p>
<hr />
Result URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayRobokassa/Check'); ?><br />
Success URL: <?php echo fsHtml::Url(URL_ROOT.'fsPayRobokassa/Success'); ?><br />
Fail URL: <?php echo fsHtml::Url(URL_ROOT.'Pay/Fail'); ?>