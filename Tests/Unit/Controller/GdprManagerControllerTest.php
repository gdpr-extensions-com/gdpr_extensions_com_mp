<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComMp\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 */
class GdprManagerControllerTest extends UnitTestCase
{
    /**
     * @var \GdprExtensionsCom\GdprExtensionsComMp\Controller\GdprManagerController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\GdprExtensionsCom\GdprExtensionsComMp\Controller\GdprManagerController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllGdprManagersFromRepositoryAndAssignsThemToView(): void
    {
        $allGdprManagers = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gdprManagerRepository = $this->getMockBuilder(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $gdprManagerRepository->expects(self::once())->method('findAll')->will(self::returnValue($allGdprManagers));
        $this->subject->_set('gdprManagerRepository', $gdprManagerRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('gdprManagers', $allGdprManagers);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenGdprManagerToView(): void
    {
        $gdprManager = new \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('gdprManager', $gdprManager);

        $this->subject->showAction($gdprManager);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenGdprManagerToGdprManagerRepository(): void
    {
        $gdprManager = new \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager();

        $gdprManagerRepository = $this->getMockBuilder(\GdprExtensionsCom\GdprExtensionsComMp\Domain\Repository\GdprManagerRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $gdprManagerRepository->expects(self::once())->method('add')->with($gdprManager);
        $this->subject->_set('gdprManagerRepository', $gdprManagerRepository);

        $this->subject->createAction($gdprManager);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenGdprManagerToView(): void
    {
        $gdprManager = new \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('gdprManager', $gdprManager);

        $this->subject->editAction($gdprManager);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenGdprManagerInGdprManagerRepository(): void
    {
        $gdprManager = new \GdprExtensionsCom\GdprExtensionsComMp\Domain\Model\GdprManager();

        $gdprManagerRepository = $this->getMockBuilder(\GdprExtensionsCom\GdprExtensionsComYoutube\Domain\Repository\GdprManagerRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $gdprManagerRepository->expects(self::once())->method('update')->with($gdprManager);
        $this->subject->_set('gdprManagerRepository', $gdprManagerRepository);

        $this->subject->updateAction($gdprManager);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenGdprManagerFromGdprManagerRepository(): void
    {
        $gdprManager = new \GdprExtensionsCom\GdprExtensionsComYoutube\Domain\Model\GdprManager();

        $gdprManagerRepository = $this->getMockBuilder(\GdprExtensionsCom\GdprExtensionsComYoutube\Domain\Repository\GdprManagerRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $gdprManagerRepository->expects(self::once())->method('remove')->with($gdprManager);
        $this->subject->_set('gdprManagerRepository', $gdprManagerRepository);

        $this->subject->deleteAction($gdprManager);
    }
}
