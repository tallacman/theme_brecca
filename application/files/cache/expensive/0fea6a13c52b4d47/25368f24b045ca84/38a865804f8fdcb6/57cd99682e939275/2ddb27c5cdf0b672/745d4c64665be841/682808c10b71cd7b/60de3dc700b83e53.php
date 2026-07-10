<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendarpermissionassignment$calendar][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T22:19:15-07:00 */



$loaded = true;
$expiration = 1784092755;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\Id::__set_state(array(
  )),
  1 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Calendar',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'permission_assignments',
  )),
  2 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'caID',
     'referencedColumnName' => 'caID',
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
