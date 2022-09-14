<?php

namespace YaleREDCap\Recalc;

use ExternalModules\AbstractExternalModule;

class Recalc extends AbstractExternalModule
{
    function redcap_every_page_top()
    {
        list($server_name, $port, $ssl, $page_full) = getServerNamePortSSL();
        $isDataQuality = strpos($page_full, '/DataQuality/') != false;
        if ($isDataQuality) {
            \System::increaseMemory(1024 * 32);
            \System::increaseMaxExecTime(60 * 60);
        }
    }
}
