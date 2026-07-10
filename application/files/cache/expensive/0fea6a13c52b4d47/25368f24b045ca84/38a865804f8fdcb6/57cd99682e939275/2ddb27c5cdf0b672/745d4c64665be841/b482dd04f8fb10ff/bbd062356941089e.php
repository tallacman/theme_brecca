<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\board\item$categories][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T07:45:38-07:00 */



$loaded = true;
$expiration = 1784126738;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'item',
     'targetEntity' => 'ItemCategory',
     'cascade' => 
    array (
      0 => 'persist',
      1 => 'remove',
    ),
     'fetch' => 'EXTRA_LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
