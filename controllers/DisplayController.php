<?php
/* ----------------------------------------------------------------------
 * plugins/attributevaluesBacklink/controllers/DisplayController.php :
 * ----------------------------------------------------------------------
 */

require_once(__CA_LIB_DIR__.'/TaskQueue.php');
require_once(__CA_LIB_DIR__.'/Configuration.php');
require_once(__CA_MODELS_DIR__.'/ca_lists.php');
require_once(__CA_MODELS_DIR__.'/ca_objects.php');
require_once(__CA_MODELS_DIR__.'/ca_object_representations.php');
require_once(__CA_MODELS_DIR__.'/ca_locales.php');

/*
Référence datatype : voir attribute_types.conf

	# ObjectRepresentations = 21
	# Entities = 22
	# Places = 23
	# Occurrences = 24
	# Collections = 25
	# StorageLocations = 26
	# Loans = 27
	# Movements = 28
	# Objects = 29
*/

class DisplayController extends ActionController {
	# -------------------------------------------------------
	protected $opo_config;		// plugin configuration file
	protected $pa_parameters;

	# -------------------------------------------------------
	# Constructor
	# -------------------------------------------------------

	public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
		parent::__construct($po_request, $po_response, $pa_view_paths);
		$this->opo_config = Configuration::load(__CA_APP_DIR__.'/plugins/SimpleZ3950/conf/SimpleZ3950.conf');

	}

	# -------------------------------------------------------
	# Functions to render views
	# -------------------------------------------------------
	public function Index() {
		if($representation_id = $this->getRequest()->getParameter("representation_id", pInteger)){
			$id = $representation_id;
			$vs_type = "ObjectRepresentations";
			$vs_class = "ca_object_representations";
		}
		if($entity_id = $this->getRequest()->getParameter("entity_id", pInteger)){
			$id = $entity_id;
			$vs_type = "Entities";
			$vs_class = "ca_entities";
		}
		if($place_id = $this->getRequest()->getParameter("place_id", pInteger)){
			$id = $place_id;
			$vs_type = "Places";
			$vs_class = "ca_places";
		}
		if($occurrence_id = $this->getRequest()->getParameter("occurrence_id", pInteger)){
			$id = $occurrence_id;
			$vs_type = "Occurrences";
			$vs_class = "ca_occurrences";
		}
		if($collection_id = $this->getRequest()->getParameter("collection_id", pInteger)){
			$id = $collection_id;
			$vs_type = "Collections";
			$vs_class= "ca_collections";

		}
		if($location_id = $this->getRequest()->getParameter("location_id", pInteger)){
			$id = $location_id;
			$vs_type = "StorageLocations";
			$vs_class= "ca_storage_locations";

		}
		if($loan_id = $this->getRequest()->getParameter("loan_id", pInteger)){
			$id = $loan_id;
			$vs_type = "Loans";
			$vs_class= "ca_loans";

		}
		if($movement_id = $this->getRequest()->getParameter("movement_id", pInteger)){
			$id = $movement_id;
			$vs_type = "Movements";
			$vs_class= "ca_movements";

		}
		if($object_id = $this->getRequest()->getParameter("object_id", pInteger)){
			$id = $object_id;
			$vs_type = "Objects";
			$vs_class= "ca_objects";
		}

		$o_data = new Db();
		$attributeType = [
			"ObjectRepresentations" => 21,
			"Entities" => 22,
			"Places" => 23,
			"Occurrences" => 24,
			"Collections" => 25,
			"StorageLocations" => 26,
			"Loans" => 27,
			"Movements" =>28,
			"Objects" => 29
		];
		
		$query = "SELECT table_num, row_id, ca.attribute_id, cav.value_id
		from ca_attribute_values cav
		left join ca_attributes ca on cav.attribute_id = ca.attribute_id
		WHERE cav.element_id in (SELECT element_id
		FROM ca_metadata_elements
		WHERE datatype = ".$attributeType[$vs_type].")
		and cav.value_longtext1 = ".$id.";";

		$qr_res = $o_data->query($query);

		$allUtilisations = [];
		while ($qr_res->nextRow()){
			$allUtilisations[$qr_res->get("table_num")][] = $qr_res->getRow();
		}

		$this->view->setVar("utilisations", $allUtilisations);
		$this->view->setVar("item", new $vs_class($id));

		$this->render("display_attribute_values_html.php");

	}
}
?>
