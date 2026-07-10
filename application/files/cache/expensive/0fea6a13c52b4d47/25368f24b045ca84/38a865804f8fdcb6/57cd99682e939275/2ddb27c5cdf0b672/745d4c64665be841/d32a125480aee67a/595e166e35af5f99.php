<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\board\instanceitembatch$items][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T22:24:06-07:00 */



$loaded = true;
$expiration = 1784093046;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'batch',
     'targetEntity' => 'InstanceItem',
     'cascade' => 
    array (
      0 => 'remove',
    ),
     'fetch' => 'EXTRA_LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
