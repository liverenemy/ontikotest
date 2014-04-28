<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Варианты',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => 'Последние новости',
        ));
        $this->widget('LastNewsWidget');
        $this->endWidget();
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>