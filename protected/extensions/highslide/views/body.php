<script type="text/javascript">
hs.graphicsDir = '<?php echo '../..'. $this->graphics ?>/'

hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'glossy-dark';
hs.wrapperClassName = 'dark';
hs.fadeInOut = true;
hs.dimmingOpacity = 0.75;
hs.showCredits = 0; 
// Add the controlbar
if (hs.addSlideshow) hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: .6,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});


</script>

<div class="highslide-gallery">

		
		<a href="<?php echo $image;?>" class="highslide" onclick="return hs.expand(this)">
			<img src="<?php echo $smallImage;?>" alt="Highslide JS"
				title="Click to enlarge" />
		</a>

		
		<!--
		<a href="/workspace/svngreen/assets/1ce51abe/foto2.jpg" class="highslide" onclick="return hs.expand(this)">
			<img src="/workspace/svngreen/assets/1ce51abe/foto1.jpg" alt="Highslide JS"
				title="Click to enlarge" />
		</a>
		-->
		<!--
			5 (optional). This is how you mark up the caption. The correct class name is important.
		-->
		
		<div class="highslide-caption">
			<?php echo $caption;?>
		</div>
</div>
	