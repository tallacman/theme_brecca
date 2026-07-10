<?php 
/* Cachekey: cache/stash_default/doctrine/[concrete\core\entity\page\pagepath][1]/ */
/* Type: array */
/* Expiration: 2026-07-14T20:29:47-07:00 */



$loaded = true;
$expiration = 1784086187;

$data = array();

/* Child Type: array */
$data['return'] = array (
  0 => 
  \Doctrine\ORM\Mapping\Entity::__set_state(array(
     'repositoryClass' => NULL,
     'readOnly' => false,
  )),
  1 => 
  \Doctrine\ORM\Mapping\Table::__set_state(array(
     'name' => 'PagePaths',
     'schema' => NULL,
     'indexes' => 
    array (
      0 => 
      \Doctrine\ORM\Mapping\Index::__set_state(array(
         'name' => 'ppIsCanonical',
         'columns' => 
        array (
          0 => 'ppIsCanonical',
        ),
         'fields' => NULL,
         'flags' => NULL,
         'options' => NULL,
      )),
      1 => 
      \Doctrine\ORM\Mapping\Index::__set_state(array(
         'name' => 'cID',
         'columns' => 
        array (
          0 => 'cID',
        ),
         'fields' => NULL,
         'flags' => NULL,
         'options' => NULL,
      )),
      2 => 
      \Doctrine\ORM\Mapping\Index::__set_state(array(
         'name' => 'cPath',
         'columns' => 
        array (
          0 => 'cPath',
        ),
         'fields' => NULL,
         'flags' => NULL,
         'options' => 
        array (
          'lengths' => 
          array (
            0 => 255,
          ),
        ),
      )),
    ),
     'uniqueConstraints' => NULL,
     'options' => 
    array (
    ),
  )),
);

/* Child Type: integer */
$data['createdOn'] = 1783713374;
