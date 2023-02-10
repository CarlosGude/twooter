<?php

namespace App\State;

use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Twoot as TwootEntity;
use App\Dto\Input\Twoot as TwootInput;
use App\Dto\Output\Twoot as TwootOutput;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TwootStateProcessor implements ProcessorInterface
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected Security $security
    )
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
        if(!($data instanceof TwootInput))
            throw new Exception("data is not an instance of ".TwootInput::class);

        $model = new TwootEntity();
        $data->toModel($model,$this->security->getUser());
        $this->em->persist($model);
        $this->em->flush();

        return TwootOutput::fromModel($model);
    }
}
