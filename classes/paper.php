<?php
namespace App;

class Paper {
    public $doi;
    public $reference;
    public $linkscholar;
    public $linkonepetro;
    public $linkgithub;

    public function __construct($args =[]) {
        $this->doi = $args['doi'] ?? '';
        $this->reference = $args['reference'] ?? '';
        $this->linkscholar = $args['linkscholar'] ?? '';
        $this->linkonepetro = $args['linkonepetro'] ?? '';
        $this->linkgithub = $args['linkgithub'] ?? '';
    }
}