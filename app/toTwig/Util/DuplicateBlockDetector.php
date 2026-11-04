<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Util;

/**
 * Finds block names that are defined more than once in one converted template.
 *
 * Smarty renders every occurrence of a block name, Twig refuses to compile the template
 * ("The block 'x' has already been defined"). The converter cannot pick a new name on its
 * own - modules and themes extend blocks by name - so the conversion only reports it.
 */
class DuplicateBlockDetector
{
    private const COMMENT_PATTERN = '/\{#.*?#\}/s';
    private const BLOCK_PATTERN = '/\{%-?\s*block\s+([A-Za-z_][A-Za-z0-9_]*)/';

    /**
     * @return array<string, int[]> block name => line numbers, only names defined more than once
     */
    public function detect(string $twigTemplate): array
    {
        $lines = [];
        $masked = $this->maskComments($twigTemplate);

        if (!preg_match_all(self::BLOCK_PATTERN, $masked, $matches, PREG_OFFSET_CAPTURE)) {
            return [];
        }

        foreach ($matches[1] as [$name, $offset]) {
            $lines[$name][] = substr_count($masked, "\n", 0, $offset) + 1;
        }

        return array_filter(
            $lines,
            function (array $occurrences): bool {
                return count($occurrences) > 1;
            }
        );
    }

    /**
     * @return string[] one human readable message per duplicated block name
     */
    public function getWarnings(string $twigTemplate): array
    {
        $warnings = [];
        foreach ($this->detect($twigTemplate) as $name => $lines) {
            $warnings[] = sprintf(
                "Block '%s' is defined %d times (lines %s); Twig will not compile this template."
                . ' Rename all but one occurrence.',
                $name,
                count($lines),
                implode(', ', $lines)
            );
        }

        return $warnings;
    }

    /**
     * A block inside a comment is no block. Comments are blanked out keeping their line
     * breaks, so offsets after them still map to the right line.
     */
    private function maskComments(string $twigTemplate): string
    {
        return preg_replace_callback(
            self::COMMENT_PATTERN,
            function (array $matches): string {
                return preg_replace('/[^\n]/', ' ', $matches[0]);
            },
            $twigTemplate
        );
    }
}
