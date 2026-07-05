<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoundItems Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ClaimsTable&\Cake\ORM\Association\HasMany $Claims
 *
 * @method \App\Model\Entity\FoundItem newEmptyEntity()
 * @method \App\Model\Entity\FoundItem newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoundItem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoundItem get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoundItem findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoundItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoundItem[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoundItem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoundItem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoundItem[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoundItem[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoundItem[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoundItem[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoundItemsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('found_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Claims', [
            'foreignKey' => 'found_item_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->scalar('item_name')
            ->maxLength('item_name', 255)
            ->allowEmptyString('item_name');

        $validator
            ->scalar('category')
            ->maxLength('category', 255)
            ->allowEmptyString('category');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('location')
            ->maxLength('location', 255)
            ->allowEmptyString('location');

        $validator
            ->date('date_found')
            ->allowEmptyDate('date_found');

        $validator
            ->scalar('status')
            ->maxLength('status', 255)
            ->allowEmptyString('status');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->allowEmptyDateTime('updated_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
    public function add()
{
    $foundItem = $this->FoundItems->newEmptyEntity();

    if ($this->request->is('post')) {

        $data = $this->request->getData();

        $image = $data['image'];

        if ($image && $image->getError() === UPLOAD_ERR_OK) {

            $filename = time() . '_' . $image->getClientFilename();

            $image->moveTo(
                WWW_ROOT . 'uploads' . DS . $filename
            );

            $data['image'] = $filename;
        }

        $foundItem = $this->FoundItems->patchEntity($foundItem, $data);

        if ($this->FoundItems->save($foundItem)) {
            $this->Flash->success('Found item saved.');
            return $this->redirect(['action' => 'index']);
        }

        debug($foundItem->getErrors());
        die();
    }

    $this->set(compact('foundItem'));
}
}
