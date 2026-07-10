<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\oauth\scope$clients][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T01:37:58-07:00 */



$loaded = true;
$expiration = 1784104678;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToMany::__set_state(array(
     'targetEntity' => 'Client',
     'mappedBy' => 'scopes',
     'inversedBy' => NULL,
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
