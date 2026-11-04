<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Util;

use PHPUnit\Framework\TestCase;
use toTwig\Util\DuplicateBlockDetector;

/**
 * Class DuplicateBlockDetectorTest
 */
class DuplicateBlockDetectorTest extends TestCase
{
    /**
     * @covers \toTwig\Util\DuplicateBlockDetector::detect
     * @dataProvider provider
     *
     * @param string               $template
     * @param array<string, int[]> $expected
     */
    public function testDetect(string $template, array $expected): void
    {
        $this->assertSame($expected, (new DuplicateBlockDetector())->detect($template));
    }

    /**
     * @return array<int, array{0: string, 1: array<string, int[]>}>
     */
    public function provider(): array
    {
        return [
            // Distinct names
            [
                "{% block a %}{% endblock %}\n{% block b %}{% endblock %}",
                []
            ],
            // Same name twice: Smarty renders both, Twig refuses to compile
            [
                "{% block a %}x{% endblock %}\n\n{% block a %}y{% endblock %}",
                ['a' => [1, 3]]
            ],
            // Nested and with whitespace control
            [
                "{% block outer %}\n  {%- block a %}x{% endblock %}\n  {% block a -%}y{% endblock %}\n{% endblock %}",
                ['a' => [2, 3]]
            ],
            // A block inside a comment is no block (an "override me empty" hint in the docs)
            [
                "{# override with {% block a %}{% endblock %} #}\n{% block a %}x{% endblock %}",
                []
            ],
            // Lines after a multi-line comment are still counted right
            [
                "{#\n\n#}\n{% block a %}{% endblock %}\n{% block a %}{% endblock %}",
                ['a' => [4, 5]]
            ],
            // Only the duplicated name is reported
            [
                "{% block a %}{% endblock %}{% block b %}{% endblock %}{% block b %}{% endblock %}",
                ['b' => [1, 1]]
            ],
        ];
    }

    /**
     * @covers \toTwig\Util\DuplicateBlockDetector::getWarnings
     */
    public function testGetWarnings(): void
    {
        $warnings = (new DuplicateBlockDetector())->getWarnings(
            "{% block a %}x{% endblock %}\n{% block a %}y{% endblock %}"
        );

        $this->assertSame(
            [
                "Block 'a' is defined 2 times (lines 1, 2); Twig will not compile this template."
                . ' Rename all but one occurrence.'
            ],
            $warnings
        );
    }
}
