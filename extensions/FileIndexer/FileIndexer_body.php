<?php
/**
 * TITEL:                Extension: FileIndexer / FileIndexer_body.php
 * ERSTELLDATUM:        26.06.2008
 * AUTHOR:                Ramon Dohle aka 'raZe'
 * ORGANISATION:        GRASS-MERKUR GmbH & Co. KG & raZe.it
 * VERSION:                0.4.1.00    28.09.2010
 * REVISION:
 *         26.06.2008    0.1.0.00    raZe        *    Initiale Version
 *         29.06.2009    0.1.1.00    raZe        *    Weitere Offene Punkte abgearbeitet:
 *                                                 *    $wgFiArticleNamespace auch bei Uploads nutzen.
 *                                                 *    $wgFiAutoIndexMark automatisch beim Upload mit Indexerstellungs-Aufforderung im Artikel einsetzbar machen.
 *                                                     Neue Option: $wgFiSetAutoIndexMark
 *                                                 *    $wgFiCheckSystem nutzen um Systemvoraussetzung bei jedem Aufruf zu pruefen
 *                                                     Neue Option: $wgFiCheckSystem
 *                                                 *    Temporaere Datei muss eindeutig einer Session zugeordnet werden koennen...
 *         01.07.2009    0.1.2.00    raZe        *    An neue Funktionen in FileIndexer.php angeglichen
 *         26.08.2010    0.2.0.00    raZe        *    Formular an neue Funktionsweise angepasst
 *         27.08.2010    0.2.1.00    raZe        *    Logik nach neuem Formular umgesetzt
 *         28.08.2010    0.2.2.00    raZe        *    Formular mit Hilfeinformationen verbessert
 *                                             *    Ergebnisinformationsausgabe verbessert
 *                                             *    Check Funktionalitaet hinzugefuegt
 *                                             *    Formularreset abgestellt
 *         29.08.2010    0.2.2.01    raZe        *    Revisionskopf korrigiert
 *         30.08.2010    0.3.0.00    raZe        *    Mehrere Umstellungen auf Formularanpassungen im Page edit und Upload Bereich
 *         23.09.2010    0.3.1.00    raZe        *    BUGFIX: Fehler entfernt, der bei Leerzeilen in der Spezialseite zum Abbruch fuehrte
 *         25.09.2010    0.3.2.00    raZe        *    Reihenfolge der Felder in der Spezialseite umgestellt
 *                                             *    Anzeige einiger Eingabefelder (Namespace und WildcardSign) optional gemacht.
 *         26.09.2010    0.3.3.00    raZe        *    Trennung zwischen "nicht gefundenen Dateien" und "nicht unterstuetzten Dateitypen" bei der Ergebnisausgabe
 *                                                 herbeigefuehrt
 *                                             *    Systempruefung eines Kommandos ist nun auch mehrsprachig
 *         27.09.2010    0.4.0.00    raZe        *    SCHNITTSTELLE: neue, statische Funktion getCommandLine() zur endgueltigen Bestimmung des Kommandoaufrufes
 *                                             *    BUGFIX: Funktion checkNecessaryCommands() ueberarbeitet un dFehler behoben
 *                                                 +    Ausgabe verbesssert
 *                                                 +    'whereis' durch 'which' abgeloest
 *                                                 +    wenn Pfad inkorrekt, aber Erfolg mit which => kein Fehler, sondern Warnung (Spezialseite), aber
 *                                                     Indexierung wird mit gefundenem Pfad trotzdem durchgefuehrt.
 *         28.09.2010    0.4.1.00    raZe        *    BUGFIX: In processIndex() Links korrigiert, sodass Bilder nicht mehr als Bild aufgelistet werden
 *                                             *    Wildcard Zeichen Text in Spezialzeichenhilfe besser hervorgehoben fuer die Konfiguration
 *                                                 $wgFiSpWildcardSignChangeable = false
 *
 *
 * BESCHREIBUNG:
 *         Diese Erweiterung basiert auf der Wiki-Erweiterung 'FileIndexer' vom Stand 15.05.2008.
 *         Wie sein Original soll sie Dateien Indexieren um auch den Inhalt dieser Dateien durch Suchmaschienen zu erfassen.
 *
 *         Hier wird die Spezialseitenklasse zur Verfuegung gestellt.
 */
 
