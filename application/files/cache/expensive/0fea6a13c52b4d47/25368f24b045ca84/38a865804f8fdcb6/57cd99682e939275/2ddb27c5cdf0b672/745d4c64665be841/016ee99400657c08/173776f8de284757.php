<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\entity$entries][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T02:25:52-07:00 */



$loaded = true;
$expiration = 1784107552;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'entity',
     'targetEntity' => 'Entry',
     'cascade' => 
    array (
      0 => 'persist',
      1 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783719194;
