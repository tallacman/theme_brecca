<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\attribute\value\sitetypevalue$skeleton][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T03:56:21-07:00 */



$loaded = true;
$expiration = 1784112981;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => '\\Concrete\\Core\\Entity\\Site\\Skeleton',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => NULL,
  )),
  1 => 
  \Doctrine\ORM\Mapping\JoinColumn::__set_state(array(
     'name' => 'siteSkeletonID',
     'referencedColumnName' => 'siteSkeletonID',
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
