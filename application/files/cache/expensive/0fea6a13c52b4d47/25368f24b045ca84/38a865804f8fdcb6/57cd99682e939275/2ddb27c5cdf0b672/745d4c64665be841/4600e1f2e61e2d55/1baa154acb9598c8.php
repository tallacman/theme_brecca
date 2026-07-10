<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\calendar\calendarevent$summary_templates][1]/ */
/* Type: array */
/* Expiration: 2026-07-15T01:48:27-07:00 */



$loaded = true;
$expiration = 1784105307;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\OneToMany::__set_state(array(
     'mappedBy' => 'event',
     'targetEntity' => 'Concrete\\Core\\Entity\\Calendar\\Summary\\CalendarEventTemplate',
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
$data['createdOn'] = 1783713337;
