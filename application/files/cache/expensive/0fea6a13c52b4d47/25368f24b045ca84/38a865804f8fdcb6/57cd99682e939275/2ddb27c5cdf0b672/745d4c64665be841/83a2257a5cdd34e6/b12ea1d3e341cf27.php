<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\oauth\accesstoken$client][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T12:02:11-07:00 */



$loaded = true;
$expiration = 1784142131;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Client',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => NULL,
  )),
  1 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'client',
     'referencedColumnName' => 'identifier',
     'unique' => false,
     'nullable' => true,
     'onDelete' => 'CASCADE',
     'columnDefinition' => NULL,
     'fieldName' => NULL,
     'options' => 
    array (
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
