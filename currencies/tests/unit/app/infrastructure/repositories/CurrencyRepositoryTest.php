<?php

namespace tests\unit\app\infrastructure\repositories;

use app\application\enums\CurrencyIso;
use app\domain\entities\Currency;
use app\infrastructure\models\CurrencyQuery;
use app\infrastructure\repositories\CurrencyRepository;
use app\application\exceptions\NotValidException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\db\Exception;

class CurrencyRepositoryTest extends UnitTestCase
{
    private CurrencyRepository $repository;
    private CurrencyQuery|MockObject $query;

    public function setUp(): void
    {
        parent::setUp();
        $this->query = $this->getCurrencyQueryMock();
        $this->repository = new CurrencyRepository($this->query);
    }

    /**
     * @return CurrencyQuery|MockObject
     */
    protected function getCurrencyQueryMock(): CurrencyQuery|MockObject
    {
        return $this->getMockBuilder(CurrencyQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'findByCode',
                    'save',
                ]
            )
            ->getMock();
    }

    public function testFindByCode()
    {
        $code = CurrencyIso::USD->value;
        $model = $this->getCurrencyModelMock();
        $model->id = 1;
        $model->iso3 = $code;
        $model->rate = 1;

        $this->query->expects(self::once())
            ->method('findByCode')
            ->with($code)
            ->willReturn($model);

        $actual = $this->repository->findByCode($code);
        self::assertInstanceOf(Currency::class, $actual);
    }

    public function testFindByCodeNotExit()
    {
        $code = CurrencyIso::USD->value;
        $this->query->expects(self::once())
            ->method('findByCode')
            ->with($code)
            ->willReturn(null);

        $actual = $this->repository->findByCode($code);
        self::assertEquals(null, $actual);
    }

    /**
     * @throws NotValidException
     * @throws Exception
     */
    public function testSave()
    {
        $entity = $this->getCurrencyEntity();

        $code = CurrencyIso::USD->value;
        $model = $this->getCurrencyModelMock();
        $model->id = 1;
        $model->iso3 = $code;
        $model->rate = 1;

        $this->query->expects(self::once())
            ->method('save')
            ->with([
                'id' => $entity->getId()->value(),
                'iso3' => $entity->getIso3()->value(),
                'rate' => $entity->getRate()->value(),
                'created_at' => $entity->getCreatedAt()->value(),
                'updated_at' => $entity->getUpdatedAt()->value(),
            ])
            ->willReturn($model);

        $actual = $this->repository->save($entity);
        self::assertInstanceOf(Currency::class, $actual);
    }
}

