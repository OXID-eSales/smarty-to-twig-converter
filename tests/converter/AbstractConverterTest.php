<?php

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
abstract class AbstractConverterTest extends TestCase
{
    /**
     * @return \SplFileInfo
     */
    protected function getFileMock()
    {
        /** @var \SplFileInfo $mock */
        $mock = $this->getMockBuilder('\SplFileInfo')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}