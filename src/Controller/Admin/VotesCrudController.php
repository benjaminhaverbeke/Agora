<?php

namespace App\Controller\Admin;

use App\Entity\Vote;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VotesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vote::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            AssociationField::new('proposal')
                ->setFormTypeOption('choice_label', 'title'),
            AssociationField::new('sujet')
                ->setFormTypeOption('choice_label', 'title'),
            TextField::new('notes'),

        ];
    }

}
