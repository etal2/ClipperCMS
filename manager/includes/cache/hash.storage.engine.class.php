<?php

/**
 * hash storage engine - filesystem based storage engine
 * based on the original modx cache implementation
 * but split into smaller files depending on hash values
 * 
 * @author etal
 */
@include_once MODX_BASE_PATH . '/manager/includes/cache/storage.engine.interface.php';
class hashStorageEngine implements iStorageEngine {
    
    private $cachePath;
    private $showReport = false;
    
    /*
     * Array of various config settings
     */
    private $config;
    
    /*
     * Array of system events and plugins registered for each event
     * Key event name
     * Value is array of plugin names
     */
    private $pluginEvent;
    
    /*
     * Array of all chunks
     * Key chunk name
     * Value is chunk contents
     */
    private $chunkCache;
    
    /*
     * Array of all snippets and properties
     * Key snippet name (or snippet name + 'Props')
     * Value is snippet code
     */
    private $snippetCache;
    
    /*
     * Array of all plugins and properties
     * Key plugin name (or plugin name + 'Props')
     * Value is plugin code
     */
    private $pluginCache;
    
    /*
     * Array of documents with non default content type (not text/html)
     * Key is document id
     * Value is content type
     */
    private $contentTypes;
    
    /*
     * Array of all documents
     * Key is document id
     * Value is an array containing 'id', 'alias', 'path', 'parent'
     */
    private $aliasListing;
    
    /*
     * Array of all documents
     * Key is document url (changes depending on friendly url settings)
     * Value is document id
     */
    private $documentListing;
    
    /*
     * Array of all documents
     * No Key
     * Values are arrays of parentId => childId
     * removed - cannot be optimized
     */
    //private $documentMap;
    
    /*
     * Array of all documents
     * Key is document id
     * Values are arrays of direct child ids
     */
    private $childMap;
    
    public function setCachepath($path) {
        $this->cachePath = $path;
    }

    public function setReport($bool) {
        $this->showReport = $bool;
    }
    
    /**
     * Initialize cache - this implementation loads all values on init
     * @return boolean is cache rebuild required
     */
    public function init() {
        $included = include_once (MODX_BASE_PATH . 'assets/cache/siteCache.idx.php');
        
        if (!$included || !is_array($this->config) || empty ($this->config)) {
            $requires_cache_rebuild = true;
        } else {
            $this->aliasListing = array();
            $this->documentListing = array();
            $this->childMap = array();
            $this->contentTypes = array();
            $requires_cache_rebuild = false;
        }
        return $requires_cache_rebuild;
    }
    
     /*
     * returns an array of various config settings
     */
    public function getConfig(){
        return $this->config;
    }
    
    /*
     * returns an array of system events and the plugins mapped to them
     */
    public function getPluginEvents(){
        return $this->pluginEvent;
    }
    
    /*
     * returns the chunck code (string)
     */
    public function getChunk($chunkname){
        return $this->chunkCache[$chunkname];
    }
    
    /*
     * stores the chunck in cache
     */
    public function setChunk($chunkname, $chunkvalue){
        return $this->chunkCache[$chunkname] = $chunkvalue;
    }
    
    /*
     * checkes if chunck is stored in cache
     * returnes boolean
     */
    public function containsChunk($chunkname){
        return array_key_exists ($chunkname, $this->chunkCache);
    }
    
    /*
     * returns the snippet code or snippet properties (snippet name + 'Props')
     */
    public function getSnippet($snippetname){
        return $this->snippetCache[$snippetname];
    }
    
    /*
     * stores the snippet (or properties) in cache
     */
    public function setSnippet($snippetname, $snippetvalue){
        return $this->snippetCache[$snippetname] = $snippetvalue;
    }
    
    /*
     * checkes if snippet is stored in cache
     * returnes boolean
     */
    public function containsSnippet($snippetname){
        return array_key_exists ($snippetname, $this->snippetCache);
    }
    
    public function getPlugin($pluginname){
        return $this->pluginCache[$pluginname];
    }
    
    public function setPlugin($pluginname, $pluginvalue){
        return $this->pluginCache[$pluginname] = $pluginvalue;
    }
    
    public function containsPlugin($pluginname){
        return array_key_exists ($pluginname, $this->pluginCache);
    }
    
    public function getContentType($documentIdentifier){
        if(!array_key_exists($documentIdentifier, $this->contentTypes)){
            $t = &$this->contentTypes;
            @include_once $this->cachePath.'contentTypes.'.$this->hashVal($documentIdentifier).'.idx.php';
        }
        return $this->contentTypes[$documentIdentifier];
    }   
    
    public function getAliasListing($documentIdentifier){
        return $this->_getAliasListing($documentIdentifier);
    }
    
    public function containsAliasListing($documentIdentifier){
        $this->_getAliasListing($documentIdentifier);
        return array_key_exists($documentIdentifier, $this->aliasListing);
    }
    
    public function getDocumentListing($documentIdentifier){
        return $this->_getDocumentListing($documentIdentifier);
    }
    
