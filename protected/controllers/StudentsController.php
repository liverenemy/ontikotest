<?php

class StudentsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'first', 'second', 'third'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Students;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Students']))
		{
			$model->attributes=$_POST['Students'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Students']))
		{
			$model->attributes=$_POST['Students'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Students');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

    /**
     * Показать имена и средний балл всех студентов, которые были "лайкнуты" более чем одним студентом
     */
    public function actionFirst()
    {
        $connection=Yii::app()->db;
        $sql="
            select
                `name`,
                `grade`
            from
                `Students`
                    inner join `Likes`
                    on `Likes`.`liked_ID` = `Students`.`ID`
            group by
                `Students`.ID
            having
                count(`Likes`.`liked_ID`) > 1";
        $result = $connection->createCommand($sql)->queryAll();
        $this->render('first', array(
            'students' => $result,
        ));
    }

    /**
     * Показать имена и средний балл студентов А, которые лайкнули студентов В,
     * но при этом студенты В не поставили лайк ни на одной из страниц других студентов.
     */
    public function actionSecond()
    {
        $connection=Yii::app()->db;
        $sql="
            select
                `Students_A`.name,
                `Students_A`.grade
            from
                `Students` `Students_A`,
                `Students` `Students_B`
                    left outer join `Likes` `Likes_B` on
                      `Likes_B`.like_ID = `Students_B`.ID,
                `Likes` `Likes_A`
            where
                `Likes_A`.like_ID = `Students_A`.ID and
                `Likes_A`.liked_ID = `Students_B`.ID and
                `Likes_B`.like_ID is null
            group by
              `Students_B`.ID;
        ";
        $result = $connection->createCommand($sql)->queryAll();
        $this->render('second', array(
            'students' => $result,
        ));
    }

    /**
     * Вернуть имена и средний балл всех студентов,
     * которые не лайкали чужие страницы и не были лайкнуты другими пользователями.
     */
    public function actionThird()
    {
        $connection=Yii::app()->db;
        $sql="
            select
                `Students`.name,
                `Students`.grade
            from
                `Students`
                    left outer join `Likes` `Likes_From`
                      on `Likes_From`.like_ID = `Students`.ID
                    left outer join `Likes` `Likes_To`
                      on `Likes_To`.liked_ID = `Students`.ID
            where
              `Likes_From`.like_ID is null and
              `Likes_To`.liked_ID is null
        ";
        $result = $connection->createCommand($sql)->queryAll();
        $this->render('third', array(
            'students' => $result,
        ));
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Students('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Students']))
			$model->attributes=$_GET['Students'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Students the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Students::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Students $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='students-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
