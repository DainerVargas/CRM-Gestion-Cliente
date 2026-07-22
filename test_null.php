<?php
class A { public $service = null; }
$a = new A();
echo $a->service->name ?? 'Fallback';
