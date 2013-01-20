<?php

/**
 * modx storage engine - filesystem based storage engine
 * based on the original modx cache implementation.
 * loads all values on startup and stores them in memory
 * slow startup time & high memory consumption - not most performant
 * supports extra compatibility bits
 * 
 * @author etal
 */

class flintstoneStorageEngine  {
    
    private $cachePath;
    private $showReport = false;
    
    private $storage;
    
    public function setCachepath($path) {
        $this->cachePath = $path;
    }

    public function setReport($bool) {
        $this->showReport = $bool;
    }
    
    /**
     * Initialize cache - this implementation loads all values on init
     * @param type $modx
     * @throws Exception
     */
    public function init($modx) {
        $this->initStorage();
        if (count($this->storage->load('config')->getKeys()) > 0) {
            $init = true;
        } else {
            $init = $modx->cacheManager->buildCache($modx);
        }
        if(!$init)throw new Exception('Could not initialize flintstone cache.');
    }
    
    /*
     * initialize internal storage engine
     */
    private function initStorage(){
        include_once MODX_BASE_PATH . "/manager/includes/cache/flintstone/flintstone.class.php";
        $this->storage = new Flintstone(array('dir' => $this->cachePath));
    }
    
     /*
     * returns an array of various config settings
     */
    public function getConfig(){
        return $this->storage->load('config')->get('settings');
    }
    
    /*
     * returns an array of system events and the plugins mapped to them
     */
    public function getPluginEvents(){
        return $this->storage->load('config')->get('plugin_events');
    }
    
    /*
     * returns the chunk code (string)
     */
    public function getChunk($chunkname){
        return $this->storage->load('chunks')->get($this->makeKeyValid($chunkname));
    }
    
    /*
     * stores the chunk in cache
     */
    public function setChunk($chunkname, $chunkvalue){
        $this->storage->load('chunks')->set($this->makeKeyValid($chunkname), $chunkvalue);
    }
    
    /*
     * checkes if chunk is stored in cache
     * returnes boolean
     */
    public function containsChunk($chunkname){
        return $this->storage->load('chunks')->get($this->makeKeyValid($chunkname)) != false;
    }
    
    /*
     * returns the snippet code or snippet properties (snippet name + 'Props')
     */
    public function getSnippet($snippetname){
        return $this->storage->load('snippets')->get($this->makeKeyValid($snippetname));
    }
    
    /*
     * stores the snippet (or properties) in cache
     */
    public function setSnippet($snippetname, $snippetvalue){
        $this->storage->load('snippets')->set($this->makeKeyValid($snippetname), $snippetvalue);
    }
    
    /*
     * checkes if snippet is stored in cache
     * returnes boolean
     */
    public function containsSnippet($snippetname){
        return $this->storage->load('snippets')->get($this->makeKeyValid($snippetname)) != false;
    }
    
    public function getPlugin($pluginname){
        return $this->storage->load('plugins')->get($this->makeKeyValid($pluginname));
    }
    
    public function setPlugin($pluginname, $pluginvalue){
        $this->storage->load('plugins')->set($this->makeKeyValid($pluginname),$pluginvalue);
    }
    
    public function containsPlugin($pluginname){
        return $this->storage->load('plugins')->get($this->makeKeyValid($pluginname)) != false;
    }
    
    public function getContentType($documentIdentifier){
        return $this->storage->load('content_types')->get($this->makeKeyValid($documentIdentifier));
    }   
    
    public function getAliasListing($documentIdentifier){
        return $this->storage->load('alias_listings')->get($this->makeKeyValid($documentIdentifier));
    }
    
    public function containsAliasListing($documentIdentifier){
        return $this->storage->load('alias_listings')->get($this->makeKeyValid($documentIdentifier)) != false;
    }
    
    public function getDocumentListing($documentIdentifier){
        return $this->storage->load('document_listings')->get($this->makeKeyValid($documentIdentifier));
    }
    
    public function containsDocumentListing($documentIdentifier){
        return $this->storage->load('document_listings')->get($this->makeKeyValid($documentIdentifier)) != false;
    }
    
    public function getChildren($documentIdentifier){
        return $this->storage->load('document_children')->get($this->makeKeyValid($documentIdentifier));
    }
    
    public function emptyCache() {
        if($this->storage == null) $this->initStorage();
        
        $this->storage->load('config')->flush();
        $this->storage->load('chunks')->flush();
        $this->storage->load('snippets')->flush();
        $this->storage->load('plugins')->flush();
        $this->storage->load('content_types')->flush();
        $this->storage->load('alias_listings')->flush();
        $this->storage->load('document_listings')->flush();
        $this->storage->load('document_children')->flush();
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
        $this->storage->load('document_listings')->set($this->makeKeyValid($alias), $id);
    }
    
    public function storeAliasListing($id, $al){
        $this->storage->load('alias_listings')->set($this->makeKeyValid($id), $al);
    }
    
    public function storeChildMap($parent, $child){
        $this->tmpChildMap[$parent][]= $child;
    }
    
    public function storeContentType($id, $contentType){
        $this->storage->load('content_types')->set($this->makeKeyValid($id), $contentType);
    }
    
    public function storeChunk($name, $content){
        $this->storage->load('chunks')->set($this->makeKeyValid($name), $content);
    }
    
    public function storeSnippet($name,$snippet){
        $this->storage->load('snippets')->set($this->makeKeyValid($name), $snippet);
    }
    
    public function storePlugin($name,$plugin){
        $this->storage->load('plugins')->set($this->makeKeyValid($name), $plugin);
    }
    
    public function storePluginEvents($evtname, $pluginnames){
        $this->tmpPluginEvents[$evtname]= $pluginnames;
    }
    
    public function finalizeCacheBuild(){
        $this->storage->load('config')->set('settings', $this->tmpConfig);
        $this->storage->load('config')->set('plugin_events', $this->tmpPluginEvents);
        foreach($this->tmpChildMap as $parent => $children){
            $this->storage->load('document_children')->set($this->makeKeyValid($parent), $children);
        }
        unset($this->tmpConfig);
        unset($this->tmpPluginEvents);
        unset($this->tmpChildMap);
    }
    
    /**
     * Make valid key
     * @param type $key
     * @return string
     */
    private function makeKeyValid($key){
       /* flintstone has key limitations
        * max key length is 50
        * key can only contain A-Za-z0-9_
        */
        
        // Check key length
        $len = strlen($key);

        // Check valid characters in key
        if ($len > 50 || !preg_match("/^([A-Za-z0-9_]+)$/", $key)) {
            //key is invalid - create a valid one
            $md5 = md5($key);
            
            //this makes for easier debugging but isnt necessary
            //we can just use the md5 and be done with it
            $key = preg_replace('/[^A-Za-z0-9_]/', "_", $key);
            $key = substr($key,0,18).$md5;
	}
        
        return $key;
    }
}

?>
