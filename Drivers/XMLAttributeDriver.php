<?php
/**
 * Xml Parsing Driver
 *
 * formats config varibles and reads values from that format
 * @author Carlos Ferrera
 * @version 1.0
 * @package configuration
 */

namespace Drivers;

class XMLAttribute {
    
    var $name;
    var $value = null;

    function &XMLAttribute($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }

    function get() {
        return $this->value;
    }

    function toString() {
        return "[{$this->name} = {$this->value}]";
    }
}

class XMLNode {

    var $name;
    var $attributes = array();
    var $content;
    var $children;
    var $parent = null;

    function &XMLNode($name, $attributes) {
        $this->name = $name;
        foreach ($attributes as $name=>$value) 
        {
            $this->attributes[$name] = new XMLAttribute($name, $value);
        }
        $this->children = array();
    }
    function setContent(&$data) {
        $this->content = $data;
    }

    function &addChild(&$child) {
        $child->setParent($this);
        $this->children[] =& $child;
        return $this->children[count($this->children) - 1];    
    }

    function setParent(&$parent) {
        $this->parent =& $parent;
    }

    function &getName() {
        return $this->name;
    }

    function &getParent() {
        return $this->parent;
    }

    function &getAttribute($attname) {
        if (array_keys_exists($attname, $this->attributes)) {
            return $this->attributes[$attname];
        }
    }

    function &getAttributes() {
        return $this->attributes;
    }
    
    function &getChildren() {
        return $this->children;
    }

    function &getContent() {
        return $this->content;
    }

    function toString($level=1) {
        $sep = '  ';
        $out = str_repeat($sep, $level - 1).
                "tag :: ".$this->getName()."\n";
        $out .= str_repeat($sep, $level)."attributes ::\n";
        foreach ($this->getAttributes() as $attr) {
            $out .= str_repeat($sep, $level + 1).
                    $attr->toString()."\n";
        }
        $out .= str_repeat($sep, $level).
                "content :: ".$this->getContent()."\n";
        foreach ($this->getChildren() as $child) {
            $out .= $child->toString($level + 1);
        }
        return $out;
    }
}
?>