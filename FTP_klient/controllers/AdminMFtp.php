<?php
class AdminMFtp extends AdminPanel {

  private $_pZip = 'temp/ftp/';
      
  public function Init($Param)
  {
    parent::Init($Param);
    $this->Tag('title', T('XMLftp_client'));
    $this->_pZip = PATH_ROOT.$this->_pZip;
  }

  public function UnInstall()
  {
    fsFunctions::DeleteFile(PATH_ATHEME_CSS.'AdminMFtp.css');
    fsFunctions::DeleteFile(PATH_ATHEME_IMG.'ftp-file.png');
    fsFunctions::DeleteFile(PATH_ATHEME_IMG.'ftp-folder.png');
    fsFunctions::DeleteFile(PATH_LANG.'xml/en_MFtp.xml');
    fsFunctions::DeleteFile(PATH_LANG.'xml/ru_MFtp.xml');
    fsFunctions::DeleteFile(PATH_LANG.'ru-to-en/AdminMFtp.php');
    fsFunctions::DeleteFile(PATH_LANG.'en-to-ru/AdminMFtp.php');
    fsFunctions::DeleteDirectory(PATH_ROOT.'plugins/createZip');
    fsFunctions::DeleteDirectory(PATH_TPL.CMSSettings::GetInstance('template_admin').'/AdminMFtp');
    fsFunctions::DeleteDirectory($this->_pZip);
  }

  private function _Link($action)
  {
    return fsHtml::Url($this->_link.$action);
  }

  private function _TplFile($Name, $Path)
  {
    $nLen = 11;
    $vName = strlen($Name) > $nLen ? substr($Name, 0, $nLen - 3).'...' : $Name;
    $md5Name = md5($Name);
    return "<div id='".$md5Name."' onclick=\"Select('".$md5Name."');\" class='ftp-file'><a target='_blank' href='".$this->_Link('GetFile')."?name=".$Name."&path=".$Path."' title=".$Name.">".$vName."</a></div>";
  }
  
  private function _TplFolder($Name, $Path)
  {
    $nLen = 11;
    $vName = strlen($Name) > $nLen ? substr($Name, 0, $nLen).'...' : $Name;
    $md5Name = md5($Name);
    return "<div id='".$md5Name."' onclick=\"Select('".$md5Name."');\" class='ftp-folder'><a href=\"javascript:fsCMS.Ajax('".$this->_Link('AjaxLoadPath')."', 'POST', 'path=".$Path.($Name == '..' ? '' : '/'.$Name)."&folder=".($Name == '..' ? '' : $Name)."', 'server-ftp', true);CurrentPath('".$Path.($Name == '..' ? '' : '/'.$Name)."');\" title='".$Name."'>".$vName."</a></div>";
  }

  public function actionAjaxLoadPath($Param)
  {
    if (!$Param->Exists('path') || $Param->path == '' || !is_dir($Param->path)) {
      $this->Redirect($this->_My());
      return;
    }
    $this->Html($this->_GetPath($Param->path, $Param->folder));
  }

  private function _GetPath($Path, $Folder = '')
  {
    $html = '';
    if ($Path != PATH_ROOT && $Path.'/' != PATH_ROOT) {
      $html .= $this->_TplFolder('..', substr($Path, 0, strrpos($Path, '/')));
    }
    $Folder = fsFunctions::Slash($Folder);
    $folders = fsFunctions::DirectoryInfo($Path, true, true);
    foreach ($folders['NAMES'] as $item) {
      if (is_file($Path.'/'.$item)) {
        $html .= $this->_TplFile($item, $Path);
      } else {
        $html .= $this->_TplFolder($item, $Path);
      }
    }
    return $html.'<div class="clr"></div>';
  }
  
  public function actionAjaxMkDir($Param)
  {
    if(!$Param->Exists('path') || $Param->path == '' || !$Param->Exists('name') || $Param->name == '') {
      $this->Html('Error');
      return;
    }
    if (!is_dir($Param->path.'/'.$Param->name)) {
      mkdir($Param->path.'/'.$Param->name, 0777);
    }
    $this->Html($this->_GetPath($Param->path));
  }
  
  public function actionAjaxMkFile($Param)
  {
    if(!$Param->Exists('path') || $Param->path == '' || !$Param->Exists('name') || $Param->name == '') {
      $this->Html('Error');
      return;
    }
    if (!file_exists($Param->path.'/'.$Param->name)) {
      $f = fopen($Param->path.'/'.$Param->name, 'w');
      fclose($f);
    }
    $this->Html($this->_GetPath($Param->path));
  }
  
