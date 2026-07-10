<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\board\instancelogentry$log][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T23:59:50-07:00 */



$loaded = true;
$expiration = 1784098790;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'InstanceLog',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'entries',
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
