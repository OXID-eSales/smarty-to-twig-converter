<?php

namespace toTwig;

/**
 * Class ConversionResult
 */
class ConversionResult
{
    /** @var string */
    private $originalTemplate;

    /** @var string */
    private $convertedTemplate;

    /** @var string */
    private $diff;

    /** @var string[] */
    private $appliedConverters = [];


    /**
     * @return string|null
     */
    public function getOriginalTemplate(): ?string
    {
        return $this->originalTemplate;
    }

    /**
     * @param string $originalTemplate
     *
     * @return ConversionResult
     */
    public function setOriginalTemplate(string $originalTemplate): self
    {
        $this->originalTemplate = $originalTemplate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConvertedTemplate(): ?string
    {
        return $this->convertedTemplate;
    }

    /**
     * @param string $convertedTemplate
     *
     * @return ConversionResult
     */
    public function setConvertedTemplate(string $convertedTemplate): self
    {
        $this->convertedTemplate = $convertedTemplate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDiff(): ?string
    {
        return $this->diff;
    }

    /**
     * @param string $diff
     *
     * @return ConversionResult
     */
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

    /**
     * @param string $appliedConverter
     *
     * @return ConversionResult
     */
    public function addAppliedConverter(string $appliedConverter): self
    {
        $this->appliedConverters[] = $appliedConverter;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAppliedConverters(): bool
    {
        return !empty($this->appliedConverters);
    }
}
