<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\user\groupsignup$notifications][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T21:00:02-07:00 */



$loaded = true;
$expiration = 1784088002;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'signup',
     'targetEntity' => '\\Concrete\\Core\\Entity\\Notification\\GroupSignupNotification',
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