  public function actionAjaxDelete($Param)
  {
    if(!$Param->Exists('path') ||
        $Param->path == '' ||
        !$Param->Exists('name') ||
        $Param->name == '') {
      $this->Html('Error');
      return;
    }
    if (is_dir($Param->path.'/'.$Param->name))
      fsFunctions::DeleteDirectory($Param->path.'/'.$Param->name);  
    else
      unlink($Param->path.'/'.$Param->name);  
    $this->Html($this->_GetPath($Param->path));
  }
  
  public function actionAjaxUnzip($Param) {
    if(!$Param->Exists('path') ||
        $Param->path == '' ||
        !$Param->Exists('name') ||
        $Param->name == '' ||
        !file_exists($Param->path.'/'.$Param->name) ||
        substr($Param->name, -4) != '.zip') {
      $this->Html('Error');
      return;
    }
    $zip = new ZipArchive();
    if ($zip->open($Param->path.'/'.$Param->name))
    {
      $zip->extractTo($Param->path);
      $zip->close();
    }
    $this->Html($this->_GetPath($Param->path));    
  }
  
  public function actionGetFile($Param)
  {
    if(!$Param->Exists('path') ||
        $Param->path == '' ||
        !$Param->Exists('name') ||
        $Param->name == '') {
      $this->Html('Error');
      return;
    }
    if (is_dir($Param->path.'/'.$Param->name)) {
      if (!is_dir($this->_pZip)) {
        mkdir($this->_pZip, 0777);
      }
      $createZip = new createDirZip();
      $createZip->get_files_from_folder($Param->path.'/'.$Param->name, '');
      $fileName = $Param->name.'.zip';
      $fd = fopen ($this->_pZip.$fileName, 'wb');
      $out = fwrite ($fd, $createZip->getZippedfile());
      fclose ($fd);
      $createZip->forceDownload($this->_pZip.$fileName);   
    } else {
      $file = ($Param->path.'/'.$Param->name);
      header ("Content-Type: application/octet-stream");
      header ('Content-Description: File Transfer');
      header ("Accept-Ranges: bytes");
      header ('Content-Transfer-Encoding: binary');
      header ("Content-Length: ".filesize($file));
      header ("Content-Disposition: attachment; filename=".basename($file));  
      readfile($file);
    }
    $this->_response->empty = true;
  }
  
  public function actionAddFile($Param)
  {
    $this->Redirect($this->_My());
    if (!$Param->Exists('path') || $Param->path == '' || !is_dir($Param->path)) {
      return;
    } 
    $NewFile = '';
    $Param->path = fsFunctions::Slash($Param->path);
    if (fsFunctions::UploadFiles('userfile', $Param->path, $NewFile, false)) {
      $this->Redirect($this->_Link('Index').'?path='.$Param->path);
    }
  }
  
  public function actionIndex($Param)
  {
      fsFunctions::DeleteDirectory($this->_pZip);
      $path = $Param->path == '' ? PATH_ROOT : $Param->path;
      if (substr($path, -1) == '/') {
        $path = substr($path, 0, strlen($path) - 1);
      }
      $this->Tag('path', $path);
      $this->Tag('content', $this->_GetPath($path));
  }


  public function actionGetFileContent($param)
  {
    if (file_exists($param->file) && !is_dir($param->file)) {
      $this->Tag('content', htmlspecialchars(trim(file_get_contents($param->file))));
      $this->Tag('file', $param->file);
      $this->Html($this->CreateView(array(), $this->_Template('Form')));
    }
    else {
      $this->Html('<center>'.T('XMLcms_text_page_not_found').'</center>');
    }
  }
  
  public function actionAjaxSaveFile($param)
  {
    if(!$param->Exists('file') || $param->file == '') {
      $this->Html(T('XMLcms_error_action'));
      return;
    }
    fsFileWorker::UpdateFile($param->file, $param->data);
    $this->Html('<input type="button" value="'.T('XMLcms_save').'" onclick="fsCMS.Ajax(\''.$this->_Link('AjaxSaveFile').'\', \'POST\', \'file='.$param->file.'&data=\'+$.base64.encode($(\'#file-content\').val()), \'btn-save\', \'btn-save\', 16);" />');  
  }
}