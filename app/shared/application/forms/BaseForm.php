<?php

namespace app\shared\application\forms;

use app\shared\application\traits\ErrorsTrait;
use app\shared\application\traits\ValidationRulesTrait;

class BaseForm
{
    use ErrorsTrait;
    use ValidationRulesTrait;

    /**
     * @param string $string
     * @return string
     */
    protected function filterStringPolyfill(string $string): string
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }

    /**
     * @param string $str
     * @return string
     */
    protected function purify(string $str): string
    {
        $text = strip_tags($this->filterStringPolyfill($str));
        $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE);
        return trim(strip_tags(addslashes($text)));
    }

    /**
     * @param string $value
     * @return array<int>
     */
    protected function extractIntValues(string $value): array
    {
        return array_map('intval', $this->extractValues($value));
    }

    /**
     * @param string $value
     * @return array<mixed>
     */
    protected function extractValues(string $value): array
    {
        $values = explode(",", $value);
        return array_values(array_unique($values));
    }

    /**
     * @return void
     */
    public function filterAttributes(): void
    {
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }
}
