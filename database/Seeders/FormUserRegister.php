<?php

namespace Modules\Iuser\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Model;
use Module;
use Modules\Iform\Events\UpdateForm;

class FormUserRegister extends Seeder
{

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Model::unguard();

    if (Module::find('iform') && Module::isEnabled('iform')) {
      $roleRepository = app('Modules\Iuser\Repositories\RoleRepository');
      $formRepository = app("Modules\Iform\Repositories\FormRepository");
      $blockRepository = app("Modules\Iform\Repositories\BlockRepository");
      $fieldRepository = app("Modules\Iform\Repositories\FieldRepository");
      $params = [
        'filter' => [
          'field' => 'system_name',
        ],
        'include' => [],
        'fields' => [],
      ];
      $form = $formRepository->getItem('form_user_register', json_decode(json_encode($params)));
      if (!isset($form->id)) {
        $form = $formRepository->create([
          'title' => itrans('iuser::forms.userRegister.title'),
          'system_name' => 'form_user_register',
          'active' => true,
        ]);

        $block = $blockRepository->create([
          'form_id' => $form->id,
          'name' => 'fields',
        ]);

        $fieldRepository->create([
          'form_id' => $form->id,
          'block_id' => $block->id,
          'es' => [
            'label' => itrans('iuser::forms.userRegister.fields.firstName', [], 'es'),
          ],
          'en' => [
            'label' => itrans('iuser::forms.userRegister.fields.firstName', [], 'en'),
          ],
          'type_id' => 1,
          'system_name' => 'first_name',
          'required' => true,
        ]);

        $fieldRepository->create([
          'form_id' => $form->id,
          'block_id' => $block->id,
          'es' => [
            'label' => itrans('iuser::forms.userRegister.fields.lastName', [], 'es'),
          ],
          'en' => [
            'label' => itrans('iuser::forms.userRegister.fields.lastName', [], 'en'),
          ],
          'type_id' => 1,
          'system_name' => 'last_name',
          'required' => true,
        ]);

        $fieldRepository->create([
          'form_id' => $form->id,
          'block_id' => $block->id,
          'es' => [
            'label' => itrans('iuser::forms.userRegister.fields.birthday', [], 'es'),
          ],
          'en' => [
            'label' => itrans('iuser::forms.userRegister.fields.birthday', [], 'en'),
          ],
          'type_id' => 11,
          'system_name' => 'birthday',
          'required' => false,
        ]);

        $fieldRepository->create([
          'form_id' => $form->id,
          'block_id' => $block->id,
          'es' => [
            'label' => itrans('iuser::forms.userRegister.fields.documentType', [], 'es'),
          ],
          'en' => [
            'label' => itrans('iuser::forms.userRegister.fields.documentType', [], 'en'),
          ],
          'options' => [
            'fieldOptions' => [
              'Cédula de Ciudadanía',
              'Cédula de Extranjería',
              'Número de Pasaporte',
            ],
          ],
          'type_id' => 5,
          'system_name' => 'document_type',
          'required' => false,
        ]);

        $fieldRepository->create([
          'form_id' => $form->id,
          'block_id' => $block->id,
          'es' => [
            'label' => itrans('iuser::forms.userRegister.fields.documentNumber', [], 'es'),
          ],
          'en' => [
            'label' => itrans('iuser::forms.userRegister.fields.documentNumber', [], 'en'),
          ],
          'type_id' => 3,
          'system_name' => 'document_number',
          'required' => true,
        ]);
      }

      $role = $roleRepository->getItem('user', json_decode(json_encode($params)));

      event(new UpdateForm([
        'data' => [
          'form_id' => $form->id
        ],
        'model' => $role
      ]));
    }
  }
}
