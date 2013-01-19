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
class modxStorageEngine  {
    
    private $cachePath;
    private $showReport = false;
    private $deletedfiles = array();
    
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
     */
    private $documentMap;
    
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
     * @param type $modx
     * @throws Exception
     */
    public function init($modx) {
        if ($included= file_exists(MODX_BASE_PATH . 'assets/cache/siteCache.idx.php')) {
            $included= include_once (MODX_BASE_PATH . 'assets/cache/siteCache.idx.php');
        }
        if (!$included || !is_array($this->config) || empty ($this->config)) {
            $rebuilt = $modx->cacheManager->buildCache($modx);
            $included = false;
            if($rebuilt && $included= file_exists(MODX_BASE_PATH . 'assets/cache/siteCache.idx.php')) {
                $included= include MODX_BASE_PATH . 'assets/cache/siteCache.idx.php';
            }
        }
        if(!$included)throw new Exception('Could not initialize modx cache.');
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
        return $this->contentTypes[$documentIdentifier];
    }   
    
    public function getAliasListing($documentIdentifier){
        return $this->aliasListing[$documentIdentifier];
    }
    
    public function containsAliasListing($documentIdentifier){
        return array_key_exists ($documentIdentifier, $this->aliasListing);
    }
    
    public function getDocumentListing($documentIdentifier){
        return $this->documentListing[$documentIdentifier];
    }
    
    public function containsDocumentListing($documentIdentifier){
        return array_key_exists ($documentIdentifier, $this->documentListing);
    }
    
    public function getChildren($documentIdentifier){
        return $this->childMap[$documentIdentifier];
    }
    
    function emptyCache($modx) {
        if(!isset($this->cachePath)) {
            echo "Cache path not set.";
            exit;
        }
        $filesincache = 0;
        $deletedfilesincache = 0;
        if (function_exists('glob')) {
            // New and improved!
            $files = glob(realpath($this->cachePath).'/*');
            $filesincache = count($files);
            $deletedfiles = array();
            while ($file = array_shift($files)) {
                $name = basename($file);
                if (preg_match('/\.pageCache/',$name) && !in_array($name, $deletedfiles)) {
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
                            if ( preg_match("/\.pageCache/", $file) && (!is_array($deletedfiles) || !array_search($file,$deletedfiles)) ) {
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

        $report = array();
        $report->filesincache = $filesincache;
        $report->deletedfilesincache = $deletedfilesincache;
        $report->deletedfiles = $deletedfiles;
        
        return $report;
    }
    
    
    private $tmpPHP;
    /**
     * start cache building procedure
     */
    public function startCacheBuild($modx) {
        $this->tmpPHP = "<?php\n";
        $this->tmpPHP .= '$g=&$this->config;'."\n";
        $this->tmpPHP .= '$this->aliasListing = array();' . "\n";
        $this->tmpPHP .= '$a = &$this->aliasListing;' . "\n";
        $this->tmpPHP .= '$d = &$this->documentListing;' . "\n";
        $this->tmpPHP .= '$m = &$this->documentMap;' . "\n";
        $this->tmpPHP .= '$x = &$this->childMap;' . "\n";
        $this->tmpPHP .= '$t = &$this->contentTypes;' . "\n";
        $this->tmpPHP .= '$c = &$this->chunkCache;' . "\n";
        $this->tmpPHP .= '$s = &$this->snippetCache;' . "\n";
        $this->tmpPHP .= '$p = &$this->pluginCache;' . "\n";
        $this->tmpPHP .= '$e = &$this->pluginEvent;' . "\n";
    }
    
    public function storeConfigSetting($key, $value){
        $this->tmpPHP .= '$g[\''.$key.'\']'.' = "'.$this->escapeDoubleQuotes($value)."\";\n";
    }
    
    public function storeDocumentListing($alias, $id){
        $this->tmpPHP .= '$d[\''.$alias.'\']'." = ".$id.";\n";
    }
    
    public function storeAliasListing($id, $al){
        $this->tmpPHP .= '$a[' . $id . ']'." = array('id' => ".$al['id'].", 'alias' => '".$al['alias']."', 'path' => '" . $al['path']."', 'parent' => " . $al['parent']. ");\n";
    }
    
    public function storeChildMap($parent, $child){
        $this->tmpPHP .= '$m[]'." = array('".$parent."' => '".$child."');\n";
        $this->tmpPHP .= '$x[' . $parent . '][]='.$child.";\n";
    }
    
    public function storeContentType($id, $contentType){
        $this->tmpPHP .= '$t['.$id.']'." = '".$contentType."';\n";
    }
    
    public function storeChunk($name, $content){
        $this->tmpPHP .= '$c[\''.$name.'\']'." = '".$this->escapeSingleQuotes($content)."';\n";
    }
    
    public function storeSnippet($name,$snippet){
        $this->tmpPHP .= '$s[\''.$name.'\']'." = '".$this->escapeSingleQuotes($snippet)."';\n";
    }
    
    public function storePlugin($name,$plugin){
        $this->tmpPHP .= '$p[\''.$name.'\']'." = '".$this->escapeSingleQuotes($plugin)."';\n";
    }
    
    public function storePluginEvents($evtname, $pluginnames){
        $this->tmpPHP .= '$e[\''.$evtname.'\'] = array(\''.implode("','",$this->escapeSingleQuotes($pluginnames))."');\n";
    }
    
    public function finalizeCacheBuild(){
        // close and write the file
        $this->tmpPHP .= "\n";
        $filename = $this->cachePath.'siteCache.idx.php';
        $somecontent = $this->tmpPHP;
 
        if (!$handle = fopen($filename, 'w')) {
             echo 'Cannot open file (',$filename,')';
             exit;
        }

        // Write $somecontent to our opened file.
        if (fwrite($handle, $somecontent) === FALSE) {
           echo 'Cannot write main cache file! Make sure the assets/cache directory is writable!';
           exit;
        }
        fclose($handle);
    }
    
    function escapeDoubleQuotes($s) {
        $q1 = array("\\","\"","\r","\n","\$");
        $q2 = array("\\\\","\\\"","\\r","\\n","\\$");
        return str_replace($q1,$q2,$s);
    }

    function escapeSingleQuotes($s) {
        $q1 = array("\\","'");
        $q2 = array("\\\\","\\'");
        return str_replace($q1,$q2,$s);
    }
}

?>
