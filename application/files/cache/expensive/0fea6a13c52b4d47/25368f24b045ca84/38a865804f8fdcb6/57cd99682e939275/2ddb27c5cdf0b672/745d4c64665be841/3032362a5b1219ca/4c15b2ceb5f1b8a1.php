<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\user\grouprolechange$notifications][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T11:43:41-07:00 */



$loaded = true;
$expiration = 1784141021;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'groupRoleChange',
     'targetEntity' => '\\Concrete\\Core\\Entity\\Notification\\GroupRoleChangeNotification',
     'cascade' => 
    array (
      0 => 'remove',
    ),
     'fetch' => 'LAZY',
     'orphanRemoval' => false,
     'indexBy' => NULL,
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713338;
