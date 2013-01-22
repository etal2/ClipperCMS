<?php
// cache & synchronize class

class synccache{
    var $cachePath;
    var $showReport;
    
    var $aliases = array();
    var $parents = array();

    private $initialized = false;
    
    private $storageEngine = null;
    
    //private $engine = 'modx';
    
    function __construct() {
        if(!isset($this->engine)){
            //fallback to default$this->engine
            $this->engine = 'flintstone';
        }
        
        $engine = $this->engine;
        if(include_once MODX_BASE_PATH . "/manager/includes/cache/$engine.storage.engine.class.php"){
            $engineclassname= $engine.'StorageEngine';
            $this->storageEngine = new $engineclassname();
        } else {
            throw new Exception("Cannot initialize $engine cache storage engine");
        }
    }
    
    function setCachepath($path) {
        $this->cachePath = $path;
        $this->storageEngine->setCachepath($path);
    }

    function setReport($bool) {
        $this->showReport = $bool;
        $this->storageEngine->setReport($bool);
    }

    function getParents($id, $path = '') { // modx:returns child's parent
        global $modx;
        if(empty($this->aliases)) {
            $sql = "SELECT id, IF(alias='', id, alias) AS alias, parent FROM ".$modx->getFullTableName('site_content');
            $qh = $modx->db->query($sql);
            if ($qh && $modx->db->getRecordCount($qh) > 0)  {
                while ($row = $modx->db->getRow($qh)) {
                    $this->aliases[$row['id']] = $row['alias'];
                    $this->parents[$row['id']] = $row['parent'];
                }
            }
        }
        if (isset($this->aliases[$id])) {
            $path = $this->aliases[$id] . ($path != '' ? '/' : '') . $path;
            return $this->getParents($this->parents[$id], $path);
        }
        return $path;
    }
    
    /**
     * Load everything, rebuild cache if necessary
     */
    public function init($modx) {
        if(!$this->initialized){
            if(!isset($this->cachePath)){
                setCachepath(MODX_BASE_PATH . "/assets/cache/");
            }
            //setReport(false);
            $requires_rebuild = $this->storageEngine->init();
            if($requires_rebuild){
                //rebuild cache
                $rebuild = $this->buildCache($modx);
                
                if($rebuild){
                    $requires_rebuild = $this->storageEngine->init();
                    if($requires_rebuild)throw new Exception("Could not initialize $this->engine cache.");
                } else {
                    throw new Exception("Could not rebuild $this->engine cache on initialization.");
                }
            }
            
            $this->initialized = true;
        }
    }
    
    /*
     * returns an array of various config settings
     */
    public function getConfig(){
        return $this->storageEngine->getConfig();
    }
    
    /*
     * returns an array of system events and the plugins mapped to them
     */
    public function getPluginEvents(){
        return $this->storageEngine->getPluginEvents();
    }
    
    /*
     * returns the chunck code (string)
     */
    public function getChunk($chunkname){
        return $this->storageEngine->getChunk($chunkname);
    }
    
    /*
     * stores the chunck in cache
     */
    public function setChunk($chunkname, $chunkvalue){
        $this->storageEngine->setChunk($chunkname, $chunkvalue);
    }
    
    /*
     * checkes if chunck is stored in cache
     * returnes boolean
     */
    public function containsChunk($chunkname){
        return $this->storageEngine->containsChunk($chunkname);
    }
    
    /*
     * returns the snippet code or snippet properties (snippet name + 'Props')
     */
    public function getSnippet($snippetname){
        return $this->storageEngine->getSnippet($snippetname);
    }
    
    /*
     * stores the snippet (or properties) in cache
     */
    public function setSnippet($snippetname, $snippetvalue){
        $this->storageEngine->setSnippet($snippetname, $snippetvalue);
    }
    
    /*
     * checkes if snippet is stored in cache
     * returnes boolean
     */
    public function containsSnippet($snippetname){
        return $this->storageEngine->containsSnippet($snippetname);
    }
    
    public function getPlugin($pluginname){
        return $this->storageEngine->getPlugin($pluginname);
    }
    
    public function setPlugin($pluginname, $pluginvalue){
        $this->storageEngine->setPlugin($pluginname, $pluginvalue);
    }
    
    public function containsPlugin($pluginname){
        return $this->storageEngine->containsPlugin($pluginname);
    }
    
    public function getContentType($documentIdentifier){
        return $this->storageEngine->getContentType($documentIdentifier);
    }   
    
    public function getAliasListing($documentIdentifier){
        return $this->storageEngine->getAliasListing($documentIdentifier);
    }
    
    public function containsAliasListing($documentIdentifier){
        return $this->storageEngine->containsAliasListing($documentIdentifier);
    }
    
