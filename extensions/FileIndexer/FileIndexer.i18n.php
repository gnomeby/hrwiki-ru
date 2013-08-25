<?php
/**
 * TITEL:                Extension: FileIndexer / FileIndexer.i18n.php
 * ERSTELLDATUM:        26.06.2008
 * AUTHOR:                Ramon Dohle aka 'raZe'
 * ORGANISATION:        GRASS-MERKUR GmbH & Co. KG & raZe.it
 * VERSION:                0.3.4.00    01.10.2010
 * REVISION:
 *         26.06.2008    0.1.0.00    raZe        *    Initiale Version
 *         29.06.2009    0.1.1.00    raZe        *    Texte ueberarbeitet
 *         27.08.2010    0.2.0.00    raZe        *    Texte ueberarbeitet
 *         28.08.2010    0.2.1.00    raZe        *    Texte erweitert
 *         29.08.2010    0.2.2.00    raZe        *    Rechtschreibung korrigiert
 *         30.08.2010    0.3.0.00    raZe        *    Texte angepasst
 *         25.09.2010    0.3.1.00    raZe        *    Text zur Erweiterung der Hilfe zu Filematches hinzugefuegt
 *                                             *    Rechtschreibung korrigiert
 *         26.09.2010    0.3.2.00    raZe        *    Neue Texte hinzugefuegt
 *         27.09.2010    0.3.3.00    raZe        *    Neue Texte hinzugefuegt
 *         01.10.2010    0.3.4.00    raZe        *    Rechtschreibung korrigiert
 *
 *
 * BESCHREIBUNG:
 *         Diese Erweiterung basiert auf der Wiki-Erweiterung 'FileIndexer' vom Stand 15.05.2008.
 *         Wie sein Original soll sie Dateien Indexieren um auch den Inhalt dieser Dateien durch Suchmaschienen zu erfassen.
 *
 *         Hier wird speziell die Funktion zur multilingualen Nutzbarkeit zur Verfuegung gestellt.
 */
 
$messages = array();

$messages['en'] = array(
    'fileindexer' => 'FileIndexer: Index Uploaded Files',
    'fileindexer_sp_title' => 'FileIndexer: Index Uploaded Files',
    'fileindexer_sp_formular_title' => 'Specify the parameters for the index creation process',
    'fileindexer_sp_description' => "On this form you may create/update indexes of uploaded files.",
    'fileindexer_sp_lable_destination_namespace' => 'Destiantion Namespace',
    'fileindexer_sp_lable_no_updates' => 'No Updates',
    'fileindexer_sp_lable_wildcard_sign' => 'Wildcard Sign',
    'fileindexer_sp_lable_file_matches' => 'Files',
    'fileindexer_sp_lable_submit_check' => 'Check',
    'fileindexer_sp_lable_submit_create' => 'Create',
    'fileindexer_sp_help_wildcard_sign' => 'Specify a sign to be used as wildcards in the \'Files\' inputfield.',
    'fileindexer_sp_help_no_updates' => 'If checked no indexes will be updated. Only new indexes in the articles of the destination namespace will be created.',
    'fileindexer_sp_help_file_matches' => 'Each line may specify a full filename or may be used to filter many filenames by using the wildcard sign.',
    'fileindexer_sp_help_file_matches_default_wildcardsign' => 'The wildcard sign is: ',
    'fileindexer_sp_help_destination_namespace' => 'Specify the destination namespace for the articles in which to create/update the files indexes.',
    'fileindexer_sp_help_submits' => 'By using the button \'Create\' the indexing process will be initiated while using the button \'Check\' will only result in a list of those files titles that would have been indexed by this request.',
    'fileindexer_sp_msg_response_no_params' => 'You have to spezify at least one file!',
    'fileindexer_sp_msg_response_files_index_created' => 'For the following list of articles the index creation process was started:',
    'fileindexer_sp_msg_response_files_index_updated' => 'For the following list of articles the index update process was started:',
    'fileindexer_sp_msg_response_files_index_update_suppressed' => 'For the following list of articles the index update process was suppressed:',
    'fileindexer_sp_msg_response_files_not_found' => 'The following list of files could not be found:',
    'fileindexer_sp_msg_response_files_with_unsupported_filetypes' => 'The following list of files was of unsupported types:',
    'fileindexer_sp_msg_response_files_to_be_indexed' => 'By this request the following list of files would have been indexed:',
    'fileindexer_sp_msg_response_systemcheck_command_missing' => 'Systemcommand not found',
    'fileindexer_sp_msg_response_systemcheck_command_wrong_path' => 'Systemcommandpath incorrect. \'which\' returned: ',
    'fileindexer_form_label_create_index' => 'Create/update index',
    'fileindexer_sp_index_creation_comment' => "FileIndexer: Indexcreation by special page",
    'fileindexer_upl_index_creation_comment' => "FileIndexer: Indexcreation by upload",
    'fileindexer_index_creation_complete_comment' => "-> Creation complete",
    'fileindexer_index_creation_failed_comment' => "-> Creation failed: ",
    'fileindexer_index_creation_failed_comment_missing_systemcommand' => 'Systemcheck showed missing or incorrect configurated, dependent commands!',
    'fileindexer_index_creation_failed_comment_unknown_filetype' => 'Unsupported filetype!',
    'fileindexer_index_creation_failed_comment_unknown_reason' => 'Unkonwn reason',
    'fileindexer_index_update_complete_comment' => "-> Update complete",
    'fileindexer_msg_missing_dependencies' => 'A systemcheck returned that some dependent applications for indexcreation are missing!',
    'fileindexer_sp_main_ns' => 'Main'
);

