<?php

namespace App\Controller\Admin;

use App\Entity\Proposal;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProposalsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Proposal::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('description'),
            AssociationField::new('salon')
                ->setFormTypeOption('choice_label', 'title'),
            AssociationField::new('sujet')
                ->setFormTypeOption('choice_label', 'title'),
            AssociationField::new('user')
                ->setFormTypeOption('choice_label', 'username'),
        ];
    }

}
