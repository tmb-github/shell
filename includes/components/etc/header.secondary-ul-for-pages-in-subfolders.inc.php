<?php
if (count($page_array) > 0) {
?>
			<li data-span="<?php echo $switcher_name; ?>" class="no-progress-line menu-side-one">
				<input id=side-switcher data-switcher-target="Main Menu" aria-labelledby="<?php echo $switcher_id; ?> main-menu" type=checkbox title="hidden checkbox" name=ignore2 value=ignore2>
				<div data-for=side-switcher id=<?php echo $switcher_id; ?>><span class=switch-to-secondary-ul tabindex=-1><?php echo $switcher_name; ?></span></div>
				<ul class="<?php echo $ul_class; ?>">
					<li data-span="Main Menu" class="menu-side-two no-progress-line orange ripple">
						<div data-for=side-switcher data-switcher-target="<?php echo $switcher_name; ?>" id=main-menu class=return-to-main-menu><span class="necessary-span switch-to-primary-ul" tabindex=-1>Main Menu</span></div>
					</li>
<?php
// Can't use $page for cycling variable here, as it's defined previously
// and used to find the right CSS file for the page in question.
foreach ($page_array as $p) {
	$page_folder = $p[0];
	$page_slug = $p[1];
	$page_name = $p[2];
	$li_class = $p[3];
?>
					<li data-page=<?php echo $page_slug; ?> data-href=<?php echo $page_folder . $page_slug; ?> class="<?php echo $li_class; ?>"><a class=internal-anchor href="<?php echo $page_folder . $page_slug; ?>/" tabindex=-1><?php echo $page_name; ?></a></li>
<?php
}
?>
				</ul>
			</li>
<?php
}
