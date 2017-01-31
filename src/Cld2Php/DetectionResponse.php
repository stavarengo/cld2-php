<?php
namespace Sta\Cld2Php;

class DetectionResponse
{
    /**
     * @var string
     */
    protected $language = false;
    /**
     * @var float
     */
    protected $confidence = false;

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return float
     */
    public function getConfidence()
    {
        return $this->confidence;
    }

    /**
     * @param float $confidence
     *
     * @return $this
     */
    public function setConfidence($confidence)
    {
        $this->confidence = $confidence;

        return $this;
    }

}
