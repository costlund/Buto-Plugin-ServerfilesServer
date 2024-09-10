<?php
class PluginServerfilesServer{
  private $settings = null;
  private $data = null;
  function __construct(){
    /**
     * 
     */
    wfPlugin::includeonce('wf/yml');
    /**
     * settings
     */
    $this->settings = wfPlugin::getModuleSettings('serverfiles/server', true);
    /**
     * request
     */
    wfRequest::set('date_time', date('Y-m-d H:i:s'));
    wfRequest::set('request_uri', wfServer::getRequestUri());
    wfRequest::set('remote_addr', wfServer::getRemoteAddr());
    $folder_in_settings = false;
    $remote_in_settings = false;
    foreach($this->settings->get('folders') as $k => $v){
      if($v['name']==wfRequest::get('folder')){
        $folder_in_settings = true;
        $remote_in_settings = in_array(wfServer::getRemoteAddr(), $v['remote_addr']);
        break;
      }
    }
    /**
     * 
     */
    $this->log();
    /**
     * data
     */
    $this->data = new PluginWfArray();
    $this->data->set('settings/folder_in_settings', $folder_in_settings);
    $this->data->set('settings/remote_in_settings', $remote_in_settings);
    $this->data->set('request', wfRequest::getAll());
    $this->data->set('folder_exist', wfFilesystem::fileExist(wfGlobals::getAppDir().wfRequest::get('folder')));
    $this->data->set('file_exist', wfFilesystem::fileExist(wfGlobals::getAppDir().wfRequest::get('folder').'/'.wfRequest::get('filename')));
    $this->data->set('content-type', '');
    $this->data->set('content', '');
    $this->data->set('size', '');
    $this->data->set('files', array());

    /**
     * 
     */
    if(!$folder_in_settings){
      $this->data = new PluginWfArray();
      $this->data->set('error/description', 'Folder ('.wfRequest::get('folder').') not declared in theme settings file!');
    }elseif(!$remote_in_settings){
      $this->data = new PluginWfArray();
      $this->data->set('error/description', 'Remote ip ('.wfRequest::get('remote_addr').') is not declared in theme settings file!');
    }
  }
  public function page_get_file(){
    /**
     *
     */
    if($this->data->get('file_exist')){
      $this->data->set('content-type', mime_content_type(wfGlobals::getAppDir().wfRequest::get('folder').'/'.wfRequest::get('filename')));
      $content = wfFilesystem::getContents(wfRequest::get('folder').'/'.wfRequest::get('filename'));
      $this->data->set('content', base64_encode($content));
      $this->data->set('size', filesize(wfGlobals::getAppDir().wfRequest::get('folder').'/'.wfRequest::get('filename')));
    }
    /**
     *
     */
    wfPlugin::includeonce('json/request');
    $jsonRequest = new PluginJsonRequest();
    $jsonRequest->output($this->data->get());
  }
  public function page_get_folder(){
    /**
     *
     */
    if($this->data->get('folder_exist')){
      $content = wfFilesystem::getScandir(wfGlobals::getAppDir().wfRequest::get('folder'));
      $this->data->set('files', $content);
    }else{
    }
    /**
     *
     */
    wfPlugin::includeonce('json/request');
    $jsonRequest = new PluginJsonRequest();
    $jsonRequest->output($this->data->get());
  }
  public function page_set_file(){
    /**
     *
     */
    if(!$this->data->get('file_exist')){
      wfFilesystem::saveFile(wfGlobals::getAppDir().wfRequest::get('folder').'/'.wfRequest::get('filename'), base64_decode(wfRequest::get('content')));
    }
    /**
     *
     */
    wfPlugin::includeonce('json/request');
    $jsonRequest = new PluginJsonRequest();
    $jsonRequest->output($this->data->get());
  }
  public function page_delete_file(){
    /**
     *
     */
    if($this->data->get('file_exist')){
      $this->data->set('action', 'delete');
      wfFilesystem::delete(wfGlobals::getAppDir().wfRequest::get('folder').'/'.wfRequest::get('filename'));
    }
    /**
     *
     */
    wfPlugin::includeonce('json/request');
    $jsonRequest = new PluginJsonRequest();
    $jsonRequest->output($this->data->get());
  }
  private function log(){
    /**
     * 
     */
    $content = wfRequest::get('content');
    wfRequest::set('content', '');
    /**
     * 
     */
    $log = new PluginWfYml(wfGlobals::getAppDir().'/../buto_data/theme/[theme]/plugin/serverfiles/server/log_'.date('ymd').'.yml');
    $log->set('request/', wfRequest::getAll());
    $log->save();
    /**
     * 
     */
    wfRequest::set('content', $content);
    /**
     * 
     */
    return null;
  }
}