class FileIndexer extends SpecialPage{
// CONFIGURATION
 
    var $insertEditTools = true;
 
    /**
     * Konstruktor der Spezialseite.
     */
    function FileIndexer() {
        SpecialPage::SpecialPage('FileIndexer');
    }
 
    /**
     * Systempruefung eines Kommandos
     *
     * @param $sCommand STRING Systemkommando
     * @return STRING | TRUE Fehlermeldung oder TRUE fuer Erfolgreiche Pruefung
     */
    static private function isCommandPresent($sCommandKey, $sCommandPath){
        global $wgFiCommandPaths;
 
        if($sCommandPath === false){
            return "* ERROR (" . $sCommandKey . "): " . wfMsg('fileindexer_sp_msg_response_systemcheck_command_missing') . "\n";
        }
 
        if(file_exists($sCommandPath) === false){
            $sCommand = (strrpos($sCommandPath, '/') !== false) ? substr($sCommandPath, strrpos($sCommandPath, '/') + 1) : $sCommandPath;
            exec("which " . $sCommand, $aWhichReply);
            if(empty($aWhichReply)){
                $wgFiCommandPaths[$sCommand] = false;
                return "* ERROR (" . $sCommand . "): " . wfMsg('fileindexer_sp_msg_response_systemcheck_command_missing') . "\n";
            }
            else{
                $wgFiCommandPaths[$sCommand] = $aWhichReply[0];
                return "* WARNING (" . $sCommand . "): " . wfMsg('fileindexer_sp_msg_response_systemcheck_command_wrong_path') . $aWhichReply[0] . "\n";
            }
 
        }
 
        return true;
    }
 
    /**
     * Systemvoraussetzungen werden geprueft.
     *
     * @return STRING | TRUE Fehlermeldung oder TRUE fuer Erfolgreiche Pruefung
     */
    static function checkNecessaryCommands(){
        global $wgFiCheckSystem, $wgFiCommandPaths;
 
        $sAnswer = "";
        // Testen, ob alle benoetigten Tools auf dem System vorhanden sind.
        if($wgFiCheckSystem === true){
            foreach($wgFiCommandPaths as $sCommandKey => $sPath){
                $xCheck = self::isCommandPresent($sCommandKey, $sPath);
                if($xCheck !== true){
                    $sAnswer .= $xCheck;
                }
            }
        }
 
        return ($sAnswer == "") ? true : $sAnswer;
    }
 
    static function getCommandLine($sFile){
        global $wgFiCommandCalls, $wgFiCommandPaths;
 
        $sFileExtension = strtolower(substr(strrchr($sFile, '.'),1));
        $sCommandLine = $wgFiCommandCalls[$sFileExtension];
 
        while(strpos($sCommandLine, WC_FI_COMMAND) !== false){
            $iSignStart = strpos($sCommandLine, WC_FI_COMMAND);
            $iSignEnd = strlen(WC_FI_COMMAND) + $iSignStart;
            $iOpenSign = strpos($sCommandLine, '[', $iSignEnd);
            $iCloseSign = strpos($sCommandLine, ']', $iSignEnd + 1);
            if($iOpenSign === false || $iCloseSign === false || $iOpenSign > $iCloseSign){
                return false;
            }
 
            $sCommand = trim(substr($sCommandLine, $iOpenSign + 1, $iCloseSign - $iOpenSign - 1));
            $sCommandLine = substr($sCommandLine, 0, $iSignStart) . $wgFiCommandPaths[$sCommand] . substr($sCommandLine, $iCloseSign + 1);
        }
        return str_replace(WC_FI_FILEPATH, $sFile, $sCommandLine);
    }
 
    /**
     * Prueft, ob der Dateityp der angegebenen Datei vom FileIndexer geprueft wird und liefert
     * das die Tatsache zurueck.
     *
     * @param $sFilename STRING Dateiname
     * @return BOOL Pruefergebnis
     */
    static function checkFileType($sFilename){
        global $wgFiCommandCalls;
 
        return isset($wgFiCommandCalls[strtolower(substr(strrchr($sFilename, '.'),1))]);
    }
 
