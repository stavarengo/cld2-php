<?php

if (!isset($_POST['t']) && !isset($_GET['t'])) {
    $helper->echoJson(
        $helper->apiProblem(
            'Missing parameter',
            400,
            'Parameter "t" not set. You can send it either through query or post parameter.'
        ),
        400
    );
    return;
}

$text = trim(isset($_POST['t']) ? $_POST['t'] : $_GET['t']);

try {
    $detectLanguage     = new \Sta\Cld2Php\DetectLanguage();
    $detectionResponses = $detectLanguage->detect($_POST['t']);
} catch (\Sta\Cld2Php\Exception\ModuleCld2NotFound $e) {
    $helper->echoJson($helper->apiProblem('Server misconfigured', 500, $e->getMessage()), 500);
    return;
}

$result = [];
foreach ($detectionResponses as $detectionResponse) {
    $result[] = [
        'lang' => $detectionResponse->getLanguage(),
        'confidence' => $detectionResponse->getConfidence(),
    ];
}


$helper->echoJson($result);
