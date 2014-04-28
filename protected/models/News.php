<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $title
 */
class News extends CActiveRecord
{
    /**
     * @var string $cacheDir Папка для кэша новостей
     */
    protected static $cacheDir;

    protected function afterDelete()
    {
        $this->model()->cacheLast(3);
        parent::afterDelete();
    }

    protected function afterSave()
    {
        $this->model()->cacheLast(3);
        parent::afterSave();
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID новости',
			'title' => 'Заголовок',
		);
	}

    /**
     * @param   int         $number Количество новостей для кэширования
     * @throws  Exception   Ошибки прав доступа и наличия папки self::$cacheDir и просто ошибка сохранения файла
     */
    protected static function cacheLast($number = 3)
    {
        if (!self::$cacheDir)
        {
            self::initCacheDir();
        }
        if (!is_dir(self::$cacheDir))
        {
            throw new Exception(__METHOD__ . ': cache directory does not exist.');
        }
        if (!is_writable(self::$cacheDir))
        {
            throw new Exception(__METHOD__ . ': news cache directory is not writable.');
        }
        $number = (int) $number;
        $connection=Yii::app()->db;
        $sql="
            select
                *
            from
                `news`
            order by
                `id` desc
            limit
                0, $number;
        ";
        $result = $connection->createCommand($sql)->queryAll();
        if (!file_put_contents(
            self::$cacheDir . '/last' . $number . '.json',
            json_encode($result, JSON_UNESCAPED_UNICODE)
        ))
        {
            throw new Exception(__METHOD__ . ': cannot save news cache to cache file.');
        }
    }

    /**
     * Получить массив из нескольких последних новостей
     *
     * @param   int         $number Количество интересующих нас последних новостей
     * @return  array       Массив последних $number новостей
     * @throws  Exception   Ошибка отсутствия папка кэша новостей
     */
    public static function getLast($number = 3)
    {
        $number = (int) $number;
        if (!self::$cacheDir)
        {
            self::initCacheDir();
        }
        if (!is_dir(self::$cacheDir))
        {
            throw new Exception(__METHOD__ . ': cache directory does not exist.');
        }
        $fileName = self::$cacheDir . '/last' . $number . '.json';
        if (!is_file($fileName) || !$json = file_get_contents($fileName))
        {
            self::cacheLast($number);
            $json = file_get_contents($fileName);
        }
        return json_decode($json);
    }

    protected static function initCacheDir()
    {
        self::$cacheDir = Yii::app()->basePath . '/../cache/news';
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
