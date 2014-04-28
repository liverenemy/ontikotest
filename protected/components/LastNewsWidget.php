<?php
/**
 * Виджет для отображения блока последних новостей
 *
 * @author LiverEnemy
 */

Yii::import('zii.widgets.CPortlet');

class LastNewsWidget extends CPortlet {
    /**
     * @var int ID HTML-блока для рендеринга
     */
    public $number = 3;

    protected function renderContent()
    {
        $news = News::model()->getLast($this->number);
        $this->render('lastNewsWidget', array(
            'news' => $news,
        ));
    }
} 