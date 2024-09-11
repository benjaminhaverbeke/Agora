<?php

namespace App\Controller\Admin;

use App\Entity\Salon;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class SalonsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Salon::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
            DateField::new('createdAt'),
            DateField::new('dateCampagne'),
            DateField::new('dateVote'),
            AssociationField::new('users')
            ->setFormTypeOption('choice_label', 'username')
        ];

    }

}
