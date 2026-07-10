<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendar$permission_assignments][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T20:20:30-07:00 */



$loaded = true;
$expiration = 1784085630;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'calendar',
     'targetEntity' => 'CalendarPermissionAssignment',
     'cascade' => 
    array (
      0 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
