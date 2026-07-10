<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendareventversionoccurrence$version][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T19:16:18-07:00 */



$loaded = true;
$expiration = 1784081778;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'CalendarEventVersion',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'occurrences',
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
