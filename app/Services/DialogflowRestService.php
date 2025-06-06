<?php

namespace App\Services;

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;

class DialogflowRestService
{
    protected $sessionsClient;
    protected $projectId;
    protected $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.dialogflow.project_id');
        $this->credentialsPath = storage_path(
            'app/' . config('services.dialogflow.credentials')
        );
    }

    public function detectIntent($sessionId, $text, $languageCode = 'id-ID')
    {
        try {
            $this->sessionsClient = new SessionsClient([
                'credentials' => $this->credentialsPath,
            ]);

            $sessionName = $this->sessionsClient->sessionName(
                $this->projectId,
                $sessionId ?: uniqid()
            );

            $textInput = (new TextInput())
                ->setText($text)
                ->setLanguageCode($languageCode);

            $queryInput = (new QueryInput())->setText($textInput);

            $response = $this->sessionsClient->detectIntent($sessionName, $queryInput);
            $queryResult = $response->getQueryResult();

            return [
                'success' => true,
                'response' => $queryResult->getFulfillmentText(),
                'intent' => $queryResult->getIntent()->getDisplayName(),
                'confidence' => $queryResult->getIntentDetectionConfidence(),
                'parameters' => json_decode($queryResult->getParameters()->serializeToJsonString(), true),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response' => config('chatbot.fallback_response', 'Maaf, saya tidak mengerti pertanyaan Anda.'),
            ];
        }
    }

    public function __destruct()
    {
        if ($this->sessionsClient) {
            $this->sessionsClient->close();
        }
    }
}