    public function getDocumentListing($documentIdentifier){
        return $this->storageEngine->getDocumentListing($documentIdentifier);
    }
    
    public function containsDocumentListing($documentIdentifier){
        return $this->storageEngine->containsDocumentListing($documentIdentifier);
    }
    
    public function getChildren($documentIdentifier){
        return $this->storageEngine->getChildren($documentIdentifier);
    }
    
    public function emptyCache($modx = null) {
        if((function_exists('is_a') && is_a($modx, 'DocumentParser') === false) || get_class($modx) !== 'DocumentParser') {
            $modx = $GLOBALS['modx'];
        }
        $this->storageEngine->emptyCache();
        $report = $this->deletePageCache();
        $this->buildCache($modx);
        $nextevent = $this->calculateNextPublishTime($modx);
        $this->storeNextPublishTime($nextevent);

        // finished cache stuff.
        if($this->showReport==true) {
            global $_lang;
            printf($_lang['refresh_cache'], $report['filesincache'], $report['deletedfilesincache']);
            $limit = count($report['deletedfiles']);
            if($limit > 0) {
                echo '<p>'.$_lang['cache_files_deleted'].'</p><ul>';
                for($i=0;$i<$limit; $i++) {
                    echo '<li>',$report['deletedfiles'][$i],'</li>';
                }
                echo '</ul>';
            }
        }
    }

    private $deletedfiles = array();
    
    private function deletePageCache() {
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

        $report = array(
            filesincache => $filesincache,
            deletedfilesincache => $deletedfilesincache,
            deletedfiles => $deletedfiles);
        
        return $report;
    }
    
    /**
     * Store next publish time
     * should be moved into storage engine later
     * @param type $nextevent
     */
    private function storeNextPublishTime($nextevent){
        // write the file
        $filename = $this->cachePath.'/sitePublishing.idx.php';
        $somecontent = '<?php $cacheRefreshTime='.$nextevent.'; ?>';

        if (!$handle = fopen($filename, 'w')) {
             echo 'Cannot open file ('.$filename.')';
             exit;
        }

        // Write $somecontent to our opened file.
        if (fwrite($handle, $somecontent) === FALSE) {
           echo 'Cannot write publishing info file! Make sure the assets/cache directory is writable!';
           exit;
        }

        fclose($handle);
    }
    
    /**
     * Calculate next publish time
     * @param type $modx
     * @return int
     */
    private function calculateNextPublishTime($modx){
        // update publish time file
        $timesArr = array();
        $sql = 'SELECT MIN(pub_date) AS minpub FROM '.$modx->getFullTableName('site_content').' WHERE pub_date>'.time();
        if(@!$result = $modx->db->query($sql)) {
            echo 'Couldn\'t determine next publish event!';
        }

        $tmpRow = $modx->db->getRow($result);
        $minpub = $tmpRow['minpub'];
        if($minpub!=NULL) {
            $timesArr[] = $minpub;
        }

        $sql = 'SELECT MIN(unpub_date) AS minunpub FROM '.$modx->getFullTableName('site_content').' WHERE unpub_date>'.time();
        if(@!$result = $modx->db->query($sql)) {
            echo 'Couldn\'t determine next unpublish event!';
        }
        $tmpRow = $modx->db->getRow($result);
        $minunpub = $tmpRow['minunpub'];
        if($minunpub!=NULL) {
            $timesArr[] = $minunpub;
        }

        if(count($timesArr)>0) {
            $nextevent = min($timesArr);
        } else {
            $nextevent = 0;
        }
        
        return $nextevent;
    }
    
    
    /**
     * build siteCache file
     * @param  DocumentParser $modx
     * @return boolean success
     */
    function buildCache($modx) {
        
        $this->storageEngine->startCacheBuild();
        
        // SETTINGS & DOCUMENT LISTINGS CACHE

        // get settings
        $sql = 'SELECT * FROM '.$modx->getFullTableName('system_settings');
        $rs = $modx->db->query($sql);
        $limit_tmp = $modx->db->getRecordCount($rs);
        $config = array();
        
        while(list($key,$value) = $modx->db->getRow($rs,'num')) {
            $this->storageEngine->storeConfigSetting($key, $value);
            $config[$key] = $value;
        }

        // get aliases modx: support for alias path
        $tmpPath = '';
        $sql = 'SELECT IF(alias=\'\', id, alias) AS alias, id, contentType, parent FROM '.$modx->getFullTableName('site_content').' WHERE deleted=0 ORDER BY parent, menuindex';
        $rs = $modx->db->query($sql);
        $limit_tmp = $modx->db->getRecordCount($rs);
        for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
            $tmp1 = $modx->db->getRow($rs);
            if ($config['friendly_urls'] == 1 && $config['use_alias_path'] == 1) {
                $tmpPath = $this->getParents($tmp1['parent']);
                $alias= (strlen($tmpPath) > 0 ? "$tmpPath/" : '').$tmp1['alias'];
                $alias= $modx->db->escape($alias);
                $this->storageEngine->storeDocumentListing($alias, $tmp1['id']);
            }
            else {
                $this->storageEngine->storeDocumentListing($modx->db->escape($alias), $tmp1['id']);
            }
            $this->storageEngine->storeAliasListing($tmp1['id'], array('id' => $tmp1['id'], 'alias' => $modx->db->escape($tmp1['alias']), 'path' => $modx->db->escape($tmpPath), 'parent' => $tmp1['parent']));
            $this->storageEngine->storeChildMap($tmp1['parent'],$tmp1['id']);
        }


