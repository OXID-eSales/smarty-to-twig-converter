<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig;

/**
 * Class ConversionResult
 */
class ConversionResult
{
    private ?string $originalTemplate = null;
    private ?string $convertedTemplate = null;
    private ?string $diff = null;

    /** @var string[] */
    private array $appliedConverters = [];

    public function getOriginalTemplate(): ?string
    {
        return $this->originalTemplate;
    }

    public function setOriginalTemplate(string $originalTemplate): self
    {
        $this->originalTemplate = $originalTemplate;

        return $this;
    }

    public function getConvertedTemplate(): ?string
    {
        return $this->convertedTemplate;
    }

    public function setConvertedTemplate(string $convertedTemplate): self
    {
        $this->convertedTemplate = $convertedTemplate;

        return $this;
    }

    public function getDiff(): ?string
    {
        return $this->diff;
    }

    public function setDiff(string $diff): self
    {
        $this->diff = $diff;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAppliedConverters(): array
    {
        return $this->appliedConverters;
    }

    public function addAppliedConverter(string $appliedConverter): self
    {
        $this->appliedConverters[] = $appliedConverter;

        return $this;
    }

    public function hasAppliedConverters(): bool
    {
        return !empty($this->appliedConverters);
    }
}
