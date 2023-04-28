<?php
	$cakeDescription = __d('cake_dev', 'Message Board');
	$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('custom');
		echo $this->Html->css('font-awesome');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('jquery-ui');

		echo $this->Html->script('jquery3.3.1.min');
		echo $this->Html->script('jquery-ui');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>
<body>
	<?php if(AuthComponent::user() && $this->request->here != '/thankyou') :?>
		<nav class="navbar navbar-expand-lg navbar-white bg-white">
			<div class="container">
				<h3><?php echo $this->Html->link($cakeDescription, array('controller' => 'messages', 'action' => 'index')); ?></h3>
				<div class="d-flex">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<?php echo $this->Html->link('Message', array('controller' => 'messages', 'action' => 'index'), array('class' => 'nav-link'))?>
						</li>
						<li class="nav-item">
							<?php echo $this->Html->link('Profile', array('controller' => 'users', 'action' => 'profile'), array('class' => 'nav-link'))?>
						</li>
						<li>
							<a class="nav-link disabled">|</a>
						</li>
						<li class="nav-item">
							<div class="dropdown show">
								<a class="nav-link dropdown-toggle text-dark" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Hi, <strong><?php echo AuthComponent::user('name'); ?></strong>
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
									<?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('class' => 'dropdown-item')); ?>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	<?php endif; ?>



	<div class="page-wrapper mt-3">
		<div class="container">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	
	<?php
		echo $this->Html->script('popper.min');
		echo $this->Html->script('bootstrap.min');
	?>

	<?php echo $this->element('sql_dump'); ?>
	
</body>
</html>
