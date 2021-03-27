<?php
namespace Joker\Animal;

use Cowsayphp\AbstractAnimal;

class Dog extends AbstractAnimal
{
    protected $character = <<<DOC

{{bubble}}
  \   _
   \ /  \
    /|oo \
   (_|  /_)
     `@/  \
     |     \     _
      \||   \   //
      |||\ /  \//
    _//|| _\   /
   (_/(_|(____/
   
DOC;
}