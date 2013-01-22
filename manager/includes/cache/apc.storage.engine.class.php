<?php

/**
 * apc storage engine - apc memory cache based storage engine
 * very fast, requires apc installed
 * 
 * @author etal
 */

class apcStorageEngine  {
    
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
        
        if (count(apc_fetch('config')) > 0) {
            $requires_cache_rebuild = false;
        } else {
            $requires_cache_rebuild = true;
        }
        return $requires_cache_rebuild;
    }
    
     /*
     * returns an array of various config settings
     */
    public function getConfig(){
        return apc_fetch('config');
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
        if($this->storage == null) $this->initStorage();
        $this->tmpConfig = array();
        $this->tmpPluginEvents = array();
        $this->tmpChildMap = array();
    }
    
    public function storeConfigSetting($key, $value){
        $this->tmpConfig[$key]=$value;
    }
    
    public function storeDocumentListing($alias, $id){
        apc_store('document_listings/'.$alias, $id);
    }
    
    public function storeAliasListing($id, $al){
        apc_store('alias_listings/'.$id, $al);
    }
    
    public function storeChildMap($parent, $child){
        $this->tmpChildMap[$parent][]= $child;
    }
    
    public function storeContentType($id, $contentType){
        apc_store('content_types/'.$id, $contentType);
    }
    
    public function storeChunk($name, $content){
        apc_store('chunks/'.$name, $content);
    }
    
    public function storeSnippet($name,$snippet){
        apc_store('snippets/'.$name, $snippet);
    }
    
    public function storePlugin($name,$plugin){
        apc_store('plugins/'.$name, $plugin);
    }
    
    public function storePluginEvents($evtname, $pluginnames){
        $this->tmpPluginEvents[$evtname]= $pluginnames;
    }
    
    public function finalizeCacheBuild(){
        apc_store('config', $this->tmpConfig);
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
