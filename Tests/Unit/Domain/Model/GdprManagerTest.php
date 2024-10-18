<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComMp\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class GdprManagerTest extends UnitTestCase
{
    /**
     * @var \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getHeadingReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getHeading()
        );
    }

    /**
     * @test
     */
    public function setHeadingForStringSetsHeading(): void
    {
        $this->subject->setHeading('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('heading'));
    }

    /**
     * @test
     */
    public function getContentReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getContent()
        );
    }

    /**
     * @test
     */
    public function setContentForStringSetsContent(): void
    {
        $this->subject->setContent('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('content'));
    }

    /**
     * @test
     */
    public function getButtonTextReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getButtonText()
        );
    }

    /**
     * @test
     */
    public function setButtonTextForStringSetsButtonText(): void
    {
        $this->subject->setButtonText('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('buttonText'));
    }

    /**
     * @test
     */
    public function getEnableBackgroundImageReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getEnableBackgroundImage()
        );
    }

    /**
     * @test
     */
    public function setEnableBackgroundImageForIntSetsEnableBackgroundImage(): void
    {
        $this->subject->setEnableBackgroundImage(12);

        self::assertEquals(12, $this->subject->_get('enableBackgroundImage'));
    }

    /**
     * @test
     */
    public function getBackgroundImageReturnsInitialValueForFileReference(): void
    {
        self::assertEquals(
            null,
            $this->subject->getBackgroundImage()
        );
    }

    /**
     * @test
     */
    public function setBackgroundImageForFileReferenceSetsBackgroundImage(): void
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setBackgroundImage($fileReferenceFixture);

        self::assertEquals($fileReferenceFixture, $this->subject->_get('backgroundImage'));
    }

    /**
     * @test
     */
    public function getBackgroundImageColorReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getBackgroundImageColor()
        );
    }

    /**
     * @test
     */
    public function setBackgroundImageColorForStringSetsBackgroundImageColor(): void
    {
        $this->subject->setBackgroundImageColor('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('backgroundImageColor'));
    }

    /**
     * @test
     */
    public function getButtonColorReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getButtonColor()
        );
    }

    /**
     * @test
     */
    public function setButtonColorForStringSetsButtonColor(): void
    {
        $this->subject->setButtonColor('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('buttonColor'));
    }

    /**
     * @test
     */
    public function getTextColorReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTextColor()
        );
    }

    /**
     * @test
     */
    public function setTextColorForStringSetsTextColor(): void
    {
        $this->subject->setTextColor('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('textColor'));
    }

    /**
     * @test
     */
    public function getButtonShapeReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getButtonShape()
        );
    }

    /**
     * @test
     */
    public function setButtonShapeForStringSetsButtonShape(): void
    {
        $this->subject->setButtonShape('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('buttonShape'));
    }
}
