<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendarrelatedevent$event][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T22:00:57-07:00 */



$loaded = true;
$expiration = 1784091657;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'CalendarEvent',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => NULL,
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
