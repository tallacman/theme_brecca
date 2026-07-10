<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\express\fieldset$controls][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T07:23:10-07:00 */



$loaded = true;
$expiration = 1784125390;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'field_set',
     'targetEntity' => '\\Concrete\\Core\\Entity\\Express\\Control\\Control',
     'cascade' => 
    array (
      0 => 'persist',
      1 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 => 
  \Doctrine\ORM\Mapping\OrderBy::__set_state(array(
     'value' => 
    array (
      'position' => 'ASC',
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713337;
