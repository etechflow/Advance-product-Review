<?php
/**
 * ETechFlow_AdvancedProductReviews
 *
 * @author ETechFlow <etechflow0@gmail.com>
 */
declare(strict_types=1);

namespace ETechFlow\AdvancedProductReviews\Model\Service\Translator;

/**
 * Translation client for the Anthropic (Claude) Messages API.
 *
 * @see https://docs.anthropic.com/en/api/messages
 */
class ClaudeClient extends AbstractTranslator
{
    private const API_ENDPOINT = 'https://api.anthropic.com/v1/messages';
    private const API_VERSION = '2023-06-01';

    /**
     * @inheritDoc
     */
    protected function callModel(array $fields, string $targetLanguage, string $apiKey, string $model): string
    {
        $payload = [
            'model' => $model,
            'max_tokens' => self::MAX_TOKENS,
            'system' => $this->buildSystemPrompt($targetLanguage),
            'messages' => [
                ['role' => 'user', 'content' => $this->json->serialize($fields)],
            ],
        ];

        $response = $this->request(
            self::API_ENDPOINT,
            [
                'content-type' => 'application/json',
                'x-api-key' => $apiKey,
                'anthropic-version' => self::API_VERSION,
            ],
            $payload
        );

        $text = '';
        foreach ($response['content'] ?? [] as $block) {
            if (($block['type'] ?? '') === 'text') {
                $text .= $block['text'] ?? '';
            }
        }
        return $text;
    }

    /**
     * @inheritDoc
     */
    protected function getProviderLabel(): string
    {
        return 'Anthropic';
    }
}
