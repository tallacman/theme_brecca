<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\board\instancelog$entries][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T23:29:49-07:00 */



$loaded = true;
$expiration = 1784096989;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'log',
     'targetEntity' => 'InstanceLogEntry',
     'cascade' => 
    array (
      0 => 'persist',
      1 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 => 
  \Doctrine\ORM\Mapping\OrderBy::__set_state(array(
     'value' => 
    array (
      'timestamp' => 'ASC',
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
