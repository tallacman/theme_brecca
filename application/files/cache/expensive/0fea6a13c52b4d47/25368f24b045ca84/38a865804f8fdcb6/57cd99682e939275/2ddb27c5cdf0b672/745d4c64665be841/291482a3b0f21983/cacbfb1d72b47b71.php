<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\health\report\finding$result][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T10:18:20-07:00 */



$loaded = true;
$expiration = 1784135900;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\ManyToOne::__set_state(array(
     'targetEntity' => 'Result',
     'cascade' => NULL,
     'fetch' => 'LAZY',
     'inversedBy' => 'findings',
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713338;
