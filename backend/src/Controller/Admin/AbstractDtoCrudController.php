<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Admin\FormDTOInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

abstract class AbstractDtoCrudController extends AbstractCrudController
{
    abstract public static function getDtoFqcn(): string;

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formOptions->set('data_class', $this->getDtoFqcn());
        $formOptions->set('attr.class', $this->getDtoFqcn());

        return $this->updateBuilder(parent::createNewFormBuilder($entityDto, $formOptions, $context), $formOptions);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formOptions->set('data_class', $this->getDtoFqcn());
        $formOptions->set('attr.class', $this->getDtoFqcn());

        return $this->updateBuilder(parent::createEditFormBuilder($entityDto, $formOptions, $context), $formOptions);
    }

    private function updateBuilder(FormBuilderInterface $builder, KeyValueStore $formOptions): FormBuilderInterface
    {
        /** @var object|null $savedObject */
        $savedObject = null;
        $get = static function () use (&$savedObject): object|null { return $savedObject; };
        $set = static function ($newObject) use (&$savedObject) { $savedObject = $newObject; };

        return $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($set) {
                $dtoClass = $this->getDtoFqcn();
                $entityClass = $this->getEntityFqcn();

                if (!is_a($dtoClass, FormDTOInterface::class, true)) {
                    throw new \RuntimeException(\sprintf('DTO class "%s" must implement the "%s" interface.', $dtoClass, FormDTOInterface::class));
                }

                $entity = $event->getData();

                if (!$entity instanceof $entityClass) {
                    throw new \RuntimeException('An unknown error occured when retrieving entity object before setting form data.');
                }

                $set($entity);
                $dto = $dtoClass::createFromEntity($entity);
                $event->setData($dto);
            }, priority: -65535)
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($get) {
                $dto = $event->getData();
                $errors = $event->getForm()->getErrors(true);
                $entity = $get();

                if (!$entity) {
                    throw new \RuntimeException('An unknown error occured when retrieving entity object after form was submitted.');
                }

                if (!$errors->count()) {
                    $mutator = $dto::getEntityMutatorMethodName();
                    if (!method_exists($entity, $mutator)) {
                        throw new \RuntimeException(\sprintf(
                            'Entity class "%s" was expected method "%s", specified by the "%s" DTO class, but the method does not exist.',
                            \get_class($entity)
                        ));
                    }
                    $entity->{$mutator}($dto);
                }
            }, priority: -65535)
            ;
    }
}
