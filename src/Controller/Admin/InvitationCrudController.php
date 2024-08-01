<?php

namespace App\Controller\Admin;

use App\Entity\Invitation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InvitationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invitation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('sender'),
            AssociationField::new('receiver'),
            AssociationField::new('salon'),
            DateField::new('createdAt'),
        ];
    }

}
