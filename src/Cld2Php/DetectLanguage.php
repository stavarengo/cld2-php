<?php
namespace Sta\Cld2Php;

use Sta\Cld2Php\Cld2\CLD2Detector;

class DetectLanguage
{
    /**
     * @var string
     */
    protected $text;
    /**
     * @var CLD2Detector
     */
    protected $cld2Detector;

    /**
     * DetectLanguage constructor.
     */
    public function __construct()
    {
        if (class_exists('\CLD2Detector')) {
            $this->cld2Detector = new CLD2Detector();
            $this->cld2Detector->setEncodingHint(\CLD2Encoding::UTF8);
        } else {
            throw new \Sta\Cld2Php\Exception\ModuleCld2NotFound('CLD2 extension not installed.');
        }
    }

    /**
     * @return DetectionResponse[]
     */
    public function detect($text, $normalize = true)
    {
        if (!$text) {
            return [];
        }

        $result = [];
        $text   = $this->text;

        if ($normalize) {
            $text = $this->normalizeText($text);
        }

        $detectionResult = $this->cld2Detector->detect($text);
        if ($detectionResult && is_array($detectionResult)) {
            $result[] = (new DetectionResponse())->setLanguage($detectionResult['language_code'])
                                                 ->setConfidence($detectionResult['language_probability'] / 100);
        }

        return $result;
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function normalizeText($text)
    {
        // Remove emails.
        $text = preg_replace('/\S+@\S+\.\S+/', ' ', $text);
        // Remove HTTP links.
        $text = preg_replace('#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i', ' ', $text);
        // Remove any character that is not valid to make words (eg: emoticons, symbols).
        $text = preg_replace('/[^[:alnum:][:alpha:][:ascii:][:cntrl:][:word:]]/u', ' ', $text);
        // Remove numbers and letter mixed with numbers.
        $text = preg_replace('/\b[a-zA-Z]*?[0-9]+[a-zA-Z]*?\b/', ' ', $text);
        // Remove any repeated character in sequence.
        $text = preg_replace("/([^[:alnum:]])\\1{3,}/", ' ', $text);
        // Removes repeated punctuation in sequence.
        $text = preg_replace('/([.,!?\'"%\[\]{}();:|\\\+=])\\1+/', ' ', $text);

        return trim($text);
    }
}
