<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\board\instance$items][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T10:29:40-07:00 */



$loaded = true;
$expiration = 1784136580;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'instance',
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
$data['createdOn'] = 1783716392;
