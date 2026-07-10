<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\entity$forms][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T13:13:08-07:00 */



$loaded = true;
$expiration = 1784146388;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'entity',
     'targetEntity' => 'Form',
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