$messages['de'] = array(
    'fileindexer' => 'FileIndexer: Dateien Indexieren',
    'fileindexer_sp_title' => 'FileIndexer: Dateien Indexieren',
    'fileindexer_sp_formular_title' => 'Festlegung der Parameter f&uuml;r den Indexerstellungsprozess',
    'fileindexer_sp_description' => "In diesem Formular k&ouml;nnen Dateien nachtr&auml;glich indexiert werden.",
    'fileindexer_sp_lable_destination_namespace' => 'Zielnamensraum',
    'fileindexer_sp_lable_no_updates' => 'Keine Updates',
    'fileindexer_sp_lable_wildcard_sign' => 'Wildcardzeichen',
    'fileindexer_sp_lable_file_matches' => 'Dateien',
    'fileindexer_sp_lable_submit_check' => 'Pr&uuml;fen',
    'fileindexer_sp_lable_submit_create' => 'Erstellen',
    'fileindexer_sp_help_wildcard_sign' => 'Legen Sie ein Zeichen fest, welches im Eingabefeld \'Dateien\' als Wildcard interpretiert werden soll.',
    'fileindexer_sp_help_no_updates' => 'Falls aktiviert, werden keine Indexaktualisierungen ausgef&uuml;hrt. In diesem Fall werden in den Artikeln des Zielnamensraumes ausschlie&szlig;lich neu zu erzeugende Indexe erstellt.',
    'fileindexer_sp_help_file_matches' => 'Je Zeile kann entweder ein voller Dateiname oder ein Namensteil, inklusive Wildcards zur Bestimmung mehrerer Dateien, angegeben werden.',
    'fileindexer_sp_help_file_matches_default_wildcardsign' => 'Eingestelltes Wildcard Zeichen: ',
    'fileindexer_sp_help_destination_namespace' => 'Legen Sie einen Zielnamensraum der Artikel fest, in denen die Dateiindexe erzeugt/aktualisiert werden sollen.',
    'fileindexer_sp_help_submits' => 'Der Button \'Erstellen\' l&ouml;st die Indexierung aus, der Button \'Pr&uuml;fen\' liefert hingegen nur eine Liste der Dateititel, die mit dieser Abfrage indexiert worden w&auml;ren.',
    'fileindexer_sp_msg_response_no_params' => 'Sie m&uuml;ssen mindestens einen Dateinamen angeben!',
    'fileindexer_sp_msg_response_files_index_created' => 'F&uuml;r folgende Artikel wurde eine Indexerstellung eingeleitet:',
    'fileindexer_sp_msg_response_files_index_updated' => 'F&uuml;r folgende Artikel wurde eine Indexaktualisierung eingeleitet:',
    'fileindexer_sp_msg_response_files_index_update_suppressed' => 'F&uuml;r folgende Artikel wurde eine Indexaktualisierung unterdr&uuml;ckt:',
    'fileindexer_sp_msg_response_files_not_found' => 'Folgende Dateien konnten nicht gefunden werden:',
    'fileindexer_sp_msg_response_files_with_unsupported_filetypes' => 'Die Typen folgender Dateien sind nicht unterst&uuml;tzt:',
    'fileindexer_sp_msg_response_files_to_be_indexed' => 'Mit den angegebenen Parametern w&auml;ren folgende Dateien indexiert worden:',
    'fileindexer_sp_msg_response_systemcheck_command_missing' => 'Systemkommando nicht gefunden.',
    'fileindexer_sp_msg_response_systemcheck_command_wrong_path' => 'Aufrufpfad des Systemkommandos nicht korrekt. \'which\' lieferte: ',
    'fileindexer_form_label_create_index' => 'Index erstellen/aktualisieren',
    'fileindexer_sp_index_creation_comment' => "FileIndexer: Indexerstellung per Spezialseite",
    'fileindexer_upl_index_creation_comment' => "FileIndexer: Indexerstellung per Dateiupload",
    'fileindexer_index_creation_complete_comment' => "-> Erstellung abgeschlossen",
    'fileindexer_index_creation_failed_comment' => "-> Erstellung fehlgeschlagen: ",
    'fileindexer_index_creation_failed_comment_missing_systemcommand' => 'Systempr&uuml;fung ergab fehlende Systemkommandos!',
    'fileindexer_index_creation_failed_comment_unknown_filetype' => 'Nicht unterst&uuml;tzte Dateiendung!',
    'fileindexer_index_creation_failed_comment_unknown_reason' => 'Ursache unbekannt',
    'fileindexer_index_update_complete_comment' => "-> Update abgeschlossen",
    'fileindexer_msg_missing_dependencies' => 'Ein Systemcheck ergab, dass einige f&uuml;r die Indexerstellung abh&auml;ngige Applikationen nicht verf&uuml;gbar oder inkorrekt konfiguriert sind!',
    'fileindexer_sp_main_ns' => 'Hauptnamensraum'
);


