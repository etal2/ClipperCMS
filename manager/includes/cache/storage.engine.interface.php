<?php

/**
 * general storage engine interface
 * 
 * @author etal
 */

interface iStorageEngine  {
    
    public function setCachepath($path);
    public function setReport($bool);
    
    
    /**
     * Initialize cache
     * @return boolean is cache rebuild required
     */
    public function init();
    
    /**
     * Store next publish time
     * @param type $nextevent
     */
    public function setNextPublishTime($nextevent);
    
    /**
     * returns next publish time
     */
    public function getNextPublishTime();
    
     /*
     * returns an array of various config settings
     */
    public function getConfig();
    
    /*
     * returns an array of system events and the plugins mapped to them
     */
    public function getPluginEvents();
    
    /*
     * returns the chunk code (string)
     */
    public function getChunk($chunkname);
    
    /*
     * stores the chunk in cache
     */
    public function setChunk($chunkname, $chunkvalue);
    
    /*
     * checkes if chunk is stored in cache
     * returnes boolean
     */
    public function containsChunk($chunkname);
    
    /*
     * returns the snippet code or snippet properties (snippet name + 'Props')
     */
    public function getSnippet($snippetname);
    
    /*
     * stores the snippet (or properties) in cache
     */
    public function setSnippet($snippetname, $snippetvalue);
    
    /*
     * checkes if snippet is stored in cache
     * returnes boolean
     */
    public function containsSnippet($snippetname);
    
    public function getPlugin($pluginname);
    
    public function setPlugin($pluginname, $pluginvalue);
    
    public function containsPlugin($pluginname);
    
    public function getContentType($documentIdentifier);
    
    public function getAliasListing($documentIdentifier);
    
    public function containsAliasListing($documentIdentifier);
    
    public function getDocumentListing($documentIdentifier);
    
    public function containsDocumentListing($documentIdentifier);
    
    public function getChildren($documentIdentifier);
    
    public function emptyCache();
    
    /**
     * start cache building procedure
     */
    public function startCacheBuild();
    
    public function buildConfigSetting($key, $value);
    
    public function buildDocumentListing($alias, $id);
    
    public function buildAliasListing($id, $al);
    
    public function buildChildMap($parent, $child);
    
    public function buildContentType($id, $contentType);
    
    public function buildChunk($name, $content);
    
    public function buildSnippet($name,$snippet);
    
    public function buildPlugin($name,$plugin);
    
    public function buildPluginEvents($evtname, $pluginnames);
    
    public function finalizeCacheBuild();
}

?>
