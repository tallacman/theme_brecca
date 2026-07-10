<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\entry\association$selectedentries][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T23:27:28-07:00 */



$loaded = true;
$expiration = 1784096848;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'association',
     'targetEntity' => '\\Concrete\\Core\\Entity\\Express\\Entry\\AssociationEntry',
     'cascade' => 
    array (
      0 => 'all',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
