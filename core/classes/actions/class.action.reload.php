<?php

class ActionReload
{
    public function __construct($args)
    {
        global $neardBs, $neardConfig, $neardBins, $neardTools, $neardApps, $neardHomepage;
        
        // Start loading
        Util::startLoading();
        
        // Refresh hostname
        $neardConfig->replace(Config::CFG_HOSTNAME, gethostname());
        
        // Check browser
        $currentBrowser = $neardConfig->getBrowser();
        if (empty($currentBrowser) || !file_exists($currentBrowser)) {
            $neardConfig->replace(Config::CFG_BROWSER, Util::getDefaultBrowser());
        }
        
        // Rebuild hosts file
        Util::refactorWindowsHosts();
        
        // Process neard.ini
        file_put_contents($neardBs->getIniFilePath(), Util::utf8ToCp1252(TplApp::process()));
        
        // Process Console config 
        file_put_contents($neardTools->getConsole()->getConf(), TplConsole::process());
        
        // Process Websvn config
        file_put_contents($neardApps->getWebsvn()->getConf(), TplWebsvn::process());
        
        // Process Gitlist config
        file_put_contents($neardApps->getGitlist()->getConf(), TplGitlist::process());
        
        // Refresh PEAR version cache file
        $neardBins->getPhp()->getPearVersion();
        
        // Rebuild alias homepage
        $neardHomepage->refreshAliasContent();
    }

}
