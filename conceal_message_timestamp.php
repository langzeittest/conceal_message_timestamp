<?php

if (!defined('PHORUM')) return;

//
// Conceals message timestamp for messages older than 24 hours.
//
function mod_conceal_message_timestamp($rows) {
    global $PHORUM;

    if (!is_array($rows)) return $rows;

    include_once('./include/format_functions.php');

    $limit = time() - (24 * 60 * 60);

    // Loop all messages.
    foreach ($rows as $key => $row) {
        if (isset($row['raw_datestamp'])) {
            if ($row['raw_datestamp'] < $limit) {
                // We use date format mask from language file.
                if (isset($rows[$key]['short_datestamp'])) {
                    $rows[$key]['short_datestamp'] = phorum_date($PHORUM['short_date'], $row['raw_datestamp']);
                }
                if (isset($rows[$key]['datestamp'])) {
                    if (phorum_page=='read') {
                        // Read page uses long date format
                        $rows[$key]['datestamp'] = phorum_date($PHORUM['long_date'], $row['raw_datestamp']);
                    } elseif (phorum_page=='list') {
                        // List page uses short date format
                        $rows[$key]['datestamp'] = phorum_date($PHORUM['short_date'], $row['raw_datestamp']);
                    }
                }
            }
        }
        if (phorum_page=='list' && isset($row['raw_lastpost']) && $row['raw_lastpost'] < $limit) {
            // List page uses short date format
            $rows[$key]['lastpost'] = phorum_date($PHORUM['short_date'], $row['raw_lastpost']);
        }
    }

    return $rows;
}

?>