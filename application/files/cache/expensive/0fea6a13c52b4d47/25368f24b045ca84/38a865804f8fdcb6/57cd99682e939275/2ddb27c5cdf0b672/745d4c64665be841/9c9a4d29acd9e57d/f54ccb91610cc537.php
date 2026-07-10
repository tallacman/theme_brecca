<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\entry$entity][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T01:06:14-07:00 */



$loaded = true;
$expiration = 1784102774;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Entity',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'entries',
  )),
  1 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'exEntryEntityID',
     'referencedColumnName' => 'id',
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
