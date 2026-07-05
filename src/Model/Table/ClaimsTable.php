<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Claims Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FoundItemsTable&\Cake\ORM\Association\BelongsTo $FoundItems
 *
 * @method \App\Model\Entity\Claim newEmptyEntity()
 * @method \App\Model\Entity\Claim newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Claim[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Claim get($primaryKey, $options = [])
 * @method \App\Model\Entity\Claim findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Claim patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Claim[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Claim|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Claim saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Claim[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Claim[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Claim[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Claim[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ClaimsTable extends Table
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

        $this->setTable('claims');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsTo('FoundItems', [
            'foreignKey' => 'found_item_id',
        ]);
        $this->belongsTo('LostItems', [
            'foreignKey' => 'lost_item_id',
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
            ->integer('found_item_id')
            ->allowEmptyString('found_item_id');

        $validator
            ->scalar('claim_status')
            ->maxLength('claim_status', 255)
            ->allowEmptyString('claim_status');

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
        $rules->add($rules->existsIn('found_item_id', 'FoundItems'), ['errorField' => 'found_item_id']);

        return $rules; 
    }
}
