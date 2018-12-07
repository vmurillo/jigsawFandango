<?php

namespace App;

// Helper class where all possible quality transformations are defined
class Rules {
    // default quality transformation, if the object is like brie then the change is positive
    public static function defaultUpdateQuality($currentQuality, $currentSellIn, $likeAgedBrie) {  
        if ($currentSellIn > 0) {
            $qualityVariation = 1;
        } else {
            $qualityVariation = 2;
        }
        if ($likeAgedBrie) {
            $newQuality = $currentQuality + $qualityVariation;
        } else {
            $newQuality = $currentQuality - $qualityVariation;
        }
        return $newQuality;
    }
    // transformation for back stage objects
    public static function backStageUpdateQuality($currentQuality, $currentSellIn) {  
        if ($currentSellIn < 10 && $currentSellIn > 5) {
            $qualityVariation = 2;
        } else if ($currentSellIn <= 5 && $currentSellIn >= 0) {
            $qualityVariation = 3;
        } else if ($currentSellIn < 0) {
            $qualityVariation = -$currentQuality;
        } else {
            $qualityVariation = 1;
        }
        return $currentQuality + $qualityVariation;
    }
    // min transformation, defines the minimum quality
    public static function m¡nUpdateQuality($currentQuality) {
        return max($currentQuality, 0);
    }
    // max transformation, defines the maximum quality
    public static function maxUpdateQuality($currentQuality) {
        return min($currentQuality, 50);
    }
    // transformation for legendary objects, the quality doesn't change
    public static function legendaryUpdateQuality($currentQuality) {
        return $currentQuality;
    }
    // transformation for conjured objects, the change is just the double from default
    public static function conjuredUpdateQuality($currentQuality, $sellIn) {
        $qualityDelta = $currentQuality - self::defaultUpdateQuality($currentQuality, $sellIn, false);
        return $currentQuality - 2 * $qualityDelta;
    }
}

class RulesBuilder {
    // rules to be applied on quality
    private $qualityRules;
    // true for legendary objects
    private $isLegendary;

    public function __construct($name) {
        switch ($name) {
            case "Aged Brie":
                $this->qualityRules = array(
                    0 => function($quality, $sellIn) { return Rules::defaultUpdateQuality($quality, $sellIn, true);},
                    1 => function($quality) {return Rules::maxUpdateQuality($quality);},
                    2 => function($quality) {return Rules::m¡nUpdateQuality($quality);}
                );
                $this->isLegendary = false;
                break;
            case "Backstage passes to a TAFKAL80ETC concert":
                $this->qualityRules = array(
                    0 => function($quality, $sellIn) { return Rules::backStageUpdateQuality($quality, $sellIn);},
                    1 => function($quality) {return Rules::maxUpdateQuality($quality);},
                    2 => function($quality) {return Rules::m¡nUpdateQuality($quality);}
                );
                $this->isLegendary = false;
                break;
            case "Sulfuras, Hand of Ragnaros": 
                $this->qualityRules = array(0 => function($quality) {return Rules::legendaryUpdateQuality($quality);});
                $this->isLegendary = true;
                break;
            case  "Conjured Mana Cake":
                $this->qualityRules = array(
                    0 => function($quality, $sellIn) { return Rules::conjuredUpdateQuality($quality, $sellIn);},
                    1 => function($quality) {return Rules::maxUpdateQuality($quality);},
                    2 => function($quality) {return Rules::m¡nUpdateQuality($quality);}
                );
                $this->isLegendary = false;
                break;
            default:
                $this->qualityRules = array(
                    0 => function($quality, $sellIn) { return Rules::defaultUpdateQuality($quality, $sellIn, false);},
                    1 => function($quality) {return Rules::maxUpdateQuality($quality);},
                    2 => function($quality) {return Rules::m¡nUpdateQuality($quality);}
                );
                $this->isLegendary = false;
        }
    }
    // apply all rules to compute the new quality
    public function applyQualityRules($quality, $sellIn) {
        $currentQuality = $quality;
        foreach ($this->qualityRules as $rule) {
            $currentQuality = $rule($currentQuality, $sellIn);
        }
        return $currentQuality;
    }
    // compute the new sellIn
    public function getSellIn($sellIn) {
        if ($this->isLegendary) {
            $newSellIn = $sellIn;
        } else {
            $newSellIn = $sellIn - 1;
        }
        return $newSellIn;
    }
}

class GildedRose
{
    public $name;

    public $quality;

    public $sellIn;
    // ruleSet object that encapsulates quality and sellIn update logic
    private $ruleSet;

    public function __construct($name, $quality, $sellIn)
    {
        $this->name = $name;
        $this->quality = $quality;
        $this->sellIn = $sellIn;
        $this->ruleSet = new RulesBuilder($name);
    }

    public static function of($name, $quality, $sellIn) {
        return new static($name, $quality, $sellIn);
    }

    public function tick()
    {
        $this->sellIn = $this->ruleSet->getSellIn($this->sellIn);
        $this->quality = $this->ruleSet->applyQualityRules($this->quality, $this->sellIn);
    }
}
