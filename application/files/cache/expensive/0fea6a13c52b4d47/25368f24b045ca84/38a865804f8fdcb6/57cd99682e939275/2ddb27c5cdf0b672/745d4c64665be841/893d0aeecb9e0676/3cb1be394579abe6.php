<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\site\skeleton$locales][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T03:43:03-07:00 */



$loaded = true;
$expiration = 1784112183;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'skeleton',
     'targetEntity' => 'SkeletonLocale',
     'cascade' => 
    array (
      0 => 'all',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
  1 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'siteSkeletonLocaleID',
     'referencedColumnName' => 'siteSkeletonLocaleID',
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
$data['createdOn'] = 1783713338;
