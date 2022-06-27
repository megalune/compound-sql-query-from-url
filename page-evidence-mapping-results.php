<?php /* Template Name: Searchable Evidence Database Results */ 

get_header(); ?>


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
		

<?php


$sql_search = "SELECT * FROM `evidence_mapping` WHERE (`web_ready` = 1)";

function create_search_term($piece){
	global $sql_search;
	if (isset($_GET[$piece])) {
		if(is_string($_GET[$piece])){
			$sql_search .= " AND (`".$piece."` LIKE '%".$_GET[$piece]."%')";
		} else {
			$search_string = [];

			foreach($_GET[$piece] as $p){
				array_push($search_string, "`".$piece."` LIKE '%".$p."%'");
			}
			$sql_search .= " AND (".implode(" OR ", $search_string).")";
		}
	}
}


	create_search_term('part');
	create_search_term('outcomes');
	create_search_term('setting');
	create_search_term('design');
	create_search_term('race');


	// function to handle junk data
	function clean_up_text($t){
		$output = str_replace("\n", '</p><p>', $t);
		$output = str_replace("&amp;", '&', $output);
		return $output;
	}

	// connect to wp sql database
	$servername = "******";
	$username = "******";
	$password = "******";
	$dbname = "******";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}


	$result = $conn->query($sql_search);
	echo "<h5>".$result->num_rows." Result". ( $result->num_rows > 1 ? "s" : "" ) ."</h5>";

	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) { ?>
        <article class="result">
			
			<h5>Full Citation</h5>
				<?php
					$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
					$hyperlinked_citation = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', htmlspecialchars_decode(clean_up_text($row['citation'])));
				?>
				<p><?php echo htmlspecialchars_decode($hyperlinked_citation); ?></p>
					
			<h5>Annotation</h5>
				<p><?php echo clean_up_text($row['annotation']); ?></p>


				<button class="accordion">Details</button>
				<div class="panel">
			
					<h5>Abstract</h5>
						<ul><li><?php echo clean_up_text($row['abstract']); ?></li></ul>

					<h5>Study Design</h5>
						<ul><li><?php echo clean_up_text($row['design']); ?></li></ul>
					
					<h5>Measures</h5>	
						<ul><li><?php echo clean_up_text($row['measures']); ?></li></ul>
					
					<h5>Outcomes</h5>	
						<ul><li><?php echo clean_up_text($row['outcomes']); ?></li></ul>
		        	
		        	<h5>Part of Theory of Change</h5>		     
						<ul><li><?php echo clean_up_text($row['part']); ?></li></ul>
		        	
		        	<h5>Setting</h5>		     
						<ul><li><?php echo clean_up_text($row['setting']); ?></li></ul>
					
					<h5>Type of Publication</h5>
						<ul><li><?php echo $row['type']; ?></li></ul>
					
					<h5>Findings disaggregated by disability status?</h5>
						<ul><li><?php echo $row['disability']; ?></li></ul>
					
					<h5>Findings disaggregated by linguistic background?</h5>
						<ul><li><?php echo $row['linguistics']; ?></li></ul>
					
					<h5>Findings disaggregated by race/ethnicity?</h5>
						<ul><li><?php echo $row['race']; ?></li></ul>
				</div>

		</article>
	  	<?php
	  }
	} else {
	  echo "0 results";
	}


	$conn->close();

?>

		
			
		<?php endwhile; endif; ?>
		


	<p><a href="/evidence-mapping/" class="button">New Search</a></p>
	</div>
</div>

<?php get_footer(); ?>