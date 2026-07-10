<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\automation\tasksettask$set][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T00:47:14-07:00 */



$loaded = true;
$expiration = 1784101634;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\Id::__set_state(array(
  )),
  1 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => '\\Concrete\\Core\\Entity\\Automation\\TaskSet',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'tasks',
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713338;
