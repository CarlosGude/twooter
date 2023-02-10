<?php

namespace App\State;

use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User as UserEntity;
use App\Dto\Input\User as UserInput;
use App\Dto\Output\User as UserOutput;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserStateProcessor implements ProcessorInterface
{
    public function __construct(protected EntityManagerInterface $em)
    {
    }

    /**
     * @throws Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var HttpOperation */
        $op = $operation;
        switch ($op->getMethod()) {
            case HttpOperation::METHOD_POST:
                return $this->processCreate($data);

            case HttpOperation::METHOD_PUT :
            case HttpOperation::METHOD_PATCH:
            case HttpOperation::METHOD_DELETE:
                throw new NotFoundHttpException();

            default:
                break;
        }


        throw new NotFoundHttpException();
    }

    private function processCreate($data)
    {
        if(!($data instanceof UserInput))
            throw new Exception("data is not an instance of ".UserInput::class);

        $model = new UserEntity();
        $data->toModel($model);
        $this->em->persist($model);
        $this->em->flush();

        return UserOutput::fromModel($model);
    }
}
