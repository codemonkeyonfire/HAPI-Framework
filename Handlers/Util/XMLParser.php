<?php
// a more general XML parser
namespace Handlers\Util;

class XMLParser {

    var $parser;
    var $root;
    var $last = null;

    function XMLParser() {
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'start', 'end');
        xml_set_character_data_handler($this->parser, 'cdata');
    }

    function parse($xml) {
        xml_parse($this->parser, $xml);
    }

    function start($parser, $name, $attrs) {
        $tag = trim($name);
        $node = new \Drivers\XMLNode($tag, $attrs);
        if (is_null($this->last)) {
            $this->root =& $node;
        } else {
            $this->last->addChild($node);
        }
        $this->last =& $node;
        
    }

    function end($parser, $name) {
        $this->last =& $this->last->parent;
    }

    function cdata($parser, $data) {
        if (!is_null($this->last))
            $this->last->setContent(trim($data));
    }

    function &getRoot() {
        return $this->root;
    }
}