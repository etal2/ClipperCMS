<?php

/**
 * apc storage engine - apc shared memory cache based storage engine
 * requires apc installed
 * 
 * @author etal
 */
@ include_once MODX_BASE_PATH . '/manager/includes/cache/storage.engine.interface.php';
class apcStorageEngine implements iStorageEngine {
    
    private $cachePath;
    private $showReport = false;
    
    public function setCachepath($path) {
        $this->cachePath = $path;
    }

    public function setReport($bool) {
        $this->showReport = $bool;
    }
    
    
    /**
     * Initialize cache
     * @return boolean is cache rebuild required
     */
    public function init() {
        
        if(!extension_loaded('apc')) throw new Exception("Cannot initialize apc cache without apc installed");
        
        if (apc_exists('config_settings')) {
            $requires_cache_rebuild = false;
        } else {
            $requires_cache_rebuild = true;
        }
        return $requires_cache_rebuild;
    }
    
    /**
     * Store next publish time
     * @param type $nextevent
     */
    public function setNextPublishTime($nextevent){
        apc_store('next_publish_time', $nextevent);
    }
    
    /**
     * returns next publish time
     */
    public function getNextPublishTime(){
        return apc_fetch('next_publish_time');
    }
    
     /*
     * returns an array of various config settings
     */
    public function getConfig(){
        return apc_fetch('config_settings');
    }
    
    /*
     * returns an array of system events and the plugins mapped to them
     */
    public function getPluginEvents(){
        return apc_fetch('plugin_events');
    }
    
    /*
     * returns the chunk code (string)
     */
    public function getChunk($chunkname){
        return apc_fetch('chunks/'.$chunkname);
    }
    
    /*
     * stores the chunk in cache
     */
    public function setChunk($chunkname, $chunkvalue){
        apc_store('chunks/'.$chunkname, $chunkvalue);
    }
    
    /*
     * checkes if chunk is stored in cache
     * returnes boolean
     */
    public function containsChunk($chunkname){
        return apc_exists('chunks/'.$chunkname);
    }
    
    /*
     * returns the snippet code or snippet properties (snippet name + 'Props')
     */
    public function getSnippet($snippetname){
        return apc_fetch('snippets/'.$snippetname);
    }
    
    /*
     * stores the snippet (or properties) in cache
     */
    public function setSnippet($snippetname, $snippetvalue){
        apc_store('snippets/'.$snippetname, $snippetvalue);
    }
    
    /*
     * checkes if snippet is stored in cache
     * returnes boolean
     */
    public function containsSnippet($snippetname){
        return apc_exists('snippets/'.$snippetname);
    }
    
    public function getPlugin($pluginname){
        return apc_fetch('plugins/'.$pluginname);
    }
    
    public function setPlugin($pluginname, $pluginvalue){
        apc_store('plugins/'.$pluginname,$pluginvalue);
    }
    
    public function containsPlugin($pluginname){
        return apc_exists('plugins/'.$pluginname);
    }
    
    public function getContentType($documentIdentifier){
        return apc_fetch('content_types/'.$documentIdentifier);
    }   
    
    public function getAliasListing($documentIdentifier){
        return apc_fetch('alias_listings/'.$documentIdentifier);
    }
    
    public function containsAliasListing($documentIdentifier){
        return apc_exists('alias_listings/'.$documentIdentifier);
    }
    
    public function getDocumentListing($documentIdentifier){
        return apc_fetch('document_listings/'.$documentIdentifier);
    }
    
    public function containsDocumentListing($documentIdentifier){
        return apc_exists('document_listings/'.$documentIdentifier);
    }
    
    public function getChildren($documentIdentifier){
        return apc_fetch('document_children/'.$documentIdentifier);
    }
    
    public function emptyCache() {
        apc_clear_cache('user');
    }
    
    /**
     * start cache building procedure
     */
    public function startCacheBuild() {
        $this->tmpConfig = array();
        $this->tmpPluginEvents = array();
        $this->tmpChildMap = array();
    }
    
    public function buildConfigSetting($key, $value){
        $this->tmpConfig[$key]=$value;
    }
    
    public function buildDocumentListing($alias, $id){
        apc_store('document_listings/'.$alias, $id);
    }
    
    public function buildAliasListing($id, $al){
        apc_store('alias_listings/'.$id, $al);
    }
    
    public function buildChildMap($parent, $child){
        $this->tmpChildMap[$parent][]= $child;
    }
    
    public function buildContentType($id, $contentType){
        apc_store('content_types/'.$id, $contentType);
    }
    
    public function buildChunk($name, $content){
        apc_store('chunks/'.$name, $content);
    }
    
    public function buildSnippet($name,$snippet){
        apc_store('snippets/'.$name, $snippet);
    }
    
    public function buildPlugin($name,$plugin){
        apc_store('plugins/'.$name, $plugin);
    }
    
    public function buildPluginEvents($evtname, $pluginnames){
        $this->tmpPluginEvents[$evtname]= $pluginnames;
    }
    
    public function finalizeCacheBuild(){
        apc_store('config_settings', $this->tmpConfig);
        apc_store('plugin_events', $this->tmpPluginEvents);
        foreach($this->tmpChildMap as $parent => $children){
            apc_store('document_children/'.$parent, $children);
        }
        unset($this->tmpConfig);
        unset($this->tmpPluginEvents);
        unset($this->tmpChildMap);
    }
}

?>