    public function containsDocumentListing($documentIdentifier){
        $this->_getDocumentListing($documentIdentifier);
        return array_key_exists($documentIdentifier, $this->documentListing);
    }
    
    public function getChildren($documentIdentifier){
        if(!array_key_exists($documentIdentifier, $this->childMap)){
            $x = &$this->childMap;
            @include_once $this->cachePath.'childMap.'.$this->hashVal($documentIdentifier).'.idx.php';
        }
        return $this->childMap[$documentIdentifier];
    }
    
    private function _getDocumentListing($documentIdentifier){
        if(!array_key_exists($documentIdentifier, $this->documentListing)){
            $d = &$this->documentListing;
            @include_once $this->cachePath.'documentListing.'.$this->hashVal($documentIdentifier).'.idx.php';
        }
        return $this->documentListing[$documentIdentifier];
    }
    
    private function _getAliasListing($documentIdentifier){
        if(!array_key_exists($documentIdentifier, $this->aliasListing)){
            $a = &$this->aliasListing;
            @include_once $this->cachePath.'aliasListing.'.$this->hashVal($documentIdentifier).'.idx.php';
        }
        return $this->aliasListing[$documentIdentifier];
    }
    
    public function emptyCache() {
        $this->deleteCacheFiles('documentListing');
        $this->deleteCacheFiles('aliasListing');
        $this->deleteCacheFiles('contentTypes');
        $this->deleteCacheFiles('childMap');
    }
    
    private function hashVal($key){
        $val = substr(MD5($key), -1); //take last char
        return $val;
    }
    
    private $configPHP;
    private $aliasListingPHP;
    private $documentListingPHP;
    private $contentTypesPHP;
    private $tmpChildMap;
    
    private $PHP_START = "<?php\n";
    
    /**
     * start cache building procedure
     */
    public function startCacheBuild() {
        $this->configPHP = $this->PHP_START;
        $this->configPHP .= '$g = &$this->config;'."\n";
        $this->configPHP .= '$e = &$this->pluginEvent;' . "\n";
        $this->configPHP .= '$c = &$this->chunkCache;' . "\n";
        $this->configPHP .= '$s = &$this->snippetCache;' . "\n";
        $this->configPHP .= '$p = &$this->pluginCache;' . "\n";
        
        $this->aliasListingPHP = array();
        $this->documentListingPHP = array();
        $this->contentTypesPHP = array();
        $this->tmpChildMap = array();
        
    }
    
    public function buildConfigSetting($key, $value){
        $this->configPHP .= '$g[\''.$key.'\']'.' = "'.$this->escapeDoubleQuotes($value)."\";\n";
    }
    
    public function buildDocumentListing($alias, $id){
        $this->documentListingPHP[$this->hashVal($alias)][] = '$d[\''.$alias.'\']'." = ".$id.";\n";
    }
    
    public function buildAliasListing($id, $al){
        $this->aliasListingPHP[$this->hashVal($id)][] = '$a[' . $id . ']'." = array('id' => ".$al['id'].", 'alias' => '".$al['alias']."', 'path' => '" . $al['path']."', 'parent' => " . $al['parent']. ");\n";
    }
    
    public function buildChildMap($parent, $child){
        // $this->tmpPHP .= '$m[]'." = array('".$parent."' => '".$child."');\n";
        $this->tmpChildMap[$parent][]= $child;
        //$this->tmpPHP .= '$x[' . $parent . '][]='.$child.";\n";
    }
    
    public function buildContentType($id, $contentType){
        $this->contentTypesPHP[$this->hashVal($id)][] = '$t['.$id.']'." = '".$contentType."';\n";
    }
    
    public function buildChunk($name, $content){
        $this->configPHP .= '$c[\''.$name.'\']'." = '".$this->escapeSingleQuotes($content)."';\n";
    }
    
    public function buildSnippet($name,$snippet){
        $this->configPHP .= '$s[\''.$name.'\']'." = '".$this->escapeSingleQuotes($snippet)."';\n";
    }
    
    public function buildPlugin($name,$plugin){
        $this->configPHP .= '$p[\''.$name.'\']'." = '".$this->escapeSingleQuotes($plugin)."';\n";
    }
    
    public function buildPluginEvents($evtname, $pluginnames){
        $this->configPHP .= '$e[\''.$evtname.'\'] = array(\''.implode("','",$this->escapeSingleQuotes($pluginnames))."');\n";
    }
    
