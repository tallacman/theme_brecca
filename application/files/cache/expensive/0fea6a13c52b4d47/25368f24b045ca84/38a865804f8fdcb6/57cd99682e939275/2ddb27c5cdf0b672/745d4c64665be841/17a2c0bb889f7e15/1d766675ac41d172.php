<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\user\groupsignuprequest$notifications][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T08:28:20-07:00 */



$loaded = true;
$expiration = 1784129300;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'signupRequest',
     'targetEntity' => '\\Concrete\\Core\\Entity\\Notification\\GroupSignupRequestNotification',
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
