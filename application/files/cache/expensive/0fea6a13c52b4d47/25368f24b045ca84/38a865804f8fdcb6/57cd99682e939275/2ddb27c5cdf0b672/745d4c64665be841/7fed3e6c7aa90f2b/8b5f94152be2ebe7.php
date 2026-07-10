<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendar$events][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T10:59:55-07:00 */



$loaded = true;
$expiration = 1784138395;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'calendar',
     'targetEntity' => 'CalendarEvent',
     'cascade' => 
    array (
      0 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'eventID',
     'referencedColumnName' => 'eventID',
     'unique' => false,
     'nullable' => true,
     'onDelete' => NULL,
     'columnDefinition' => NULL,
     'fieldName' => NULL,
     'options' => 
    array (
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