    public function finalizeCacheBuild(){
        // close and write the file
        $this->configPHP .= "\n";
        $filename = $this->cachePath.'siteCache.idx.php';
        $somecontent = $this->configPHP;
 
        $this->writeFile($filename, $somecontent);
        
        unset($this->configPHP);
        
        //create documentListing files
        foreach($this->documentListingPHP as $hash => $values){
            $somecontent = $this->PHP_START;
            $filename = $this->cachePath.'documentListing.'.$hash.'.idx.php';
            foreach($values as $value){
                $somecontent .= $value;
            }
            $somecontent .= "\n";
            $this->writeFile($filename, $somecontent);
        }
        unset($this->documentListingPHP);
        
        //create aliasListing files
        foreach($this->aliasListingPHP as $hash => $values){
            $somecontent = $this->PHP_START;
            $filename = $this->cachePath.'aliasListing.'.$hash.'.idx.php';
            foreach($values as $value){
                $somecontent .= $value;
            }
            $somecontent .= "\n";
            $this->writeFile($filename, $somecontent);
        }
        unset($this->aliasListingPHP);
        
        
        //create contentTypes files
        foreach($this->contentTypesPHP as $hash => $values){
            $somecontent = $this->PHP_START;
            $filename = $this->cachePath.'contentTypes.'.$hash.'.idx.php';
            foreach($values as $value){
                $somecontent .= $value;
            }
            $somecontent .= "\n";
            $this->writeFile($filename, $somecontent);
        }
        unset($this->contentTypesPHP);
        
        //create childMap files
        $childMapPHP = array();
        foreach($this->tmpChildMap as $key => $values){
            $childMapPHP[$this->hashVal($key)][] = '$x[' . $key . ']='.var_export($values, TRUE).";\n";
        }
        unset($this->tmpChildMap);
        foreach($childMapPHP as $hash => $values){
            $somecontent = $this->PHP_START;
            $filename = $this->cachePath.'childMap.'.$hash.'.idx.php';
            foreach($values as $value){
                $somecontent .= $value;
            }
            $somecontent .= "\n";
            $this->writeFile($filename, $somecontent);
        }
    }
    
    private function writeFile($filename, $content){
        if (!$handle = fopen($filename, 'w')) {
             echo 'Cannot open file (',$filename,')';
             exit;
        }

        // Write $somecontent to our opened file.
        if (fwrite($handle, $content) === FALSE) {
           echo 'Cannot write '.$filename.' cache file! Make sure the assets/cache directory is writable!';
           exit;
        }
        fclose($handle);
    }
    
    /**
     * return next publish time
     */
    public function getNextPublishTime(){
        $cacheRefreshTime = 0;
        @include $this->cachePath.'/sitePublishing.idx.php';
        return $cacheRefreshTime;
    }
    
    /**
     * Store next publish time
     * @param type $nextevent
     */
    public function setNextPublishTime($nextevent){
        // write the file
        $filename = $this->cachePath.'/sitePublishing.idx.php';
        $somecontent = '<?php $cacheRefreshTime='.$nextevent.'; ?>';

        if (!$handle = fopen($filename, 'w')) {
             echo 'Cannot open file ('.$filename.')';
             exit;
        }

        flock($handle, LOCK_EX);
        
        // Write $somecontent to our opened file.
        if (fwrite($handle, $somecontent) === FALSE) {
           echo 'Cannot write publishing info file! Make sure the assets/cache directory is writable!';
           exit;
        }

        flock($handle, LOCK_UN);
        
        fclose($handle);
    }
    
    private function escapeDoubleQuotes($s) {
        $q1 = array("\\","\"","\r","\n","\$");
        $q2 = array("\\\\","\\\"","\\r","\\n","\\$");
        return str_replace($q1,$q2,$s);
    }

    private function escapeSingleQuotes($s) {
        $q1 = array("\\","'");
        $q2 = array("\\\\","\\'");
        return str_replace($q1,$q2,$s);
    }
    
    private function deleteCacheFiles($filename) {
        $filesincache = 0;
        $deletedfilesincache = 0;
        if (function_exists('glob')) {
            // New and improved!
            $files = glob(realpath($this->cachePath).'/*');
            $filesincache = count($files);
            $deletedfiles = array();
            while ($file = array_shift($files)) {
                $name = basename($file);
                if (preg_match('/\.'.$filename.'/',$name) && !in_array($name, $deletedfiles)) {
                    $deletedfilesincache++;
                    $deletedfiles[] = $name;
                    unlink($file);
                }
            }
        } else {
            // Old way of doing it (no glob function available)
            if ($handle = opendir($this->cachePath)) {
                // Initialize deleted per round counter
                $deletedThisRound = 1;
                while ($deletedThisRound){
                    if(!$handle) $handle = opendir($this->cachePath);
                    $deletedThisRound = 0;
                    while (false !== ($file = readdir($handle))) {
                        if ($file != "." && $file != "..") {
                            $filesincache += 1;
                            if ( preg_match('/\.'.$filename.'/', $file) && (!is_array($deletedfiles) || !array_search($file,$deletedfiles)) ) {
                                $deletedfilesincache += 1;
                                $deletedThisRound++;
                                $deletedfiles[] = $file;
                                unlink($this->cachePath.$file);
                            } // End if
                        } // End if
                    } // End while
                    closedir($handle);
                    $handle = '';
                } // End while ($deletedThisRound)
            }
        }

        $report = array(
            filesincache => $filesincache,
            deletedfilesincache => $deletedfilesincache,
            deletedfiles => $deletedfiles);
        
        return $report;
    }
}

?>
