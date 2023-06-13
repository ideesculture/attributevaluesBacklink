<?php

require_once __CA_MODELS_DIR__."/ca_objects.php";
require_once __CA_MODELS_DIR__."/ca_entities.php";
require_once __CA_MODELS_DIR__."/ca_storage_locations.php";
require_once __CA_MODELS_DIR__."/ca_collections.php";
require_once __CA_MODELS_DIR__."/ca_occurrences.php";
require_once __CA_MODELS_DIR__."/ca_places.php";
require_once __CA_MODELS_DIR__."/ca_object_representations.php";
require_once __CA_MODELS_DIR__."/ca_attributes.php";



$utilisations = $this->getVar("utilisations");
$item = $this->getVar("item");
$item_label = $item->getLabelForDisplay();
?>

<h1>Utilisations</h1>

<p style="font-size:14px;margin-top:-20px;"><?= $item->getWithTemplate("<l>^".$item->tableName().".preferred_labels</l>") ?></p>
<?php 
foreach ($utilisations as $table => $infos):
	//var_dump($infos);
    $infos = reset($infos);
	switch ($table):
        case 57:
            $label = "Objets";
			$class = "ca_objects";
            break;
        case 13:
            $label = "Collections";
			$class = "ca_collections";
            break;
        case 20:
            $label = "Entités";
			$class = "ca_entities";
            break;
        case 67:
            $label = "Occurrences";
			$class = "ca_occurrences";
            break;
        case 72:
            $label = "Places";
			$class = "ca_places";
            break;
        case 89:
            $label = "Emplacements";
			$class = "ca_storage_locations";
            break;
        case 56:
            $label = "Représentations";
			$class= "ca_object_representations";
            break;
		default:
			//continue;
			break;
        endswitch;
        ?>
<h2><?= $label ?></h2>
<?php
$vt_attr = new ca_attributes($infos["attribute_id"]);
//$vt_item = new $class($infos["row_id"]);
$vt_item = new ca_objects($infos["row_id"]);
print $vt_item->getWithTemplate("<h3><l>^".$class.".preferred_labels</l></h3>");
print "<blockquote>";
$template = "<unit relativeTo='".$class.".".$vt_attr->getElementCode()."'>^".$class.".".$vt_attr->getElementCode()."</unit>";
$attributes = $vt_item->getAttributesForDisplay($vt_attr->getElementCode(), null, ["delimiter"=>"§"]);
foreach(explode("§", $attributes) as $attribute) {
	if (strpos($attribute, $item_label) !== false) {
		print $attribute;
	}
}
print "</blockquote>";
//print $vt_item->getWithTemplate("^".$class.".".$vt_attr->getElementCode());

?>
    
<?php endforeach;?>


