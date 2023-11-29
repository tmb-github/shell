<?php
/* 

BEGIN: LI with nested UL

2020-02-13:
We need 2 LABEL elements, one for each side of the drawer, each referencing
the checkbox. This is an accessibility issue, but I see no way around it.
But see: https://www.w3.org/TR/html401/interact/forms.html#h-17.9.1
"More than one LABEL may be associated with the same control by creating 
multiple references via the 'for' attribute."

To satisfy accessibility requirements, I can't have two LABEL elements
that reference the same INPUT. Using aria-labelledby is meant to reference
non-LABEL elements. I can't use a custom element, because the Wiersch 
Validator can't associate the aria-labelledby values on the INPUT with 
the IDs on the custom element. Also, 'for' can only be used on LABELs.
So, I'm using a DIV with a custom attribute of 'data-for' to reference the
ID of the INPUT element.

NB: We're excluding As and SPANSs surrounding text from being tabbable
items by adding tabindex=-1 to each of them. The true, tabbable LIs and DIVs
are provided tabindexes by the JavaScript.

&#9658; would be the HTML equivalent of the right-pointing triangle

VARIABLES NEEDED BY THIS ROUTINE...DEFINE THEM BEFORE THE INCLUDE:

$switcher_name = 'Additional Pages 1';
$switcher_id = 'additional-pages-1';
// This must be 'side-switcher-' followed by a unique character:
$side_switcher_id = 'side-switcher-1';
$main_menu_id = 'main-menu-1';
// $page_name => $page_slug:
$page_array = [
	'Dummy 3' => 'dummy-3',
	'Dummy 4' => 'dummy-4', 
	'Dummy 5' => 'dummy-5'
];
*/

if (count($page_array) > 0) {
?>
			<li data-span="<?php echo $switcher_name; ?>" class="no-progress-line menu-side-one">
				<input id=<?php echo $side_switcher_id; ?> data-switcher-target="Main Menu" aria-labelledby="<?php echo $switcher_id; ?> main-menu" type=checkbox title="hidden checkbox" name=ignore2 value=ignore2>
				<div data-for=<?php echo $side_switcher_id; ?> id=<?php echo $switcher_id; ?>><span class=switch-to-secondary-ul tabindex=-1><?php echo $switcher_name; ?></span></div>
				<ul class=secondary-ul>
					<li data-span="Main Menu" class="menu-side-two no-progress-line orange ripple">
						<div data-for=<?php echo $side_switcher_id; ?> data-switcher-target="<?php echo $switcher_name; ?>" id=<?php echo $main_menu_id; ?> class=return-to-main-menu><span class="necessary-span switch-to-primary-ul" tabindex=-1>Main Menu</span></div>
					</li>
<?php
foreach ($page_array as $page_name => $page_slug) {
?>
					<li data-page=<?php echo $page_slug; ?> data-href=<?php echo $page_slug; ?> class=menu-side-two><a class=internal-anchor href="<?php echo $page_slug; ?>/" tabindex=-1><?php echo $page_name; ?></a></li>
<?php
}
?>
				</ul>
			</li>
<?php
}
