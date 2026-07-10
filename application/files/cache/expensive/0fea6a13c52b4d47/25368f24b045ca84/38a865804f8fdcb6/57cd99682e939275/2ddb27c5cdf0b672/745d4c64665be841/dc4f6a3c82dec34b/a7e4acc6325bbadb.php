<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\automation\tasksettask$task][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T06:30:09-07:00 */



$loaded = true;
$expiration = 1784122209;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\Id::__set_state(array(
  )),
  1 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => '\\Concrete\\Core\\Entity\\Automation\\Task',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'set_tasks',
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713338;
