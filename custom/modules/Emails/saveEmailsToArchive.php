<?php

class archiveEmails
{
  function saveEmailsToArchive($bean, $event, $arguments)
  {
    if ($bean->type === 'inbound' && (int)$bean->is_archived_c !== 1) {
      $mailbox = $this->getMailBox($bean->mailbox_id);

      if ($mailbox && $this->isArchivedEmail($bean->mailbox_id) == 1) {
        $email_addresses = $this->extractEmailAddresses(
          $bean->description_html,
          $bean->from_addr
        );

        foreach ($email_addresses as $address) {
          if ($this->emailExistsInSystem($address)) {
            if ($contact = $this->contactExists($address)) {
              $contact->load_relationship('emails');
              $contact->emails->add($bean->id);
            } elseif ($lead = $this->leadExists($address)) {
              $lead->load_relationship('emails');
              $lead->emails->add($bean->id);
            }
          }
        }

        $bean->is_archived_c = 1;
        $bean->save();
      }
    }
  }

  function getMailBox($mailbox_id)
  {
    $mailbox = BeanFactory::newBean('InboundEmail');
    $mailbox->retrieve($mailbox_id);
    return $mailbox->id ? $mailbox : false;
  }

  function extractEmailAddresses($string, $from)
  {
    $pattern = '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}\b/i';
    preg_match_all($pattern, $string, $matches);
    $matches = $matches[0];
    array_push($matches, $from);
    return array_unique($matches);
  }

  function contactExists($address)
  {
    $contact = BeanFactory::newBean('Contacts');
    return $contact->retrieve_by_string_fields(array('email1' => $address));
  }

  function leadExists($address)
  {
    $lead = BeanFactory::newBean('Leads');
    return $lead->retrieve_by_string_fields(array('email1' => $address));
  }

  function emailExistsInSystem($email)
  {
    $db = DBManagerFactory::getInstance();
    $query = "SELECT id FROM email_addresses WHERE email_address = '$email' AND deleted = 0";
    $result = $db->query($query);
    return $db->fetchByAssoc($result) ? true : false;
  }

  function isArchivedEmail($inboundId)
  {
    if (empty($inboundId)) {
      return false;
    }

    global $db;

    $quotedId = $db->quote($inboundId);
    $sql = sprintf(
      "SELECT archive_email
            FROM gen_configs
            WHERE deleted = 0
              AND sync_key = '%s'
            LIMIT 1",
      $quotedId
    );

    $res = $db->query($sql);

    if (!$res) {
      return false;
    }

    $row = $db->fetchByAssoc($res);

    if (!$row) {
      return false;
    }

    return !empty($row['archive_email']);
  }
}
