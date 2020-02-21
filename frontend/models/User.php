<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id Идентификатор
 * @property string $email E-mail
 * @property string $password Пароль
 * @property string $name Имя пользователя
 * @property string $dt_add Время создания записи
 *
 * @property Chat[] $chats
 * @property Favorite[] $favorite
 * @property Feedback[] $feedbacks
 * @property Job[] $jobs
 * @property Profile $profile
 * @property Reply[] $replies
 * @property Settings $settings
 * @property Task[] $customerTasks
 * @property Task[] $contractorTasks
 * @property Skill[] $skills
 */

class User extends \yii\db\ActiveRecord
{

    public function taskCount()
    {
        return count($this->contractorTasks);
    }

    public function feedbackCount()
    {
        return count($this->feedbacks);
    }

    public function rating()
    {
        $rating = 0;
        if(count($this->feedbacks) > 0) {
            $rating = array_sum(array_column($this->feedbacks, 'rating')) / count($this->feedbacks);
        }
        return $rating;
    }

    public function stars()
    {
        $stars = '';
        for($star = 1; $star <= 5; $star++) {
            if($this->rating() >= $star) {
                $stars .= '<span></span>';
            } else {
                $stars .= '<span class="star-disabled"></span>';
            }
        }
        return $stars;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'name'], 'required'],
            [['dt_add'], 'safe'],
            [['email', 'password', 'name'], 'string', 'max' => 64],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'name' => 'Name',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[Favorite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorite()
    {
        return $this->hasMany(Favorite::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[Jobs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJobs()
    {
        return $this->hasMany(Job::className(), ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Reply::className(), ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[Settings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasOne(Settings::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CustomerTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerTasks()
    {
        return $this->hasMany(Task::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ContractorTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContractorTasks()
    {
        return $this->hasMany(Task::className(), ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[Skills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkills()
    {
        return $this->hasMany(Skill::className(), ['id' => 'skill_id'])->viaTable('user_skill', ['user_id' => 'id']);
    }
}