        // get content types
        $sql = 'SELECT id, contentType FROM '.$modx->getFullTableName('site_content')." WHERE contentType != 'text/html'";
        $rs = $modx->db->query($sql);
        $limit_tmp = $modx->db->getRecordCount($rs);
        for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
           $tmp1 = $modx->db->getRow($rs);
           $this->storageEngine->storeContentType($tmp1['id'], $tmp1['contentType']);
        }

        // WRITE Chunks to cache file
        $sql = 'SELECT * FROM '.$modx->getFullTableName('site_htmlsnippets');
        $rs = $modx->db->query($sql);
        $limit_tmp = $modx->db->getRecordCount($rs);
        for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
           $tmp1 = $modx->db->getRow($rs);
           $this->storageEngine->storeChunk($modx->db->escape($tmp1['name']), $tmp1['snippet']);
        }

        // WRITE snippets to cache file
        $sql = 'SELECT ss.*,sm.properties as `sharedproperties` '.
                'FROM '.$modx->getFullTableName('site_snippets').' ss '.
                'LEFT JOIN '.$modx->getFullTableName('site_modules').' sm on sm.guid=ss.moduleguid';
        $rs = $modx->db->query($sql);
        $limit_tmp = $modx->db->getRecordCount($rs);
        for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
           $tmp1 = $modx->db->getRow($rs);
           $this->storageEngine->storeSnippet($modx->db->escape($tmp1['name']),$tmp1['snippet']);
           // Raymond: save snippet properties to cache
           if ($tmp1['properties']!=""||$tmp1['sharedproperties']!="") $this->storageEngine->storeSnippet($tmp1['name'].'Props', $tmp1['properties']." ".$tmp1['sharedproperties']);
            // End mod
        }

        // WRITE plugins to cache file
        $sql = 'SELECT sp.*,sm.properties as `sharedproperties`'.
                'FROM '.$modx->getFullTableName('site_plugins').' sp '.
                'LEFT JOIN '.$modx->getFullTableName('site_modules').' sm on sm.guid=sp.moduleguid '.
                'WHERE sp.disabled=0';
        $rs = $modx->db->query($sql);
        $limit_tmp = $modx->db->getRecordCount($rs);
        for ($i_tmp=0; $i_tmp<$limit_tmp; $i_tmp++) {
           $tmp1 = $modx->db->getRow($rs);
           $this->storageEngine->storePlugin($modx->db->escape($tmp1['name']), $tmp1['plugincode']);
           if ($tmp1['properties']!=''||$tmp1['sharedproperties']!='') $this->storageEngine->storePlugin($tmp1['name'].'Props', $tmp1['properties'].' '.$tmp1['sharedproperties']);
        }


        // WRITE system event triggers
        $sql = 'SELECT sysevt.name as `evtname`, pe.pluginid, plugs.name
                FROM '.$modx->getFullTableName('system_eventnames').' sysevt
                INNER JOIN '.$modx->getFullTableName('site_plugin_events').' pe ON pe.evtid = sysevt.id
                INNER JOIN '.$modx->getFullTableName('site_plugins').' plugs ON plugs.id = pe.pluginid
                WHERE plugs.disabled=0
                ORDER BY sysevt.name,pe.priority';
        $events = array();
        $rs = $modx->db->query($sql);
        $limit_tmp = $modx->db->getRecordCount($rs);
        for ($i=0; $i<$limit_tmp; $i++) {
            $evt = $modx->db->getRow($rs);
            if(!$events[$evt['evtname']]) $events[$evt['evtname']] = array();
            $events[$evt['evtname']][] = $evt['name'];
        }
        foreach($events as $evtname => $pluginnames) {
            $this->storageEngine->storePluginEvents($evtname, $pluginnames);
        }

        // invoke OnBeforeCacheUpdate event
        if ($modx) $modx->invokeEvent('OnBeforeCacheUpdate');

        $this->storageEngine->finalizeCacheBuild();

        // invoke OnCacheUpdate event
        if ($modx) $modx->invokeEvent('OnCacheUpdate');

        return true;
    }
}
?>
