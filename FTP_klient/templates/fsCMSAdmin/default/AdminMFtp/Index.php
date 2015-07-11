[parent:../AdminPanel/Index.php]

[block-content]
<script type='text/javascript'>
var FTP_PATH = '<?php echo $tag->path; ?>';
var FTP_SELECTED = '';
var FTP_FANCY = null;
function Select(name) { 
    var f = $('.ftp-file');
    var p = $('.ftp-folder');
    for (var i = 0; i < f.length; ++i) {
        $(f[i]).css('background-color', '');
    }
    for (var i = 0; i < p.length; ++i) {
        $(p[i]).css('background-color', '');
    }
    $('#'+name).css('background-color', 'rgba(0,200,255,0.2)');
    FTP_SELECTED = $('#'+name+' a').attr('title');
    if (FTP_SELECTED == '..') {
        FTP_SELECTED = '';
    }
    $('#fpath').html(FTP_PATH + '/' + FTP_SELECTED);
}
function CurrentPath(name) { 
    $('#cPath').val(name);
    FTP_PATH = name;
    FTP_SELECTED = '';
    $('#fpath').html(FTP_PATH + '/');
} 
function FileRedact() { 
    if(FTP_SELECTED=='') {
        alert('<?php _T("XMLftp_selectfile"); ?>');
        return;
    }
    $('#btnRedact').attr('href', '<?php echo $mLink; ?>GetFileContent' + URL_SUFFIX + '?file='+FTP_PATH+'/'+FTP_SELECTED);
    if (FTP_FANCY == null) {
        FTP_FANCY = $('#btnRedact').fancybox({'scrolling'   : 'no'});
    }
    FTP_FANCY.show();
}    

$(document).ready(function() {
      $('#btnRefresh').click(function() {
        fsCMS.Ajax('<?php echo fsHtml::Url($myLink."AjaxLoadPath"); ?>',
                   'POST',
                   'path='+FTP_PATH,
                   'server-ftp',
                   true);
    });
    $('#btnCreateFolder').click(function() {
        var r = prompt('<?php _T("XMLftp_folder_name"); ?>');
        if (r != null && r != '') {
            fsCMS.Ajax('<?php echo fsHtml::Url($myLink."AjaxMkDir"); ?>',
                       'POST',
                       'path='+FTP_PATH+'&name='+r,
                       'server-ftp',
                       true);
        }    
    }); 
    $('#btnCreateFile').click(function() {
        var r = prompt('<?php _T("XMLftp_file_name"); ?>');
        if (r != null && r != '') {
            fsCMS.Ajax('<?php echo fsHtml::Url($myLink."AjaxMkFile"); ?>',
                       'POST',
                       'path='+FTP_PATH+'&name='+r,
                       'server-ftp',
                       true);
        }    
    }); 
    $('#btnDelete').click(function() {
        if (FTP_SELECTED == '') {
            alert('<?php _T("XMLftp_ff"); ?>');
            return;
        }
        if (!confirm('<?php _T("XMLcms_text_sure"); ?>')) {
            return;
        }
        fsCMS.Ajax('<?php echo fsHtml::Url($myLink."AjaxDelete"); ?>',
                   'POST',
                   'path='+FTP_PATH+'&name='+FTP_SELECTED,
                   'server-ftp',
                   true);
    });
    $('#btnDownload').click(function() {
        if (FTP_SELECTED == '') {
            alert('<?php _T("XMLftp_ff"); ?>');
            return;
        }
        window.location = '<?php echo $myLink; ?>GetFile' + URL_SUFFIX + '?path='+FTP_PATH+'&name='+FTP_SELECTED;
    });
    $('#btnUnzip').click(function() {
        if (FTP_SELECTED == '' || FTP_SELECTED.substr(-4) != '.zip') {
            alert('<?php _T("XMLftp_archive"); ?>');
            return;
        }
        fsCMS.Ajax('<?php echo fsHtml::Url($myLink."AjaxUnzip"); ?>',
             'POST',
             'path='+FTP_PATH+'&name='+FTP_SELECTED,
             'server-ftp',
             true);
    });
});
                                      
</script>
<?php
$textEdit = T('XMLcms_edit');
?>
<div class='ftp-btn' id='btnRefresh'><?php _T('XMLftp_refresh'); ?></div>
<ul>
    <div class='ftp-btn'><?php _T('XMLftp_create'); ?></div>
    <li class="create-cat">
        <div class='ftp-btn' id='btnCreateFolder'><?php _T('XMLftp_folder'); ?></div>
    </li>
    <li class="create-file">
        <div class='ftp-btn' id='btnCreateFile'><?php _T('XMLftp_file'); ?></div>
    </li>
</ul>
<div class='ftp-btn' id='btnDelete'><?php _T('XMLcms_delete'); ?></div>
<div class='ftp-btn' id='btnDownload'><?php _T('XMLftp_download'); ?></div>      
<div class='ftp-btn' id='btnEdit'>
    <a href="#" id="btnRedact" class="fancybox.ajax" alt="<?php echo $textEdit; ?>" title="<?php echo $textEdit; ?>" onclick="FileRedact();">
        <?php echo $textEdit; ?>
    </a>    
</div>
<div class='ftp-btn' id='btnUnzip'><?php _T('XMLftp_unzip'); ?></div>
<div style="margin-top:5px;clear:both;"></div>
<hr />
<div class="server-ftp" id="server-ftp">
    <?php echo $tag->content; ?>
</div>
<hr />
<form enctype="multipart/form-data" action="<?php echo fsHtml::Url($myLink."addFile"); ?>" method="POST">
    <input id="cPath" type="hidden" name="path" value="<?php echo $tag->path; ?>" />
    <div id="fpath"></div><hr />
    <?php _T('XMLftp_upload_files'); ?>: <input type="file" size="70" name="userfile[]"  multiple="true" />
    <hr />
    <input type="submit" value="<?php _T('XMLftp_upload'); ?>" />
</form>
[endblock-content]