    /**
     * Ausfuehrung nach Aufruf und nach Formularsubmit. Startet den Indexerstellungsprozess.
     */
    function execute($par){
        global $wgRequest, $wgOut;
 
        if(!is_null($wgRequest->getVal('wpSubmitButton')) && !is_null($wgRequest->getVal('wpFileMatches'))){
            $this->processIndex();
        }
 
        $this->showForm();
    }
 
    /**
     * Fuehrt den Prozess der Indexerstellung durch.
     */
    function processIndex() {
        global $wgRequest, $wgOut, $wgDBtype, $wgDBprefix, $wgFiCreateIndexThisTime, $wgContLang, $wgFiPrefix, $wgFiPostfix;
 
        wfProfileIn('FileIndexer::processIndex');
 
        if($wgRequest->getVal('wpFileMatches') == ""){
            $wgOut->addHTML('<h3><font color=red>' . wfMsg('fileindexer_sp_msg_response_no_params') . '</font></h3><br /><hr /><br />');
            return;
        }
 
        // Feststellen, ob Indexerstellung durchgefuehrt oder Liste nicht indexierter Dateien erzeugt werden soll
        $bCreateMode = ($wgRequest->getVal('wpSubmitButton') == wfMsg('fileindexer_sp_lable_submit_create'));
 
        $bSpecialPageNoUpdates = !is_null($wgRequest->getVal('wpNoUpdates'));
        $sNamespaceDestination = $wgContLang->getNsText($wgRequest->getVal('wpNamespace'));
        $sNamespaceFile = $wgContLang->getNsText(NS_IMAGE);
        $xWildcardSign = is_null($wgRequest->getVal('wpWildcardSign')) ? false : $wgRequest->getVal('wpWildcardSign');
        $aTitleMatches = explode("\n", str_replace("\r", "\n", str_replace("\r\n", "\n", $wgRequest->getVal('wpFileMatches'))));
        $aTmp = array();
        foreach($aTitleMatches as $sTitleMatch){
            if(trim($sTitleMatch) != ""){
                $aTmp[] = $sTitleMatch;
            }
        }
        $aTitleMatches = $aTmp;
        $dbConnection = wfGetDB( DB_SLAVE );
 
        $aTitles = array();
        foreach($aTitleMatches as $sTitleMatch){
            $sTitleMatch = trim($sTitleMatch);
            $oTitleFile = Title::makeTitleSafe(NS_IMAGE, $sTitleMatch);
 
            /*
             * Title::makeTitleSafe(NS_IMAGE, $sTitleMatch) liefert komischerweise fuer %f und %pdf ein Objekt,
             * %df hingegen nicht. Weitere Analysen notwendig
             */
            $sTitleAsKey = is_null($oTitleFile) ? $sTitleMatch : $oTitleFile->getDBKey();
            if($xWildcardSign && strpos($sTitleMatch, $xWildcardSign) === false || !$xWildcardSign){
                $oTitleDestination = Title::makeTitleSafe($wgRequest->getVal('wpNamespace'), $sTitleMatch);
                $aTitles[$sTitleAsKey] = array('file' => $oTitleFile, 'destination' => $oTitleDestination);
            }
            else{
                $aWhere = array( "page_namespace = " . NS_IMAGE, "page_title LIKE '" . str_replace($xWildcardSign, "%", $sTitleAsKey) . "'" );
                $dbRessource = $dbConnection->select( 'page', 'page_title', $aWhere, __METHOD__, array('ORDER BY' => 'page_title ASC'));
                $aTitleNames = array();
                while($aRow = $dbConnection->fetchObject($dbRessource)) {
                    $aTitleNames[] = $aRow->page_title;
                }
                $dbRessource->free();
                foreach($aTitleNames as $sTitlename){
                    $oTitleFile = Title::makeTitleSafe(NS_IMAGE, $sTitlename);
                    $sTitleAsKey = is_null($oTitleFile) ? $sTitleMatch : $oTitleFile->getDBKey();
                    $oTitleDestination = Title::makeTitleSafe($wgRequest->getVal('wpNamespace'), $sTitlename);
                    $aTitles[$sTitleAsKey] = array('file' => $oTitleFile, 'destination' => $oTitleDestination);
                }
            }
        }
 
        $aNotFoundTitles = array();
        $aNewlyIndexedTitles = array();
        $aUpdatelyIndexedTitles = array();
        $aUnsupportedTypeTitles = array();
 
        // Unbekannte Dateitypen rausfiltern
        $aTmp = array();
        foreach(array_keys($aTitles) as $sTitle){
            if(FileIndexer::checkFileType($sTitle)){
                $aTmp[$sTitle] = $aTitles[$sTitle];
            }
            else{
                $aUnsupportedTypeTitles[] = $sTitle;
            }
        }
        $aTitles = $aTmp;
 
        foreach($aTitles as $sTitle => $aTitleObjects){
            $iFileArticleId = $aTitleObjects['file']->getArticleId();
            if($iFileArticleId == 0){
                $aNotFoundTitles[] = $sTitle;
                continue;
            }
            $iDestionationArticleId = $aTitleObjects['destination']->getArticleId();
            if($iDestionationArticleId != 0){
                // Zielartikel existiert
                $oArticle = Article::newFromId($iDestionationArticleId);
                $oArticle->loadContent();
 
                // Check, ob Index vorhanden
                $aFragments = wfFiGetIndexFragments($oArticle->mContent);
                if($aFragments === false){
                    $aNewlyIndexedTitles[] = $sTitle;
                }
                else{
                    $aUpdatelyIndexedTitles[] = $sTitle;
                }
 
                if($bCreateMode && (!$bSpecialPageNoUpdates || $aFragments === false)){
                    // Index soll erzeugt/aktualisiert werden
                    $wgFiCreateIndexThisTime = true;
                    $oArticle->doEdit($oArticle->mContent, wfMsg('fileindexer_sp_index_creation_comment'));
                    $wgFiCreateIndexThisTime = false;
                }
            }
            else{
                // Zielartikel ist/waere neu
                $aNewlyIndexedTitles[] = $sTitle;
 
                if($bCreateMode){
                    // Index soll erzeugt werden
                    $oArticle = new Article($aTitleObjects['destination']);
                    $wgFiCreateIndexThisTime = true;
                    $oArticle->doEdit("", wfMsg('fileindexer_sp_index_creation_comment'));
                    $wgFiCreateIndexThisTime = false;
                }
            }
        }
 
        if($bCreateMode){
            if(!empty($aNewlyIndexedTitles)){
                sort($aNewlyIndexedTitles);
                $wgOut->addHTML("<h4>" . wfMsg('fileindexer_sp_msg_response_files_index_created') . "</h4>");
                foreach($aNewlyIndexedTitles as $sTitlename){
                    $wgOut->addWikiText("* [[:" . $sNamespaceDestination . ":" . $sTitlename . "]]<br />");
                }
            }
            if(!empty($aUpdatelyIndexedTitles)){
                sort($aUpdatelyIndexedTitles);
                $wgOut->addHTML("<h4>" . ((!$bSpecialPageNoUpdates) ? wfMsg('fileindexer_sp_msg_response_files_index_updated') : wfMsg('fileindexer_sp_msg_response_files_index_update_suppressed')) . "</h4>");
                foreach($aUpdatelyIndexedTitles as $sTitlename){
                    $wgOut->addWikiText("* [[:" . $sNamespaceDestination . ":" . $sTitlename . "]]<br />");
                }
            }
        }
        else{
            $aList = (!$bSpecialPageNoUpdates) ? array_merge($aNewlyIndexedTitles, $aUpdatelyIndexedTitles) : $aNewlyIndexedTitles;
            if(!empty($aList)){
                sort($aList);
                $wgOut->addHTML("<h4>" . wfMsg('fileindexer_sp_msg_response_files_to_be_indexed') . "</h4>
                <pre>");
                foreach($aList as $sTitlename){
                    $wgOut->addHTML($sTitlename . "\n");
                }
                $wgOut->addHTML("</pre>");
            }
        }
        if(!empty($aNotFoundTitles)){
            sort($aNotFoundTitles);
            $wgOut->addHTML("<h4>" . wfMsg('fileindexer_sp_msg_response_files_not_found') . "</h4>");
            foreach($aNotFoundTitles as $sTitlename){
                $wgOut->addWikiText("* [[:" . $sNamespaceFile . ":" . $sTitlename . "]]<br />");
            }
        }
        if(!empty($aUnsupportedTypeTitles)){
            sort($aUnsupportedTypeTitles);
            $wgOut->addHTML("<h4>" . wfMsg('fileindexer_sp_msg_response_files_with_unsupported_filetypes') . "</h4>");
            foreach($aUnsupportedTypeTitles as $sTitlename){
                $wgOut->addWikiText("* [[:" . $sNamespaceDestination . ":" . $sTitlename . "]]<br />");
            }
        }
        $wgOut->addHTML("<br /><hr /><br />");
 
        wfProfileOut('FileIndexer::processIndex');
    }
 
    /**
     * Baut das Formular der Spezialseite auf.
     */
    function showForm() {
        global $wgOut, $wgUser, $wgRequest, $wgContLang, $wgScriptPath, $wgUseAjax, $wgJsMimeType, $wgFiSpDefaultWildcardSign, $wgFiArticleNamespace, $wgFiSpNamespaceChangeable, $wgFiSpWildcardSignChangeable;
 
        $wgOut->setPagetitle(wfMsg('fileindexer_sp_title'));
        $oTitle = Title::makeTitle(NS_SPECIAL, 'FileIndexer');
        $sAction = $oTitle->escapeLocalURL('');
 
        $sDescription = wfMsg('fileindexer_sp_description');
        $sLabelNoUpdates = wfMsg('fileindexer_sp_lable_no_updates');
        $sLabelWildcardSign = wfMsg('fileindexer_sp_lable_wildcard_sign');
        $sLabelFileMatches = wfMsg('fileindexer_sp_lable_file_matches');
        $sLabelNamespace = wfMsg('fileindexer_sp_lable_destination_namespace');
        $sLabelSubmitCheck = wfMsg('fileindexer_sp_lable_submit_check');
        $sLabelSubmitCreate = wfMsg('fileindexer_sp_lable_submit_create');
 
        $xCheck = FileIndexer::checkNecessaryCommands();
        if($xCheck !== true){
            // Systemvoraussetzungen sind nicht erfuellt...
            $wgOut->addHTML('<h3><font color=red>' . wfMsg('fileindexer_msg_missing_dependencies') . '</font></h3><br />');
            $wgOut->addWikiText($xCheck . "<br /><hr /><br />");
        }
 
        $sDefaultWildcardSign = is_null($wgRequest->getVal('wpWildcardSign')) ? $wgFiSpDefaultWildcardSign : $wgRequest->getVal('wpWildcardSign');
        $bDefaultNoUpdates = is_null($wgRequest->getVal('wpSubmitButton')) ? true : !is_null($wgRequest->getVal('wpNoUpdates'));
        $sDefaultFileMatches = is_null($wgRequest->getVal('wpFileMatches')) ? "" : $wgRequest->getVal('wpFileMatches');
        $iDefaultNamespace = is_null($wgRequest->getVal('wpNamespace')) ? (($wgFiArticleNamespace >= 0) ? $wgFiArticleNamespace : NS_IMAGE) : $wgRequest->getVal('wpNamespace');
 
        $wgOut->addHTML('<h3>' . wfMsg('fileindexer_sp_formular_title') . '</h3>');
        $wgOut->addWikiText($sDescription);
        $wgOut->addHTML("<br />
    <form id=\"FileIndexer\" method=\"POST\" action=\"{$sAction}\">
    <table border=\"0\">");
 
        if($wgFiSpWildcardSignChangeable){
            $wgOut->addHTML("<tr>
                <td align=\"left\" valign=\"top\" width=\"250\" style=\"border:1px solid #777777; background-color:#E0E0E0;\">" . wfMsg('fileindexer_sp_help_wildcard_sign') . "</td>
                <td align=\"left\" valign=\"bottom\">{$sLabelWildcardSign}<br /><input tabindex=\"1\" type=\"text\" size=\"2\" maxlength=\"1\" name=\"wpWildcardSign\" value=\"{$sDefaultWildcardSign}\" /></td>
            </tr>");
        }
        else{
            $wgOut->addHTML("<input type=\"hidden\" name=\"wpWildcardSign\" value=\"{$sDefaultWildcardSign}\" />");
        }
 
        $wgOut->addHTML("<tr>
            <td align=\"left\" valign=\"top\" width=\"250\" style=\"border:1px solid #777777; background-color:#E0E0E0;\">" . wfMsg('fileindexer_sp_help_no_updates') . "</td>
            <td align=\"left\" valign=\"bottom\"><input tabindex=\"2\" type=\"checkbox\" " . (($bDefaultNoUpdates) ? "checked" : "") . " name=\"wpNoUpdates\" value=\"true\" />&nbsp;{$sLabelNoUpdates}</td>
        </tr>");
 
        if($wgFiSpNamespaceChangeable){
            $wgOut->addHTML("<tr>
                <td align=\"left\" valign=\"top\" width=\"250\" style=\"border:1px solid #777777; background-color:#E0E0E0;\">" . wfMsg('fileindexer_sp_help_destination_namespace') . "</td>
                <td align=\"left\" valign=\"bottom\">
                    {$sLabelNamespace}<br />
                    <select tabindex=\"4\" name=\"wpNamespace\">");
            $aNamespaces = $wgContLang->getFormattedNamespaces();
            foreach($aNamespaces as $iNamespaceID => $sNamespaceLabel){
                if($iNamespaceID >= 0){
                    if($iNamespaceID == 0){
                        $sNamespaceLabel = wfMsg('fileindexer_sp_main_ns');
                    }
                    $wgOut->addHTML("
                        <option value=\"" . $iNamespaceID . "\"" . (($iDefaultNamespace == $iNamespaceID) ? " selected" : "") . ">{$sNamespaceLabel}</option>");
                }
            }
            $wgOut->addHTML("
                </select>
            </td>
            </tr>");
        }
        else{
            $wgOut->addHTML("<input type=\"hidden\" name=\"wpNamespace\" value=\"{$iDefaultNamespace}\" />");
        }
 
        $wgOut->addHTML("<tr>
            <td align=\"left\" valign=\"top\" width=\"250\" style=\"border:1px solid #777777; background-color:#E0E0E0;\">" . wfMsg('fileindexer_sp_help_file_matches') . ((!$wgFiSpWildcardSignChangeable) ? "<br /><br /><b>" . wfMsg('fileindexer_sp_help_file_matches_default_wildcardsign') . "</b>" . $sDefaultWildcardSign : "") . "</td>
            <td align=\"left\" valign=\"bottom\">
                {$sLabelFileMatches}<br />
                <textarea tabindex=\"3\" rows=\"5\" cols=\"80\" name=\"wpFileMatches\">{$sDefaultFileMatches}</textarea>
            </td>
        </tr>
        <tr>
            <td align=\"left\" valign=\"top\" width=\"250\" style=\"border:1px solid #777777; background-color:#E0E0E0;\">" . wfMsg('fileindexer_sp_help_submits') . "</td>
            <td style=\"padding-top: 1em\" align=\"right\" valign=\"bottom\">
                <input tabindex=\"5\" type=\"submit\" name=\"wpSubmitButton\" value=\"{$sLabelSubmitCheck}\" />
                <input tabindex=\"6\" type=\"submit\" name=\"wpSubmitButton\" value=\"{$sLabelSubmitCreate}\" />
            </td>
        </tr>
    </table>
    </form>\n");
 
        if($this->insertEditTools == true && $wgUseAjax == true && function_exists('charInsert')){
            $currentPath = str_replace('\\', '/', dirname(__FILE__));
            $curServerPath = substr($currentPath, stripos($currentPath, $wgScriptPath . '/'));
            $wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$curServerPath}/edittools.js\"></script>\n");
 
            $filename = dirname(__FILE__) . '/EditTools.htm';
            $handle = fopen($filename, 'rb');
            $contents = fread($handle, filesize($filename));
            fclose($handle);
 
            $wgOut->addHtml('<div class="mw-editTools">');
            $wgOut->addWikiText($contents);
            $wgOut->addHtml('</div>');
        }
    }
}
