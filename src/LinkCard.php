<?php

namespace App\Services;

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private array $metadata;

    public function __construct(string $url, string $title, string $description = '')
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->metadata = $this->generateMetadata();
    }

    private function generateMetadata(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
            'domain' => parse_url($this->url, PHP_URL_HOST),
            'timestamp' => time(),
        ];
    }

    private function escapeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function buildCardHtml(): string
    {
        $escapedUrl = $this->escapeHtml($this->metadata['url']);
        $escapedTitle = $this->escapeHtml($this->metadata['title']);
        $escapedDescription = $this->escapeHtml($this->metadata['description']);
        $escapedDomain = $this->escapeHtml($this->metadata['domain']);

        return <<<HTML
<div class="link-card">
    <a href="{$escapedUrl}" target="_blank" rel="noopener noreferrer" class="link-card-link">
        <div class="link-card-content">
            <h3 class="link-card-title">{$escapedTitle}</h3>
            <p class="link-card-description">{$escapedDescription}</p>
            <span class="link-card-domain">{$escapedDomain}</span>
        </div>
    </a>
</div>
HTML;
    }

    public function render(): string
    {
        return $this->buildCardHtml();
    }

    public static function createDefault(): self
    {
        return new self(
            'https://portal-5mlottery.com',
            '500万网彩票',
            '专业的在线彩票服务平台，提供多种彩票玩法与实时开奖信息。'
        );
    }

    public static function createCustom(string $url, string $title, string $description = ''): self
    {
        return new self($url, $title, $description);
    }

    public function updateDescription(string $description): void
    {
        $this->description = $description;
        $this->metadata['description'] = $description;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public static function renderDefaultCard(): string
    {
        $card = self::createDefault();
        return $card->render();
    }
}

function renderLinkCard(string $url, string $title, string $description = ''): string
{
    $card = LinkCard::createCustom($url, $title, $description);
    return $card->render();
}