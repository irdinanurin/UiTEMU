<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Certificates Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Certificate newEmptyEntity()
 * @method \App\Model\Entity\Certificate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Certificate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Certificate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Certificate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Certificate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Certificate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Certificate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Certificate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Certificate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Certificate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Certificate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Certificate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CertificatesTable extends Table
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

    $this->setTable('certificates');
    $this->setDisplayField('id');
    $this->setPrimaryKey('id');

    $this->belongsTo('Users', [
        'foreignKey' => 'user_id',
        'joinType' => 'LEFT',
    ]);

    $this->belongsTo('FoundItems', [
        'foreignKey' => 'found_item_id',
        'joinType' => 'LEFT',
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
            ->scalar('certificate_no')
            ->maxLength('certificate_no', 255)
            ->allowEmptyString('certificate_no');

        $validator
            ->date('issue_date')
            ->allowEmptyDate('issue_date');

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
