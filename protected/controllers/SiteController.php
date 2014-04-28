<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

    public function actionJsMap()
    {
        $this->layout = '//layouts/column2';
        $this->render('jsMap');
    }

    public function actionMap()
    {
        $this->layout = '//layouts/column2';
        $results = array();
        $address = '';
        if (isset($_POST['address']))
        {
            $address = strip_tags($_POST['address']);
            $geocodeResult = json_decode(
                file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($_POST['address']) . '&sensor=true')
            );
            if ($geocodeResult && $geocodeResult->results && is_array($geocodeResult->results))
            {
                foreach($geocodeResult->results as $result)
                {
                    if (empty($result->formatted_address) ||
                        empty($result->geometry) ||
                        empty($result->geometry->location) ||
                        empty($result->geometry->location->lat) ||
                        empty($result->geometry->location->lng))
                    {
                        continue;
                    }
                    $results[] = array(
                        'formatted_address' => $result->formatted_address,
                        'lat'               => $result->geometry->location->lat,
                        'lng'               => $result->geometry->location->lng,
                    );
                }
            }
        }
        $this->render('map', array(
            'results' => $results,
            'address' => $address,
        ));
    }

    /**
     * Получить массивы с клавиатуры и вывести уникальные элементы каждого массива
     */
    public function actionArraysCompareByKeyboard()
    {
        $this->layout = '//layouts/column2';
        $diffs = array();
        if (isset($_POST['arrayString']))
        {
            $arrayString = $_POST['arrayString'];
            $diffs = $this->getUniqueWords($arrayString);
        }
        $this->render(
            'arraysCompareByKeyboard',
            array(
                'diffs' => $diffs,
            )
        );
    }

    public function actionArraysCompareByFile()
    {
        $this->layout = '//layouts/column2';
        $diffs = array();

        $uploadFolder = Yii::app()->basePath . '/../uploads'; // в рабочем проекте это стоило бы вынести в конфиг

        if ($newFileName = $this->saveUploadedFile('arrayFile', $uploadFolder))
        {
            $arrayString = file_get_contents($newFileName);
            $diffs = $this->getUniqueWords($arrayString);
            unlink($newFileName);
        }

        $this->render(
            'arraysCompareByFile',
            array(
                'diffs' => $diffs,
            )
        );
    }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    /**
     * Разбить входящую переменную на строки и найти уникальные слова на каждой строке
     *
     * @param   string  $text   Строка, которую требуется разбить на массивы
     * @return  array|false     Массив слов противоположной строки, отсутствующих на данной строке, либо false
     */
    protected function getUniqueWords($text)
    {
        $strings = explode("\n", $text);
        if (!is_array($strings))
        {
            return false;
        }
        $arrays = array();
        for ($i = 0; $i < count($strings); $i++)
        {
            $strings[$i] = trim($strings[$i]);
            if (empty($strings[$i]))
            {
                continue;
            }
            $arrays[] = explode(" ", $strings[$i]);
            foreach ($arrays[count($arrays) - 1] as &$element)
            {
                $element = trim($element); // без этого последний элемент каждого массива обрабатывается неверно
            }
        }
        if (count($arrays) < 2)
        {
            return false;
        }
        return array(
            'a' => array_diff($arrays[1], $arrays[0]), // элементы массива 1, отсутствующие в массиве 0
            'b' => array_diff($arrays[0], $arrays[1]), // элементы массива 0, отсутствующие в массиве 1
        );
    }

    /**
     * Сохранить в папку $where загруженный файл с атрибутом name, равным $which
     *
     * @param   string      $which
     * @param   string      $where
     * @return  bool|string Имя сохраненного файла либо false в случае, если упомянутый файл не был закачан юзером
     * @throws  Exception   Ошибки: права на папку сохранения не работают, либо иные ошибки загрузки и сохранения
     */
    protected function saveUploadedFile($which, $where)
    {
        if (!is_writable($where))
        {
            throw new Exception(__METHOD__ . ': Upload folder is not writable.');
        }
        $uploadedFile = CUploadedFile::getInstanceByName($which);
        if (!$uploadedFile)
        {
            return false;
        }
        if ($uploadedFile->hasError)
        {
            throw new Exception(__METHOD__ . ': file upload error code ' . $uploadedFile->getError());
        }
        $files = scandir($where);
        $fileNameFound = false;
        $fileNumber = 0;
        while (!$fileNameFound)
        {
            $fileNumber++;
            $fileName = $fileNumber . '.tmp';
            $fileNameFound = !in_array($fileName, $files);
        }
        $newFileName = $where . '/' . $fileName;
        if (!$uploadedFile->saveAs($newFileName))
        {
            throw new Exception(__METHOD__ . ': File was uploaded but was not saved.');
        }
        return $newFileName;
    }
}