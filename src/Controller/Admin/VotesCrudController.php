<?php

namespace App\Controller\Admin;

use App\Entity\Votes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VotesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Votes::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('proposal'),
            AssociationField::new('sujet'),
            TextField::new('notes'),

        ];
    }

}
