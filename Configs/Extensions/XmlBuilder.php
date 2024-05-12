<?php

class XmlBuilder
{
    private XmlElement $rootElement;

    public function __construct(string $rootElementName)
    {
        $this->rootElement = new XmlElement($rootElementName);
    }

    public function appendElement(XmlElement $element): void
    {
        $this->rootElement->addChild($element);
    }

    public function getContent(): string
    {
        return $this->rootElement->getStringValue();
    }
}

class XmlElement{
    private string $stringValue;
    private string $elementName;
    private string $content;
    private array $attributes;
    private array $children;
    private int $nestingLevel;

    public function __construct(string $elementName, string $content = '', array $attributes = null, array $children = null, int $nestingLevel = 0)
    {
        $this->elementName = $elementName;
        $this->content = htmlspecialchars($content, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $this->attributes = $attributes ?? [];
        $this->children = $children ?? [];
        $this->nestingLevel = $nestingLevel;

        $this->setStringValue();
    }

    public function setNestingLevel(int $value): void 
    {
        $this->nestingLevel = $value;
        $this->setStringValue();
    }

    public function addAttribute(string $attribute, string $value): XmlElement
    {
        $this->attributes[$attribute] = $value;
        $this->setStringValue();
        return $this;
    }

    public function addChild(XmlElement $child): XmlElement {
        $child->setNestingLevel($this->nestingLevel + 1);
        $this->children[] = $child;
        $this->setStringValue();
        return $this;
    }

    public function getStringValue(): string {
        return $this->stringValue;
    }

    private function setStringValue(): void
    {
        $this->startTag();
        
        if($this->content != "")
            $this->addContentToStringValue();

        if($this->children != [])
            $this->addChildrenToStringValue();

        $this->endTag();
    }

    private function startTag(): XmlElement
    {
        $indentation = str_repeat("   ", $this->nestingLevel);
        $this->stringValue = $indentation . "<" . $this->elementName;
        $this->addAttributesToStringValueTag();
        $this->stringValue .= ">\n";
        return $this;
    }

    private function addAttributesToStringValueTag(): XmlElement
    {
        foreach ($this->attributes as $attribute => $value) {
            $this->stringValue .= " $attribute=\"$value\"";
        }
        return $this;
    }

    private function addChildrenToStringValue(): XmlElement
    {
        foreach ($this->children as $child) {
            $this->stringValue .= $child->getStringValue();
        }
        return $this;
    }
    
    private function addContentToStringValue(): XmlElement
    {
        $indentation = str_repeat("   ", $this->nestingLevel + 1);
        $this->stringValue .= $indentation . htmlspecialchars($this->content, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "\n";
        return $this;   
    }

    private function endTag(): XmlElement
    {
        $indentation = str_repeat("   ", $this->nestingLevel);
        $this->stringValue .= $indentation . "</" . $this->elementName . ">\n";
        return $this;
    }
}

