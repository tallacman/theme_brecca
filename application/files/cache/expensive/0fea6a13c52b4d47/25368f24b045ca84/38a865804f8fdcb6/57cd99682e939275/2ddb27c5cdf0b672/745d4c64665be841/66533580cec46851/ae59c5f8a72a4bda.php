<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\automation\taskset$tasks][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T02:57:16-07:00 */



$loaded = true;
$expiration = 1784109436;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'set',
     'targetEntity' => '\\Concrete\\Core\\Entity\\Automation\\TaskSetTask',
     'cascade' => 
    array (
      0 => 'all',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 => 
  \Doctrine\ORM\Mapping\OrderBy::__set_state(array(
     'value' => 
    array (
      'displayOrder' => 'ASC',
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713338;
