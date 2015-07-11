<form id="file-form" style="margin:0px;padding:0px;height:400px;">
    <input type="hidden" value="<?php echo $tag->file; ?>" name="file" />
    <div style="text-align:center;width:900px;height:400px;">
        <textarea style="height:350px;width:850px;" name="data" id="file-content" ><?php echo $tag->content; ?></textarea>
        <br />
        <br />
        <div id="btn-save">
            <input type="button"
                   value="<?php _T('XMLcms_save'); ?>"
                   onclick="fsCMS.Ajax('<?php echo fsHtml::Url($myLink.'AjaxSaveFile'); ?>', 'POST', $('#file-form').serialize(), 'btn-save', 'btn-save', 16, function(answer) { $.fancybox.close(); });" />
        </div>
    </div>
</form>