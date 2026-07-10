<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendareventversionrepetition$version][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T08:06:39-07:00 */



$loaded = true;
$expiration = 1784127999;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'CalendarEventVersion',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'repetitions',
  )),
  1 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'eventVersionID',
     'referencedColumnName' => 'eventVersionID',
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
