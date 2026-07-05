<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LostItems Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\LostItem newEmptyEntity()
 * @method \App\Model\Entity\LostItem newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\LostItem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LostItem get($primaryKey, $options = [])
 * @method \App\Model\Entity\LostItem findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\LostItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LostItem[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LostItem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LostItem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LostItem[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\LostItem[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\LostItem[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\LostItem[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class LostItemsTable extends Table
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

        $this->setTable('lost_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->date('date_lost')
            ->allowEmptyDate('date_lost');

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
}
