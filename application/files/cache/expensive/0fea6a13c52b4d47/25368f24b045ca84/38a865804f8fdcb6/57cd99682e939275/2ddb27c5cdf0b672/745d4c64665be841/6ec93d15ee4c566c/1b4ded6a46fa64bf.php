<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\board\instancelog$instance][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T20:58:08-07:00 */



$loaded = true;
$expiration = 1784087888;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToOne::__set_state(array(
     'targetEntity' => 'Instance',
     'mappedBy' => NULL,
     'inversedBy' => 'log',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
  )),
  1 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'boardInstanceID',
     'referencedColumnName' => 'boardInstanceID',
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
