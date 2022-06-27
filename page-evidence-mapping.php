<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

get_header();

/* SETUP FILEMAKER */
include_once("../globals/filemaker_init.php");
include_once("../globals/scrubber.php");
$fm = db_connect("GUCCHD");
$layout = $fm->getLayout('evidence_mapping');

function getValueListSelect($fieldName,$label){
	global $fm, $layout;

	//GET THE VALUE LIST ATTACHED TO THIS FIELD
	$valueList = $layout->getValueList($fieldName);

	// OUTPUT
	foreach ($valueList as $i=>$v) {
		if( isset($_GET[$label]) && in_array(str_replace('"', "", $v), $_GET[$label]) ){
			$selected = "selected";
		} else {
			$selected = "";
		}
		echo '<option '.$selected.' value="'.str_replace("'", "%", $v).'" id="'.$label.$i.'"> '.$v.'</option>';
	}
}

?>






<!-- page-evidence-mapping.php -->

<div class="container" id="chooseUseCategoryPage" style="margin-top: 2rem; margin-bottom: 2rem;">
	
	
	<?php get_sidebar(); ?>
	
	<div class="nine columns">
	
	<div class="tbl">
		<div style="display: table-row;">
			<div style="display: table-cell; min-width: 160px;">
				<?php $header_image_name = get_field('header_image_name'); ?>
				<img alt="<?php the_title(); ?>" src="<? echo get_template_directory_uri() ?>/images/headers/<?php echo $header_image_name; ?>.jpg" style="border-radius: 15px 0 0 15px; display: block; width: 100%;">
			</div>
			<div class="title">
				<h1 id="fittext3"><?php the_title(); ?></h1>
			</div>
		</div>
	</div>
	
				
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		

		<?php the_content(); ?>








<form method="get" action="/evidence-mapping/search-results/">
	
	<div class="chooseUseBorder filterBox">
	
	<h2><i class="mch-settings"></i> Search by Theory of Change</h2>

	<p>Use this option if you would like to learn about the current evidence for the individual elements of the <a href="https://www.iecmhc.org/resources/how-mental-health-consultation-works-a-theory-of-change-for-research-and-evaluation/">IECMHC theory of change</a>.</p>

		<select multiple="" name="part[]">
			<?php getValueListSelect('EvidenceMapping_Part','part'); ?>
		</select>

	<p><input class="button-primary" type="submit" value="Search"> <input type="Reset" onclick="location.reload();"></p>

	</div>

</form>					
	









<form method="get" action="/evidence-mapping/search-results/">

	<div class="chooseUseBorder filterBox">

	<h2><i class="mch-book-open"></i> Search by Study Details</h2>

	<p>Use this option to see the evidence for IECMHC for specific outcomes, settings, study designs, and/or disaggregation by race/ethnicity.</p>

		<h5>Outcomes</h5>
		<select multiple="" name="outcomes[]">
			<?php getValueListSelect('EvidenceMapping_Outcomes','outcomes'); ?>
		</select>


		<h5>Setting</h5>
		<select multiple="" name="setting[]">
			<?php getValueListSelect('EvidenceMapping_Setting','setting'); ?>
		</select>


		<h5>Study Design</h5>
		<select multiple="" name="design[]">
			<?php getValueListSelect('EvidenceMapping_Design','design'); ?>
		</select>


		<h5>Findings Disaggregated by Race/Ethnicity?</h5>
		<p class="select">
			<label for="Race0"><input type="radio" value="Yes" id="Race0" name="race" <?php if( isset($_GET['race']) && $_GET['race'] == "Yes") { echo "checked"; } ?>> Yes</label>
			<label for="Race1"><input type="radio" value="N" id="Race1" name="race"<?php if( isset($_GET['race']) && $_GET['race'] == "N") { echo "checked"; } ?>> No</label>
		</p>
	
	<p><input class="button-primary" type="submit" value="Search"> <input type="Reset" onclick="location.reload();"></p>

	</div>

</form>




		
			
		<?php endwhile; endif; ?>
		
	</div>
</div>

<?php
	add_action( 'wp_footer', 'my_footer_scripts' );
	function my_footer_scripts(){
		wp_enqueue_style( 'multi-select', get_stylesheet_directory_uri() . '/evidence-mapping/jquery.multi-select.css' );
		wp_enqueue_script( 'multi-select', get_stylesheet_directory_uri() . '/evidence-mapping/jquery.multi-select.min.js', array( 'jquery' ) );
	}
?>

<?php get_footer(); ?>