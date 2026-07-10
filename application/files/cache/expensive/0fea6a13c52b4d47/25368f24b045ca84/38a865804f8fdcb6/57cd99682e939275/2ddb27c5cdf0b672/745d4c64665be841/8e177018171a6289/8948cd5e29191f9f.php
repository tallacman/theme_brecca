<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\announcement\announcementuserview$announcement][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T01:30:17-07:00 */



$loaded = true;
$expiration = 1784104217;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\Id::__set_state(array(
  )),
  1 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Announcement',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713338;
