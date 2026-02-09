<?php

    $hook_array['before_save'][] = array(
        1,
        'saves an email to archive',
        'custom/modules/Emails/saveEmailsToArchive.php',
        'archiveEmails',
        'saveEmailsToArchive'
    );
?>