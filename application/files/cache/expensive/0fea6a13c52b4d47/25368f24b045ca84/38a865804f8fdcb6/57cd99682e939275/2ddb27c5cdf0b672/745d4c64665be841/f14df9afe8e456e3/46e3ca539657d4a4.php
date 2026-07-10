<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\summary\template$fields][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T11:36:51-07:00 */



$loaded = true;
$expiration = 1784140611;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'template',
     'targetEntity' => 'TemplateField',
     'cascade' => 
    array (
      0 => 'persist',
      1 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713729;
