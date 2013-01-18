<?php

/**
 * Original clipper cache implementation
 * moved out of document parser
 *
 * @author etal
 */
class ClipperCache {
    
    var $config;
    var $chunkCache;
    var $snippetCache;
    var $pluginCache;
    var $contentTypes;
    var $aliasListing;
    var $documentListing;
    var $documentMap;
    var $pluginEvent;
    
    function loadSettings(&$parser){
        
        $parser->config = &$this->config;
        $parser->chunkCache = &$this->chunkCache;
        $parser->snippetCache = &$this->snippetCache;
        $parser->pluginCache = &$this->pluginCache;
        $parser->contentTypes = &$this->contentTypes;
        $parser->aliasListing = &$this->aliasListing;
        $parser->documentListing = &$this->documentListing;
        $parser->documentMap = &$this->documentMap;
        $parser->pluginEvent = &$this->pluginEvent;
        
        if ($included= file_exists(MODX_BASE_PATH . 'assets/cache/siteCache.idx.php')) {
            $included= include_once (MODX_BASE_PATH . 'assets/cache/siteCache.idx.php');
        }
        if (!$included || !is_array($this->config) || empty ($this->config)) {
            include_once MODX_BASE_PATH . "/manager/processors/cache_sync.class.processor.php";
            $cache = new synccache();
            $cache->setCachepath(MODX_BASE_PATH . "/assets/cache/");
            $cache->setReport(false);
            $rebuilt = $cache->buildCache($this);
            $included = false;
            if($rebuilt && $included= file_exists(MODX_BASE_PATH . 'assets/cache/siteCache.idx.php')) {
                $included= include MODX_BASE_PATH . 'assets/cache/siteCache.idx.php';
            }
        }
    }
}

?>
